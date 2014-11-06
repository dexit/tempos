<?php

class sfReservationPastValidator extends sfValidatorSchema
{
  public function __construct($options = array(), $messages = array())
	{
    $this->addMessage('invalid', 'You cannot book in the past !');

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

		$date = strtotime($values['date']);

		if ($date < time())
		{
			$error = new sfValidatorError($this, 'invalid', array());

			if ($this->getOption('throw_global_error'))
			{
				throw $error;
			}

			throw new sfValidatorErrorSchema($this, array('date' => $error));
		}

		return $values;
	}
}
