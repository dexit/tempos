<?php

class ActivityPeer extends BaseActivityPeer
{
	static $cache = array();

	public static function retrieveByPK($pk, PropelPDO $con = null)
	{
		if (isset($cache[$pk]))
		{
			return $cache[$pk];
		} else
		{
			return $cache[$pk] = parent::retrieveByPK($pk, $con);
		}
	}

	static public function getSortAliases()
	{
		return array(
			'name' => self::NAME,
			'minimum_occupation' => self::MINIMUM_OCCUPATION,
			'maximum_occupation' => self::MAXIMUM_OCCUPATION,
			'minimum_delay' => self::MINIMUM_DELAY,
		);
	}

	public static function getUsergroupActivityCriteria($usergroupId, $c = null)
	{
		if (is_null($c))
		{
			$c = new Criteria();
		}

		$c->addJoin(UsergroupHasActivityPeer::ACTIVITY_ID, ActivityPeer::ID);
		$c->addAnd(UsergroupHasActivityPeer::USERGROUP_ID, $usergroupId, Criteria::EQUAL);

		return $c;
	}
}
