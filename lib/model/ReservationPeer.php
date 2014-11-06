<?php

class ReservationPeer extends BaseReservationPeer
{
	public static function doSelectWeek($roomId, $date)
	{
		$c = self::getWeekCriteria($date);

		$c->addJoin(ReservationPeer::ROOMPROFILE_ID, RoomprofilePeer::ID);
		$c->addAnd(RoomprofilePeer::ROOM_ID, $roomId, Criteria::EQUAL);

		return self::doSelect($c);
	}
	
	public static function doSelectWeekOrderbyDate($roomId, $date)
	{
		$c = self::getWeekCriteria($date);

		$c->addJoin(ReservationPeer::ROOMPROFILE_ID, RoomprofilePeer::ID);
		$c->addAnd(RoomprofilePeer::ROOM_ID, $roomId, Criteria::EQUAL);
		$c->addAscendingOrderByColumn(ReservationPeer::DATE);

		return self::doSelect($c);
	}
	
	public static function doSelectDay($roomId, $date)
	{
		$c = self::getDayCriteria($date);

		$c->addJoin(ReservationPeer::ROOMPROFILE_ID, RoomprofilePeer::ID);
		$c->addAnd(RoomprofilePeer::ROOM_ID, $roomId, Criteria::EQUAL);
		$c->addAscendingOrderByColumn(ReservationPeer::DATE);

		return self::doSelect($c);
	}

	public static function doSelectNow()
	{
		$c = new Criteria();

		$now_date = date('Y-m-d H:i:s');
		$c->add(ReservationPeer::DATE, $now_date, Criteria::GREATER_EQUAL);
		$c->addAnd(ReservationPeer::DATE, "DATE_ADD(".ReservationPeer::DATE.", INTERVAL ".ReservationPeer::DURATION." MINUTE) < '".$now_date."'", Criteria::CUSTOM);

		return self::doSelect($c);
	}

	public static function doSelectPendingReservations($includeInactive = false, $time = null)
	{
		if (is_null($time))
		{
			$time = time();
		}

		$c = new Criteria();

		$check_date = date('Y-m-d H:i:s', $time);
		$c->addJoin(ReservationPeer::ACTIVITY_ID, ActivityPeer::ID);
		$c->add(ReservationPeer::DATE, "DATE_ADD(".ReservationPeer::DATE.", INTERVAL - ".ActivityPeer::MINIMUM_DELAY." MINUTE) <= '".$check_date."'", Criteria::CUSTOM);
		$c->addAnd(ReservationPeer::DATE, "DATE_ADD(".ReservationPeer::DATE.", INTERVAL ".ReservationPeer::DURATION." MINUTE) >= '".$check_date."'", Criteria::CUSTOM);

		if (!$includeInactive)
		{
			$c->addAnd(ReservationPeer::IS_ACTIVATED, true, Criteria::EQUAL);
		}

		$c->addAnd(ReservationPeer::STATUS, Reservation::IDLE, Criteria::EQUAL);

		return self::doSelect($c);
	}
	
	public static function doSelectPendingReservationsForNextHours($includeInactive = false, $beginTime = null, $endTime = null)
	{
		if (is_null($beginTime))
		{
			$beginTime = time();
		}
		if (is_null($endTime))
		{
			$endTime = time();
		}

		$c = new Criteria();

		$beginCheckDate = date('Y-m-d H:i:s', $beginTime);
		$endCheckDate = date('Y-m-d H:i:s', $endTime);
		
		$c->addJoin(ReservationPeer::ACTIVITY_ID, ActivityPeer::ID);
		$c->add(ReservationPeer::DATE, "DATE_ADD(".ReservationPeer::DATE.", INTERVAL - ".ActivityPeer::MINIMUM_DELAY." MINUTE) <= '".$endCheckDate."'", Criteria::CUSTOM);
		$c->addAnd(ReservationPeer::DATE, "DATE_ADD(".ReservationPeer::DATE.", INTERVAL ".ReservationPeer::DURATION." MINUTE) >= '".$beginCheckDate."'", Criteria::CUSTOM);

		if (!$includeInactive)
		{
			$c->addAnd(ReservationPeer::IS_ACTIVATED, true, Criteria::EQUAL);
		}

		$c->addAnd(ReservationPeer::STATUS, Reservation::IDLE, Criteria::EQUAL);

		return self::doSelect($c);
	}

	public static function doSelectReady($includeInactive = false, $time = null)
	{
		if (is_null($time))
		{
			$time = time();
		}

		$c = new Criteria();

		$now_date = date('Y-m-d H:i:s', $time);

		$c->addAnd(ReservationPeer::DATE, "DATE_ADD(".ReservationPeer::DATE.", INTERVAL ".ReservationPeer::DURATION." MINUTE) >= '".$now_date."'", Criteria::CUSTOM);

		if (!$includeInactive)
		{
			$c->addAnd(ReservationPeer::IS_ACTIVATED, true, Criteria::EQUAL);
		}

		$c->addAnd(ReservationPeer::STATUS, Reservation::BLOCKED, Criteria::EQUAL);
		$c->addAscendingOrderByColumn(ReservationPeer::DATE);
		$c->addJoin(ReservationPeer::ROOMPROFILE_ID, RoomprofilePeer::ID);
		$c->addJoin(RoomprofilePeer::ROOM_ID, RoomPeer::ID);

		return self::doSelect($c);
	}

	public static function doSelectOver($includeInactive = false, $time = null)
	{
		if (is_null($time))
		{
			$time = time();
		}
		
		$c = new Criteria();

		$now_date = date('Y-m-d H:i:s', $time);

		$c->addJoin(ReservationPeer::ACTIVITY_ID, ActivityPeer::ID);
		$c->addAnd(ReservationPeer::DATE, "DATE_ADD(".ReservationPeer::DATE.", INTERVAL ".ReservationPeer::DURATION." MINUTE) < '".$now_date."'", Criteria::CUSTOM);
		$c->addAnd(ReservationPeer::DATE, "DATE_ADD(".ReservationPeer::DATE.", INTERVAL (".ReservationPeer::DURATION." + ".ActivityPeer::MINIMUM_DELAY.") MINUTE) >= '".$now_date."'", Criteria::CUSTOM);

		if (!$includeInactive)
		{
			$c->addAnd(ReservationPeer::IS_ACTIVATED, true, Criteria::EQUAL);
		}

		$c->addAnd(ReservationPeer::STATUS, Reservation::BLOCKED, Criteria::EQUAL);
		$c->addAscendingOrderByColumn(ReservationPeer::DATE);
		$c->addJoin(ReservationPeer::ROOMPROFILE_ID, RoomprofilePeer::ID);
		$c->addJoin(RoomprofilePeer::ROOM_ID, RoomPeer::ID);

		return self::doSelect($c);
	}

	public static function report($users, $usergroups, $activities, $zones, $rooms, $begin_date, $end_date, $c = null)
	{
		if (is_null($c))
		{
			$c = new Criteria();
		}

		$begin_date = date('Y-m-d', $begin_date);
		$end_date = date('Y-m-d', $end_date);

		$c->addAnd(ReservationPeer::DATE, $begin_date, Criteria::GREATER_EQUAL);
		$c->addAnd(ReservationPeer::DATE, $end_date, Criteria::LESS_THAN);

		if (!empty($users))
		{
			$c->addAnd(ReservationPeer::USER_ID, $users, Criteria::IN);
		}

		if (!empty($usergroups))
		{
			//print ('Before usergroup'.$c->toString());
			//$c->addJoin(ReservationPeer::USER_ID, UsergroupHasUserPeer::USER_ID, Criteria::LEFT_JOIN);
			//$c->addJoin(ReservationPeer::USER_ID, UsergroupHasChiefPeer::USER_ID, Criteria::LEFT_JOIN);

			//$critUser = $c->getNewCriterion(UsergroupHasUserPeer::USERGROUP_ID, $usergroups, Criteria::IN);
			//$critChief = $c->getNewCriterion(UsergroupHasChiefPeer::USERGROUP_ID, $usergroups, Criteria::IN);
			//$critChief->addOr($critUser);

			//$c->addAnd($critChief);
			$c->addAnd(ReservationPeer::USERGROUP_ID, $usergroups, Criteria::IN);
			//print ('After usergroup'.$c->toString());
		}

		if (!empty($activities))
		{
			$c->addAnd(ReservationPeer::ACTIVITY_ID, $activities, Criteria::IN);
		}
		
		if (is_null($rooms))
		{
			$rooms = array();
		}
		
		if (!empty($zones))
		{
			// Retrieving all rooms from the zone and adding to the rooms array
			foreach ($zones as $zone)
			{
				//print ('   Current zone: '.$zone.'<br>');
				$zp = ZonePeer::retrieveByPk($zone);

				if (!is_null($zp))
				{
					$rms = $zp->getRooms();
					if (!is_null($rms) && is_array($rms))
					{
						foreach($rms as $r)
						{
								//print ('      Room ID: '.$r->getId().'<br>');
								$rooms[] = $r->getId();
						}
					}
				}
			}
		}
		
		//print_r ($rooms);

		if (!empty($rooms) && is_array($rooms) && count($rooms) > 0)
		{
			// Retrieve room profile ID from all rooms
			$roomsProfiles = array();
			foreach ($rooms as $room)
			{
				//print ('   Current room: '.$room.'<br>');
				$rpp = RoomprofilePeer::doSelectFromRoom($room);

				if (!is_null($rpp) && is_array($rpp))
				{
					foreach($rpp as $prof)
					{
						//print ('      Room profile: '.$prof->getId().'<br>');
						$roomsProfiles[] = $prof->getId();
					}
				}
			}
			
			//print_r ($roomsProfiles);

			if (!empty($roomsProfiles) && is_array($roomsProfiles) && count($roomsProfiles) > 0)
			{
				$c->addAnd(ReservationPeer::ROOMPROFILE_ID, $roomsProfiles, Criteria::IN);
			}
		}

		// These joins are needed for sorting purposes
		$c->addJoin(ReservationPeer::USER_ID, UserPeer::ID);
		$c->addJoin(ReservationPeer::ACTIVITY_ID, ActivityPeer::ID);
		$c->addJoin(ReservationPeer::RESERVATIONREASON_ID, ReservationreasonPeer::ID, Criteria::LEFT_JOIN);
		$c->addJoin(ReservationPeer::ROOMPROFILE_ID, RoomprofilePeer::ID);
		$c->addJoin(RoomprofilePeer::ROOM_ID, RoomPeer::ID);
		$c->addJoin(ReservationPeer::USERGROUP_ID, UsergroupPeer::ID, Criteria::LEFT_JOIN);
		$c->addGroupByColumn(ReservationPeer::ID);

		//print ($c->toString().'<br>');

		return self::doSelect($c);
	}

	public static function reportTime($activities, $rooms, $begin_date, $end_date, $begin_hour, $end_hour, $periodicity, $number, $c = null)
	{
		if (is_null($c))
		{
			$c = new Criteria();
		}

		/*print '<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>';

		$l = ReservationPEER::doSelect($c);
		foreach ($l as $t) {
			$tab_res = $t->getReservationsRelatedByReservationparentId();

			print 'ID : '.$t->getId().'<br/>';
			print 'DT : '.$t->getDate().'<br/>';
			print 'AC : '.$t->getActivity().'<br/>';
			print 'CT : '.$t->countReservationsRelatedByReservationparentId().'<br/>';
			print 'RP : '.$t->getReservationRelatedByReservationparentId().'<br/>';
			print 'RF : '.$tab_res[0].'<br/><br/>';
		}*/

		$begin_date = date('Y-m-d', $begin_date);
		$end_date = date('Y-m-d', $end_date);

		// Tableaux contenant les heures de début et fin
		$begin_hour = date('H:i', $begin_hour);
		$tab_begin_hour = explode(":", $begin_hour);

		$end_hour = date('H:i', $end_hour);
		$tab_end_hour = explode(":", $end_hour);

		// Heure pour dates curseurs
		$cursor_date = strtotime("$begin_date + $tab_begin_hour[0] hours + $tab_begin_hour[1] minutes");
		if ($end_hour <= $begin_hour)
		{
			$end_cursor = strtotime("$begin_date + $tab_end_hour[0] hours + $tab_end_hour[1] minutes + 1 day");
			if ($begin_date == $end_date || (($begin_hour == '00:00') && ($begin_hour == $end_hour)))
			{
				$end_date = strtotime("$end_date + $tab_end_hour[0] hours + $tab_end_hour[1] minutes + 1 day");
			} else {
				$end_date = strtotime("$end_date + $tab_end_hour[0] hours + $tab_end_hour[1] minutes");
			}
		} else {
			$end_cursor = strtotime("$begin_date + $tab_end_hour[0] hours + $tab_end_hour[1] minutes");
			$end_date = strtotime("$end_date + $tab_end_hour[0] hours + $tab_end_hour[1] minutes");
		}

		// print "DATE : ".date('Y-m-d H:i:s', $cursor_date)." - ".date('Y-m-d H:i:s', $end_cursor)."<br/>";
		// print "DATE : ".date('Y-m-d H:i:s', strtotime($begin_date))." - ".date('Y-m-d H:i:s', $end_date)."<br/>";

		// Définition du pas
		switch ($periodicity)
		{
		case 'dayly':
			$period='day';
			break;
		case 'weekly':
			$period='week';
			break;
		case 'monthly':
			$period='month';
			break;
		case 'annual':
			$period='year';
			break;
		default:
			$period='day';
			break;
		}

		$count = 0;
		$crit = null;

		while($end_cursor <= $end_date)
		{
			$count++;
			// print "DATE $count => cursor_date : ".date('Y-m-d H:i:s', $cursor_date)." - end_cursor : ".date('Y-m-d H:i:s', $end_cursor)." - end_date : ". date('Y-m-d H:i:s', $end_date)."<br/>";

			// Requête Criteria
			$crit1 = $c->getNewCriterion(ReservationPeer::DATE, $cursor_date, Criteria::GREATER_EQUAL);
			$crit2 = $c->getNewCriterion(ReservationPeer::DATE, strtotime(date('Y-m-d H:i:s')), Criteria::GREATER_EQUAL);
			$crit3 = $c->getNewCriterion(ReservationPeer::DATE, "DATE_ADD(".ReservationPeer::DATE.", INTERVAL ".ReservationPeer::DURATION." MINUTE) <= '".date('Y-m-d H:i:s', $end_cursor)."'", Criteria::CUSTOM);

			$crit1->addAnd($crit2);
			$crit1->addAnd($crit3);

			if ($count > 1) {
				$crit->addOr($crit1);
			} else {
				$crit = $crit1;
			}

			$cursor_date = strtotime(date('Y-m-d H:i:s', $cursor_date).' + '.$number.' '.$period);
			$end_cursor = strtotime(date('Y-m-d H:i:s', $end_cursor).' + '.$number.' '.$period);
		}
		
		$c->add($crit);

		if (!empty($activities))
		{
			$c->addAnd(ReservationPeer::ACTIVITY_ID, $activities, Criteria::IN);
		}
		
		if (is_null($rooms))
		{
			$rooms = array();
		}

		if (!empty($rooms) && is_array($rooms) && count($rooms) > 0)
		{
			// Retrieve room profile ID from all rooms
			$roomsProfiles = array();
			foreach ($rooms as $room)
			{
				//print ('   Current room: '.$room.'<br>');
				$rpp = RoomprofilePeer::doSelectFromRoom($room);

				if (!is_null($rpp) && is_array($rpp))
				{
					foreach($rpp as $prof)
					{
						//print ('      Room profile: '.$prof->getId().'<br>');
						$roomsProfiles[] = $prof->getId();
					}
				}
			}
			
			//print_r ($roomsProfiles);

			if (!empty($roomsProfiles) && is_array($roomsProfiles) && count($roomsProfiles) > 0)
			{
				$c->addAnd(ReservationPeer::ROOMPROFILE_ID, $roomsProfiles, Criteria::IN);
			}
		}

		// These joins are needed for sorting purposes
		$c->addJoin(ReservationPeer::ACTIVITY_ID, ActivityPeer::ID);
		$c->addJoin(ReservationPeer::ROOMPROFILE_ID, RoomprofilePeer::ID);
		$c->addJoin(RoomprofilePeer::ROOM_ID, RoomPeer::ID);
		$c->addGroupByColumn(ReservationPeer::ID);

		// print ($c->toString().'<br>');

		return self::doSelect($c);
	}

	public static function reportRepeatAll($reservation)
	{
		$res = ReservationPeer::retrieveByPk($reservation->getId());
		$result = array();
		
		while ($res->hasParent())
		{
			$res = $res->getReservationRelatedByReservationparentId();
		}
		
		$result[] = $res;
		
		while ($res->hasDaughters())
		{
			$daughter = $res->getReservationsRelatedByReservationparentId();
			
			$result[] = $daughter[0];
			
			$res = $daughter[0];
		}
		
		return $result;
	}
	
	public static function reportRepeatNext($reservation)
	{
		$res = ReservationPeer::retrieveByPk($reservation->getId());
		$result = array();
		
		$result[] = $res;
		
		while ($res->hasDaughters())
		{
			$daughter = $res->getReservationsRelatedByReservationparentId();
			
			$result[] = $daughter[0];
			
			$res = $daughter[0];
		}
		
		return $result;
	}
	
	public static function reportRepeatPrevious($reservation)
	{
		$res = ReservationPeer::retrieveByPk($reservation->getId());
		$result = array();
		
		while ($res->hasParent())
		{
			$res = $res->getReservationRelatedByReservationparentId();
		}
		
		$result[] = $res;	
		
		while ($res->hasDaughters() && $res->getId() != $reservation->getId())
		{
			$daughter = $res->getReservationsRelatedByReservationparentId();
			
			$result[] = $daughter[0];
			
			$res = $daughter[0];
		}
		
		return $result;
	}

	public static function getDayCriteria($date, $c = null)
	{
		if (is_null($c))
		{
			$c = new Criteria();
		}

		$cton1 = $c->getNewCriterion(ReservationPeer::DATE, "DATE_ADD(".ReservationPeer::DATE.", INTERVAL ".ReservationPeer::DURATION." MINUTE) > '".strftime('%Y-%m-%d', self::getDayStart($date))."'", Criteria::CUSTOM);
		$cton2 = $c->getNewCriterion(ReservationPeer::DATE, strftime('%Y-%m-%d', self::getDayStop($date)), Criteria::LESS_THAN);

		$cton1->addAnd($cton2);

		$c->addAnd($cton1);

		return $c;
	}

	public static function getWeekCriteria($date, $c = null)
	{
		if (is_null($c))
		{
			$c = new Criteria();
		}

		$cton1 = $c->getNewCriterion(ReservationPeer::DATE, "DATE_ADD(".ReservationPeer::DATE.", INTERVAL ".ReservationPeer::DURATION." MINUTE) > '".strftime('%Y-%m-%d', self::getWeekStart($date))."'", Criteria::CUSTOM);
		$cton2 = $c->getNewCriterion(ReservationPeer::DATE, strftime('%Y-%m-%d', self::getWeekStop($date)), Criteria::LESS_THAN);

		$cton1->addAnd($cton2);

		$c->addAnd($cton1);

		return $c;
	}

	public static function getMonthCriteria($date, $c = null)
	{
		if (is_null($c))
		{
			$c = new Criteria();
		}

		$cton1 = $c->getNewCriterion(ReservationPeer::DATE, "DATE_ADD(".ReservationPeer::DATE.", INTERVAL ".ReservationPeer::DURATION." MINUTE) > '".strftime('%Y-%m-%d', self::getMonthStart($date))."'", Criteria::CUSTOM);
		$cton2 = $c->getNewCriterion(ReservationPeer::DATE, strftime('%Y-%m-%d', self::getMonthStop($date)), Criteria::LESS_THAN);

		$cton1->addAnd($cton2);

		$c->addAnd($cton1);

		return $c;
	}

	public static function getPeriodCriteria($begin_date, $end_date, $c = null)
	{
		if (is_null($c))
		{
			$c = new Criteria();
		}

		$cton1 = $c->getNewCriterion(ReservationPeer::DATE, "DATE_ADD(".ReservationPeer::DATE.", INTERVAL ".ReservationPeer::DURATION." MINUTE) > '".strftime('%Y-%m-%d', $begin_date)."'", Criteria::CUSTOM);
		$cton2 = $c->getNewCriterion(ReservationPeer::DATE, strftime('%Y-%m-%d', $end_date), Criteria::LESS_THAN);

		$cton1->addAnd($cton2);

		$c->addAnd($cton1);

		return $c;
	}

	static public function getSortAliases()
	{
		return array(
			'user' => array(UserPeer::FAMILY_NAME, UserPeer::SURNAME),
			'date' => self::DATE,
			'duration' => self::DURATION,
			'activity' => ActivityPeer::NAME,
			'room' => RoomPeer::NAME,
			'reservationreason' => ReservationreasonPeer::NAME,
			'comment' => self::COMMENT,
			'usergroup' => UsergroupPeer::NAME,
			'members_count' => self::MEMBERS_COUNT,
			'guests_count' => self::GUESTS_COUNT,
			'status' => self::STATUS,
		);
	}

	public static function getDayStart($date)
	{
		return mktime(0, 0, 0, date('n', $date), date('d', $date), date('Y', $date));
	}

	public static function getDayStop($date)
	{
		return mktime(0, 0, 0, date('n', $date), date('d', $date) + 1, date('Y', $date));
	}

	public static function getWeekStart($date)
	{
		$days = date('N', $date) - 1;
		return self::getDayStart(strtotime("- $days day", $date));
	}

	public static function getWeekStop($date)
	{
		return self::getDayStart(strtotime('next monday', $date));
	}

	public static function getMonthStart($date)
	{
		return mktime(0, 0, 0, date('n', $date), 1, date('Y', $date));
	}

	public static function getMonthStop($date)
	{
		return strtotime('+1 month', self::getMonthStart($date));
	}

	protected static function getWeekDaysFromInterval($start, $stop)
	{
		$result = array();

		// We check if $stop is after one week or more after $start

		if ($stop >= strtotime(date('Y-m-d H:i:s', $start).' +1 week'))
		{
			for ($i = 0; $i < 7; ++$i)
			{
				$result[] = $i;
			}
		} else
		{
			$start_day = intval(date('N', $start)) - 1;
			$stop_day = intval(date('N', $stop)) - 1;

			for ($i = $start_day; $i % 7 != $stop_day; ++$i)
			{
				$result[] = $i % 7;
			}

			$result[] = $stop_day;
		}

		return $result;
	}

	public static function checkDayperiods($start, $stop, $roomId)
	{
		$starttime = strtotime($start);
		$stoptime = strtotime($stop);

		if ($starttime >= $stoptime)
		{
			//throw new Exception('Start time cannot be superior or equal to stop time.');
			return false;
		}

		$c = DayperiodPeer::getFromRoomCriteria($roomId);

		$c->addAnd(DayperiodPeer::DAY_OF_WEEK, self::getWeekDaysFromInterval($starttime, $stoptime), Criteria::IN);

		$dayperiods = DayperiodPeer::doSelect($c);

		while ($starttime < $stoptime)
		{
			// Check if the start matches one dayperiod
			$tst = null;

			foreach ($dayperiods as $index => $dayperiod)
			{
				if ($dayperiod->matchTimestamp($starttime))
				{
					$tst = $dayperiod->getStopTimestamp($starttime);
					unset($dayperiods[$index]);
					break;
				}
			}

			if (is_null($tst))
			{
				return false;
			}

			$starttime = $tst;
		}

		return true;
	}

	public static function checkCloseperiods($start, $stop, $roomId)
	{
		$starttime = strtotime($start);
		$stoptime = strtotime($stop);

		if ($starttime >= $stoptime)
		{
			//throw new Exception('Start time cannot be superior or equal to stop time.');
			return false;
		}

		$c = CloseperiodPeer::getFromRoomCriteria($roomId);
		$c->addAnd(CloseperiodPeer::STOP, $start, Criteria::GREATER_THAN);
		$c->addAnd(CloseperiodPeer::START, $stop, Criteria::LESS_THAN);

		return (CloseperiodPeer::doCount($c) == 0);
	}

	public static function getOverlappingReservations($id, $start, $stop, $roomId = null, $userId = null, $cardId = null)
	{
		$c = self::getOverlappingReservationsCriteria($id, $start, $stop, $roomId, $userId, $cardId);

		return ReservationPeer::doSelect($c);
	}

	public static function getOverlappingReservationsCount($id, $start, $stop, $roomId = null, $userId = null, $cardId = null)
	{
		$c = self::getOverlappingReservationsCriteria($id, $start, $stop, $roomId, $userId, $cardId);

		return ReservationPeer::doCount($c);
	}

	public static function overlaps($id, $start, $stop, $roomId = null, $userId = null, $cardId = null)
	{
		return (self::getOverlappingReservationsCount($id, $start, $stop, $roomId, $userId, $cardId) > 0);
	}

	public static function getOverlappingReservationsCriteria($id, $start, $stop, $roomId = null, $userId = null, $cardId = null)
	{
		$c = new Criteria();

		if (!is_null($roomId))
		{
			$c->addJoin(ReservationPeer::ROOMPROFILE_ID, RoomprofilePeer::ID);
			$c->add(RoomprofilePeer::ROOM_ID, $roomId, Criteria::EQUAL);
		}

		if (!is_null($userId))
		{
			$c->add(ReservationPeer::USER_ID, $userId, Criteria::EQUAL);
		}

		if (!is_null($cardId))
		{
			$c->add(ReservationPeer::CARD_ID, $cardId, Criteria::EQUAL);
		}

		if (!is_null($id))
		{
			$c->addAnd(ReservationPeer::ID, $id, Criteria::NOT_EQUAL);
		}

		// Starts before or when I start
		// AND
		// Ends after I start

		$cStartBeforeMe = $c->getNewCriterion(ReservationPeer::DATE, $start, Criteria::LESS_EQUAL);
		$cEndsAfterMe = $c->getNewCriterion(ReservationPeer::DATE, "DATE_ADD(".ReservationPeer::DATE.", INTERVAL ".ReservationPeer::DURATION." MINUTE) > '$start'", Criteria::CUSTOM);

		$cStartBeforeMe->addAnd($cEndsAfterMe);

		// Starts after or when I start
		// AND
		// Starts before I end

		$cStartAfterMe = $c->getNewCriterion(ReservationPeer::DATE, $start, Criteria::GREATER_EQUAL);
		$cStartBeforeIEnd = $c->getNewCriterion(ReservationPeer::DATE, $stop, Criteria::LESS_THAN);

		$cStartAfterMe->addAnd($cStartBeforeIEnd);

		// Combine criterions

		$cStartBeforeMe->addOr($cStartAfterMe);

		$c->addAnd($cStartBeforeMe);

		return $c;
	}
	
	public static function checkDaughter($id = 0)
	{
		$c = new Criteria();
		$crit = $c->getNewCriterion(ReservationPeer::RESERVATIONPARENT_ID, $id);
		
		$c->add($crit);
		
		$result = ReservationPEER::doSelect($c);
		
		/* foreach ($result as $res)
		{
			print $id.' / RESULTAT : '.$res->getId().'<br />';
		} */
		
		if (count($result) > 0) {
			return true;
		} else {
			return false;
		}
	}
}
