<?php

class CloseperiodPeer extends BaseCloseperiodPeer
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

		$c->addAnd(CloseperiodPeer::ROOM_ID, $roomId, Criteria::EQUAL);

		return $c;
	}

	public static function getDayCriteria($date, $c = null)
	{
		if (is_null($c))
		{
			$c = new Criteria();
		}

		$cton1 = $c->getNewCriterion(CloseperiodPeer::STOP, strftime('%Y-%m-%d', ReservationPeer::getDayStart($date)), Criteria::GREATER_THAN);
		$cton2 = $c->getNewCriterion(CloseperiodPeer::START, strftime('%Y-%m-%d', ReservationPeer::getDayStop($date)), Criteria::LESS_THAN);

		$cton1->addAnd($cton2);

		$c->addAnd($cton1);

		return $c;
	}

	public static function getWeekCriteria($date, $c = null)
	{
		if (is_null($c))
		{
			$c = new Criteria();
		}

		$cton1 = $c->getNewCriterion(CloseperiodPeer::STOP, strftime('%Y-%m-%d', ReservationPeer::getWeekStart($date)), Criteria::GREATER_THAN);
		$cton2 = $c->getNewCriterion(CloseperiodPeer::START, strftime('%Y-%m-%d', ReservationPeer::getWeekStop($date)), Criteria::LESS_THAN);

		$cton1->addAnd($cton2);

		$c->addAnd($cton1);

		return $c;
	}

	public static function getMonthCriteria($date, $c = null)
	{
		if (is_null($c))
		{
			$c = new Criteria();
		}

		$cton1 = $c->getNewCriterion(CloseperiodPeer::STOP, strftime('%Y-%m-%d', ReservationPeer::getMonthStart($date)), Criteria::GREATER_THAN);
		$cton2 = $c->getNewCriterion(CloseperiodPeer::START, strftime('%Y-%m-%d', ReservationPeer::getMonthStop($date)), Criteria::LESS_THAN);

		$cton1->addAnd($cton2);

		$c->addAnd($cton1);

		return $c;
	}

	static public function getSortAliases()
	{
		return array(
			'start' => self::START,
			'stop' => self::STOP,
			'reason' => self::REASON,
		);
	}

	public static function getOverlappingCloseperiodsCount($id, $start, $stop, $roomId)
	{
		$c = self::getOverlappingCloseperiodsCriteria($id, $start, $stop, $roomId);

		return self::doCount($c);
	}

	public static function overlaps($id, $start, $stop, $roomId)
	{
		return (self::getOverlappingCloseperiodsCount($id, $start, $stop, $roomId) > 0);
	}

	public static function getOverlappingCloseperiodsCriteria($id, $start, $stop, $roomId)
	{
		$c = self::getFromRoomCriteria($roomId);

		if (!is_null($id))
		{
			$c->addAnd(CloseperiodPeer::ID, $id, Criteria::NOT_EQUAL);
		}

		$cton1 = $c->getNewCriterion(CloseperiodPeer::STOP, $start, Criteria::GREATER_THAN);
		$cton1b = $c->getNewCriterion(CloseperiodPeer::STOP, $stop, Criteria::LESS_EQUAL);
		$cton2 = $c->getNewCriterion(CloseperiodPeer::START, $start, Criteria::GREATER_EQUAL);
		$cton2b = $c->getNewCriterion(CloseperiodPeer::START, $stop, Criteria::LESS_THAN);

		$cton1->addAnd($cton1b);
		$cton2->addAnd($cton2b);

		$cton1->addOr($cton2);

		$c->addAnd($cton1);

		return $c;
	}
}
