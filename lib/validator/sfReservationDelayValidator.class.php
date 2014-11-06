<?php

class sfReservationDelayValidator extends sfValidatorSchema
{
  public function __construct($options = array(), $messages = array())
	{
    $this->addMessage('no_delay', 'No delay found ! Please contact an administrator.');
    $this->addMessage('no_subscription', 'No valid subscription found at this date ! Please contact an administrator.');
    $this->addMessage('minimum_delay', 'You cannot book that soon. Minimum delay: %minimum_delay% minute(s).');
    $this->addMessage('maximum_delay', 'You cannot book that far in the future. Maximum delay: %maximum_delay% day(s).');

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
		$now = time();

		if ($date < $now)
		{
			return $values;
		}

		$activity = ActivityPeer::retrieveByPK($values['Activity_id']);
		$roomId = isset($values['Room_id']) ? $values['Room_id'] : null;

		if (!is_null($activity))
		{
			$overall_minimum_delay = $activity->getMinimumDelay();
			$overall_minimum_date = $activity->getMinimumDate($now);

			if (!is_null($values['User_id']))
			{
				$user = UserPeer::retrieveByPK($values['User_id']);
				$minimum_delay = $user->getMinimumDelay($activity->getId(), $roomId);
				$minimum_date = $user->getMinimumDate($activity->getId(), $roomId, $now);
				$maximum_delay = $user->getMaximumDelay($activity->getId(), $roomId);
				$maximum_date = $user->getMaximumDate($activity->getId(), $roomId, $now);
				$has_subscription = $user->hasSubscription($activity->getId(), $roomId, $date);

			} else if (!is_null($values['Card_id']))
			{
				$card = CardPeer::retrieveByPK($values['Card_id']);
				$minimum_delay = $card->getMinimumDelay($activity->getId(), $roomId);
				$minimum_date = $card->getMinimumDate($activity->getId(), $roomId, $now);
				$maximum_delay = $card->getMaximumDelay($activity->getId(), $roomId);
				$maximum_date = $card->getMaximumDate($activity->getId(), $roomId, $now);
				$has_subscription = $card->hasSubscription($activity->getId(), $roomId, $date);
			} else
			{
				/* Trick to enforce potential new login objects (Like User or Card) to update this function */
				/* This way, the validator will always throw. */

				$has_subscription = false;
				$minimum_delay = null;
				$maximum_delay = null;
			}

			if (!$has_subscription)
			{
				$error = new sfValidatorError($this, 'no_subscription', array());

				if ($this->getOption('throw_global_error'))
				{
					throw $error;
				}

				throw new sfValidatorErrorSchema($this, array('date' => $error));
			}

			if ($date < $overall_minimum_date)
			{
				$error = new sfValidatorError($this, 'minimum_delay', array(
							'minimum_delay' => $overall_minimum_delay,
							));

				if ($this->getOption('throw_global_error'))
				{
					throw $error;
				}

				throw new sfValidatorErrorSchema($this, array('date' => $error));
			}

			if (($maximum_delay < 0) || (is_null($maximum_delay)))
			{
				$error = new sfValidatorError($this, 'no_delay', array());

				if ($this->getOption('throw_global_error'))
				{
					throw $error;
				}

				throw new sfValidatorErrorSchema($this, array('date' => $error));
			}

			if ($date >= $maximum_date)
			{
				$error = new sfValidatorError($this, 'maximum_delay', array(
							'maximum_delay' => $maximum_delay,
							));

				if ($this->getOption('throw_global_error'))
				{
					throw $error;
				}

				throw new sfValidatorErrorSchema($this, array('date' => $error));
			}
		}

		return $values;
	}
}
