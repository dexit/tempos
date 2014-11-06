<?php

class temposCheckactionsTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
			new sfCommandOption('verbose', null, sfCommandOption::PARAMETER_REQUIRED, 'If set, error messages will contain more information', '0'),
			new sfCommandOption('update-status', null, sfCommandOption::PARAMETER_REQUIRED, 'If set, action status will be updated on success', '1'),
			new sfCommandOption('force', null, sfCommandOption::PARAMETER_REQUIRED, 'Forces status update', '0'),
    ));

    $this->namespace        = 'tempos';
    $this->name             = 'check-actions';
    $this->briefDescription = 'Check for energy actions';
    $this->detailedDescription = <<<EOF
The [tempos:check-actions|INFO] checks if some actions needs to change their status.
Call it with:

  [php symfony tempos:check-actions|INFO]
EOF;
  }

	protected function execute($arguments = array(), $options = array())
	{
		$this->configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'prod', true);
		$this->context = sfContext::createInstance($this->configuration);

		$databaseManager = new sfDatabaseManager($this->configuration);

		ConfigurationHelper::load();

		$now = time();

		$this->logSection('tempos', sprintf('Checking energy actions at: %s.', strftime('%c', $now)), 1024);

		// Creates physical access controller

		/* 
		
		$hac = BaseHomeAutomationController::create();

		$hac->setVerbose($options['verbose']);
		$hac->setUpdateStatus($options['update-status']);

		$this->logSection('tempos', sprintf('Creating a home automation controller: %s', $hac->getName()), 1024);
		
		*/

		// Refresh statuses

		$actions = EnergyactionPeer::doSelect(new Criteria());

		if (empty($actions))
		{
			$this->logSection('tempos', sprintf('Refreshing statuses for all actions: no actions.'), 1024);
		} else
		{
			$this->logSection('tempos', sprintf('Refreshing statuses for all actions: %d action(s) to refresh.', count($actions)), 1024);

			foreach ($actions as $action)
			{
				$hac = self::findHac($action);
				
				if (is_null($hac))
				{
					$this->logSection('tempos', sprintf('Can\'t create a home automation controller for this action: id(%s) HAC is null', $action->getId()), 1024);
					continue;
				}
				
				$hac->setVerbose($options['verbose']);
				$hac->setUpdateStatus($options['update-status']);
				$this->logSection('tempos', sprintf('Creating a home automation controller: %s for action: %s', $hac->getName(), $action->getId(), 1024));
				
				$this->refreshAction($hac, $action);
			}
		}

		// Check out of period actions

		$actions = EnergyactionPeer::doSelectOutOfPeriod(true, $now);

		if (empty($actions))
		{
			$this->logSection('tempos', sprintf('Checking out-of-period active actions: no action is out-of-period.'), 1024);
		} else
		{
			$this->logSection('tempos', sprintf('Checking out-of-period active actions: %d action(s) to shut down.', count($actions)), 1024);

			foreach ($actions as $action)
			{
				$hac = self::findHac($action);
				
				if (is_null($hac))
				{
					$this->logSection('tempos', sprintf('Can\'t check out-of-period with this action: id(%s) HAC is null', $action->getId()), 1024);
					continue;
				}
				
				$this->updateAction($hac, $action, false, $options['force']);
			}
		}

		// Check the ready actions

		$actions = EnergyactionPeer::doSelectReady($now);

		if (empty($actions))
		{
			$this->logSection('tempos', sprintf('Checking reservation ready actions: no action must be triggered.'), 1024);
		} else
		{
			$this->logSection('tempos', sprintf('Checking reservation ready actions: %d action(s) to power on.', count($actions)), 1024);

			foreach ($actions as $action)
			{
				$hac = self::findHac($action);
				
				if (is_null($hac))
				{
					$this->logSection('tempos', sprintf('Can\'t update this action: id(%s) HAC is null', $action->getId()), 1024);
					continue;
				}
				
				$this->updateAction($hac, $action, true, $options['force']);
			}
		}

		// Check the over actions

		$actions = EnergyactionPeer::doSelectOver($now, $actions);

		if (empty($actions))
		{
			$this->logSection('tempos', sprintf('Checking reservation over actions: no action must be triggered.'), 1024);
		} else
		{
			$this->logSection('tempos', sprintf('Checking reservation over actions: %d action(s) to shut down.', count($actions)), 1024);

			foreach ($actions as $action)
			{
				$hac = self::findHac($action);
				
				if (is_null($hac))
				{
					$this->logSection('tempos', sprintf('Can\'t update this action: id(%s) HAC is null', $action->getId()), 1024);
					continue;
				}
				
				$this->updateAction($hac, $action, false, $options['force']);
			}
		}
	}

	private static function findHac($action)
	{
		$action_controllername = $action->getConfiguredControllerName();
		
		$hac_infos = BaseHomeAutomationController::findHacFromNameIdentifier($action_controllername);
		// var_dump($action_controllername);
		if (is_null($hac_infos))
			return null;
		
		$hac_name = $hac_infos['name'];
		// print 'hac_name: ';
		// var_dump($hac_name);
		
		$hac_id	= $hac_infos['id'];
		// print 'hac_id: ';
		// var_dump($hac_id);
		
		$hac_selec = ConfigurationHelper::getParameter(null, $hac_name);
		// print 'hac_selec: ';
		// var_dump($hac_selec);
		
		$hac_conf = ConfigurationHelper::getNamespace($hac_selec.$hac_id);
		// print 'hac_conf: ';
		// var_dump($hac_conf);
		
		// var_dump($hac_infos);
		
		$hac = BaseHomeAutomationController::create($hac_selec, $hac_conf);
		// var_dump($hac);
		
		return $hac;
	}
	
	protected function refreshAction($hac, $action)
	{
		try
		{
			$result = $hac->refreshAction($action);

			if ($result == true)
			{
				$this->logSection('tempos', sprintf('Refreshing action "%s": status set to "%s"', $action->getName(), ($action->getStatus() ? 'on' : 'off')), 1024);
			}
		}
		catch (Exception $ex)
		{
			$this->logSection('tempos', sprintf('Refreshing action "%s": %s', $action->getName(), $ex->getMessage()), 1024, 'ERROR');
		}
	}

	protected function updateAction($hac, $action, $status, $force)
	{
		$title = $status ? 'Powering on' : 'Shutting down';
		try
		{
			$result = $hac->updateAction($action, $status, $force);

			$this->logSection('tempos', sprintf('%s action "%s": %s', $title, $action->getName(), ($result ? 'OK' : 'not needed')), 1024);
		}
		catch (Exception $ex)
		{
			$this->logSection('tempos', sprintf('%s action "%s": %s', $title, $action->getName(), $ex->getMessage()), 1024, 'ERROR');
		}
	}
}
