<?php

class myUser extends sfBasicSecurityUser
{
	public function setTemposUser($user)
	{
		if (!is_null($user))
		{
				$this->setTemposCard(null);
				$this->addCredential('user');

				$roles = $user->getRoles();

				foreach($roles as $role)
				{
					$this->addCredential($role->getId());
				}
		} else
		{
			$this->clearCredentials();
		}

		$this->setAttribute('user', $user, 'tempos');
		$this->setAuthenticated(!is_null($user));
	}

	public function getTemposUser($default = null)
	{
		return $this->getAttribute('user', $default, 'tempos');
	}

	public function checkTemposUser(User $user)
	{
		$tuser = $this->getTemposUser();

		if (!is_null($tuser))
		{
			return ($tuser->getId() == $user->getId());
		}
	}

	public function setTemposCard($card)
	{
		if (!is_null($card))
		{
				$this->setTemposUser(null);
				$this->addCredential('user');
		} else
		{
			$this->clearCredentials();
		}

		$this->setAttribute('card', $card, 'tempos');
		$this->setAuthenticated(!is_null($card));
	}

	public function getTemposCard($default = null)
	{
		return $this->getAttribute('card', $default, 'tempos');
	}

	public function checkTemposCard(Card $card)
	{
		$tcard = $this->getTemposCard();

		if (!is_null($tcard))
		{
			return ($tcard->getId() == $card->getId());
		}
	}

	public function getPerson()
	{
		if (!is_null($this->getTemposUser()))
		{
			return $this->getTemposUser();
		}

		if (!is_null($this->getTemposCard()))
		{
			return $this->getTemposCard();
		}

		return null;
	}

	public function getActivities()
	{
		$user = $this->getTemposUser();

		if (!is_null($user))
		{
			return $user->getActiveSubscriptionsActivities();
		}

		$card = $this->getTemposCard();

		if (!is_null($card))
		{
			return $card->getActiveSubscriptionsActivities();
		}

		return array();
	}

	public function getGroupsAsMember()
	{
		if (!is_null($this->getTemposUser()))
		{
			return $this->getTemposUser()->getGroupsAsMember();
		}

		return array();
	}

	public function getGroupsAsLeader()
	{
		if (!is_null($this->getTemposUser()))
		{
			return $this->getTemposUser()->getGroupsAsLeader();
		}

		return array();
	}

	public function getUpcomingReservations($count)
	{
		$user = $this->getTemposUser();

		if (!is_null($user))
		{
			return $user->getUpcomingReservations($count);
		}

		$card = $this->getTemposCard();

		if (!is_null($card))
		{
			return $card->getUpcomingReservations($count);
		}

		return array();
	}

	public function hasActivity($activityId)
	{
		$user = $this->getTemposUser();

		if (!is_null($user))
		{
			return $user->hasActivity($activityId);
		}

		$card = $this->getTemposCard();

		if (!is_null($card))
		{
			return $card->hasActivity($activityId);
		}

		return false;
	}

	public function canAccessRoom($roomId, $activityId = null)
	{
		$user = $this->getTemposUser();

		if (!is_null($user))
		{
			return $user->canAccessRoom($roomId, $activityId);
		}

		$card = $this->getTemposCard();

		if (!is_null($card))
		{
			return $card->canAccessRoom($roomId, $activityId);
		}

		return false;
	}

	public function canEditReservation($reservation)
	{
		$user = $this->getTemposUser();

		if (!is_null($user))
		{
			return $user->canEditReservation($reservation);
		}

		$card = $this->getTemposCard();

		if (!is_null($card))
		{
			return $card->canEditReservation($reservation);
		}

		return false;
	}

	public function canDeleteReservation($reservation)
	{
		$user = $this->getTemposUser();

		if (!is_null($user))
		{
			return $user->canDeleteReservation($reservation);
		}

		$card = $this->getTemposCard();

		if (!is_null($card))
		{
			return $card->canDeleteReservation($reservation);
		}

		return false;
	}

	public function canSendMessage($reservation)
	{
		$user = $this->getTemposUser();

		if (!is_null($user))
		{
			return $user->canSendMessage($reservation);
		}

		$card = $this->getTemposCard();

		if (!is_null($card))
		{
			return $card->canSendMessage($reservation);
		}

		return false;
	}

	public function getSavedParameters($module = null, $action = null)
	{
		$array = $this->getAttribute('saved_parameters', array(), 'tempos');

		if (!is_null($module))
		{
			if (array_key_exists($module, $array))
			{
				$array = $array[$module];

				if (!is_null($action))
				{
					if (array_key_exists($action, $array))
					{
						$array = $array[$action];
					} else
					{
						$array = array();
					}
				}
			} else
			{
				$array = array();
			}
		}

		return $array;
	}

	public function setSavedParameters($params, $module = null, $action = null)
	{
		if (!is_null($module))
		{
			$array = $this->getSavedParameters();

			if (!is_null($action))
			{
				$array[$module][$action] = $params;
			} else
			{
				$array[$module] = $params;
			}
		} else
		{
			$array = $params;
		}

		$this->setAttribute('saved_parameters', $array, 'tempos');
	}

	public function clearSavedParameters()
	{
		$this->setAttribute('saved_parameters', null, 'tempos');
	}

	public function syncParameters($object, $module, $action, $params, sfWebRequest $request)
	{
		if ($request->hasParameter('clear'))
		{
			$clearVal = $request->getParameter('clear');

			if (empty($clearVal))
			{
				$saved_params = array();
			} else
			{
				$saved_params = $this->getSavedParameters($module, $action);
				unset($saved_params[$clearVal]);
			}
		} else
		{
			$saved_params = $this->getSavedParameters($module, $action);
		}

		foreach ($params as $param)
		{
			if ($request->hasParameter($param))
			{
				$object->$param = $request->getParameter($param);
				$saved_params[$param] = $object->$param;
			} elseif (array_key_exists($param, $saved_params))
			{
				$object->$param = $saved_params[$param];
				$request->setParameter($param, $object->$param);
			} else
			{
				$object->$param = null;
			}
		}
		
		$this->setSavedParameters($saved_params, $module, $action);
	}
}
