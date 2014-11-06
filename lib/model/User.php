<?php

class User extends BaseUser implements Person
{
	protected $groups_as_member = null;
	protected $groups_as_leader = null;
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
		$this->groups_as_member = null;
		$this->groups_as_leader = null;
		$this->has_subscription_array = array();
		$this->minimum_delay_array = array();
		$this->maximum_delay_array = array();
		$this->minimum_duration_array = array();
		$this->maximum_duration_array = array();
		$this->hours_per_week_array = array();

		return $this;
	}

	public function getRoles()
	{
		$c = new Criteria();
		$c->add(UserHasRolePeer::USER_ID, $this->getId(), Criteria::EQUAL);

		$result = array();

		$userHasRoles = UserHasRolePeer::doSelect($c);

		if (!empty($userHasRoles))
		{
			foreach ($userHasRoles as $userHasRole)
			{
				$result[] = $userHasRole->getRole();
			}
		}

		return $result;
	}

	public function hasRole($roleId)
	{
		$c = new Criteria();

		$c->add(UserHasRolePeer::USER_ID, $this->getId(), Criteria::EQUAL);
		$c->addAnd(UserHasRolePeer::ROLE_ID, $roleId, Criteria::EQUAL);

		return (UserHasRolePeer::doCount($c) > 0);
	}

	public function __toString()
	{
		return $this->getFullName();
	}

	public function getFullName()
	{
		return sprintf('%s %s', $this->getFamilyName(), $this->getSurname());
	}

	public function setPassword($password)
	{
		$this->setPasswordHash(sha1($password));
	}

	public function checkPassword($password)
	{
		return (sha1($password) == $this->getPasswordHash());
	}

	public function autoSetLogin()
	{
		$login_base = $this->getAutoLoginSlug();
		$i = 2;
		$login = $login_base;

		while (UserPeer::loginExists($login))
		{
			$login = $login_base."-$i";
			$i++;
		}

		$this->setLogin($login);
	}

	public function autoSetPassword()
	{
		$phash = $this->getPasswordHash();

		if (empty($phash))
		{
			$password = exec('/usr/bin/pwgen -c 8 -n 1', $output, $return);

			if ($return != 0)
			{
				return null;
			}

			$this->setPassword($password);

			return $password;
		}

		return null;
	}

	public function autoCorrectNames()
	{
		$family_name = $this->getFamilyName();
		$surname = $this->getSurname();

		$family_name = ucwords(strtolower($family_name));
		$surname = ucwords(strtolower($surname));

		$family_name = str_replace(
			array('De'),
			array('de'),
			$family_name
		);

		$this->setFamilyName($family_name);
		$this->setSurname($surname);
	}

	protected function getAutoLoginSlug()
	{
		$surname_slug = $this->slug($this->getSurname());
		$family_name_slug = $this->slug($this->getFamilyName());
		$year_slug = $this->getBirthdate('%y');
		$login = substr($surname_slug, 0, 1).'.'.$family_name_slug.$year_slug;

		return $login;
	}

	protected function slug($str)
	{
		$str = strtolower(trim($str));
		$str = preg_replace('/[^a-z0-9-]/', '-', $str);
		$str = preg_replace('/-+/', '-', $str);

		return $str;
	}

	public function getSubscriptionForUsergroupCriteria($usergroupId, $zoneId = null, $activityId = null, $c = null)
	{
		$c = UserPeer::getSubscriptionForUsergroupCriteria($usergroupId, $zoneId, $activityId, $c);

		$c->addAnd(SubscriptionPeer::USER_ID, $this->getId(), Criteria::EQUAL);

		return $c;
	}

	public function countSubscriptionForUsergroup($usergroupId, $zoneId = null, $activityId = null, $c = null)
	{
		$c = $this->getSubscriptionForUsergroupCriteria($usergroupId, $zoneId, $activityId, $c);

		return SubscriptionPeer::doCount($c);
	}

	public function removeSubscriptionForUsergroup($usergroupId, $zoneId = null, $activityId = null, $c = null)
	{
		$c = $this->getSubscriptionForUsergroupCriteria($usergroupId, $zoneId, $activityId, $c);

		SubscriptionPeer::doDelete($c);
	}

	public function isGroupLeader()
	{
		return ($this->countUsergroupHasChiefs() > 0);
	}

	public function getGroupsAsMember()
	{
		if (is_null($this->groups_as_member))
		{
			$c = $this->getUsergroupAsMemberCriteria();

			return $this->groups_as_member = UsergroupPeer::doSelect($c);
		} else
		{
			return $this->groups_as_member;
		}
	}

	public function getGroupsAsLeader($activityId = null)
	{
		if (is_null($this->groups_as_leader) || (!is_null($activityId)))
		{
			$c = $this->getUsergroupAsLeaderCriteria($activityId);

			if (is_null($activityId))
			{
				$this->groups_as_leader = UsergroupPeer::doSelect($c);
			}

			return $this->groups_as_leader;
		} else
		{
			return $this->groups_as_leader;
		}
	}

	public function getGroups()
	{
		$c = new Criteria();

		$c->addJoin(UsergroupPeer::ID, UsergroupHasUserPeer::USERGROUP_ID);
		$c->addJoin(UsergroupPeer::ID, UsergroupHasChiefPeer::USERGROUP_ID);

		$cGroupsAsMember = $c->getNewCriterion(UsergroupHasUserPeer::USER_ID, $this->getId(), Criteria::EQUAL);
		$cGroupsAsLeader = $c->getNewCriterion(UsergroupHasChiefPeer::USER_ID, $this->getId(), Criteria::EQUAL);
		$cGroupsAsMember->addOr($cGroupsAsLeader);

		$c->addGroupByColumn(UsergroupPeer::ID);

		$c->add($cGroupsAsMember);

		return UsergroupPeer::doSelect($c);
	}

	public function isMember($usergroup = null)
	{
		$groups = $this->getGroupsAsMember();

		if (is_null($usergroup))
		{
			return (count($groups) > 0);
		} else
		{
			foreach ($groups as $group)
			{
				if ($group->getId() == $usergroup->getId())
				{
					return true;
				}
			}

			return false;
		}
	}

	public function isLeader($usergroup = null)
	{
		$groups = $this->getGroupsAsLeader();

		if (is_null($usergroup))
		{
			return (count($groups) > 0);
		} else
		{
			foreach ($groups as $group)
			{
				if ($group->getId() == $usergroup->getId())
				{
					return true;
				}
			}

			return false;
		}
	}

	public function sameGroup($user)
	{
		$groups = $this->getGroupsAsLeader();

		foreach ($groups as $group)
		{
			if ($user->isLeader($group))
			{
				return true;
			}
		}

		$groups = $this->getGroupsAsMember();

		foreach ($groups as $group)
		{
			if ($user->isMember($group))
			{
				return true;
			}
		}

		return false;
	}

	public function leadGroup($groupId)
	{
		if (!is_null($groupId))
		{
			$groups = $this->getGroupsAsLeader();

			foreach ($groups as $groupItem)
			{
				if ($groupId == $groupItem->getId())
				{
					return true;
				}
			}
		}

		return false;
	}

	public function getUsergroupAsMemberCriteria($activityId = null)
	{
		$c = new Criteria();
		$c->addJoin(UsergroupPeer::ID, UsergroupHasUserPeer::USERGROUP_ID);
		$c->add(UsergroupHasUserPeer::USER_ID, $this->getId());
		$c->addGroupByColumn(UsergroupPeer::ID);

		if (!is_null($activityId))
		{
			$c->addJoin(UsergroupPeer::ID, UsergroupHasActivityPeer::USERGROUP_ID);
			$c->addAnd(UsergroupHasActivityPeer::ACTIVITY_ID, $activityId, Criteria::EQUAL);
		}

		return $c;
	}

	public function getUsergroupAsLeaderCriteria($activityId = null)
	{
		$c = new Criteria();
		$c->addJoin(UsergroupPeer::ID, UsergroupHasChiefPeer::USERGROUP_ID);
		$c->add(UsergroupHasChiefPeer::USER_ID, $this->getId());
		$c->addGroupByColumn(UsergroupPeer::ID);

		if (!is_null($activityId))
		{
			$c->addJoin(UsergroupPeer::ID, UsergroupHasActivityPeer::USERGROUP_ID);
			$c->addAnd(UsergroupHasActivityPeer::ACTIVITY_ID, $activityId, Criteria::EQUAL);
		}

		return $c;
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
		return $this->getSurname();
	}

	public function getLastName()
	{
		return $this->getFamilyName();
	}

	public function getTagNumber()
	{
		return $this->getCardNumber();
	}

	public function getActiveSubscriptions($timestamp = null, $activityId = null, $roomId = null)
	{
		$c = SubscriptionPeer::getActiveCriteria($timestamp);
		$c->addAnd(SubscriptionPeer::USER_ID, $this->getId(), Criteria::EQUAL);

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
			$c->addAnd(SubscriptionPeer::USER_ID, $this->getId(), Criteria::EQUAL);
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
			$c->addAnd(SubscriptionPeer::USER_ID, $this->getId(), Criteria::EQUAL);
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
			$c->addAnd(SubscriptionPeer::USER_ID, $this->getId(), Criteria::EQUAL);
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
			$c->addAnd(SubscriptionPeer::USER_ID, $this->getId(), Criteria::EQUAL);
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
			$c->addAnd(SubscriptionPeer::USER_ID, $this->getId(), Criteria::EQUAL);
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

		$c->addAnd(ReservationPeer::USER_ID, $this->getId(), Criteria::EQUAL);
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
			$c->addAnd(SubscriptionPeer::USER_ID, $this->getId(), Criteria::EQUAL);
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
		$c->addAnd(SubscriptionPeer::USER_ID, $this->getId(), Criteria::EQUAL);
		$c->addJoin(SubscriptionPeer::ACTIVITY_ID, ActivityPeer::ID);
		$c->addGroupByColumn(ActivityPeer::ID);

		$c->addAscendingOrderByColumn(ActivityPeer::NAME);

		return ActivityPeer::doSelect($c);
	}

	public function hasActivity($activityId)
	{
		$c = SubscriptionPeer::getActiveCriteria();
		$c->addAnd(SubscriptionPeer::USER_ID, $this->getId(), Criteria::EQUAL);
		$c->addAnd(SubscriptionPeer::ACTIVITY_ID, $activityId);

		return (SubscriptionPeer::doCount($c) > 0);
	}

	public function getActiveSubscriptionsZones($activityId = null, $roomId = null)
	{
		$c = SubscriptionPeer::getActiveCriteria();
		$c->addAnd(SubscriptionPeer::USER_ID, $this->getId(), Criteria::EQUAL);
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
		if ($this->canEditReservation($reservation))
		{
			return true;
		}

		$user = $reservation->getUser();

		if (is_null($user))
		{
			return false;
		}

		if ($this->sameGroup($user))
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

		$user = $reservation->getUser();

		if (is_null($user))
		{
			return false;
		}

		if ($this->getMinimumDate($reservation->getActivityId(), $reservation->getRoomprofile()->getRoomId()) >= strtotime($reservation->getDate()))
		{
			return false;
		}

		if (($this->getId() == $user->getId()) || ($this->leadGroup($reservation->getUsergroupId())))
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
		if ($reservation->isPast())
		{
			return false;
		}

		if ($reservation->isBlocked())
		{
			return false;
		}

		$user = $reservation->getUser();

		if (is_null($user))
		{
			return false;
		} else
		{
			if ($user->getId() == $this->getId())
			{
				return false;
			}
		}

		return true;
	}

	public function getUpcomingReservations($count)
	{
		if (!is_int($count) || ($count <= 0))
		{
			throw new InvalidArgumentException('`$count` must be a strictly positive integer');
		}

		$c = new Criteria();

		$groups = $this->getGroupsAsMember();

		$cton1 = $c->getNewCriterion(ReservationPeer::USERGROUP_ID, PropelLogic::getIdList($groups), Criteria::IN);
		$cton2 = $c->getNewCriterion(ReservationPeer::USER_ID, $this->getId(), Criteria::EQUAL);
		$cton1->addOr($cton2);

		$c->add($cton1);
		$c->addAnd(ReservationPeer::DATE, strftime('%Y-%m-%d %H:%M:%S'), Criteria::GREATER_EQUAL);
		$c->addAscendingOrderByColumn(ReservationPeer::DATE);
		$c->setLimit($count);

		return ReservationPeer::doSelect($c);
	}
}
