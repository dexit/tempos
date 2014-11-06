<?php

class sfReservationOverlapValidator extends sfValidatorBase
{
  public function __construct($options = array(), $messages = array())
	{
		parent::__construct($options, $messages);
	}

	public function configure($options = array(), $messages = array())
	{
		$this->setMessage('invalid', 'An overlapping reservation exists.');
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

		if (is_null($values['date']))
		{
			$valid = true;
		} else
		{
			$roomprofileId = $values['RoomProfile_id'];
			$roomprofile = RoomprofilePeer::retrieveByPk($roomprofileId);

			if (is_null($roomprofile))
			{
				$valid = true;
			} else
			{
				$roomId = $roomprofile->getRoomId();
				$valid = !ReservationPeer::overlaps($values['id'], $values['date'], date('Y-m-d H:i:s', strtotime($values['date'].' +'.$values['duration'].' minute')), $roomId);
			}
		}

		if (!$valid)
		{
			$error = new sfValidatorError($this, 'invalid', array(
			));

			if ($this->getOption('throw_global_error'))
			{
				throw $error;
			}
			
			throw new sfValidatorErrorSchema($this, array('date' => $error));
		}

		return $values;
	}
}

?>
