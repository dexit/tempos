<?php

class Usergroup extends BaseUsergroup
{
	protected $activities = null;

	public function __wakeup()
	{
		$this->reload(true);
	}

	public function reload($deep = false, PropelPDO $con = null)
	{
		parent::reload($deep, $con);

		$this->clearCache();
	}

	public function clearCache()
	{
		$this->activities = null;
	}

	public function __toString()
	{
		return $this->getName();
	}

	public function getUsers($c = null)
	{
		$c = UserPeer::getUsergroupCriteria($this->getId(), $c);
		$c->addAscendingOrderByColumn(UserPeer::FAMILY_NAME);
		$c->addAscendingOrderByColumn(UserPeer::SURNAME);

		return UserPeer::doSelect($c);
	}

	public function getMembers($c = null)
	{
		$c = UserPeer::getUsergroupMemberCriteria($this->getId(), $c);
		$c->addAscendingOrderByColumn(UserPeer::FAMILY_NAME);
		$c->addAscendingOrderByColumn(UserPeer::SURNAME);

		return UserPeer::doSelect($c);
	}

	public function getMembersCount($c = null)
	{
		$c = UserPeer::getUsergroupMemberCriteria($this->getId(), $c);

		return UserPeer::doCount($c);
	}

	public function getLeaders($c = null)
	{
		$c = UserPeer::getUsergroupLeaderCriteria($this->getId(), $c);
		$c->addAscendingOrderByColumn(UserPeer::FAMILY_NAME);
		$c->addAscendingOrderByColumn(UserPeer::SURNAME);

		return UserPeer::doSelect($c);
	}

	public function getLeadersCount($c = null)
	{
		$c = UserPeer::getUsergroupLeaderCriteria($this->getId(), $c);

		return UserPeer::doCount($c);
	}

	public function getNonMembers($c = null)
	{
		$c = UserPeer::getNonUsergroupCriteria($this->getId(), $c);
		$c->addAscendingOrderByColumn(UserPeer::FAMILY_NAME);
		$c->addAscendingOrderByColumn(UserPeer::SURNAME);

		return UserPeer::doSelect($c);
	}

	public function hasLeader($userId)
	{
		$c = new Criteria();
		$c->add(UsergroupHasChiefPeer::USERGROUP_ID, $this->getId(), Criteria::EQUAL);
		$c->addAnd(UsergroupHasChiefPeer::USER_ID, $userId, Criteria::EQUAL);

		return (UsergroupHasChiefPeer::doCount($c) > 0);
	}

	public function hasMember($userId)
	{
		$c = new Criteria();
		$c->add(UsergroupHasUserPeer::USERGROUP_ID, $this->getId(), Criteria::EQUAL);
		$c->addAnd(UsergroupHasUserPeer::USER_ID, $userId, Criteria::EQUAL);

		return (UsergroupHasUserPeer::doCount($c) > 0);
	}

	public function getActivities($c = null)
	{
		if (($c == null) && (!is_null($this->activities)))
		{
			return $this->activities;
		} else
		{
			$c = ActivityPeer::getUsergroupActivityCriteria($this->getId(), $c);
			$c->addAscendingOrderByColumn(ActivityPeer::NAME);

			$result = ActivityPeer::doSelect($c);

			if ($c == null)
			{
				$this->activities = $result;
			}

			return $result;
		}
	}

	public function getActivitiesCount($c = null)
	{
		$c = ActivityPeer::getUsergroupActivityCriteria($this->getId(), $c);

		return ActivityPeer::doCount($c);
	}

	public function filterActivities($activities)
	{
		$r = array();

		foreach ($activities as $activity)
		{
			if ($this->hasActivity($activity->getId()))
			{
				$r[] = $activity;
			}
		}

		return $r;
	}

	public function hasActivity($activityId)
	{
		$activities = $this->getActivities();

		foreach ($activities as $activity)
		{
			if ($activityId == $activity->getId())
			{
				return true;
			}
		}

		return false;
	}
}
