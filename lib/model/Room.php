<?php

class Room extends BaseRoom
{
	public function __toString()
	{
		return $this->getName();
	}

	public function addParentZone($parentZone)
	{
		$zoneHasRoom = new ZoneHasRoom();
		$zoneHasRoom->setZoneId($parentZone);
		$this->addZoneHasRoom($zoneHasRoom);
	}

	public function getActivities()
	{
		$c = new Criteria();

		$c->addJoin(ActivityPeer::ID, RoomHasActivityPeer::ACTIVITY_ID);
		$c->add(RoomHasActivityPeer::ROOM_ID, $this->getId(), Criteria::EQUAL);
		$c->addAscendingOrderByColumn(ActivityPeer::NAME);

		return ActivityPeer::doSelect($c);
	}

	public function getEnergyactions()
	{
		$c = new Criteria();

		$c->addJoin(EnergyactionPeer::ID, RoomHasEnergyactionPeer::ENERGYACTION_ID);
		$c->add(RoomHasEnergyactionPeer::ROOM_ID, $this->getId(), Criteria::EQUAL);
		$c->addAscendingOrderByColumn(EnergyactionPeer::NAME);

		return EnergyactionPeer::doSelect($c);
	}

	public function hasCapacityIssue()
	{
		if (is_null($this->getCapacity()))
		{
			return false;
		}

		$c = new Criteria();

		$c->add(ActivityPeer::MAXIMUM_OCCUPATION, $this->getCapacity(), Criteria::GREATER_THAN);
		$c->addJoin(ActivityPeer::ID, RoomHasActivityPeer::ACTIVITY_ID);
		$c->addAnd(RoomHasActivityPeer::ROOM_ID, $this->getId(), Criteria::EQUAL);

		return (ActivityPeer::doCount($c) > 0);
	}

	public function hasDayperiods()
	{
		return ($this->countDayperiods() > 0);
	}

	public function hasCloseperiods()
	{
		return ($this->countCloseperiods() > 0);
	}

	public function getOpeningDuration($dayOfWeek = null)
	{
		return RoomPeer::getOpeningDuration($this->getId(), $dayOfWeek);
	}

	public function getOpeningDurationString()
	{
		$sum = $this->getOpeningDuration();

		return sprintf("%02d:%02d", $sum / 60, $sum % 60);
	}

	public function clearDayperiods()
	{
		$dayperiods = $this->getDayperiods();

		foreach ($dayperiods as $dayperiod)
		{
			$dayperiod->delete();
		}
	}

	public function isOpen($tst)
	{
		$dayperiods = $this->getDayperiods();
		$closeperiods = $this->getCloseperiods();

		$result = false;

		foreach ($dayperiods as $dayperiod)
		{
			if ($dayperiod->matchTimestamp($tst))
			{
				$result = true;
				break;
			}
		}

		if ($result)
		{
			foreach ($closeperiods as $closeperiod)
			{
				if ($closeperiod->matchTimestamp($tst))
				{
					$result = false;
					break;
				}
			}
		}

		return $result;
	}

	public function isOpenDay($day)
	{
		$dayperiods = $this->getDayperiods();

		foreach ($dayperiods as $dayperiod)
		{
			if ($dayperiod->getDayOfWeek() == $day)
			{
				return true;
			}
		}

		return false;
	}

	public function getOverallStartIndex()
	{
		$dayperiods = $this->getDayperiods();

		$result = 48;

		foreach($dayperiods as $dayperiod)
		{
			$index = $dayperiod->getStartIndex();

			if ($index < $result)
			{
				$result = $index;
			}
		}

		return $result;
	}

	public function getOverallStopIndex()
	{
		$dayperiods = $this->getDayperiods();

		$result = 0;

		foreach($dayperiods as $dayperiod)
		{
			$index = $dayperiod->getStopIndex();

			if ($index > $result)
			{
				$result = $index;
			}
		}

		return $result;
	}

	public function copyDayperiodsFromRoom(Room $room)
	{
		if ($room->getId() == $this->getId())
		{
			return;
		}

		$this->clearDayperiods();

		$dayperiods = $room->getDayperiods();

		foreach ($dayperiods as $dayperiod)
		{
			$newDayperiod = $dayperiod->copyWithoutRoom();
			$this->addDayperiod($newDayperiod);
		}
	}

	public function repeatDayperiodWeek(Dayperiod $dayperiod)
	{
		for ($week_day = 0; $week_day < 7; ++$week_day)
		{
			$newDayperiod = $dayperiod->copyWithoutRoom();
			$newDayperiod->setDayOfWeek($week_day);
			$newDayperiod->setRoomId($this->getId());

			if (!$newDayperiod->overlaps())
			{
				$newDayperiod->save();
			}
		}
	}

	public function getFeatures()
	{
		$c = new Criteria();

		$c->addJoin(ActivityHasFeaturePeer::FEATURE_ID, FeaturePeer::ID);
		$c->addJoin(RoomHasActivityPeer::ACTIVITY_ID, ActivityHasFeaturePeer::ACTIVITY_ID);
		$c->add(RoomHasActivityPeer::ROOM_ID, $this->getId(), Criteria::EQUAL);
		$c->addGroupByColumn(FeaturePeer::ID);

		return FeaturePeer::doSelect($c);
	}

	public function getFeaturevaluesFromFeature($featureId)
	{
		$c = new Criteria();

		$c->addJoin(RoomHasFeaturevaluePeer::FEATUREVALUE_ID, FeaturevaluePeer::ID);
		$c->add(RoomHasFeaturevaluePeer::ROOM_ID, $this->getId(), Criteria::EQUAL);
		$c->addAnd(FeaturevaluePeer::FEATURE_ID, $featureId, Criteria::EQUAL);

		return FeaturevaluePeer::doSelect($c);
	}

	public function getFeaturevaluesFromFeatureAsIdArray($featureId)
	{
		$featurevalues = $this->getFeaturevaluesFromFeature($featureId);

		return $this->getFeaturesvaluesAsIdArray($featurevalues);
	}

	public function addFeaturevaluesOfFeature($featureId, $featurevalues)
	{
		if (is_array($featurevalues))
		{
			foreach($featurevalues as $featurevalue)
			{
				$featurevalueId = is_object($featurevalue) ? $featurevalue->getId() : $featurevalue;

				if (is_null(RoomHasFeaturevaluePeer::retrieveByPk($this->getId(), $featurevalueId)))
				{
					$roomHasFeaturevalue = new RoomHasFeaturevalue();
					$roomHasFeaturevalue->setFeaturevalueId($featurevalueId);

					$this->addRoomHasFeaturevalue($roomHasFeaturevalue);
				}
			}
		}
	}

	public function clearFeaturevalues()
	{
		$featurevalues = $this->getRoomHasFeaturevalues();

		foreach ($featurevalues as $featurevalue)
		{
			$featurevalue->delete();
		}
	}

	protected function getFeaturesvaluesAsIdArray($featurevalues)
	{
		$result = array();

		foreach($featurevalues as $featurevalue)
		{
			$result[] = $featurevalue->getId();
		}

		return $result;
	}

	public function getValuedFeaturesArray()
	{
		$result = array();

		foreach ($this->getRoomHasFeaturevalues() as $roomhasfeaturevalue)
		{
			$featurevalue = FeaturevaluePeer::retrieveByPk($roomhasfeaturevalue->getFeaturevalueId());
			$feature = $featurevalue->getFeature();

			if (isset($result[$feature->getName()]))
			{
				$result[$feature->getName()] .= ", ".$featurevalue->getValue();
			} else
			{
				$result[$feature->getName()] = $featurevalue->getValue();
			}
		}

		return $result;
	}
}
