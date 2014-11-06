<?php

class Subscription extends BaseSubscription
{
	public function __toString()
	{
		return __("%1 - %2", $this->getActivity()->getName(), $this->getZone()->getName());
	}

	public function isActive($time = null)
	{
		if (is_null($time))
		{
			$time = time();
		}

		$c = SubscriptionPeer::getActiveCriteria($time);

		$c->addAnd(SubscriptionPeer::ID, $this->getId(), Criteria::EQUAL);

		return (SubscriptionPeer::doCount($c) > 0);
	}

	public function isStartValid()
	{
		return (is_null($this->getStart()) || (strtotime($this->getStart()) <= time()));
	}

	public function isStopValid()
	{
		return (is_null($this->getStop()) || (strtotime($this->getStop()) > time()));
	}

	public function getAllReservationsCount($reservation_id = null)
	{
		$c = ReservationPeer::getPeriodCriteria(strtotime($this->getStart()), strtotime($this->getStop()));

		$c->addAnd(ReservationPeer::ACTIVITY_ID, $this->getActivityId(), Criteria::EQUAL);

		if (!is_null($this->getUserId()))
		{
			$c->addAnd(ReservationPeer::USER_ID, $this->getUserId(), Criteria::EQUAL);
		}

		if (!is_null($this->getCardId()))
		{
			$c->addAnd(ReservationPeer::CARD_ID, $this->getCardId(), Criteria::EQUAL);
		}

		if (!is_null($reservation_id))
		{
			$c->addAnd(ReservationPeer::ID, $reservation_id, Criteria::NOT_EQUAL);
		}

		$c->clearSelectColumns();
		$c->addSelectColumn('SUM('.ReservationPeer::DURATION.')');

		$stmt = ReservationPeer::doSelectStmt($c);
		$row = $stmt->fetch(PDO::FETCH_NUM);

		return $row[0];
	}
	
	public function getRemainingCredit($duration, $reservation_id = null)
	{
		$credit = $this->getCredit();

		if (is_null($credit))
		{
			return 0;
		}

		$credit *= 60;

		$reservationsCount = $this->getAllReservationsCount($reservation_id);

		$all = $duration + $reservationsCount;

		$diff = $credit - $all;

		return $diff;
	}
}
