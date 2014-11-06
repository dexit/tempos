<?php

class sfReservationCollideValidator extends sfValidatorBase
{
  public function __construct($options = array(), $messages = array())
	{
		parent::__construct($options, $messages);
	}

	public function configure($options = array(), $messages = array())
	{
		$this->setMessage('invalid', 'The reservation does not fit in the timetable.');
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
			$start = $values['date'];
			$stop = date('Y-m-d H:i:s', strtotime($start.' +'.$values['duration'].' minute'));
			$roomprofileId = $values['RoomProfile_id'];
			$roomprofile = RoomprofilePeer::retrieveByPk($roomprofileId);

			if (is_null($roomprofile))
			{
				$valid = true;
			} else
			{
				$roomId = $roomprofile->getRoomId();

				$valid = ReservationPeer::checkDayperiods($start, $stop, $roomId);

				if ($valid)
				{
					$valid = ReservationPeer::checkCloseperiods($start, $stop, $roomId);
				}
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
