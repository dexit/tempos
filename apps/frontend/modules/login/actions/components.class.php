<?php
class loginComponents extends sfComponents
{
	public function executeHandleConnection()
	{
		$this->authenticated = ($this->getUser()->isAuthenticated());
		$this->is_admin = $this->getUser()->hasCredential('admin', false);
	}

	public function executeWelcome()
	{
		$this->authenticated = ($this->getUser()->isAuthenticated());

		if ($this->authenticated)
		{
			if ($user = $this->getUser()->getTemposUser())
			{
				$this->name = $user->__toString();
			} elseif ($card = $this->getUser()->getTemposCard())
			{
				$owner = $card->getOwnerObject();

				if (!is_null($owner))
				{
					$this->name = $owner->__toString();
				} else
				{
					$this->name = $card->__toString();
				}
			} else
			{
				$this->name = "unknown user";
			}
		}
	}
}
?>
