<?php

class sfReservationRepeatTypeValidator extends sfValidatorSchema
{
  public function __construct($options = array(), $messages = array())
	{
    $this->addMessage('missing_count', 'Count cannot be null with the selected repetition type.');
    $this->addMessage('missing_until_date', 'Until date cannot be null with the selected repetition type.');

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

		$repeat_type = $values['repeat_type'];
		$count = $values['count'];
		$until_date = $values['until_date'];

		if (empty($count) && in_array($repeat_type, array(ReservationRepeatForm::COUNT, ReservationRepeatForm::COUNTDATE)))
		{
			$error = new sfValidatorError($this, 'missing_count', array());

			if ($this->getOption('throw_global_error'))
			{
				throw $error;
			}

			throw new sfValidatorErrorSchema($this, array('count' => $error));
		}

		if (empty($until_date) && in_array($repeat_type, array(ReservationRepeatForm::DATE, ReservationRepeatForm::COUNTDATE)))
		{
			$error = new sfValidatorError($this, 'missing_until_date', array());

			if ($this->getOption('throw_global_error'))
			{
				throw $error;
			}

			throw new sfValidatorErrorSchema($this, array('until_date' => $error));
		}

		return $values;
	}
}
