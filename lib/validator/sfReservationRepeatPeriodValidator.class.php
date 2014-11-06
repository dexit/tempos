<?php

class sfReservationRepeatPeriodValidator extends sfValidatorSchema
{
  public function __construct($options = array(), $messages = array())
	{
    $this->addMessage('null_period', 'You cannot specify 0 for all periods.');

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

		$day_period = $values['day_period'];
		$week_period = $values['week_period'];
		$month_period = $values['month_period'];
		$year_period = $values['year_period'];
		$null_period = empty($day_period) && empty($week_period) && empty($month_period) && empty($year_period);

		if ($null_period)
		{
			$error = new sfValidatorError($this, 'null_period', array());

			if ($this->getOption('throw_global_error'))
			{
				throw $error;
			}

			throw new sfValidatorErrorSchema($this, array('day_period' => $error));
		}

		return $values;
	}
}
