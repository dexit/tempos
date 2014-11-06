<?php

class sfReservationHoursPerWeekValidator extends sfValidatorSchema
{
  public function __construct($options = array(), $messages = array())
	{
    $this->addMessage('invalid', 'You may not book more than %minutes_per_week% minutes in the same week. You already have %total%.');
    $this->addMessage('no_hours_per_week', 'No hours per week found. Please contact your administrator !');

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

		$duration = $values['duration'];

		if (is_null($duration))
		{
			return $values;
		}

		$date = $values['date'];

		if (is_null($date))
		{
			return $values;
		}

		$date = strtotime($date);

		$activity = ActivityPeer::retrieveByPK($values['Activity_id']);
		$roomId = isset($values['Room_id']) ? $values['Room_id'] : null;
		$reservation_id = isset($values['id']) ? $values['id'] : null;

		if (!is_null($activity))
		{
			if (!is_null($values['User_id']))
			{
				$user = UserPeer::retrieveByPK($values['User_id']);
				$hours_per_week = $user->getHoursPerWeek($activity->getId(), $roomId);
				$total = $user->countMinutesPerWeek($activity->getId(), $roomId, $date, $reservation_id);

			} else if (!is_null($values['Card_id']))
			{
				$card = CardPeer::retrieveByPK($values['Card_id']);
				$hours_per_week = $card->getHoursPerWeek($activity->getId(), $roomId);
				$total = $card->countMinutesPerWeek($activity->getId(), $roomId, $date, $reservation_id);
			} else
			{
				/* Trick to enforce potential new login objects (Like User or Card) to update this function */
				/* This way, the validator will always throw. */

				$hours_per_week = null;
				$total = null;
			}

			if (empty($total))
			{
				$total = 0;
			}

			if (($hours_per_week < 0) || (is_null($hours_per_week)))
			{
				$error = new sfValidatorError($this, 'no_hours_per_week', array());

				if ($this->getOption('throw_global_error'))
				{
					throw $error;
				}

				throw new sfValidatorErrorSchema($this, array('duration' => $error));
			}

			if ($total + $duration > $hours_per_week * 60)
			{
				$error = new sfValidatorError($this, 'invalid', array(
							'minutes_per_week' => $hours_per_week * 60,
							'total' => $total,
							));

				if ($this->getOption('throw_global_error'))
				{
					throw $error;
				}

				throw new sfValidatorErrorSchema($this, array('duration' => $error));
			}
		}

		return $values;
	}
}
