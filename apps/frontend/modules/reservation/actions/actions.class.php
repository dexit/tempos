<?php

/**
* reservation actions.
*
* @package    tempos
* @subpackage reservation
* @author     ISLOG
* @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
*/
class reservationActions extends sfActions
{
	protected function getUserAttributes()
	{
		if ($this->getUser()->hasAttribute('userId'))
		{
			$this->forward404Unless($this->usergroup = UsergroupPeer::retrieveByPk($this->getUser()->getAttribute('usergroupId')), sprintf('Object usergroup does not exist (%s).', $this->getUser()->getAttribute('usergroupId')));
			$this->forward404Unless($this->user = UserPeer::retrieveByPk($this->getUser()->getAttribute('userId')), sprintf('Object user does not exist (%s).', $this->getUser()->getAttribute('userId')));
			$this->forward404Unless($this->user->isMember($this->usergroup), 'The user is not member of the specified usergroup.');
			$this->forward404Unless($this->getUser()->getTemposUser()->isLeader($this->usergroup), 'The logged user is not leader of the specified usergroup.');

			$this->person = $this->user;
			return true;
		} else
		{
			$this->forward404Unless($this->person = $this->getUser()->getPerson(), 'No user or card logged-in.');
			return false;
		}
	}

	protected function checkRoomAccess($roomId, $activityId)
	{
		if (isset($this->user) && (!is_null($this->user)))
		{
			$this->forward404Unless($this->user->canAccessRoom($roomId, $activityId), sprintf('Cannot access room ("%s").', $this->room));
		} else
		{
			$this->forward404Unless($this->getUser()->canAccessRoom($roomId, $activityId), sprintf('Cannot access room ("%s").', $this->room));
		}
	}

	protected function isAdmin()
	{
		return $this->getUser()->hasCredential('admin', false);
	}

	public function executeIndex(sfWebRequest $request)
	{
		$this->forward404Unless($this->room = RoomPeer::retrieveByPk($request->getParameter('roomId')), sprintf('Object room does not exist (%s).', $request->getParameter('roomId')));

		$this->getUserAttributes();

		if ($request->hasParameter('activityId'))
		{
			$this->forward404Unless($this->activity = ActivityPeer::retrieveByPk($request->getParameter('activityId')), sprintf('Object activity does not exist (%s).', $request->getParameter('activityId')));
			$this->forward404Unless($this->person->hasActivity($this->activity->getId()), sprintf('Cannot access entry ("%s").', $this->activity));
			$this->getUser()->setAttribute('activityId', $this->activity->getId());
		} else
		{
			$this->forward404Unless($this->activity = ActivityPeer::retrieveByPk($this->getUser()->getAttribute('activityId')), sprintf('Object activity does not exist (%s).', $this->getUser()->getAttribute('activityId')));
		}

		$this->checkRoomAccess($this->room->getId(), $this->activity->getId());

		$this->forward404Unless($this->realPerson = $this->getUser()->getPerson(), 'No user or card logged-in.');

		$this->is_admin = $this->isAdmin();

		$this->getUser()->syncParameters($this, 'reservation', 'index', array('displayPeriod'), $request);
		$this->getUser()->syncParameters($this, 'general', 'index', array('date'), $request);

		if (is_null($this->date))
		{
			$this->date = time();
		} else
		{
			$this->date = strtotime($this->date);
		}

		if ($request->hasParameter('displayPeriod'))
		{
			$this->displayPeriod = $request->getParameter('displayPeriod');
		} else
		{
			$this->displayPeriod = 'week';
		}

		if ($this->displayPeriod == 'week')
		{
			$this->weekStart = ReservationPeer::getWeekStart($this->date);
			$this->reservation_list = ReservationPeer::doSelectWeekOrderbyDate($this->room->getId(), $this->date);
		} else
		{
			$this->forward404(sprintf('No valid display period set (%s).', $this->displayPeriod));
		}

		if ($this->getUser()->hasFlash('deleteError'))
		{
			$this->deleteError = $this->getUser()->getFlash('deleteError');
		}
	}

	public function executeRandom(sfWebRequest $request)
	{
		$rooms = explode(',', $request->getParameter('rooms'));

		$this->forward404Unless(!empty($rooms), sprintf('Missing or empty "rooms" parameter'));

		$roomId = $rooms[rand(0, count($rooms) - 1)];

		if ($request->hasParameter('date'))
		{
			$this->redirect('reservation/new?roomId='.$roomId.'&date='.$request->getParameter('date'));
		} else
		{
			$this->redirect('reservation/new?roomId='.$roomId);
		}
	}

	protected function getCurrentUser()
	{
		$user = $this->getUser()->getTemposUser();
		
		if (is_null($user))
		{
			$user = $this->getUser()->getTemposCard();
		}
		return $user;
	}

	protected function setFormDefaultOwner()
	{
		if ($this->getUserAttributes())
		{
			$this->form->setDefaultUser($this->user);
		} else
		{
			$user = $this->getUser()->getTemposUser();

			if (!is_null($user))
			{
				$this->form->setDefaultUser($user);
			} else
			{
				$card = $this->getUser()->getTemposCard();

				if (!is_null($card))
				{
					$this->form->setDefaultCard($card);
				} else
				{
					$this->forward404('No user or card logged-in.');
				}
			}
		}
	}

	public function executeNew(sfWebRequest $request)
	{
		$this->forward404Unless($this->room = RoomPeer::retrieveByPk($request->getParameter('roomId')), sprintf('Object room does not exist (%s).', $request->getParameter('roomId')));
		$this->forward404Unless($this->activity = ActivityPeer::retrieveByPk($this->getUser()->getAttribute('activityId')), sprintf('Object activity does not exist (%s).', $this->getUser()->getAttribute('activityId')));

		$this->getUserAttributes();
		$this->checkRoomAccess($this->room->getId(), $this->activity->getId());

		$this->form = new ReservationForm();

		$this->form->setDefaultRoom($this->room);
		$this->form->setDefaultActivity($this->activity);
		$this->form->setDefaultCustomUsers(null, $this->getCurrentUser(), $this->activity->getId());
		$this->setFormDefaultOwner();

		if ($request->hasParameter('duration'))
		{
			$duration = $request->getParameter('duration');
			$this->form->setDefaultDuration($duration);
		} else
		{
			$duration = $this->form->getDefault('duration');
		}

		if ($request->hasParameter('date'))
		{
			$date = $request->getParameter('date');
			$this->form->setDefaultDate($date);

			if ($this->getUser()->getTemposUser())
			{
				$userId = $this->getUser()->getTemposUser()->getId();
				$cardId = null;
			} else if ($this->getUser()->getTemposCard())
			{
				$userId = null;
				$cardId = $this->getUser()->getTemposCard()->getId();
			}

			$reservations = ReservationPeer::getOverlappingReservations(null, $date, strtotime("+ $duration minutes", strtotime($date)), null, $userId, $cardId);
			$this->colliding_reservation = count($reservations) > 0 ? $reservations[0] : null;
		} else
		{
			$this->colliding_reservation = null;
		}
	}

	public function executeCreate(sfWebRequest $request)
	{
		$this->forward404Unless($request->isMethod('post'));
		$this->forward404Unless($this->room = RoomPeer::retrieveByPk($request->getParameter('roomId')), sprintf('Object room does not exist (%s).', $request->getParameter('roomId')));
		$this->forward404Unless($this->activity = ActivityPeer::retrieveByPk($this->getUser()->getAttribute('activityId')), sprintf('Object activity does not exist (%s).', $this->getUser()->getAttribute('activityId')));

		$this->getUserAttributes();
		$this->checkRoomAccess($this->room->getId(), $this->activity->getId());

		$this->form = new ReservationForm();

		$this->form->setDefaultRoom($this->room);
		$this->form->setDefaultActivity($this->activity);
		$this->form->setDefaultCustomUsers(null, $this->getCurrentUser(), $this->activity->getId());
		$this->setFormDefaultOwner();

		if ($request->hasParameter('date'))
		{
			$this->date = $request->getParameter('date');
			$this->form->setDefaultDate($this->date);
		}

		$this->processForm($request, $this->form);

		$this->setTemplate('new');
	}

	public function executeEdit(sfWebRequest $request)
	{
		$this->forward404Unless($reservation = ReservationPeer::retrieveByPk($request->getParameter('id')), sprintf('Object reservation does not exist (%s).', $request->getParameter('id')));
		$this->forward404Unless($this->activity = ActivityPeer::retrieveByPk($this->getUser()->getAttribute('activityId')), sprintf('Object activity does not exist (%s).', $this->getUser()->getAttribute('activityId')));

		if (!$this->isAdmin() || $reservation->isPast())
		{
			$this->forward404Unless($this->getUser()->canEditReservation($reservation), sprintf('Insufficent permissions to edit reservation (%s).', $reservation->getId()));
		}

		$this->room = $reservation->getRoomprofile()->getRoom();

		$this->getUserAttributes();
		$this->checkRoomAccess($this->room->getId(), $this->activity->getId());

		$this->form = new ReservationForm($reservation);

		$this->form->setDefaultRoom($this->room);
		$this->form->setDefaultActivity($this->activity);
		$this->form->setDefaultCustomUsers($reservation->getUsergroupId(), $this->getCurrentUser(), $this->activity->getId());
		$this->setFormDefaultOwner();
	}

	public function executeUpdate(sfWebRequest $request)
	{
		$this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));
		$this->forward404Unless($reservation = ReservationPeer::retrieveByPk($request->getParameter('id')), sprintf('Object reservation does not exist (%s).', $request->getParameter('id')));
		$this->forward404Unless($this->activity = ActivityPeer::retrieveByPk($this->getUser()->getAttribute('activityId')), sprintf('Object activity does not exist (%s).', $this->getUser()->getAttribute('activityId')));

		if (!$this->isAdmin() || $reservation->isPast())
		{
			$this->forward404Unless($this->getUser()->canEditReservation($reservation), sprintf('Insufficent permissions to edit reservation (%s).', $reservation->getId()));
		}

		$this->room = $reservation->getRoomprofile()->getRoom();

		$this->getUserAttributes();
		$this->checkRoomAccess($this->room->getId(), $this->activity->getId());

		$this->form = new ReservationForm($reservation);

		$this->form->setDefaultRoom($this->room);
		$this->form->setDefaultActivity($this->activity);
		$this->form->setDefaultCustomUsers($reservation->getUsergroupId(), $this->getCurrentUser(), $this->activity->getId());
		$this->setFormDefaultOwner();

		$this->processForm($request, $this->form);

		$this->setTemplate('edit');
	}

	public function executeRepeat(sfWebRequest $request)
	{
		$this->forward404Unless($reservation = ReservationPeer::retrieveByPk($request->getParameter('id')), sprintf('Object reservation does not exist (%s).', $request->getParameter('id')));
		$this->forward404Unless($this->activity = ActivityPeer::retrieveByPk($this->getUser()->getAttribute('activityId')), sprintf('Object activity does not exist (%s).', $this->getUser()->getAttribute('activityId')));

		if (!$this->isAdmin() || $reservation->isPast())
		{
			$this->forward404Unless($this->getUser()->canEditReservation($reservation), sprintf('Insufficent permissions to edit reservation (%s).', $reservation->getId()));
		}

		$this->forward404Unless(!$reservation->hasParent(), sprintf('The reservation is already a part of reservations repetition'));
		$this->forward404Unless(!$reservation->hasDaughters(), sprintf('The reservation is already a part of reservations repetition'));

		$this->room = $reservation->getRoomprofile()->getRoom();

		$this->getUserAttributes();
		$this->checkRoomAccess($this->room->getId(), $this->activity->getId());

		$this->form = new ReservationRepeatForm($reservation);
		
		$this->forms = array();
	}

	public function executeProcessRepeat(sfWebRequest $request)
	{
		$this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));
		$this->forward404Unless($reservation = ReservationPeer::retrieveByPk($request->getParameter('id')), sprintf('Object reservation does not exist (%s).', $request->getParameter('id')));
		$this->forward404Unless($this->activity = ActivityPeer::retrieveByPk($this->getUser()->getAttribute('activityId')), sprintf('Object activity does not exist (%s).', $this->getUser()->getAttribute('activityId')));
		
		if (!$this->isAdmin() || $reservation->isPast())
		{
			$this->forward404Unless($this->getUser()->canEditReservation($reservation), sprintf('Insufficent permissions to edit reservation (%s).', $reservation->getId()));
		}
		
		$this->forward404Unless(!$reservation->hasParent(), sprintf('The reservation is already a part of reservations repetition'));
		$this->forward404Unless(!$reservation->hasDaughters(), sprintf('The reservation is already a part of reservations repetition'));

		$this->room = $reservation->getRoomprofile()->getRoom();

		$this->getUserAttributes();
		$this->checkRoomAccess($this->room->getId(), $this->activity->getId());

		$this->form = new ReservationRepeatForm($reservation);
		
		$this->processRepeatForm($request, $this->form);
		
		if (!$this->form->isValid())
		{
			$this->setTemplate('repeat');
		}
	}

	public function executeSendMessage(sfWebRequest $request)
	{
		$this->forward404Unless($this->reservation = ReservationPeer::retrieveByPk($request->getParameter('id')), sprintf('Object reservation does not exist (%s).', $request->getParameter('id')));
		$this->forward404Unless($this->activity = ActivityPeer::retrieveByPk($this->getUser()->getAttribute('activityId')), sprintf('Object activity does not exist (%s).', $this->getUser()->getAttribute('activityId')));

		$this->forward404Unless($this->getUser()->canSendMessage($this->reservation), sprintf('Insufficient permissions to send message (%s).', $this->reservation->getId()));

		
		$this->room = $this->reservation->getRoomprofile()->getRoom();
		$recipient = $this->reservation->getUser();
		$sender = $this->getUser()->getTemposUser();

		$this->form = new MessageForm();
		
		$this->form->setRecipient($recipient);
		$this->form->setSender($sender);
		$this->form->setText(
            sprintf("%s\n%s %s\n%s\n%s %s\n=============================\n\n",
                sfContext::getInstance()->getI18N()->__('About reservation: '),
                sfContext::getInstance()->getI18N()->__('Activity: '),
                $this->activity->getName(),
                $this->reservation->getDate(),
                sfContext::getInstance()->getI18N()->__('Room: '),
                $this->reservation->getRoomProfile()->getRoom()->getName()
            )
        );
	}

	public function executeSendMessageProcess(sfWebRequest $request)
	{
		$this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));
		$this->forward404Unless($this->reservation = ReservationPeer::retrieveByPk($request->getParameter('id')), sprintf('Object reservation does not exist (%s).', $request->getParameter('id')));
		$this->executeSendMessage($request);

		$this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));
		
		if ($this->form->isValid())
		{
			$this->succeeded = false;
			
			try
			{
				// $this->form->setText(sprintf("%s\n\n--------------------\n\n%s", 'TEST REUSSI', $this->form->getValue('text')));
				$this->form->save();
				$this->succeeded = true;
			}
			catch (Exception $ex)
			{
			}

			if ($this->succeeded)
			{
				$this->redirect('reservation/index?roomId='.$this->room->getId());
			}
		}

		$this->setTemplate('sendMessage');
	}

	public function executeSendDeleteMessage(sfWebRequest $request)
	{
		$this->forward404Unless($this->user = UserPeer::retrieveByPk($request->getParameter('userId')), sprintf('Object user does not exist (%s).', $request->getParameter('userId')));
		$this->forward404Unless($this->room = RoomPeer::retrieveByPk($request->getParameter('roomId')), sprintf('Object room does not exist (%s).', $request->getParameter('roomId')));
		$this->forward404Unless($this->activity = ActivityPeer::retrieveByPk($this->getUser()->getAttribute('activityId')), sprintf('Object activity does not exist (%s).', $this->getUser()->getAttribute('activityId')));

		$sender = $this->getUser()->getTemposUser();

		$this->form = new MessageForm();
		$this->form->setRecipient($this->user);
		$this->form->setSender($sender);
	}

	public function executeSendDeleteMessageProcess(sfWebRequest $request)
	{
		$this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));

		$this->executeSendDeleteMessage($request);

		$this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));

		if ($this->form->isValid())
		{
			$this->succeeded = false;

			try
			{
				$this->form->save();
				$this->succeeded = true;
			}
			catch (Exception $ex)
			{
			}

			if ($this->succeeded)
			{
				$this->redirect('reservation/index?roomId='.$this->room->getId());
			}
		}

		$this->setTemplate('sendDeleteMessage');
	}
	
	public function executeDelete(sfWebRequest $request)
	{
		$request->checkCSRFProtection();

		$this->forward404Unless($reservation = ReservationPeer::retrieveByPk($request->getParameter('id')), sprintf('Object reservation does not exist (%s).', $request->getParameter('id')));

		$this->room = $reservation->getRoomprofile()->getRoom();

		if (!$this->isAdmin() || !$reservation->isEditable())
		{
			if (!$this->getUser()->canDeleteReservation($reservation))
			{
				$this->getUser()->setFlash('deleteError', true);
				$this->redirect('reservation/index?roomId='.$this->room->getId());
			}
		}

		$this->user = $reservation->getUser();
		$user = $this->getUser()->getTemposUser();

		if ($reservation->countReservationsRelatedByReservationparentId() > 0) {
			
			$parent_reservation = $reservation->getReservationRelatedByReservationparentId();
			$daughter_reservation = $reservation->getReservationsRelatedByReservationparentId();
			if ($parent_reservation != null) {
				foreach ($daughter_reservation as $res) 
				{
					$res->setReservationparentId($parent_reservation->getId());
					$res->save();
				}
			} else {
				foreach ($daughter_reservation as $res) 
				{
					$res->setReservationparentId(null);
					$res->save();
				}
			}
		}
		$reservation->delete();
		
		if (!is_null($this->user) && (is_null($user) || ($user->getId() != $this->user->getId())))
		{
			$this->redirect('reservation/sendDeleteMessage?userId='.$this->user->getId().'&roomId='.$this->room->getId());
		} else
		{
			$this->redirect('reservation/index?roomId='.$this->room->getId());
		}
	}
	
	public function executeView(sfWebRequest $request)
	{
		$this->forward404Unless($this->reservation = ReservationPeer::retrieveByPk($request->getParameter('id')), sprintf('Object reservation does not exist (%s).', $request->getParameter('id')));
		$this->room = $this->reservation->getRoomProfile()->getRoom();
		$this->activity = $this->reservation->getActivity();
		
		if (!$this->isAdmin())
		{
			$this->forward404Unless($this->getUser()->canEditReservation($this->reservation), sprintf('Insufficent permissions to edit reservation (%s).', $this->reservation->getId()));
		}
		
		$this->form = new ReservationForm();
		
		if ($this->reservation->isPast()) {
			$this->form->setDefaultRoom($this->room);
			$this->form->setDefaultActivity($this->activity);
			$this->form->setDefaultCustomUsers(null, $this->getCurrentUser(), $this->activity->getId());
			$this->setFormDefaultOwner();
		} else {
			$this->getUser()->setFlash('viewError', 'Impossible de voir cette réservation');
			$this->redirect('reservation/index?roomId='.$this->room->getId());
		}
	}

	protected function processForm(sfWebRequest $request, sfForm $form)
	{
		$form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
		if ($form->isValid())
		{
			$reservation = $form->save();
			
			$this->redirect('reservation/index?roomId='.$reservation->getRoomprofile()->getRoomId());
		}
	}

	// Appelé par executeProcessRepeat
	protected function processRepeatForm(sfWebRequest $request, sfForm $form)
	{
		$form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
		$reservation = $this->form->getObject();
		$this->reservation_id = $reservation->getId();
		
		if ($form->isValid())
		{
			$form->process();
			
			$this->forms = $form->getReservationForms();
			//
			$this->getUser()->setAttribute('form_tmp', $form);
			// --------------------
			$this->setTemplate('repeat');
		}
	}
	
	public function executeRepeatResult(sfWebRequest $request)
	{
		// print 'executeRepeatList<br />';
		$this->forward404Unless($request->isMethod('post'));
		$this->forward404Unless($this->reservation = ReservationPeer::retrieveByPk($request->getParameter('id')), sprintf('Object reservation does not exist (%s).', $request->getParameter('id')));
		$this->room = $this->reservation->getRoomprofile()->getRoom();
		
		$next_id = $this->reservation->getId();
		$result = true;
		
		//
		$formRepeat = $this->getUser()->getAttribute('form_tmp');
		// --------------------
		
		$formRepeat->bind($request->getParameter($formRepeat->getName()), $request->getFiles($formRepeat->getName()));
		
		$this->forms = $formRepeat->getReservationForms();
		
		$checked_list = $request->getPostParameters();
		
		// var_dump($checked_list);
		// var_dump($forms);
		
		$ids = array();
		
		foreach ($checked_list as $id)
		{
			array_push($ids, $id);
		}
		
		// var_dump($ids);
		
		if (empty($ids) || is_null($ids))
		{
			$this->setTemplate('processRepeat');
		}
		
		$j = 0;
		
		$this->formsResult = array();
		
		foreach ($this->forms as $form)
		//while ($j < count($this->forms))
		{
			// print '============><br />'.$j.' '.count($this->forms).'<br />';
			$i=0;
			$checked = false;
			
			while (!$checked && $i < count($ids))
			{
				// print $i.' : '.$ids[$i].'<br />';
				// if($this->forms[$j]->getObject()->getId() == $ids[$i])
				if ($form->getObject()->getId() == $ids[$i])
				{
					// print 'FORM '.$this->forms[$j]->getObject()->getId().' is checked<br />';
					$checked = true;
				}
				$i++;
			}
			
			if ($checked)
			{
				// $reservation = $this->forms[$j]->getObject()->copy();
				$reservation = $form->getObject()->copy();
				$reservation->setReservationparentId($next_id);
				
				$resDate = $reservation->getDate();
				
				// $this->forms[$j] = new ReservationForm($reservation);
				// $this->forms[$j]->bindObject($reservation);
				$form = new ReservationForm($reservation);
				$form->bindObject($reservation);
				
				// if ($this->forms[$j]->isValid())
				if ($form->isValid())
				{
					// print 'IS VALID<br />';
					
					// var_dump($this->forms[$j]->getObject());
					// var_dump($form->getObject());
					
					// $reservation = $this->forms[$j]->save();
					$reservation = $form->save();
					
					$next_id = $reservation->getId();
					
					foreach ($this->reservation->getReservationOtherMemberss() as $value)
					{
						$other_members = new ReservationOtherMembers();
						$other_members->setReservationId($next_id);
						$other_members->setUserId($value->getUserId());
						$other_members->save();
						// print('Reservation : '.$value.'<br/>Id user : '.$value->getUserId().'<br/>');
					}
				} else
				{
					// print 'NOT VALID<br />';
					$result = false;
				}
				
				if ($checked)
				{
					$form->getObject()->setDate($resDate);
				}
				
				$this->formsResult[] = $form;
				
				// print $this->forms[$j]->renderErrors();
				// print $form->renderErrors();
			}
			
			$j++;
		}
		
		if ($result)
		{
			$this->redirect('reservation/index?roomId='.$this->reservation->getRoomprofile()->getRoomId());
		}
	}
	
	/*
		
	protected function processRepeatForm(sfWebRequest $request, sfForm $form)
	{
		$form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
		$reservation = $this->form->getObject();
		if ($form->isValid())
		{
			if ($form->process())
			{
				$this->redirect('reservation/index?roomId='.$form->getObject()->getRoomprofile()->getRoomId());
			} else
			{
				$this->forms = $form->getReservationForms();
				$this->setTemplate('repeatResult');
			}
		}
	}
	
	public function process()
	{
		$this->forms = array();

		$repeat_type = $this->getValue('repeat_type');
		$stop_on_error = $this->getValue('stop_on_error');
		$count = $this->getValue('count');
		$until_date = strtotime($this->getValue('until_date'));
		$day_period = $this->getValue('day_period');
		$week_period = $this->getValue('week_period');
		$month_period = $this->getValue('month_period');
		$year_period = $this->getValue('year_period');

		$base_date = $this->object->getDate();

		$result = true;
		$i = 0;
		$next_id = $this->object->getId();

		do
		{
			++$i;

			$day = $day_period * $i;
			$week = $week_period * $i;
			$month = $month_period * $i;
			$year = $year_period * $i;

			$testDate = strtotime("$base_date + $day day + $week week + $month month + $year year");
			$testDatePrec = strtotime("$base_date + $day day + $week week + ".($month - 1)." month + $year year");
			
			if (date('m', $testDate) > date('m', $testDatePrec) + 1)
			{
				$date = strtotime("$base_date + ???? day + $week week");
			}
			
			$date = strtotime("$base_date + $day day + $week week + $month month + $year year");

			if ($repeat_type == self::COUNT)
			{
				if ($i > $count)
				{
					break;
				}
			} elseif ($repeat_type == self::DATE)
			{
				if ($date > $until_date)
				{
					break;
				}
			} else
			{
				if (($i > $count) || ($date > $until_date))
				{
					break;
				}
			}

			$reservation = $this->object->copy();
			$reservation->setDate($date);
			$reservation->setReservationparentId($next_id);

			$form = new ReservationForm($reservation);

			$form->bindObject($reservation);

			if ($form->isValid())
			{
				$reservation = $form->save();

				$next_id = $reservation->getId();

				foreach($this->object->getReservationOtherMemberss() as $value) {
					$other_members = new ReservationOtherMembers();
					$other_members->setReservationId($reservation->getId());
					$other_members->setUserId($value->getUserId());

					$other_members->save();
					//print('Reservation : '.$value.'<br/>Id user : '.$value->getUserId().'<br/>');
				}
			} else
			{
				$result = false;

				if ($stop_on_error)
				{
					$this->forms[] = $form;
					break;
				}
			}

			$this->forms[] = $form;
		} while (true);

		return $result;
	}
	
	*/
}
