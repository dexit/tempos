<?php

class Closeperiod extends BaseCloseperiod
{
	public function __toString()
	{
		return $this->getPeriod();
	}

	public function getPeriod()
	{
		return sprintf('%s - %s', $this->getStart(), $this->getStop());
	}

	public function matchTimestamp($tst)
	{
		return (strtotime($this->getStart()) <= $tst) && (strtotime($this->getStop()) > $tst);
	}

	public function matchFullDayTimestamp($tst)
	{
		$start = strtotime($this->getStart());
		$stop = strtotime($this->getStop());
		$dayStart = mktime(0, 0, 0, date('m', $tst), date('d', $tst), date('Y', $tst));
		$dayStop = mktime(0, 0, 0, date('m', $tst), date('d', $tst) + 1, date('Y', $tst));

		return ($start <= $dayStart) && ($stop >= $dayStop);
	}

	public function matchDayTimestamp($tst)
	{
		$dateStr = date('Y-m-d', $tst);
		$dateStart = $this->getStart('Y-m-d');
		$dateStop = $this->getStop('Y-m-d');

		if (($dateStr == $dateStart) || ($dateStr == $dateStop))
		{
			if (($this->getStop('H') != 0) || ($this->getStop('i') != 0))
			{
				return true;
			}
		}

		return false;
	}

	public function getDayCloseDuration($tst)
	{
		$start = strtotime($this->getStart());
		$stop = strtotime($this->getStop());
		$dayStart = mktime(0, 0, 0, date('m', $tst), date('d', $tst), date('Y', $tst));
		$dayStop = mktime(0, 0, 0, date('m', $tst), date('d', $tst) + 1, date('Y', $tst));

		if ($start < $dayStart)
		{
			$start = $dayStart;
		}

		if ($stop > $dayStop)
		{
			$stop = $dayStop;
		}
		
		$dayOfWeek = date('N', $tst) - 1;

		$c = DayperiodPeer::getFromRoomCriteria($this->getRoomId());

		$c->addAnd(DayperiodPeer::DAY_OF_WEEK, $dayOfWeek, Criteria::EQUAL);

		$dayperiods = DayperiodPeer::doSelect($c);

		if (count($dayperiods) > 0)
		{
			$result = 0;

			foreach ($dayperiods as $dayperiod)
			{
				$result += $dayperiod->getDurationIn($start, $stop, $dayStart);
			}

			return $result;
		} else
		{
			return 0;
		}
	}
}
