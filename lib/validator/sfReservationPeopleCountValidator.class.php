<?php

class sfReservationPeopleCountValidator extends sfValidatorSchema
{
  public function __construct($options = array(), $messages = array())
	{
		parent::__construct(null, $options, $messages);

		$this->addOption('members_count_field', 'members_count');
		$this->addOption('guests_count_field', 'guests_count');

    $this->addMessage('people_missing', 'Insufficient people count: you have %people_count% people, at least %needed_count% needed.');
    $this->addMessage('too_much_people', 'Too much people: you have %people_count% people, at most %granted_count% granted.');
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

		if (is_null($values[$this->getOption('members_count_field')]) || is_null($values[$this->getOption('guests_count_field')]))
		{
			return $values;
		}

		$activity = ActivityPeer::retrieveByPK($values['Activity_id']);

		if (!is_null($activity))
		{
			$needed_count = $activity->getMinimumOccupation() - 1;
			$granted_count = $activity->getMaximumOccupation() - 1;
			$people_count = $values[$this->getOption('members_count_field')] + $values[$this->getOption('guests_count_field')];

			if ($people_count < $needed_count)
			{
				$error = new sfValidatorError($this, 'people_missing', array(
							'people_count' => $people_count,
							'needed_count' => $needed_count,
							));

				if ($this->getOption('throw_global_error'))
				{
					throw $error;
				}

				throw new sfValidatorErrorSchema($this, array($this->getOption('members_count_field') => $error));
			}

			if ($people_count > $granted_count)
			{
				$error = new sfValidatorError($this, 'too_much_people', array(
							'people_count' => $people_count,
							'granted_count' => $granted_count,
							));

				if ($this->getOption('throw_global_error'))
				{
					throw $error;
				}

				throw new sfValidatorErrorSchema($this, array($this->getOption('members_count_field') => $error));
			}
		}

    return $values;
	}
}

?>
