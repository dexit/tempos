<?php

class temposCheckTask extends sfBaseTask
{
	protected function configure()
	{
		$this->addOptions(array(
			new sfCommandOption('include-inactive', null, sfCommandOption::PARAMETER_REQUIRED, 'Wether to include or not deactivated reservations', '0'),
			new sfCommandOption('update-status', null, sfCommandOption::PARAMETER_REQUIRED, 'If set, reservations status will be updated on success', '1'),
			new sfCommandOption('verbose', null, sfCommandOption::PARAMETER_REQUIRED, 'If set, error messages will contain more information', '0'),
		));

		$this->namespace = 'tempos';
		$this->name = 'check';
		$this->briefDescription = 'Check for reservations';
		$this->detailedDescription = <<<EOF
The [tempos:check|INFO] task checks if one or more reservations are pending and tells the physical access device if so:

  [./symfony tempos:check|INFO]
EOF;
	}

	protected function execute($arguments = array(), $options = array())
	{
		$this->configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'prod', true);
		$this->context = sfContext::createInstance($this->configuration);

		$databaseManager = new sfDatabaseManager($this->configuration);

		ConfigurationHelper::load();
		$nb_controllers = ConfigurationHelper::get('number_of_physical_access');
		
		$reservations = ReservationPeer::doSelectPendingReservations($options['include-inactive'], $delayedTime);

		if (empty($reservations))
		{
			$this->logSection('tempos', 'check - No reservations pending. Doing nothing.', 1024);
		}
		else
		{
			$this->logSection('tempos', sprintf('check - %d reservation(s) pending.', count($reservations)), 1024);

			$i = 0;

			foreach ($reservations as $reservation)
			{
				$roomprofile_id = $reservation->getRoomprofile()->getId();
				$roomprofile = RoomprofilePeer::doSelectFromId($roomprofile_id);
				
				$rp_controllername = $roomprofile->getConfiguredControllerName();
				
				$pac_infos = BasePhysicalAccessController::findPacFromNameIdentifier($rp_controllername);
				
				if (is_null($pac_infos) || empty($pac_infos))
				{
					$this->logSection('tempos', sprintf('check - Can\'t create a physical access controller for this reservation: id(%s)', $reservation->getId()), 1024);
					continue;
				}
				
				$pac_name = $pac_infos['name'];
				// print 'pac_name: ';
				// var_dump($pac_name);
				
				$pac_id	= $pac_infos['id'];
				// print 'pac_id: ';
				// var_dump($pac_id);
				
				$pac_selec = ConfigurationHelper::getParameter(null, $pac_name);
				// print 'pac_selec: ';
				// var_dump($pac_selec);
				
				$pac_conf = ConfigurationHelper::getNamespace($pac_selec.$pac_id);
				// print 'pac_conf: ';
				// var_dump($pac_conf);
				
				// var_dump($pac_infos);
				
				$pac = BasePhysicalAccessController::create($pac_selec, $pac_conf);
				// var_dump($pac);
				
				$delay = $pac->getParameter('delay');
				// print 'delay: ';
				// var_dump($delay);
				
				$pac->setVerbose($options['verbose']);
				
				$this->logSection('tempos', sprintf('check - Creating a physical access controller: %s', $pac->getName()), 1024);
				
				if (is_null($delay) || empty($delay))
					$delay = 0;
					
				$now = time();
				$delayedTime = time() + ($delay * 60);	// Need to add the delay in milliseconds to the time() function
				$this->logSection('tempos', sprintf('check - Checking reservations at: %s', strftime('%c', $now)), 1024);
				
				if ($delay > 0)
				{
					$this->logSection('tempos', sprintf('check - There is a starting delay ! Need to start reservation %s minutes before the reservation date !', $delay), 1024);
					$this->logSection('tempos', sprintf('check - Finally looking reservations at: %s', strftime('%c', $delayedTime)), 1024);
				}
				
				if ($delay > 0)
				{
					$this->logSection('tempos',  sprintf("check - \t(%d)\tBefore delay: %s", $i, $reservation->__toString()), 1024);
					$reservation->updateDateWithDelay($delay);
					$this->logSection('tempos',  sprintf("check - \t(%d)\tAfter delay: %s", $i, $reservation->__toString()), 1024);
				}
				else
				{
					$this->logSection('tempos',  sprintf("check - \t(%d)\tReservation: %s", $i, $reservation->__toString()), 1024);
				}
				
				$results = $pac->sendReservation($reservation, $options['update-status']);

				if (empty($results))
				{
					$this->logSection('tempos', sprintf("check - \t\tSuccess."), 1024);
				}
				else
				{
					foreach ($results as $result)
					{
						$this->logSection('tempos', sprintf("check - \t\t%s: %s", $result['person'], $result['exception']->getMessage()), 1024, 'ERROR');
					}
				}
				$i++;
			}
		}
	}
}
