<?php

class ReservationreasonPeer extends BaseReservationreasonPeer
{
	public static function doSelectFromActivity($activityId)
	{
		$c = self::getFromActivityCriteria($activityId);
		$c->addAscendingOrderByColumn(ReservationreasonPeer::NAME);

		return self::doSelect($c);
	}

	public static function getFromActivityCriteria($activityId)
	{
		$c = new Criteria();

		$c->add(ReservationreasonPeer::ACTIVITY_ID, $activityId, Criteria::EQUAL);
		$c->addAscendingOrderByColumn(ReservationreasonPeer::NAME);

		return $c;
	}
}
