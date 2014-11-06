<?php

class MessagePeer extends BaseMessagePeer
{
	public static function doCountUserUnreadMessages($userId)
	{
		$c = self::getUserCriteria($userId);
		$c->addAnd(self::WAS_READ, false, Criteria::EQUAL);

		return self::doCount($c);
	}

	public static function getUserCriteria($userId, $c = null)
	{
		if (is_null($c))
		{
			$c = new Criteria();
		}

		$cton1 = $c->getNewCriterion(self::OWNER_ID, $userId, Criteria::EQUAL);

		$c->add($cton1);

		$c->addJoin(self::RECIPIENT_ID, UserPeer::ID, Criteria::LEFT_JOIN);

		return $c;
	}

	static public function getSortAliases()
	{
		return array(
			'was_read' => self::WAS_READ,
			'created_at' => self::CREATED_AT,
			'sender' => self::SENDER,
			'recipient' => array(UserPeer::FAMILY_NAME, UserPeer::SURNAME),
			'subject' => self::SUBJECT,
			'text' => self::TEXT,
		);
	}
}
