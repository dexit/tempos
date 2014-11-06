<?php
class homeComponents extends sfComponents
{
	public function executeMenu()
	{
		if ($this->getUser()->isAuthenticated())
		{
			$this->modules = array();

			$this->modules['home'] = __('Home');

			if ($this->getUser()->hasCredential(array('admin', 'userManager'), false))
			{
				$this->modules['user'] = __('Users');
				$this->modules['card'] = __('Cards');
			}
			if ($this->getUser()->hasCredential(array('admin', 'zoneManager'), false))
			{
				$this->modules['zone'] = __('Zones');
			}
			if ($this->getUser()->hasCredential(array('admin', 'activityManager'), false))
			{
				$activityModule = ConfigurationHelper::getParameter('Rename', 'activity_module');

				if (is_null($activityModule) || empty($activityModule))
				{
					$activityModule = 'Activities';
				}
				$this->modules['activity'] = __($activityModule);
			}
			if ($this->getUser()->hasCredential(array('admin', 'reportingManager'), false))
			{
				$this->modules['reporting'] = __('Tools');
			}
			if ($this->getUser()->hasCredential(array('admin'), false))
			{
				$this->modules['energyaction'] = __('Energy');
			}
		}
	}

	public function executeMenuItems()
	{
		$this->user = $this->getUser()->getTemposUser();

		if (!is_null($this->user))
		{
			$this->newMessagesCount = MessagePeer::doCountUserUnreadMessages($this->user->getId());
		}
	}

	public function executeNavigation()
	{
		$this->person = $this->getUser()->getPerson();

		$activityId = $this->getUser()->getAttribute('activityId');

		if ($this->getUser()->hasActivity($activityId))
		{
			$this->activity = ActivityPeer::retrieveByPk($activityId);
		} else
		{
			$this->activity = null;
		}

		$this->usergroup = UsergroupPeer::retrieveByPk($this->getUser()->getAttribute('usergroupId'));
		$this->user = UserPeer::retrieveByPk($this->getUser()->getAttribute('userId'));
	}
}
?>
