<?php

class UserPeer extends BaseUserPeer
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

	public static function retrieveByLogin($login)
	{
		$c = new Criteria();
		$c->add(UserPeer::LOGIN, $login, Criteria::EQUAL);

		return self::doSelectOne($c);
	}

	static public function authenticate($login, $password)
	{
		$passwordHash = sha1($password);

		$c = new Criteria();
		$c->add(UserPeer::LOGIN, $login, Criteria::EQUAL);
		$c->addAnd(UserPeer::PASSWORD_HASH, $passwordHash, Criteria::EQUAL);
		$c->addAnd(UserPeer::IS_ACTIVE, true, Criteria::EQUAL);
		$c->addAnd(UserPeer::IS_MEMBER, true, Criteria::EQUAL);

		$user = UserPeer::doSelectOne($c);

		return $user;
	}

	static public function doSelectMembers($c = null)
	{
		$c = self::getMemberCriteria($c);

		return self::doSelect($c);
	}

	static public function doCountMembers($c = null)
	{
		$c = self::getMemberCriteria($c);

		return self::doCount($c);
	}

	static public function getSortAliases()
	{
		return array(
			'name' => array(self::FAMILY_NAME, self::SURNAME),
			'login' => self::LOGIN,
			'card_number' => self::CARD_NUMBER,
		);
	}

	static public function searchUsers($login, $family_name, $surname, $usergroupsAsLeader, $usergroupsAsMember, $activities, $is_active, $card_number, $begin_date, $end_date, $email_address, $address, $phone_number, $crit = null)
	{
		$c = self::getMemberCriteria($crit);

		if (!empty($login))
		{
			$c->addAnd(UserPeer::LOGIN, '%'.$login.'%', Criteria::LIKE);
		}

		if (!empty($family_name))
		{
			$c->addAnd(UserPeer::FAMILY_NAME, '%'.$family_name.'%', Criteria::LIKE);
		}

		if (!empty($surname))
		{
			$c->addAnd(UserPeer::SURNAME, '%'.$surname.'%', Criteria::LIKE);
		}

		if (!empty($usergroupsAsLeader))
		{
			$c->addJoin(UsergroupHasChiefPeer::USER_ID, UserPeer::ID);

			$values_string = implode(',', $usergroupsAsLeader);

			$subSelect = sprintf("(SELECT COUNT(*) FROM %s WHERE (%s = %s AND (%s IN (%s)))) = %d", 
				UsergroupHasChiefPeer::TABLE_NAME,
				UsergroupHasChiefPeer::USER_ID,
				UserPeer::ID,
				UsergroupHasChiefPeer::USERGROUP_ID,
				$values_string,
				count($usergroupsAsLeader)
			);

			$c->addAnd(UserPeer::ID, $subSelect, Criteria::CUSTOM);
		}

		if (!empty($usergroupsAsMember))
		{
			$c->addJoin(UsergroupHasUserPeer::USER_ID, UserPeer::ID);

			$values_string = implode(',', $usergroupsAsMember);

			$subSelect = sprintf("(SELECT COUNT(*) FROM %s WHERE (%s = %s AND (%s IN (%s)))) = %d", 
				UsergroupHasUserPeer::TABLE_NAME,
				UsergroupHasUserPeer::USER_ID,
				UserPeer::ID,
				UsergroupHasUserPeer::USERGROUP_ID,
				$values_string,
				count($usergroupsAsMember)
			);

			$c->addAnd(UserPeer::ID, $subSelect, Criteria::CUSTOM);
		}

		if (!empty($activities))
		{
			$values_string = implode(',', $activities);

			$subSelect = sprintf("(SELECT COUNT(*) FROM %s WHERE (%s = %s AND (%s IN (%s))))", 
				SubscriptionPeer::TABLE_NAME,
				SubscriptionPeer::USER_ID,
				UserPeer::ID,
				SubscriptionPeer::ACTIVITY_ID,
				$values_string
			);

			$c->addAnd(UserPeer::ID, $subSelect, Criteria::CUSTOM);
		}

		if (!is_null($is_active))
		{
			$c->addAnd(UserPeer::IS_ACTIVE, $is_active, Criteria::EQUAL);
		}

		if (!empty($card_number))
		{
			$c->addAnd(UserPeer::CARD_NUMBER, '%'.$card_number.'%', Criteria::LIKE);
		}

		if (!is_null($begin_date))
		{
			$begin_date = date('Y-m-d', $begin_date);
			$c->addAnd(UserPeer::BIRTHDATE, $begin_date, Criteria::GREATER_EQUAL);
			$c->addOr(UserPeer::BIRTHDATE, null, Criteria::ISNULL);
		}

		if (!is_null($end_date))
		{
			$end_date = date('Y-m-d', $end_date);
			$c->addAnd(UserPeer::BIRTHDATE, $end_date, Criteria::LESS_EQUAL);
			$c->addOr(UserPeer::BIRTHDATE, null, Criteria::ISNULL);
		}

		if (!empty($email_address))
		{
			$c->addAnd(UserPeer::EMAIL_ADDRESS, '%'.$email_address.'%', Criteria::LIKE);
		}

		if (!empty($address))
		{
			$c->addAnd(UserPeer::ADDRESS, '%'.$address.'%', Criteria::LIKE);
		}

		if (!empty($phone_number))
		{
			$c->addAnd(UserPeer::PHONE_NUMBER, '%'.$phone_number.'%', Criteria::LIKE);
		}

		$c->addGroupByColumn(UserPeer::ID);

		return self::doSelect($c);
	}

	static public function doSelectVisitor($offset = 0, $c = null)
	{
		$c = self::getVisitorCriteria($c);

		$c->setOffset($offset);

		return self::doSelectOne($c);
	}

	static public function getVisitorsCount($c = null)
	{
		$c = self::getVisitorCriteria($c);

		return self::doCount($c);
	}

	static public function loginExists($login)
	{
		$c = new Criteria();
		$c->add(UserPeer::LOGIN, $login, Criteria::EQUAL);

		return (self::doCount($c) > 0);
	}

# Criterias

	static public function getSortByFamilyNameAscCriteria($c = null)
	{
		if (is_null($c))
		{
			$c = new Criteria();
		}

		$c->addAscendingOrderByColumn(UserPeer::FAMILY_NAME);

		return $c;
	}

	static public function getSortBySurnameAscCriteria($c = null)
	{
		if (is_null($c))
		{
			$c = new Criteria();
		}

		$c->addAscendingOrderByColumn(UserPeer::SURNAME);

		return $c;
	}

	static public function getSortByNameAscCriteria($c = null)
	{
		$c = self::getSortByFamilyNameAscCriteria($c);
		$c = self::getSortBySurnameAscCriteria($c);

		return $c;
	}

	static public function getSearchNamePatternCriteria($namePattern, $c = null)
	{
		if (is_null($c))
		{
			$c = new Criteria();
		}

		$cton1 = $c->getNewCriterion(UserPeer::FAMILY_NAME, '%'.$namePattern.'%', Criteria::LIKE);
		$cton2 = $c->getNewCriterion(UserPeer::SURNAME, '%'.$namePattern.'%', Criteria::LIKE);
		$cton3 = $c->getNewCriterion(UserPeer::LOGIN, '%'.$namePattern.'%', Criteria::LIKE);

		$cton1->addOr($cton2);
		$cton1->addOr($cton3);

		$c->add($cton1);

		return $c;
	}

	static public function getSortByCreationDateAscCriteria($c = null)
	{
		if (is_null($c))
		{
			$c = new Criteria();
		}

		$c->addAscendingOrderByColumn(UserPeer::CREATED_AT);

		return $c;
	}

	static public function getMemberCriteria($c = null)
	{
		if (is_null($c))
		{
			$c = new Criteria();
		}

		$c->add(UserPeer::IS_MEMBER, true, Criteria::EQUAL);

		return $c;
	}

	static public function getVisitorCriteria($c = null)
	{
		if (is_null($c))
		{
			$c = new Criteria();
		}

		$c->add(UserPeer::IS_MEMBER, false, Criteria::EQUAL);

		return $c;
	}

	static public function getUsergroupCriteria($usergroupId, $c = null)
	{
		if (is_null($c))
		{
			$c = new Criteria();
		}

		$c->add(UserPeer::ID, 
			sprintf(
				'%s IN (SELECT %s FROM %s WHERE %s = %s)',
				UserPeer::ID,
				UsergroupHasUserPeer::USER_ID,
				UsergroupHasUserPeer::TABLE_NAME,
				UsergroupHasUserPeer::USERGROUP_ID,
				$usergroupId
			),
			Criteria::CUSTOM
		);
		
		$c->addOr(UserPeer::ID, 
			sprintf(
				'%s IN (SELECT %s FROM %s WHERE %s = %s)',
				UserPeer::ID,
				UsergroupHasChiefPeer::USER_ID,
				UsergroupHasChiefPeer::TABLE_NAME,
				UsergroupHasChiefPeer::USERGROUP_ID,
				$usergroupId
			),
			Criteria::CUSTOM
		);
		
		$c->addGroupByColumn(UserPeer::ID);

		return $c;
	}

	static public function getUsergroupMemberCriteria($usergroupId, $c = null)
	{
		if (is_null($c))
		{
			$c = new Criteria();
		}

		$c->addAnd(UsergroupHasUserPeer::USERGROUP_ID, $usergroupId, Criteria::EQUAL);
		$c->addJoin(UsergroupHasUserPeer::USER_ID, UserPeer::ID);

		return $c;
	}

	static public function getUsergroupLeaderCriteria($usergroupId, $c = null)
	{
		if (is_null($c))
		{
			$c = new Criteria();
		}

		$c->addAnd(UsergroupHasChiefPeer::USERGROUP_ID, $usergroupId, Criteria::EQUAL);
		$c->addJoin(UsergroupHasChiefPeer::USER_ID, UserPeer::ID);

		return $c;
	}

	static public function getNonUsergroupCriteria($usergroupId, $c = null)
	{
		if (is_null($c))
		{
			$c = new Criteria();
		}

		$c->add(UserPeer::ID, 
			sprintf(
				'%s NOT IN (SELECT %s FROM %s WHERE %s = %s)',
				UserPeer::ID,
				UsergroupHasUserPeer::USER_ID,
				UsergroupHasUserPeer::TABLE_NAME,
				UsergroupHasUserPeer::USERGROUP_ID,
				$usergroupId
			),
			Criteria::CUSTOM
		);
		
		$c->addAnd(UserPeer::ID, 
			sprintf(
				'%s NOT IN (SELECT %s FROM %s WHERE %s = %s)',
				UserPeer::ID,
				UsergroupHasChiefPeer::USER_ID,
				UsergroupHasChiefPeer::TABLE_NAME,
				UsergroupHasChiefPeer::USERGROUP_ID,
				$usergroupId
			),
			Criteria::CUSTOM
		);
		
		$c->addGroupByColumn(UserPeer::ID);

		return $c;
	}

	static public function getSubscriptionForUsergroupCriteria($usergroupId, $zoneId = null, $activityId = null, $c = null)
	{
		if (is_null($c))
		{
			$c = new Criteria();
		}

		$c->add(SubscriptionPeer::USERGROUP_ID, $usergroupId, Criteria::EQUAL);

		if (!is_null($zoneId))
		{
			$c->addAnd(SubscriptionPeer::ZONE_ID, $zoneId, Criteria::EQUAL);
		}

		if (!is_null($activityId))
		{
			$c->addAnd(SubscriptionPeer::ACTIVITY_ID, $activityId, Criteria::EQUAL);
		}

		return $c;
	}
}
