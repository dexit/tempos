<?php

class Dayperiod extends BaseDayperiod
{
	public function __toString()
	{
		return sprintf("%s (%s - %s)", $this->getDayOfWeekName(), $this->getStart("%H:%M"), $this->getStop("%H:%M"));
	}

	public static function toWeekDay($timestamp)
	{
		// %u option let use start from 1 (monday) to 7 (sunday) but it's not working on Windows !!
		// We use the %w wich start from 0 (sunday) to 6 (saturday) and we simulate the %u option
	
		$intValue = intval(strftime("%w", $timestamp));
		if ($intValue == 0)	// if sunday, we use the right %u return value
			$intValue = 7;
		return $intValue;
	}
	
	public static function dayOfWeekToName($dayofweek)
	{
		$time = mktime(12, 0, 0, 6, 8 + $dayofweek, 2009);
		return strftime("%A", $time);
	}

	public static function dayOfWeekToShortName($dayofweek)
	{
		$time = mktime(12, 0, 0, 6, 8 + $dayofweek, 2009);
		return strftime("%a", $time);
	}

	public function getDayOfWeekName()
	{
		return self::dayOfWeekToName($this->getDayOfWeek());
	}

	public function getDayOfWeekShortName()
	{
		return self::dayOfWeekToShortName($this->getDayOfWeek());
	}

	public function getOverlappingDayperiods()
	{
		$c = $this->getOverlappingDayperiodsCriteria();

		return DayperiodPeer::doSelect($c);
	}

	public function getOverlappingDayperiodsCount()
	{
		$c = $this->getOverlappingDayperiodsCriteria();

		return DayperiodPeer::doCount($c);
	}

	public function overlaps()
	{
		return ($this->getOverlappingDayperiodsCount() > 0);
	}

	protected function getOverlappingDayperiodsCriteria()
	{
		return DayperiodPeer::getOverlappingDayperiodsCriteria($this->getId(), $this->getStart(), $this->getStop(), $this->getDayOfWeek(), $this->getRoomId());
	}

	public function matchTimestamp($tst)
	{
		$day = $this->getDayOfWeek();
		$tstDay = (self::toWeekDay($tst) - 1);
		
		if ($day != $tstDay)
		{
			return false;
		}

		$startHour = intval($this->getStart("%H"));
		$stopHour = intval($this->getStop("%H"));
		$tstHour = intval(strftime("%H", $tst));
		$startMinute = intval($this->getStart("%M"));
		$stopMinute = intval($this->getStop("%M"));
		$tstMinute = intval(strftime("%M", $tst));

		if ($startHour * 60 + $startMinute > $tstHour * 60 + $tstMinute)
		{
			return false;
		}

		if (($stopHour == 0) && ($stopMinute == 0))
		{
			return true;
		}

		if ($stopHour * 60 + $stopMinute <= $tstHour * 60 + $tstMinute)
		{
			return false;
		}

		return true;
	}

	public function getStartTimestamp($tst)
	{
		$startHour = intval($this->getStart("%H"));
		$startMinute = intval($this->getStart("%M"));

		return mktime($startHour, $startMinute, 0, date('m', $tst), date('d', $tst), date('Y', $tst));
	}

	public function getStopTimestamp($tst)
	{
		$stopHour = intval($this->getStop("%H"));
		$stopMinute = intval($this->getStop("%M"));

		if (($stopHour == 0) && ($stopMinute == 0))
		{
			return mktime(0, 0, 0, date('m', $tst), date('d', $tst) + 1, date('Y', $tst));
		} else
		{
			return mktime($stopHour, $stopMinute, 0, date('m', $tst), date('d', $tst), date('Y', $tst));
		}
	}

	public function getDuration()
	{
		$start = strtotime($this->getStart());
		$stop = strtotime($this->getStop());

		return Dayperiod::computeDuration($start, $stop);
	}

	public function getDurationIn($vstart, $vstop, $tst = null)
	{
		if (is_null($tst))
		{
			$tst = time();
		}

		$start = $this->getStartTimestamp($tst);
		$stop = $this->getStopTimestamp($tst);

		if (($vstop <= $start) || ($vstart >= $stop))
		{
			$result = 0;
		} else
		{
			$result = Dayperiod::computeDuration(max($vstart, $start), min($vstop, $stop));
		}

		return $result;
	}

	public function getStartIndex()
	{
		$startHour = intval($this->getStart("%H"));
		$startMinute = intval($this->getStart("%M"));

		return $startHour * 2 + round($startMinute / 30);
	}

	public function getStopIndex()
	{
		$stopHour = intval($this->getStop("%H"));
		$stopMinute = intval($this->getStop("%M"));

		if (($stopHour == 0) && ($stopMinute == 0))
		{
			return 48;
		}

		return $stopHour * 2 + round($stopMinute / 30);
	}

	public function roundStartStop($step = 30)
	{
		$startHour = intval($this->getStart("%H"));
		$stopHour = intval($this->getStop("%H"));
		$startMinute = intval($this->getStart("%M"));
		$stopMinute = intval($this->getStop("%M"));

		$startBase = floor(($startHour * 60 + $startMinute) / $step) * $step;
		$newStartHour = $startBase / 60;
		$newStartMinute = $startBase % 60;
		$stopBase = floor(($stopHour * 60 + $stopMinute) / $step) * $step;
		$newStopHour = $stopBase / 60;
		$newStopMinute = $stopBase % 60;

		$this->setStart(sprintf("%02d:%02d", $newStartHour, $newStartMinute));
		$this->setStop(sprintf("%02d:%02d", $newStopHour, $newStopMinute));
	}

	public function copyWithoutRoom()
	{
		$dayperiod = new Dayperiod();

		$dayperiod->setStart($this->getStart());
		$dayperiod->setStop($this->getStop());
		$dayperiod->setDayOfWeek($this->getDayOfWeek());

		return $dayperiod;
	}

	public function repeatWeek()
	{
		$room = $this->getRoom();

		if (!is_null($room))
		{
			$room->repeatDayperiodWeek($this);
		}
	}

	public static function computeDuration($start, $stop)
	{
		$startHour = intval(strftime('%H', $start));
		$stopHour = intval(strftime('%H', $stop));
		$startMinute = intval(strftime('%M', $start));
		$stopMinute = intval(strftime('%M', $stop));

		if (($stopHour == 0) && ($stopMinute == 0))
		{
			return (24 - $startHour) * 60 - $startMinute;
		} else
		{
			return ($stopHour - $startHour) * 60 + $stopMinute - $startMinute;
		}
	}
}
