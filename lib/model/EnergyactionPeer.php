<?php

class EnergyactionPeer extends BaseEnergyactionPeer
{
	public static function setAllNewIdentifierName($ex_controller_name, $new_controller_name)
	{
		$c = new Criteria();
		$energyactions = self::doSelect($c);
		$results = array();
		
		if (is_array($energyactions) && !empty($energyactions))
		{
			foreach ($energyactions as $energyaction)
			{
				$configured_automation_name = $energyaction->getConfiguredControllerName();
				
				// print '<br/>----------------------------------------------------<br/>configured_automation_name:<br/>';
				// var_dump($configured_automation_name);
				// print '<br />ex_controller_name:<br />';
				// var_dump($ex_controller_name);
				
				if ($ex_controller_name === $configured_automation_name)
				{
					$action_name = $energyaction->getName();
					$new_name = self::buildName($new_controller_name, $action_name);
					// print '<br />newname:<br />';
					// var_dump($new_name);
					$energyaction->setName($new_name);
					$results[] = $energyaction;
				}
			}
		}
		
		return $results;
	}
	
	public static function buildName($controller_name, $prevname)
	{
		$names = explode(':', $prevname);
		$size = count($names);
		
		if (empty($controller_name) || is_null($controller_name))
		{
			return $names[$size-1];
		} else
		{
			return $controller_name.':'.$names[$size-1];
		}
	}

	public static function doSelectAllEnergyactions()
	{
		$c = new Criteria();
		$c->addAscendingOrderByColumn(self::NAME);

		return self::doSelect($c);
	}

	static public function doSelectOutOfPeriod($activeOnly = true, $time = null)
	{
		if (is_null($time))
		{
			$time = time();
		}

		$now_hour = date('H:i:s', $time);

		$c = new Criteria();

		$critA = $c->getNewCriterion(self::START, self::START.' < '.self::STOP, Criteria::CUSTOM);
		$critB = $c->getNewCriterion(self::START, self::START.' > '.self::STOP, Criteria::CUSTOM);

		$critStartA = $c->getNewCriterion(self::START, $now_hour, Criteria::GREATER_EQUAL);
		$critStopA = $c->getNewCriterion(self::STOP, $now_hour, Criteria::LESS_THAN);
		$critStartB = $c->getNewCriterion(self::START, $now_hour, Criteria::GREATER_EQUAL);
		$critStopB = $c->getNewCriterion(self::STOP, $now_hour, Criteria::LESS_THAN);

		$critStopA->addOr($critStartA);
		$critStopB->addAnd($critStartB);

		$critA->addAnd($critStopA);
		$critB->addAnd($critStopB);

		$critA->addOr($critB);

		if ($activeOnly)
		{
			$c->addAnd(self::STATUS, true, Criteria::EQUAL);
		}

		$c->addAnd($critA);
		$c->addAnd(self::START, self::START.' <> '.self::STOP, Criteria::CUSTOM);

		return self::doSelect($c);
	}

	static public function doSelectReady($time = null)
	{
		if (is_null($time))
		{
			$time = time();
		}

		$reservations = ReservationPeer::doSelectReady(false, $time);

		$actions = array();

		foreach ($reservations as $reservation)
		{
			foreach ($reservation->getRoomprofile()->getRoom()->getEnergyactions() as $action)
			{
				if ($action->getUpDate(strtotime($reservation->getDate())) <= $time)
				{
					$actions[$action->getId()] = $action;
				}
			}
		}

		return $actions;
	}

	static public function doSelectOver($time = null, $readyActions)
	{
		if (is_null($time))
		{
			$time = time();
		}

		$reservations = ReservationPeer::doSelectOver(false, $time);

		$actions = array();

		foreach ($reservations as $reservation)
		{
			foreach ($reservation->getRoomprofile()->getRoom()->getEnergyactions() as $action)
			{
				if (!array_key_exists($action->getId(), $readyActions))
				{
					if ($action->getDownDate(strtotime($reservation->getStopDate())) <= $time)
					{
						$actions[$action->getId()] = $action;
					}
				}
			}
		}

		return $actions;
	}

	static public function getSortAliases()
	{
		return array(
			'name' => self::NAME,
			'start' => self::START,
			'stop' => self::STOP,
			'status' => self::STATUS,
		);
	}
}
