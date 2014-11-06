<?php

class temposCheckForNextHoursTask extends sfBaseTask
{
	protected function configure()
	{
		$this->addOptions(array(
			new sfCommandOption('include-inactive', null, sfCommandOption::PARAMETER_REQUIRED, 'Wether to include or not deactivated reservations', '0'),
			new sfCommandOption('update-status', null, sfCommandOption::PARAMETER_REQUIRED, 'If set, reservations status will be updated on success', '1'),
			new sfCommandOption('verbose', null, sfCommandOption::PARAMETER_REQUIRED, 'If set, error messages will contain more information', '0'),
			new sfCommandOption('hours', null, sfCommandOption::PARAMETER_REQUIRED, 'The number of hours in which all reservations will be sent to the physical', '48'),
		));

		$this->namespace = 'tempos';
		$this->name = 'check-for-next-hours';
		$this->briefDescription = 'Check for reservations for upcoming X hours. Send only the first found reservation of each members.';
		$this->detailedDescription = <<<EOF
The [tempos:check-for-next-hours|INFO] task checks if one or more reservations are pending for the upcoming X hours and tells the physical access device if so.
Warning: Only the first found reservation of each members is sent to the physical access (this is done to never overwrite a member reservation):

  [./symfony tempos:check-for-next-hours|INFO]
EOF;
	}

	protected function execute($arguments = array(), $options = array())
	{
		$this->configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'prod', true);
		$this->context = sfContext::createInstance($this->configuration);

		$databaseManager = new sfDatabaseManager($this->configuration);

		ConfigurationHelper::load();
		$nb_controllers = ConfigurationHelper::get('number_of_physical_access');
		
		$now = time();
		$startCheckTime = $now + ($delay * 60);	// Need to add the delay in milliseconds to the time() function
		$stopCheckTime = $startCheckTime + ($options['hours'] * 60 * 60);

		$this->logSection('tempos', sprintf("check-for-next-hours - Checking reservations started at: %s", strftime('%c', $now)), 1024);
			
		$this->logSection('tempos', sprintf("check-for-next-hours - Start delay (%s) minutes --> Start date reservation is: %s", $delay, strftime('%c', $startCheckTime)), 1024);
		$this->logSection('tempos', sprintf("check-for-next-hours - NB hours to check (%s) --> End date reservation check: %s", $options['hours'], strftime('%c', $stopCheckTime)), 1024);
		
		$reservations = ReservationPeer::doSelectPendingReservationsForNextHours($options['include-inactive'], $startCheckTime, $stopCheckTime);

		if (empty($reservations))
		{
			$this->logSection('tempos', "check-for-next-hours - No reservations pending. Doing nothing.", 1024);
		}
		else
		{
			$this->logSection('tempos', sprintf("check-for-next-hours - %d reservation(s) pending.", count($reservations)), 1024);

			$uniquePersonsArray = array();
			$i = 0;

			foreach ($reservations as $reservation)
			{
				$roomprofile_id = $reservation->getRoomprofile()->getId();
				$roomprofile = RoomprofilePeer::doSelectFromId($roomprofile_id);
				
				$rp_controllername = $roomprofile->getConfiguredControllerName();
				
				$pac_infos = BasePhysicalAccessController::findPacFromNameIdentifier($rp_controllername);
				
				if (is_null($pac_infos) || empty($pac_infos))
					continue;
				
				$pac_name = $pac_infos['name'];
				// print 'pac_name';
				// var_dump($pac_name);
				
				$pac_id	= $pac_infos['id'];
				// print 'pac_id';
				// var_dump($pac_id);
				
				$pac_selec = ConfigurationHelper::getParameter(null, $pac_name);
				// print 'pac_selec';
				// var_dump($pac_selec);
				
				$pac_conf = ConfigurationHelper::getNamespace($pac_selec.$pac_id);
				// print 'pac_conf';
				// var_dump($pac_conf);
				
				// var_dump($pac_infos);
				
				$pac = BasePhysicalAccessController::create($pac_selec, $pac_conf);
				// var_dump($pac);
				
				$delay = $pac->getParameter('delay');
				// print 'delay';
				// var_dump($delay);
				
				$pac->setVerbose($options['verbose']);
				
				if (is_null($delay) || empty($delay))
					$delay = 0;
				
				$this->logSection('tempos', sprintf('check - Creating a physical access controller: %s', $pac->getName()), 1024);
				
				$this->logSection('tempos',  sprintf("check-for-next-hours - \t(%d)\tProcessing reservation: %s...", $i, $reservation->__toString()), 1024);
			
				$persons = $reservation->getAllPersons();
				$finalPersons = array();
				
				// Filter the list of persons. We only send the first reservation for each member.
				// If the user is already in the list, we remove him from the reservation.
				foreach ($persons as $person)
				{
					$this->logSection('tempos', sprintf("check-for-next-hours - \t(%d)\tProcessing user (%s)...", $i, $person), 1024);

					$uniqueId = $person->getUniqueId();			
	
					if (isset($uniquePersonsArray[$uniqueId]))
					{
						$this->logSection('tempos', sprintf("check-for-next-hours - \t(%d)\tUser (%s) had already a reservation... Ignoring him...", $i, $person->__toString()), 1024);
					}
					else
					{
						$uniquePersonsArray[$uniqueId] = 1;
						$finalPersons[] = $person;
					}
				}
				
				if (!empty($finalPersons))
				{
					if ($delay > 0)
					{
						$this->logSection('tempos',  sprintf("check-for-next-hours - \t(%d)\tDelay required ! Before delay: %s", $i, $reservation->getDateString()), 1024);
						$reservation->updateDateWithDelay($delay);
						$this->logSection('tempos',  sprintf("check-for-next-hours - \t(%d)\tAfter delay: %s", $i, $reservation->getDateString()), 1024);
					}
					
					$results = $pac->sendReservation($reservation, $options['update-status'], $finalPersons);

					if (empty($results))
					{
						$this->logSection('tempos', sprintf("check-for-next-hours - \t\tSuccess."), 1024);
					}
					else
					{
						foreach ($results as $result)
						{
							$this->logSection('tempos', sprintf("check-for-next-hours - \t\t%s: %s", $result['person'], $result['exception']->getMessage()), 1024, 'ERROR');
						}
					}
				}
				else
				{
					$this->logSection('tempos', sprintf("check-for-next-hours - \t\tNo more user in the reservation. Doing nothing."), 1024);
				}
				$i++;
			}
		}
	}
}
