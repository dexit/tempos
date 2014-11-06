<?php

class DayperiodPeer extends BaseDayperiodPeer
{	
	public static function doSelectFromRoom($roomId, $c = null)
	{
		$c = self::getFromRoomCriteria($roomId, $c);

		return self::doSelect($c);
	}

	public static function getFromRoomCriteria($roomId, $c = null)
	{
		if (is_null($c))
		{
			$c = new Criteria();
		}

		$c->addAnd(DayperiodPeer::ROOM_ID, $roomId, Criteria::EQUAL);

		return $c;
	}

	public static function getOverlappingDayperiods($id, $start, $stop, $dayOfWeek, $roomId)
	{
		$c = self::getOverlappingDayperiodsCriteria($id, $start, $stop, $dayOfWeek, $roomId);

		return self::doSelect($c);
	}

	public static function getOverlappingDayperiodsCount($id, $start, $stop, $dayOfWeek, $roomId)
	{
		$c = self::getOverlappingDayperiodsCriteria($id, $start, $stop, $dayOfWeek, $roomId);

		return self::doCount($c);
	}

	public static function overlaps($id, $start, $stop, $dayOfWeek, $roomId)
	{
		return (self::getOverlappingDayperiodsCount($id, $start, $stop, $dayOfWeek, $roomId) > 0);
	}

	public static function getOverlappingDayperiodsCriteria($id, $start, $stop, $dayOfWeek, $roomId)
	{
		$c = self::getFromRoomCriteria($roomId);

		$c->addAnd(DayperiodPeer::DAY_OF_WEEK, $dayOfWeek, Criteria::EQUAL);

		if (!is_null($id))
		{
			$c->addAnd(DayperiodPeer::ID, $id, Criteria::NOT_EQUAL);
		}

		$stopHour = intval(strftime("%H", strtotime($stop)));
		$stopMinute = intval(strftime("%M", strtotime($stop)));

		if (($stopHour == 0) && ($stopMinute == 0))
		{
			// Starts after or when I start
			$cStartAfterMe = $c->getNewCriterion(DayperiodPeer::START, $start, Criteria::GREATER_EQUAL);

			// Ends after I start
			$cEndsAfterMe = $c->getNewCriterion(DayperiodPeer::STOP, $start, Criteria::GREATER_THAN);
			$cEndsAtMidnight = $c->getNewCriterion(DayperiodPeer::STOP, '00:00:00', Criteria::EQUAL);
			$cEndsAfterMe->addOr($cEndsAtMidnight);

			// Combine criterions

			$cStartAfterMe->addOr($cEndsAfterMe);

			$c->addAnd($cStartAfterMe);
		} else
		{
			// Starts before or when I start
			// AND
			// Ends after I start

			$cStartBeforeMe = $c->getNewCriterion(DayperiodPeer::START, $start, Criteria::LESS_EQUAL);
			$cEndsAfterMe = $c->getNewCriterion(DayperiodPeer::STOP, $start, Criteria::GREATER_THAN);
			$cEndsAtMidnight = $c->getNewCriterion(DayperiodPeer::STOP, '00:00:00', Criteria::EQUAL);

			$cEndsAfterMe->addOr($cEndsAtMidnight);
			$cStartBeforeMe->addAnd($cEndsAfterMe);

			// Starts after or when I start
			// AND
			// Starts before I end

			$cStartAfterMe = $c->getNewCriterion(DayperiodPeer::START, $start, Criteria::GREATER_EQUAL);
			$cStartBeforeIEnd = $c->getNewCriterion(DayperiodPeer::START, $stop, Criteria::LESS_THAN);

			$cStartAfterMe->addAnd($cStartBeforeIEnd);

			// Combine criterions

			$cStartBeforeMe->addOr($cStartAfterMe);

			$c->addAnd($cStartBeforeMe);
		}

		return $c;
	}
}
