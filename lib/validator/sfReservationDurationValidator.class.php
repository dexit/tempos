<?php

class sfReservationDurationValidator extends sfValidatorSchema
{
  public function __construct($options = array(), $messages = array())
	{
    $this->addMessage('invalid_max', 'You may not book for more than %maximum_duration% consecutive minutes.');
    $this->addMessage('invalid_min', 'Minimum duration for a reservation is %minimum_duration% minutes.');
    $this->addMessage('invalid_step', 'You may only book by periods of %step% minutes.');
    $this->addMessage('no_duration', 'No duration found. Please contact your administrator !');

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

		$activity = ActivityPeer::retrieveByPK($values['Activity_id']);
		$roomId = isset($values['Room_id']) ? $values['Room_id'] : null;
		$step = sfConfig::get('app_booking_step');

		if (is_null($step))
		{
			throw new sfException('Cannot find `booking_step` configuration value.');
		}

		if (!is_null($activity))
		{
			if (!is_null($values['User_id']))
			{
				$user = UserPeer::retrieveByPK($values['User_id']);
				$minimum_duration = $user->getMinimumDuration($activity->getId(), $roomId);
				$maximum_duration = $user->getMaximumDuration($activity->getId(), $roomId);

			} else if (!is_null($values['Card_id']))
			{
				$card = CardPeer::retrieveByPK($values['Card_id']);
				$minimum_duration = $card->getMinimumDuration($activity->getId(), $roomId);
				$maximum_duration = $card->getMaximumDuration($activity->getId(), $roomId);
			} else
			{
				/* Trick to enforce potential new login objects (Like User or Card) to update this function */
				/* This way, the validator will always throw. */

				$minimum_duration = null;
				$maximum_duration = null;
			}

			if (($maximum_duration < 0) || (is_null($maximum_duration)))
			{
				$error = new sfValidatorError($this, 'no_duration', array());

				if ($this->getOption('throw_global_error'))
				{
					throw $error;
				}

				throw new sfValidatorErrorSchema($this, array('duration' => $error));
			}
			
			if ((floor($duration / $step) * $step) != $duration)
			{
				$error = new sfValidatorError($this, 'invalid_step', array(
							'step' => $step,
							));

				if ($this->getOption('throw_global_error'))
				{
					throw $error;
				}

				throw new sfValidatorErrorSchema($this, array('duration' => $error));
			}

			if ($duration < $minimum_duration)
			{
				$error = new sfValidatorError($this, 'invalid_min', array(
							'minimum_duration' => $minimum_duration,
							));

				if ($this->getOption('throw_global_error'))
				{
					throw $error;
				}

				throw new sfValidatorErrorSchema($this, array('duration' => $error));
			}

			if ($duration > $maximum_duration)
			{
				$error = new sfValidatorError($this, 'invalid_max', array(
							'maximum_duration' => $maximum_duration,
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
