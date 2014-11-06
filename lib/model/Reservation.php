<?php

class Reservation extends BaseReservation
{
	const IDLE = 0;
	const SYNCHRONIZED = 1;
	const BLOCKED = 2;

	public function __toString()
	{
		$result = $this->getUserFullName();
		$result .= ' - '.$this->getActivity()->__toString();
		$result .= ' - '.$this->getRoomprofile()->getRoom()->__toString();
		$result .= ' - '.$this->getDateString();

		return $result;
	}
	
	public function getDateString()
	{
		$duration = $this->getDuration();
		$hourDuration = floor($duration / 60);
		$minuteDuration = $duration % 60;

		$result = $this->getDate();
		$result .= ' - '.$hourDuration.' hour(s)';

		if ($minuteDuration != 0)
		{
			$result .= ' '.$minuteDuration.' minute(s)';
		}

		return $result;
	}

	public function getUserFullName()
	{
		if (!is_null($this->getUser()))
		{
			return $this->getUser()->__toString();
		}

		if (!is_null($this->getCard()))
		{
			return $this->getCard()->__toString();
		}

		return null;
	}

	public function matchTimestamp($tst)
	{
		return (strtotime($this->getDate()) <= $tst) && (strtotime($this->getStopDate()) > $tst);
	}

	public function matchDayTimestamp($tst)
	{
		$dateStr = date('Y-m-d', $tst);

		return ($dateStr == $this->getDate('Y-m-d')) || ($dateStr == $this->getStopDate('Y-m-d'));
	}

	public function isNow()
	{
		return $this->matchTimestamp(time());
	}

	public function isPast()
	{
		return (time() > strtotime($this->getDate()));
	}

	public function isOld()
	{
		return (time() > strtotime($this->getStopDate()));
	}

	public function isIdle()
	{
		return ($this->getStatus() == self::IDLE);
	}

	public function isSynchronized()
	{
		return ($this->getStatus() == self::SYNCHRONIZED);
	}

	public function isBlocked()
	{
		return ($this->getStatus() == self::BLOCKED);
	}

	public function isInError()
	{
		return ($this->isOld() && $this->isIdle());
	}

	public function isForgotten()
	{
		return ($this->isOld() && $this->isSynchronized());
	}

	public function isEditable()
	{
		if ($this->isPast())
		{
			return false;
		}

		if ($this->isBlocked())
		{
			return false;
		}

		if ($this->isForgotten())
		{
			return false;
		}

		return true;
	}

	public function getStopDate($format = 'Y-m-d H:i:s')
	{
		$date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s', strtotime($this->getDate())).' +'.$this->getDuration().' minute'));

		try {
			$dt = new DateTime($date);
		} catch (Exception $x) {
			throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($date, true), $x);
		}

		if ($format === null) {
			// Because propel.useDateTimeClass is TRUE, we return a DateTime object.
			return $dt;
		} elseif (strpos($format, '%') !== false) {
			return strftime($format, $dt->format('U'));
		} else {
			return $dt->format($format);
		}
	}
	
	public function getDuration($tst = null)
	{
		if (is_null($tst))
		{
			return parent::getDuration();
		}

		if (date('Y-m-d', $tst) == $this->getDate('Y-m-d'))
		{
			$start = strtotime($this->getDate());
		} else
		{
			$start = mktime(0, 0, 0, date('m', $tst), date('d', $tst), date('Y', $tst));
		}

		if (date('Y-m-d', $tst) == $this->getStopDate('Y-m-d'))
		{
			$stop = strtotime($this->getStopDate());
		} else
		{
			$stop = mktime(0, 0, 0, date('m', $tst), date('d', $tst) + 1, date('Y', $tst));
		}
		
		return round(($stop - $start) / 60);
	}
	
	public function getDurationToIndex()
	{
		return floor($this->getDuration() / 30);
	}
	
	public function endDay() {
	}
	
	public function getDateToIndex($aDate = null)
	{
		if (is_null($aDate))
		{
			$aDate = $this->getDate();
		}
		
		$res = date('H', strtotime($aDate)) * 2;
		
		if (date('i', strtotime($aDate)) == 30)
		{
			$res++;
		}
		
		return $res;
		
	}

	public function roundDate($step = 30)
	{
		$hour = intval($this->getDate("%H"));
		$minute = intval($this->getDate("%M"));

		$base = floor(($hour * 60 + $minute) / $step) * $step;
		$newHour = $base / 60;
		$newMinute = $base % 60;

		$this->setDate(sprintf("%02d:%02d", $newHour, $newMinute));
	}
	
	public function updateDateWithDelay($delay = 0)
	{
		$year = intval($this->getDate("%Y"));
		$month = intval($this->getDate("%m"));
		$day = intval($this->getDate("%d"));
		$hour = intval($this->getDate("%H"));
		$minute = intval($this->getDate("%M"));

		$base = $hour * 60 + $minute - $delay;

		$newHour = $base / 60;
		$newMinute = $base % 60;

		$this->setDate(sprintf("%04d-%02d-%02d %02d:%02d:00", $year, $month, $day, $newHour, $newMinute));
		
		$duration = $this->getDuration();
		$duration = $duration + $delay;
		$this->setDuration($duration);
		
		$this->resetModified(); // To not save modifications
	}

	public function checkDayperiods()
	{
		return ReservationPeer::checkDayperiods($this->getDate(), $this->getStopDate(), $this->getRoomprofile()->getRoomId());
	}

	public function checkReservations()
	{
		return !ReservationPeer::overlaps($this->getId(), $this->getDate(), $this->getStopDate(), $this->getRoomprofile()->getRoomId());
	}

	public function getAllPersons()
	{
		$persons = array();

		if (!is_null($this->getUser()))
		{
			$usergroup = $this->getUsergroup();

			if (!is_null($usergroup))
			{
				$users = $usergroup->getMembers();

				foreach ($users as $user)
				{
					$persons[] = $user;
				}
			}
			else
			{
				$persons[] = $this->getUser();
			}
			
			$c = new Criteria();
			$c->add(ReservationOtherMembersPeer::RESERVATION_ID, $this->getId());

			$uniquePersonsArray = array();
			
			// Initialize the unique Person ID array
			foreach($persons as $p)
			{
				$uniquePersonsArray[$p->getId()] = 1;
			}
			
			$other_members = ReservationOtherMembersPeer::doSelect($c);

			if (!is_null($other_members))
			{
				foreach($other_members as $member)
				{
					if (isset($uniquePersonsArray[$member->getUserId()]))
					{
						// Do nothing, the user is already in the list
					}
					else
					{
						$persons[] = UserPeer::retrieveByPK($member->getUserId());
						$uniquePersonsArray[$member->getUserId()] = 1;
					}
				}
			}
		}
		else if (!is_null($this->getCard()))
		{
			$persons[] = $this->getCard();
		}

		return $persons;
	}
	
	/* Optimization */

	public function getUser(PropelPDO $con = null)
	{
		if ($this->aUser === null && ($this->user_id !== null)) {
			/* UserPeer::retrieveByPK is cached */
			$this->aUser = UserPeer::retrieveByPK($this->user_id, $con);
		}

		return $this->aUser;
	}

	public function getActivity(PropelPDO $con = null)
	{
		if ($this->aActivity === null && ($this->activity_id !== null)) {
			/* ActivityPeer::retrieveByPK is cached */
			$this->aActivity = ActivityPeer::retrieveByPK($this->activity_id, $con);
		}

		return $this->aActivity;
	}

	public function hasDaughters()
	{
		if ($this->countReservationsRelatedByReservationparentId() > 0)
		{
			return true;
		} else {
			return false;
		}
	}

	public function hasParent()
	{
		if ($this->getReservationparentId() != null) 
		{
			return true;
		} else {
			return false;
		}
	}


	public function setStatus($status)
	{
		switch ($status)
		{
			case self::IDLE:
			case self::SYNCHRONIZED:
			case self::BLOCKED:
			case self::FORGOTTEN:
				{
					parent::setStatus($status);
					return $this;
				}
		}

		throw new InvalidArgumentException(sprintf('Unknown status value: %d', $status));
	}
}
