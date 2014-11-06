<?php

class Zone extends BaseZone
{
	public function __toString()
	{
		return $this->getName();
	}

	public function getParentZoneObject()
	{
		$parentId = $this->getParentZone();

		if (!is_null($parentId))
		{
			return ZonePeer::retrieveByPk($parentId);
		}

		return null;
	}

	public function isOwned()
	{
		$parentId = $this->getParentZone();

		return (!is_null($parentId));
	}

	public function isRoot()
	{
		$parentId = $this->getParentZone();

		return (is_null($parentId));
	}

	public function setParentZone($parentZoneId)
	{
		if ($this->isParentOf($parentZoneId))
		{
			return;
		}

		return parent::setParentZone($parentZoneId);
	}

	public function setParentZoneObject($parentZone)
	{
		if (!is_null($parentZone))
		{
			$this->setParentZone($parentZone->getId());
		} else
		{
			$this->setParentZone(null);
		}
	}

	public function moveUp()
	{
		$parent = $this->getParentZoneObject();

		if (!is_null($parent))
		{
			$this->setParentZone($parent->getParentZone());
		}
	}

	public function isChildOfObject(Zone $zone)
	{
		if ($zone->isNew())
		{
			return false;
		}

		return $this->isChildOf($zone->getId());
	}

	public function isChildOf($zoneId)
	{
		if ($this->getId() == $zoneId)
		{
			return true;
		}

		if ($this->isRoot())
		{
			return false;
		}

		$children = ZonePeer::doSelectChildrenZones($zoneId);

		foreach ($children as $child)
		{
			if ($child->getId() == $this->getId())
			{
				return true;
			}

			if ($this->isChildOf($child->getId()))
			{
				return true;
			}
		}

		return false;
	}

	public function isParentOfObject(Zone $zone)
	{
		return $zone->isChildOfObject($this);
	}

	public function isParentOf($zoneId)
	{
		$zone = ZonePeer::retrieveByPk($zoneId);

		if (!is_null($zone))
		{
			return $zone->isChildOfObject($this);
		}

		return false;
	}

	public function getChildrenZoneObjects()
	{
		if ($this->isNew())
		{
			return array();
		}

		return ZonePeer::doSelectChildrenZones($this->getId());
	}

	public function getChildrenZoneObjectsCount()
	{
		if ($this->isNew())
		{
			return 0;
		}

		return ZonePeer::doCountChildrenZones($this->getId());
	}

	public function getAllChildrenZoneObjects()
	{
		if ($this->isNew())
		{
			return array();
		}

		return ZonePeer::doSelectAllChildrenZones($this->getId());
	}

	public function getAllChildrenZoneObjectsCount()
	{
		if ($this->isNew())
		{
			return 0;
		}

		return ZonePeer::doCountAllChildrenZones($this->getId());
	}

	public function getParents()
	{
		$result = array();

		$zone = $this->getParentZoneObject();

		while (!is_null($zone))
		{
			$result[] = $zone;
			$zone = $zone->getParentZoneObject();
		}

		return $result;
	}

	public function hasRoom($roomId, $activities = null)
	{
		$rooms = $this->getRooms($activities);

		foreach ($rooms as $room)
		{
			if ($room->getId() == $roomId)
			{
				return true;
			}
		}

		return false;
	}

	public function getRooms($activities = null)
	{
		$result = $this->getDirectRooms($activities);

		$children = ZonePeer::doSelectChildrenZones($this->getId());

		foreach ($children as $child)
		{
			$result = array_merge($result, $child->getRooms($activities));
		}

		return array_unique($result);
	}

	public function getRoomsCount($activities = null)
	{
		$rooms = $this->getRooms($activities);
		return count($rooms);
	}

	public function getDirectRooms($activities = null, $c = null)
	{
		if (is_null($c))
		{
			$c = new Criteria();
		}

		$c->addAnd(ZoneHasRoomPeer::ZONE_ID, $this->getId(), Criteria::EQUAL);

		if (!empty($activities))
		{
			if (!is_array($activities))
			{
				$activities = array($activities);
			}

			$c->addJoin(RoomHasActivityPeer::ROOM_ID, ZoneHasRoomPeer::ROOM_ID);
			$c->addAnd(RoomHasActivityPeer::ACTIVITY_ID, $activities, Criteria::IN);
		}

		$c->addJoin(RoomPeer::ID, ZoneHasRoomPeer::ROOM_ID, Criteria::LEFT_JOIN);
		$c->addAscendingOrderByColumn(RoomPeer::NAME);

		return RoomPeer::doSelect($c);
	}

	public function getDirectRoomsCount($activities = null, $c = null)
	{
		if (is_null($c))
		{
			$c = new Criteria();
		}

		$c->addAnd(ZoneHasRoomPeer::ZONE_ID, $this->getId(), Criteria::EQUAL);

		if (!empty($activities))
		{
			if (!is_array($activities))
			{
				$activities = array($activities);
			}

			$c->addJoin(RoomHasActivityPeer::ROOM_ID, ZoneHasRoomPeer::ROOM_ID);
			$c->addAnd(RoomHasActivityPeer::ACTIVITY_ID, $activities, Criteria::IN);
		}

		return ZoneHasRoomPeer::doCount($c);
	}

	public function hasDirectRoom($room)
	{
		if (is_null($room))
		{
			return false;
		}

		$c = new Criteria();

		$c->add(ZoneHasRoomPeer::ZONE_ID, $this->getId(), Criteria::EQUAL);
		$c->addAnd(ZoneHasRoomPeer::ROOM_ID, $room->getId(), Criteria::EQUAL);

		return (ZoneHasRoomPeer::doCount($c) > 0);
	}

	public static function explode_zones($zones)
	{
		if (!is_array($zones))
		{
			return null;
		}

		$result = array();

		foreach ($zones as $zone)
		{
			$result[] = $zone;

			$result = array_merge($result, self::explode_zones($zone->getChildrenZoneObjects()));
		}

		return $result;
	}
}
