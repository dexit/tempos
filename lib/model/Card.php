<?php

class Card extends BaseCard implements Person
{
	protected $has_subscription_array = array();
	protected $minimum_delay_array = array();
	protected $maximum_delay_array = array();
	protected $minimum_duration_array = array();
	protected $maximum_duration_array = array();
	protected $hours_per_week_array = array();

	// Called when object is deserialized from session cache
	public function __wakeup()
	{
		$this->reload(true);
	}

	public function reload($deep = false, PropelPDO $con = null)
	{
		parent::reload($deep, $con);

		$this->clearCache();
	}

	protected function clearCache()
	{
		$this->has_subscription_array = array();
		$this->minimum_delay_array = array();
		$this->maximum_delay_array = array();
		$this->minimum_duration_array = array();
		$this->maximum_duration_array = array();
		$this->hours_per_week_array = array();

		return $this;
	}

	public function __toString()
	{
		$result = $this->getCardNumber();

		$owner = $this->getOwnerObject();

		if (!is_null($owner))
		{
			$result .= " ($owner)";
		}

		return $result;
	}

	public function isOwned()
	{
		$owner = $this->getOwnerObject();

		if (!is_null($owner))
		{
			if ($owner->isEmpty())
			{
				return false;
			}
		}

		return (!is_null($owner));
	}

	public function getOwnerObject()
	{
		$c = new Criteria();

		$c->add(CarduserPeer::ID, $this->getOwner(), Criteria::EQUAL);

		return CarduserPeer::doSelectOne($c);
	}

	public function getOwnerName()
	{
		if ($this->isOwned())
		{
			return $this->getOwnerObject()->getFullName();
		} else
		{
			return sprintf('#%s', $this->getCardNumber());
		}
	}

	public function delete(PropelPDO $con = null)
	{
		$owner = $this->getOwnerObject();
		$this->setOwner(null);
		$this->save();

		if (!is_null($owner))
		{
			$owner->delete();
		}

		return parent::delete($con);
	}

	public function getSubscriptions($criteria = null, PropelPDO $con = null)
	{
		if (is_null($criteria))
		{
			$criteria = new Criteria();
		}

		$criteria->addDescendingOrderByColumn(SubscriptionPeer::STOP);
		$criteria->addDescendingOrderByColumn(SubscriptionPeer::START);

		return parent::getSubscriptions($criteria, $con);
	}

	/* Person implementation */

	public function getUniqueId()
	{
		if (is_null($this->getId()))
		{
			throw new Exception('Cannot build an unique id for an object which lacks a database id number.');
		}

		return sprintf('%s-%d', __CLASS__, $this->getId());
	}

	public function getFirstName()
	{
		$owner = $this->getOwnerObject();

		if (!is_null($owner))
		{
			return $owner->getSurname();
		}

		throw new Exception('Card is not owned !');
	}

	public function getLastName()
	{
		$owner = $this->getOwnerObject();

		if (!is_null($owner))
		{
			return $owner->getFamilyName();
		}

		throw new Exception('Card is not owned !');
	}

	public function getTagNumber()
	{
		return $this->getCardNumber();
	}

	public function getActiveSubscriptions($timestamp = null, $activityId = null, $roomId = null)
	{
		$c = SubscriptionPeer::getActiveCriteria($timestamp);
		$c->addAnd(SubscriptionPeer::CARD_ID, $this->getId(), Criteria::EQUAL);

		$c->addDescendingOrderByColumn(SubscriptionPeer::STOP);
		$c->addDescendingOrderByColumn(SubscriptionPeer::START);

		if (!is_null($activityId))
		{
			$c->addAnd(SubscriptionPeer::ACTIVITY_ID, $activityId);
		}

		if (!is_null($roomId))
		{
			$c = SubscriptionPeer::getHasRoomCriteria($roomId, $c);
		}

		return SubscriptionPeer::doSelect($c);
	}

	public function getMinimumDelay($activityId, $roomId)
	{
		if (!isset($this->minimum_delay_array[$activityId][$roomId]))
		{
			$c = SubscriptionPeer::getActiveCriteria();
			$c = SubscriptionPeer::getHasRoomCriteria($roomId, $c);
			$c->addAnd(SubscriptionPeer::CARD_ID, $this->getId(), Criteria::EQUAL);
			$c->addAnd(SubscriptionPeer::ACTIVITY_ID, $activityId, Criteria::EQUAL);
			$c->clearSelectColumns();
			$c->addSelectColumn(SubscriptionPeer::MINIMUM_DELAY);
			$c->addDescendingOrderByColumn(SubscriptionPeer::MINIMUM_DELAY);
			$c->setLimit(1);

			$stmt = SubscriptionPeer::doSelectStmt($c);

			if ($row = $stmt->fetch(PDO::FETCH_NUM))
			{
				$this->minimum_delay_array[$activityId][$roomId] = $row[0];
				return $row[0];
			}

			$this->minimum_delay_array[$activityId][$roomId] = null;
			return null;
		} else
		{
			return $this->minimum_delay_array[$activityId][$roomId];
		}
	}

	public function getMaximumDelay($activityId, $roomId)
	{
		if (!isset($this->maximum_delay_array[$activityId][$roomId]))
		{
			$c = SubscriptionPeer::getActiveCriteria();
			$c = SubscriptionPeer::getHasRoomCriteria($roomId, $c);
			$c->addAnd(SubscriptionPeer::CARD_ID, $this->getId(), Criteria::EQUAL);
			$c->addAnd(SubscriptionPeer::ACTIVITY_ID, $activityId, Criteria::EQUAL);
			$c->clearSelectColumns();
			$c->addSelectColumn(SubscriptionPeer::MAXIMUM_DELAY);
			$c->addDescendingOrderByColumn(SubscriptionPeer::MAXIMUM_DELAY);
			$c->setLimit(1);

			$stmt = SubscriptionPeer::doSelectStmt($c);

			if ($row = $stmt->fetch(PDO::FETCH_NUM))
			{
				$this->maximum_delay_array[$activityId][$roomId] = $row[0];
				return $row[0];
			}

			$this->maximum_delay_array[$activityId][$roomId] = null;
			return null;
		} else
		{
			return $this->maximum_delay_array[$activityId][$roomId];
		}
	}

	public function getMinimumDate($activityId, $roomId, $tst = null)
	{
		if (empty($tst))
		{
			$tst = time();
		}

		return strtotime(date('Y-m-d H:i:s', $tst).' + '.$this->getMinimumDelay($activityId, $roomId).' hours');
	}

	public function getMaximumDate($activityId, $roomId, $tst = null)
	{
		if (empty($tst))
		{
			$tst = time();
		}

		return strtotime(date('Y-m-d H:i:s', $tst).' + '.$this->getMaximumDelay($activityId, $roomId).' days');
	}

	public function getMinimumDuration($activityId, $roomId)
	{
		if (!isset($this->minimum_duration_array[$activityId]))
		{
			$c = SubscriptionPeer::getActiveCriteria();
			$c = SubscriptionPeer::getHasRoomCriteria($roomId, $c);
			$c->addAnd(SubscriptionPeer::CARD_ID, $this->getId(), Criteria::EQUAL);
			$c->addAnd(SubscriptionPeer::ACTIVITY_ID, $activityId, Criteria::EQUAL);
			$c->clearSelectColumns();
			$c->addSelectColumn(SubscriptionPeer::MINIMUM_DURATION);
			$c->addAscendingOrderByColumn(SubscriptionPeer::MINIMUM_DURATION);
			$c->setLimit(1);

			$stmt = SubscriptionPeer::doSelectStmt($c);

			if ($row = $stmt->fetch(PDO::FETCH_NUM))
			{
				$this->minimum_duration_array[$activityId] = $row[0];
				return $row[0];
			}

			$this->minimum_duration_array[$activityId] = null;
			return null;
		} else
		{
			return $this->minimum_duration_array[$activityId];
		}
	}

	public function getMaximumDuration($activityId, $roomId)
	{
		if (!isset($this->maximum_duration_array[$activityId]))
		{
			$c = SubscriptionPeer::getActiveCriteria();
			$c = SubscriptionPeer::getHasRoomCriteria($roomId, $c);
			$c->addAnd(SubscriptionPeer::CARD_ID, $this->getId(), Criteria::EQUAL);
			$c->addAnd(SubscriptionPeer::ACTIVITY_ID, $activityId, Criteria::EQUAL);
			$c->clearSelectColumns();
			$c->addSelectColumn(SubscriptionPeer::MAXIMUM_DURATION);
			$c->addDescendingOrderByColumn(SubscriptionPeer::MAXIMUM_DURATION);
			$c->setLimit(1);

			$stmt = SubscriptionPeer::doSelectStmt($c);

			if ($row = $stmt->fetch(PDO::FETCH_NUM))
			{
				$this->maximum_duration_array[$activityId] = $row[0];
				return $row[0];
			}

			$this->maximum_duration_array[$activityId] = null;
			return null;
		} else
		{
			return $this->maximum_duration_array[$activityId];
		}
	}

	public function getHoursPerWeek($activityId, $roomId)
	{
		if (!isset($this->hours_per_week_array[$activityId]))
		{
			$c = SubscriptionPeer::getActiveCriteria();
			$c = SubscriptionPeer::getHasRoomCriteria($roomId, $c);
			$c->addAnd(SubscriptionPeer::CARD_ID, $this->getId(), Criteria::EQUAL);
			$c->addAnd(SubscriptionPeer::ACTIVITY_ID, $activityId, Criteria::EQUAL);
			$c->clearSelectColumns();
			$c->addSelectColumn('MAX('.SubscriptionPeer::HOURS_PER_WEEK.')');
			$c->setLimit(1);

			$stmt = SubscriptionPeer::doSelectStmt($c);

			if ($row = $stmt->fetch(PDO::FETCH_NUM))
			{
				$this->hours_per_week_array[$activityId] = $row[0];
				return $row[0];
			}

			$this->hours_per_week_array[$activityId] = null;
			return null;
		} else
		{
			return $this->hours_per_week_array[$activityId];
		}
	}

	public function countMinutesPerWeek($activityId, $roomId, $tst, $reservation_id = null)
	{
		$c = ReservationPeer::getWeekCriteria($tst);

		$c->addAnd(ReservationPeer::CARD_ID, $this->getId(), Criteria::EQUAL);
		$c->addAnd(ReservationPeer::ACTIVITY_ID, $activityId, Criteria::EQUAL);

		$zones = $this->getActiveSubscriptionsZones($activityId, $roomId);
		$rooms = ZonePeer::getRooms($zones);
		$rooms_ids = array();

		foreach ($rooms as $room)
		{
			$rooms_ids[] = $room->getId();
		}

		$c->addJoin(ReservationPeer::ROOMPROFILE_ID, RoomprofilePeer::ID);
		$c->addAnd(RoomprofilePeer::ROOM_ID, $rooms_ids, Criteria::IN);

		if (!is_null($reservation_id))
		{
			$c->addand(ReservationPeer::ID, $reservation_id, Criteria::NOT_EQUAL);
		}

		$c->clearSelectColumns();
		$c->addSelectColumn('SUM('.ReservationPeer::DURATION.')');
		$c->setLimit(1);

		$stmt = ReservationPeer::doSelectStmt($c);

		if ($row = $stmt->fetch(PDO::FETCH_NUM))
		{
			return $row[0];
		}

		return 0;
	}

	public function hasSubscription($activityId, $roomId, $timestamp = null)
	{
		if (!is_null($timestamp))
		{
			$timestamp = mktime(0, 0, 0, date('n', $timestamp), date('d', $timestamp), date('Y', $timestamp));
		}

		if (!isset($this->has_subscription_array[$activityId][$roomId][$timestamp]))
		{
			$c = SubscriptionPeer::getActiveCriteria($timestamp);
			$c = SubscriptionPeer::getHasRoomCriteria($roomId, $c);
			$c->addAnd(SubscriptionPeer::CARD_ID, $this->getId(), Criteria::EQUAL);
			$c->addAnd(SubscriptionPeer::ACTIVITY_ID, $activityId, Criteria::EQUAL);

			$result = (SubscriptionPeer::doCount($c) > 0);
			$this->has_subscription_array[$activityId][$roomId][$timestamp] = $result;

			return $result;
		} else
		{
			return $this->has_subscription_array[$activityId][$roomId][$timestamp];
		}
	}

	public function getActiveSubscriptionsActivities()
	{
		$c = SubscriptionPeer::getActiveCriteria();
		$c->addAnd(SubscriptionPeer::CARD_ID, $this->getId(), Criteria::EQUAL);
		$c->addJoin(SubscriptionPeer::ACTIVITY_ID, ActivityPeer::ID);
		$c->addGroupByColumn(ActivityPeer::ID);

		$c->addAscendingOrderByColumn(ActivityPeer::NAME);

		return ActivityPeer::doSelect($c);
	}

	public function hasActivity($activityId)
	{
		$c = SubscriptionPeer::getActiveCriteria();
		$c->addAnd(SubscriptionPeer::CARD_ID, $this->getId(), Criteria::EQUAL);
		$c->addAnd(SubscriptionPeer::ACTIVITY_ID, $activityId);

		return (SubscriptionPeer::doCount($c) > 0);
	}

	public function getActiveSubscriptionsZones($activityId = null, $roomId = null)
	{
		$c = SubscriptionPeer::getActiveCriteria();
		$c->addAnd(SubscriptionPeer::CARD_ID, $this->getId(), Criteria::EQUAL);
		$c->addJoin(SubscriptionPeer::ZONE_ID, ZonePeer::ID);

		if (!is_null($activityId))
		{
			$c->addAnd(SubscriptionPeer::ACTIVITY_ID, $activityId);
		}

		if (!is_null($roomId))
		{
			$c = SubscriptionPeer::getHasRoomCriteria($roomId, $c);
		}

		$c->addGroupByColumn(ZonePeer::ID);

		return ZonePeer::doSelect($c);
	}

	public function canAccessRoom($roomId, $activityId = null)
	{
		$zones = $this->getActiveSubscriptionsZones($activityId);

		foreach ($zones as $zone)
		{
			if ($zone->hasRoom($roomId))
			{
				if (RoomPeer::isRoomActive($roomId))
				{
					return true;
				}
			}
		}

		return false;
	}

	public function filterAccessibleRooms($rooms)
	{
		$result = array();

		foreach ($rooms as $room)
		{
			if ($this->canAccessRoom($room->getId()))
			{
				$result[] = $room;
			}
		}

		return $result;
	}

	public function canSeeReservationDetails($reservation)
	{
		$card = $reservation->getCard();

		if (is_null($card))
		{
			return false;
		}

		if ($this->getId() == $card->getId())
		{
			return true;
		}

		return false;
	}

	public function canEditReservation($reservation)
	{
		if (!$reservation->isEditable())
		{
			return false;
		}

		$card = $reservation->getCard();

		if (is_null($card))
		{
			return false;
		}

		if ($this->getMinimumDate($reservation->getActivityId(), $reservation->getRoomprofile()->getRoomId()) >= strtotime($reservation->getDate()))
		{
			return false;
		}

		if ($this->getId() == $card->getId())
		{
			return true;
		}

		return false;
	}

	public function canDeleteReservation($reservation)
	{
		return $this->canEditReservation($reservation);
	}

	public function canSendMessage($reservation)
	{
		return false;
	}

	public function getUpcomingReservations($count)
	{
		if (!is_int($count) || ($count <= 0))
		{
			throw new InvalidArgumentException('`$count` must be a strictly positive integer');
		}

		$c = new Criteria();

		$c->add(ReservationPeer::CARD_ID, $this->getId(), Criteria::EQUAL);
		$c->addAnd(ReservationPeer::DATE, strftime('%Y-%m-%d'), Criteria::GREATER_EQUAL);
		$c->addAscendingOrderByColumn(ReservationPeer::DATE);
		$c->setLimit($count);

		return ReservationPeer::doSelect($c);
	}
}
