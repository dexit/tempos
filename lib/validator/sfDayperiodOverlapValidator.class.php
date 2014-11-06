<?php

class sfDayperiodOverlapValidator extends sfValidatorBase
{
  public function __construct($options = array(), $messages = array())
	{
		parent::__construct($options, $messages);
	}

	public function configure($options = array(), $messages = array())
	{
		$this->setMessage('invalid', 'An overlapping period exists.');
	}

	public function doClean($values)
	{
    if (is_null($values))
    {
      $values = array();
    }

    if (!is_array($values))
    {
      throw new InvalidArgumentException('You must pass an array parameter to the clean() method');
    }

		if (is_null($values['start']) || is_null($values['stop']))
		{
			$valid = true;
		} else
		{
			$valid = !DayperiodPeer::overlaps($values['id'], $values['start'], $values['stop'], $values['day_of_week'], $values['Room_id']);
		}

    if (!$valid)
    {
      $error = new sfValidatorError($this, 'invalid', array(
      ));

      if ($this->getOption('throw_global_error'))
      {
        throw $error;
      }

      throw new sfValidatorErrorSchema($this, array('start' => $error));
		}

		return $values;
	}
}
