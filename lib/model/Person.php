<?php

interface Person
{
	public function getUniqueId();
	public function getFirstName();
	public function getLastName();
	public function getTagNumber();
	public function getActiveSubscriptions($timestamp = null, $activityId = null, $roomId = null);
	public function getMinimumDelay($activityId, $roomId);
	public function getMaximumDelay($activityId, $roomId);
	public function getMinimumDate($activityId, $roomId, $tst = null);
	public function getMaximumDate($activityId, $roomId, $tst = null);
	public function getMinimumDuration($activityId, $roomId);
	public function getMaximumDuration($activityId, $roomId);
	public function getHoursPerWeek($activityId, $roomId);
	public function countMinutesPerWeek($activityId, $roomId, $tst, $reservation_id = null);
	public function hasSubscription($activityId, $roomId, $timestamp = null);
	public function getActiveSubscriptionsActivities();
	public function hasActivity($activityId);
	public function getActiveSubscriptionsZones($activityId = null, $roomId = null);
	public function canAccessRoom($roomId, $activityId = null);
	public function filterAccessibleRooms($rooms);
	public function canSeeReservationDetails($reservation);
	public function canEditReservation($reservation);
	public function canDeleteReservation($reservation);
	public function canSendMessage($reservation);
	public function getUpcomingReservations($count);
}
