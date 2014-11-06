<?php

class sfReservationCreditValidator extends sfValidatorSchema
{
	public function __construct($options = array(), $messages = array())
	{
		$this->addMessage('invalid', 'You do not have enough credits. Remaining credits (minutes): %remaining_credit%');

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
				$subscriptions = $user->getActiveSubscriptions($date, $activity->getId(), $roomId);

			} else if (!is_null($values['Card_id']))
			{
				$card = CardPeer::retrieveByPK($values['Card_id']);
				$subscriptions = $card->getActiveSubscriptions($date, $activity->getId(), $roomId);
			} else
			{
				/* Trick to enforce potential new login objects (Like User or Card) to update this function */
				/* This way, the validator will always throw. */

				$subscriptions = null;
			}

			$valid = false;
			$maxAvailableDuration = 0;

			if (!empty($subscriptions))
			{
				foreach ($subscriptions as $subscription)
				{
					$remainingCredit = $subscription->getRemainingCredit($duration, $reservation_id);
					if ($remainingCredit >= 0)
					{
						$valid = true;
						break;
					}
					else if ($maxAvailableDuration < abs($remainingCredit))
					{
						/* We keep the maximum duration number for the reservation */
						$maxAvailableDuration = abs($remainingCredit);
					}
				}
			}

			if (!$valid)
			{		
				$error = new sfValidatorError($this, 'invalid', array(
							'remaining_credit' => $maxAvailableDuration,
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
