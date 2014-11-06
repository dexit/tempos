<?php

class ZonePeer extends BaseZonePeer
{
	static public function doSelectRoot($c = null)
	{
		$c = self::getRootZoneCriteria($c);

		return self::doSelect($c);
	}

	static public function doCountRoot($c = null)
	{
		$c = self::getRootZoneCriteria($c);

		return self::doCount($c);
	}

	static public function doSelectChildrenZones($zoneId)
	{
		if (is_null($zoneId))
		{
			return self::doSelectRoot();
		} else
		{
			$c = new Criteria();

			$c->add(ZonePeer::PARENT_ZONE, $zoneId, Criteria::EQUAL);

			return self::doSelect($c);
		}
	}

	static public function doCountChildrenZones($zoneid)
	{
		if (is_null($zoneId))
		{
			return self::doCountRoot();
		} else
		{
			$c = new Criteria();

			$c->add(ZonePeer::PARENT_ZONE, $zoneId, Criteria::EQUAL);

			return self::doCount($c);
		}
	}

	static public function doSelectAllChildrenZones($zoneId)
	{
		$zones = self::doSelectChildrenZones($zoneId);

		$result = clone $zones;

		foreach($zones as $zone)
		{
			$subzones = self::doSelectAllChildrenZones($zone->getId());

			$result = array_merge($result, $subzones);
		}

		return $result;
	}

	static public function doCountAllChildrenZones($zoneId)
	{
		$zones = self::doSelectChildrenZones($zoneId);

		$result = count($zones);

		foreach($zones as $zone)
		{
			$result += self::doCountAllChildrenZones($zone->getId());
		}

		return $result;
	}

	static public function doSelectHasRoom($roomId, $direct_only = true)
	{
		$c = new Criteria();

		$c->addJoin(ZoneHasRoomPeer::ZONE_ID, ZonePeer::ID);
		$c->add(ZoneHasRoomPeer::ROOM_ID, $roomId);

		$zones = self::doSelect($c);

		if (!$direct_only)
		{
			$zones_copy = $zones;
			$zones = array();

			foreach ($zones_copy as $zone)
			{
				$zones[$zone->getId()] = $zone;

				foreach ($zone->getParents() as $parent)
				{
					$zones[$parent->getId()] = $parent;
				}
			}
		}

		return $zones;
	}

	static public function doSelectRootHasRoom($roomId)
	{
		$zones = self::doSelectHasRoom($roomId, false);

		$result = array();

		foreach ($zones as $zone)
		{
			if ($zone->isRoot())
			{
				$result[] = $zone;
			}
		}

		return $result;
	}

	static public function getRootZoneCriteria($c = null)
	{
		if (is_null($c))
		{
			$c = new Criteria();
		}

		$c->add(ZonePeer::PARENT_ZONE, null, Criteria::ISNULL);

		return $c;
	}

	static public function getMaximumRecursion($zoneId = null)
	{
		$zones = self::doSelectChildrenZones($zoneId);

		$result = 0;

		foreach($zones as $zone)
		{
			$val = self::getMaximumRecursion($zone->getId()) + 1;

			if ($val > $result)
			{
				$result = $val;
			}
		}

		return $result;
	}

	static public function getRooms($zones)
	{
		$rooms = array();

		foreach ($zones as $zone)
		{
			foreach ($zone->getRooms() as $room)
			{
				$rooms[$room->getId()] = $room;
			}
		}

		return $rooms;
	}
}
