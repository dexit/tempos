<?php

class SubscriptionPeer extends BaseSubscriptionPeer
{
	public static function getActiveCriteria($timestamp = null)
	{
		if (is_null($timestamp))
		{
			$timestamp = time();
		}

		$c = new Criteria();

		$c->add(SubscriptionPeer::IS_ACTIVE, 1, Criteria::EQUAL);

		$cton1 = $c->getNewCriterion(SubscriptionPeer::START, null, Criteria::ISNULL);
		$cton1b = $c->getNewCriterion(SubscriptionPeer::START, strftime("%Y-%m-%d", $timestamp), Criteria::LESS_EQUAL);
		$cton1->addOr($cton1b);

		$cton2 = $c->getNewCriterion(SubscriptionPeer::STOP, null, Criteria::ISNULL);
		$cton2b = $c->getNewCriterion(SubscriptionPeer::STOP, strftime("%Y-%m-%d", $timestamp), Criteria::GREATER_THAN);
		$cton2->addOr($cton2b);

		$cton1->addAnd($cton2);
		$c->addAnd($cton1);

		return $c;
	}

	public static function getHasRoomCriteria($roomId, $c = null)
	{
		if (is_null($c))
		{
			$c = new Criteria();
		}

		if (!is_null($roomId))
		{
			$zones = ZonePeer::doSelectHasRoom($roomId, false);

			$zones_ids = array();

			foreach ($zones as $zone)
			{
				$zones_ids[] = $zone->getId();
			}

			$c->add(SubscriptionPeer::ZONE_ID, $zones_ids, Criteria::IN);
		}

		return $c;
	}
}
