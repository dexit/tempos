<?php

class RoomPeer extends BaseRoomPeer
{
	const COMPLETE = 0;
	const OCCUPIED = 1;
	const FREE = 2;
	const PAST = 3;
	const TOOFAR = 4;

	public static function doSelectFromIdList($room_id_list)
	{
		$c = new Criteria();

		$c->add(self::ID, $room_id_list, Criteria::IN);
		$c->addAscendingOrderByColumn(self::NAME);

		return self::doSelect($c);
	}

	public static function getSortAliases()
	{
		return array(
			'name' => self::NAME,
			'capacity' => self::CAPACITY,
			'address' => self::ADDRESS,
			'description' => self::DESCRIPTION,
		);
	}

	static public function searchRooms($activityId, $is_active, $namePattern, $capacity, $addressPattern, $descriptionPattern, $features = null, $criteria = null)
	{
		if (is_null($features))
		{
			$features = array();
		}

		if (is_null($criteria))
		{
			$c = new Criteria();
		} else
		{
			$c = $criteria;
		}

		if (!is_null($is_active))
		{
			$c->add(RoomPeer::IS_ACTIVE, $is_active, Criteria::EQUAL);
		}

		if (!is_null($activityId))
		{
			$c->addJoin(RoomPeer::ID, RoomHasActivityPeer::ROOM_ID);
			$c->addAnd(RoomHasActivityPeer::ACTIVITY_ID, $activityId);
		}

		if (!empty($namePattern))
		{
			$c->addAnd(RoomPeer::NAME, '%'.$namePattern.'%', Criteria::LIKE);
		}

		if (!empty($capacity))
		{
			$c->addAnd(RoomPeer::CAPACITY, $capacity, Criteria::GREATER_EQUAL);
		}

		if (!empty($addressPattern))
		{
			$c->addAnd(RoomPeer::ADDRESS, '%'.$addressPattern.'%', Criteria::LIKE);
		}

		if (!empty($descriptionPattern))
		{
			$c->addAnd(RoomPeer::DESCRIPTION, '%'.$descriptionPattern.'%', Criteria::LIKE);
		}

		if (count($features) > 0)
		{
			$values = array();

			foreach ($features as $featurevalueId)
			{
				if (is_array($featurevalueId))
				{
					foreach($featurevalueId as $valueId)
					{
						$values[] = $valueId;
					}
				} else
				{
					if (!is_null($featurevalueId))
					{
						$values[] = $featurevalueId;
					}
				}
			}

			if (count($values) > 0)
			{
				$c->addJoin(RoomPeer::ID, RoomHasFeaturevaluePeer::ROOM_ID);

				$values_string = implode(',', $values);
				
				$subSelect = sprintf("(SELECT COUNT(*) FROM %s WHERE (%s = %s AND (%s IN (%s)))) = %d", 
					RoomHasFeaturevaluePeer::TABLE_NAME,
					RoomHasFeaturevaluePeer::ROOM_ID,
					RoomPeer::ID,
					RoomHasFeaturevaluePeer::FEATUREVALUE_ID,
					$values_string,
					count($values)
				);

				$c->add(RoomPeer::ID, $subSelect, Criteria::CUSTOM);
			}
		}

		$c->addGroupByColumn(RoomPeer::ID);

		return self::doSelect($c);
	}

	public static function getOccupancy($zone, $activities, $begin_date, $end_date)
	{
		$begin_date_str = date('Y-m-d', $begin_date);
		$end_date_str = date('Y-m-d', $end_date);

		/*print('Begin date: '.$begin_date_str.'<br>');
		print('End date: '.$end_date_str.'<br>');*/
		
		if (!is_null($zone))
		{
			$zone = ZonePeer::retrieveByPk($zone);
			
			//print('Zone is not null: '.$zone.' - Getting rooms....'.'<br>');

			$rooms = $zone->getRooms($activities);
		}
		else
		{
			//print('Zone is null !'.'<br>');
			$c = new Criteria();

			if (!empty($activities))
			{
				$c->addJoin(RoomHasActivityPeer::ROOM_ID, RoomPeer::ID);
				$c->addAnd(RoomHasActivityPeer::ACTIVITY_ID, $activities, Criteria::IN);
			}

			$rooms = RoomPeer::doSelect($c);
		}

		/*print('All Rooms To Check:'.'<br>');
		print_r ($rooms);
		print('<br>');*/
		
		$report = array();

		foreach ($rooms as $room)
		{
			// Occupancy
			//print('Processing room: '.$room.'<br>');
			
			$c = new Criteria();

			$c->addJoin(ReservationPeer::ROOMPROFILE_ID, RoomprofilePeer::ID);
			$c->addJoin(RoomprofilePeer::ROOM_ID, RoomPeer::ID);
			$c->add(RoomPeer::ID, $room->getId(), Criteria::EQUAL);
			
			$c->addAnd(ReservationPeer::DATE, $begin_date_str, Criteria::GREATER_EQUAL);
			$c->addAnd(ReservationPeer::DATE, $end_date_str, Criteria::LESS_THAN);
			
			// We only keep finished reservation ?
			//$c->addAnd(ReservationPeer::STATUS, Reservation::BLOCKED, Criteria::EQUAL);

			if (!empty($activities))
			{
				$c->addAnd(ReservationPeer::ACTIVITY_ID, $activities, Criteria::IN);
			}

			$c->clearSelectColumns();
			$c->addSelectColumn('SUM('.ReservationPeer::DURATION.')');
			$c->setLimit(1);

			//print ('SQL Command: '.$c->toString().'<br>');
			
			$stmt = ReservationPeer::doSelectStmt($c);

			if ($row = $stmt->fetch(PDO::FETCH_NUM))
			{
				$occupancy_time = $row[0];
			}

			//print('Ocupancy time: '.$occupancy_time.'<br>');
			
			$total_time = 0;
			// Total time => Total number of opening hours in minutes during the begin and end date
			for ($date = $begin_date; $date < $end_date; $date = strtotime('+1 day', $date))
			{
				$total_time += $room->getOpeningDuration(date('N', $date) - 1);
			}

			//print('Total door opening duration: '.$total_time.'<br>');
			
			if ($total_time <= 0)
			{
				continue;
			}

			// Results

			$report[$room->getId()] = array('room' => $room, 'occupancy_time' => $occupancy_time, 'total_time' => $total_time, 'ratio' => ($total_time > 0) ? ($occupancy_time / $total_time) : null);
		}

		/*print('Final report:'.'<br>');
		print_r ($report);
		print('<br>');*/
		
		return $report;
	}

	public static function isRoomActive($roomId)
	{
		$c = self::getActiveCriteria(true);
		$c->addAnd(RoomPeer::ID, $roomId, Criteria::EQUAL);

		return (RoomPeer::doCount($c) > 0);
	}

	public static function getActiveCriteria($active = true, $c = null)
	{
		if (is_null($c))
		{
			$c = new Criteria();
		}

		$c->addAnd(RoomPeer::IS_ACTIVE, $active, Criteria::EQUAL);

		return $c;
	}

	public static function getOpeningDuration($roomId, $dayOfWeek = null)
	{
		$c = new Criteria();
		$c->add(DayperiodPeer::ROOM_ID, $roomId, Criteria::EQUAL);

		if (!is_null($dayOfWeek))
		{
			$c->addAnd(DayperiodPeer::DAY_OF_WEEK, $dayOfWeek, Criteria::EQUAL);
		}

		$dayperiods = DayperiodPeer::doSelect($c);

		$sum = 0;

		foreach ($dayperiods as $dayperiod)
		{
			$sum += $dayperiod->getDuration();
		}

		return $sum;
	}

	public static function getAvailability($room_list, $activityId, $person, $displayPeriod = 'month', $timestamp = null)
	{
		if (empty($room_list))
		{
			return null;
		}

		if (is_null($timestamp))
		{
			$timestamp = time();
		}

		$room_id_list = PropelLogic::getIdList($room_list);

		if ($displayPeriod == 'month')
		{
			return self::getMonthAvailability($room_id_list, $activityId, $person, $timestamp);
		} elseif ($displayPeriod == 'week')
		{
			return self::getWeekAvailability($room_id_list, $activityId, $person, $timestamp);
		} else
		{
			throw new Exception(sprintf('Invalid value "%s" for displayPeriod', $displayPeriod));
		}
	}

	protected static function getMonthAvailability($room_id_list, $activityId, $person, $timestamp)
	{
		$now = time();

		// Get the month limits

		$monthStart = ReservationPeer::getMonthStart($timestamp);
		$monthStop = ReservationPeer::getMonthStop($timestamp);
		$tst = $monthStart;

		// We get all the reservations for the current room list in the month containing the given timestamp.

		$c = ReservationPeer::getMonthCriteria($timestamp);

		$c->addJoin(ReservationPeer::ROOMPROFILE_ID, RoomprofilePeer::ID);
		$c->addAnd(RoomprofilePeer::ROOM_ID, $room_id_list, Criteria::IN);
		$c->addAscendingOrderByColumn(ReservationPeer::DATE);

		$reservations = ReservationPeer::doSelect($c);

		// We get all the day periods for the current room list.

		$c = new Criteria();

		$c->addAnd(DayperiodPeer::ROOM_ID, $room_id_list, Criteria::IN);

		$c->addAscendingOrderByColumn(DayperiodPeer::DAY_OF_WEEK);
		$c->addAscendingOrderByColumn(DayperiodPeer::START);
		$c->addGroupByColumn(DayperiodPeer::ROOM_ID);
		$c->addGroupByColumn(DayperiodPeer::DAY_OF_WEEK);

		$dayPeriods = DayperiodPeer::doSelect($c);

		// We get all the close periods for the current room list.

		$c = CloseperiodPeer::getMonthCriteria($timestamp); 

		$c->addAnd(CloseperiodPeer::ROOM_ID, $room_id_list, Criteria::IN);

		$c->addAscendingOrderByColumn(CloseperiodPeer::START);
		$c->addAscendingOrderByColumn(CloseperiodPeer::STOP);

		$closePeriods = CloseperiodPeer::doSelect($c);

		// Days off

		$dayOff = array();

		for ($i = 0; $i < 7; ++$i)
		{
			$dayOff[$i] = 0;
		}

		foreach ($dayPeriods as $dayPeriod)
		{
			++$dayOff[$dayPeriod->getDayOfWeek()];
		}

		// Opening duration

		$roomsDurations = array();

		for ($dayOfWeek = 0; $dayOfWeek < 7; ++$dayOfWeek)
		{
			$roomsDurations[$dayOfWeek] = array();

			foreach ($room_id_list as $room_id)
			{
				$roomsDurations[$dayOfWeek][$room_id] = RoomPeer::getOpeningDuration($room_id, $dayOfWeek);
			}
		}

		$roomsDuration = array();

		// Result

		$result = array();
		
		for ($i = 1; $tst < $monthStop; ++$i)
		{
			$roomsId = $room_id_list;
			$dayOfWeek = date('N', $tst) - 1;
			$roomsDuration = $roomsDurations[$dayOfWeek];

			if ($dayOff[$dayOfWeek] == 0)
			{
				$value = RoomPeer::COMPLETE;
				$roomsId = array();
			} else
			{
				$value = RoomPeer::FREE;

				foreach ($closePeriods as $closePeriod)
				{
					if ($closePeriod->matchFullDayTimestamp($tst))
					{
						$roomsDuration[$closePeriod->getRoomId()] = 0;
					} elseif ($closePeriod->matchDayTimestamp($tst))
					{
						$roomsDuration[$closePeriod->getRoomId()] -= $closePeriod->getDayCloseDuration($tst);
						$value = RoomPeer::OCCUPIED;
					}
				}

				if (strtotime(date('Y-m-d', $now)) > $tst)
				{
					$value = RoomPeer::PAST;
				} else
				{
					$cnt = 0;

					foreach ($room_id_list as $roomId)
					{
						$maximumTimestamp = $person->getMaximumDate($activityId, $roomId);

						if (($maximumTimestamp <= $tst) || (!$person->hasSubscription($activityId, $roomId, $tst)))
						{
							++$cnt;
							unset($roomsId[array_search($roomId, $roomsId)]);
						}
					}

					if ($cnt == count($room_id_list))
					{
						$value = RoomPeer::TOOFAR;
					} else
					{
						if ($dayOff[$dayOfWeek] < count($room_id_list))
						{
							$value = RoomPeer::OCCUPIED;
						}

						foreach ($reservations as $reservation)
						{
							if (!$reservation->isOld())
							{
								if ($reservation->matchDayTimestamp($tst))
								{
									$roomsDuration[$reservation->getRoomprofile()->getRoomId()] -= $reservation->getDuration($tst);
									$value = RoomPeer::OCCUPIED;
								} elseif (strtotime($reservation->getDate().' - 1 day') > $tst)
								{
									break;
								}
							}
						}

						$cnt = 0;

						foreach ($room_id_list as $room_id)
						{
							if ($roomsDuration[$room_id] < 60)
							{
								++$cnt;
								unset($roomsId[array_search($room_id, $roomsId)]);
							}
						}

						if ($cnt < count($room_id_list))
						{
							if ($cnt > 0)
							{
								$value = RoomPeer::OCCUPIED;
							}
						} else
						{
							$value = RoomPeer::COMPLETE;
							$roomsId = array();
						}
					}
				}
			}

			$result[$i] = array();
			$result[$i]['value'] = $value;
			$result[$i]['timestamp'] = $tst;
			$result[$i]['rooms'] = $roomsId;

			$tst = strtotime(date('Y-m-d', $monthStart).' + '.$i.' day');
		}

		return $result;
	}

	protected static function getWeekAvailability($room_id_list, $activityId, $person, $timestamp)
	{
		$now = time();

		// Get the week start

		$weekStart = ReservationPeer::getWeekStart($timestamp);

		// We get all the reservations for the current room list in the week containing the given timestamp.

		$c = ReservationPeer::getWeekCriteria($timestamp);

		$c->addJoin(ReservationPeer::ROOMPROFILE_ID, RoomprofilePeer::ID);
		$c->addAnd(RoomprofilePeer::ROOM_ID, $room_id_list, Criteria::IN);
		$c->addAscendingOrderByColumn(ReservationPeer::DATE);

		$reservations = ReservationPeer::doSelect($c);

		// We get all the day periods for the current room list.

		$c = new Criteria();

		$c->addAnd(DayperiodPeer::ROOM_ID, $room_id_list, Criteria::IN);

		$c->addAscendingOrderByColumn(DayperiodPeer::DAY_OF_WEEK);
		$c->addAscendingOrderByColumn(DayperiodPeer::START);

		$dayPeriods = DayperiodPeer::doSelect($c);

		// We get all the close periods for the current room list.

		$c = CloseperiodPeer::getWeekCriteria($timestamp); 

		$c->addAnd(CloseperiodPeer::ROOM_ID, $room_id_list, Criteria::IN);

		$c->addAscendingOrderByColumn(CloseperiodPeer::START);
		$c->addAscendingOrderByColumn(CloseperiodPeer::STOP);

		$closePeriods = CloseperiodPeer::doSelect($c);

		// We build the availability array

		$startIndex = 48;
		$stopIndex = 0;

		$result = array();

		for ($i = 0; $i < 7; ++$i)
		{
			$result[$i] = array();

			for ($j = 0; $j < 48; ++$j)
			{
				$tst = strtotime(date('Y-m-d H:i:s', $weekStart).' + '.$i.' day + '.($j * 30).' minute');

				$value = RoomPeer::COMPLETE;
				$roomsId = array();
				$cnt = 0;

				foreach ($dayPeriods as $dayPeriod)
				{
					if ($dayPeriod->matchTimestamp($tst))
					{
						if ($j < $startIndex)
						{
							$startIndex = $j;
						}

						if ($j >= $stopIndex)
						{
							$stopIndex = $j + 1;
						}

						++$cnt;
						$roomsId[] = $dayPeriod->getRoomId();
					} elseif ($dayPeriod->getDayOfWeek() > $i)
					{
						break;
					}
				}

				if ($cnt == count($room_id_list))
				{
					$value = RoomPeer::FREE;
				} elseif ($cnt > 0)
				{
					$value = RoomPeer::OCCUPIED;
				}

				$cnt = 0;

				foreach ($closePeriods as $closePeriod)
				{
					if ($closePeriod->matchTimestamp($tst))
					{
						++$cnt;
						unset($roomsId[array_search($closePeriod->getRoomId(), $roomsId)]);
					}

					if (strtotime($closePeriod->getStart()) > $tst)
					{
						break;
					}
				}
				
				if ($cnt == count($room_id_list))
				{
					$value = RoomPeer::COMPLETE;
				} else
				{
					if ($cnt > 0)
					{
						$value = RoomPeer::OCCUPIED;
					}
				}

				if ($value != RoomPeer::COMPLETE)
				{
					if ($tst <= $now)
					{
						$value = RoomPeer::PAST;
					} else
					{
						$cnt = 0;

						foreach ($room_id_list as $roomId)
						{
							$maximumTimestamp = $person->getMaximumDate($activityId, $roomId);

							if (($maximumTimestamp <= $tst) || (!$person->hasSubscription($activityId, $roomId, $tst)))
							{
								++$cnt;
								unset($roomsId[array_search($roomId, $roomsId)]);
							}
						}

						if ($cnt == count($room_id_list))
						{
							$value = RoomPeer::TOOFAR;
						} else
						{
							if ($cnt > 0)
							{
								$value = RoomPeer::OCCUPIED;
							}

							$cnt = 0;

							foreach ($reservations as $reservation)
							{
								if ($reservation->matchTimestamp($tst))
								{
									++$cnt;
									unset($roomsId[array_search($reservation->getRoomprofile()->getRoomId(), $roomsId)]);
								} elseif (strtotime($reservation->getDate()) > $tst)
								{
									break;
								}
							}

							if (count($roomsId) == 0)
							{
								$value = RoomPeer::COMPLETE;
							} else
							{
								if ($cnt > 0)
								{
									$value = RoomPeer::OCCUPIED;
								}
							}
						}
					}
				}

				$result[$i][$j]['value'] = $value;
				$result[$i][$j]['rooms'] = $roomsId;
				$result[$i][$j]['timestamp'] = $tst;
			}
		}

		$result['startIndex'] = $startIndex;
		$result['stopIndex'] = $stopIndex;

		return $result;
	}

	public static function getGantt($room_list, $activityId, $person, $timestamp)
	{
		$now = time();
		$room_id_list = PropelLogic::getIdList($room_list);

		// Get the day start

		$dayStart = ReservationPeer::getDayStart($timestamp);

		// We get all the reservations for the current room list in the week containing the given timestamp.

		$c = ReservationPeer::getDayCriteria($timestamp);

		$c->addJoin(ReservationPeer::ROOMPROFILE_ID, RoomprofilePeer::ID);
		$c->addAnd(RoomprofilePeer::ROOM_ID, $room_id_list, Criteria::IN);
		$c->addAscendingOrderByColumn(ReservationPeer::DATE);

		$reservations = ReservationPeer::doSelect($c);

		// We get all the day periods for the current room list.

		$dayOfWeek = date('N', $timestamp) - 1;

		$c = new Criteria();

		$c->addAnd(DayperiodPeer::ROOM_ID, $room_id_list, Criteria::IN);
		$c->addAnd(DayperiodPeer::DAY_OF_WEEK, $dayOfWeek, Criteria::EQUAL);

		$c->addAscendingOrderByColumn(DayperiodPeer::START);

		$dayPeriods = DayperiodPeer::doSelect($c);

		// We get all the close periods for the current room list.

		$c = CloseperiodPeer::getDayCriteria($timestamp); 

		$c->addAnd(CloseperiodPeer::ROOM_ID, $room_id_list, Criteria::IN);

		$c->addAscendingOrderByColumn(CloseperiodPeer::START);
		$c->addAscendingOrderByColumn(CloseperiodPeer::STOP);

		$closePeriods = CloseperiodPeer::doSelect($c);

		// We build the availability array

		$startIndex = 48;
		$stopIndex = 0;

		$result = array();

		foreach($room_list as $room)
		{
			$room_id = $room->getId();

			$result[$room_id] = array();
			$result[$room_id]['room'] = $room;

			for ($i = 0; $i < 48; ++$i)
			{
				$result[$room_id][$i] = array();

				$tst = strtotime(date('Y-m-d H:i:s', $dayStart).' + '.($i * 30).' minute');

				$value = RoomPeer::COMPLETE;

				foreach ($dayPeriods as $dayPeriod)
				{
					if ($dayPeriod->getRoomId() == $room_id)
					{
						if ($dayPeriod->matchTimestamp($tst))
						{
							if ($startIndex > $i)
							{
								$startIndex = $i;
							}

							if ($stopIndex <= $i)
							{
								$stopIndex = $i + 1;
							}

							$value = RoomPeer::FREE;
							break;
						}
					}
				}

				foreach ($closePeriods as $closePeriod)
				{
					if ($closePeriod->getRoomId() == $room_id)
					{
						if ($closePeriod->matchTimestamp($tst))
						{
							$value = RoomPeer::COMPLETE;
							break;
						}
					}
					if (strtotime($closePeriod->getStart()) > $tst)
					{
						break;
					}
				}
				
				if ($value != RoomPeer::COMPLETE)
				{
					if ($tst < $now)
					{
						$value = RoomPeer::PAST;
					} else
					{
						$maximumTimestamp = $person->getMaximumDate($activityId, $room_id);

						if (($maximumTimestamp <= $tst) || (!$person->hasSubscription($activityId, $room_id, $tst)))
						{
							$value = RoomPeer::TOOFAR;
						} else
						{
							foreach ($reservations as $reservation)
							{
								if ($reservation->getRoomprofile()->getRoomId() == $room_id)
								{
									if ($reservation->matchTimestamp($tst))
									{
										$value = RoomPeer::COMPLETE;
										break;
									}
								}

								if (strtotime($reservation->getDate()) > $tst)
								{
									break;
								}
							}
						}
					}
				}

				$result[$room_id][$i]['value'] = $value;
				$result[$room_id][$i]['timestamp'] = $tst;
				$result[$room_id][$i]['room'] = $room;
				$result['timestamps'][$i] = $tst;
			}
		}

		$result['startIndex'] = $startIndex;
		$result['stopIndex'] = $stopIndex;

		return $result;
	}
}
