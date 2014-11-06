<?php

class temposUserresetTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('username', sfCommandArgument::REQUIRED, 'The username'),
      new sfCommandArgument('password', sfCommandArgument::REQUIRED, 'The password'),
    ));

    $this->addOptions(array(
    ));

    $this->namespace        = 'tempos';
    $this->name             = 'user-reset';
    $this->briefDescription = 'Reset a user password';
    $this->detailedDescription = <<<EOF
The [tempos:user-reset|INFO] task reset a user password.

  [./symfony tempos:user-reset username password|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
		$this->configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'prod', true);
		$this->context = sfContext::createInstance($this->configuration);

		$databaseManager = new sfDatabaseManager($this->configuration);

		$user = UserPeer::retrieveByLogin($arguments['username']);

		if (is_null($user))
		{
			$this->logSection('tempos', sprintf('Unable to find an user with this username: "%s"', $arguments['username']), 512, 'ERROR');
		} else
		{
			$user->setPassword($arguments['password']);
			$user->save();
			$this->logSection('tempos', sprintf('Password reset for user "%s" (%s)', $user->getLogin(), $user->getFullname()), 512);
		}
  }
}
