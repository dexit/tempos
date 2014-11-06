<?php

class sfValidatorPasswordCheck extends sfValidatorSchema
{
  public function __construct($options = array(), $messages = array())
	{
    $this->addMessage('invalid', 'The specified password is incorrect');
		$this->addOption('password_field', 'password');

		parent::__construct(null, $options, $messages);
	}

  protected function doClean($values)
	{
		if (is_null($values))
		{
			$values = array();
		}

		if (!is_array($values))
		{
			throw new InvalidArgumentException('You must pass an array parameter to the clean() method');
		}

		$id = $values['id'];
		$user = UserPeer::retrieveByPk($id);

		if (!is_null($id) && !is_null($user))
		{
			$password = $values[$this->getOption('password_field')];

			if (!empty($password) && !$user->checkPassword($password))
			{
				$error = new sfValidatorError($this, 'invalid', array());

				if ($this->getOption('throw_global_error'))
				{
					throw $error;
				}

				throw new sfValidatorErrorSchema($this, array($this->getOption('password_field') => $error));
			}
		}

		return $values;
	}
}
