<?php

/**
 * reservationdelete actions.
 *
 * @package    tempos
 * @subpackage reservationdelete
 * @author     ISLOG
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class reservationdeleteActions extends sfActions
{
	public function executeIndex(sfWebRequest $request)
	{
		$this->form = new ReservationDeleteForm(array(), array(), false);
		$formName = $this->form->getName();

		$this->formConfirm = new ReservationDeleteConfirmForm(array(), array(), false);
		$nameConfirm = $this->formConfirm->getName();

		$this->getUser()->syncParameters($this, 'reservationDelete', 'index', array('offset', 'limit', $formName, 'sort_column', 'sort_direction'), $request);

		$this->step = sfConfig::get('app_max_reservations_on_reporting');

		$search = $request->getPostParameter($nameConfirm);
		$deletion_choice = $search['deletion_choices'];
		
		$this->displayInfo = true;

		if ($request->getParameter('id') != null)
		{
			$this->forward404Unless($reservation = ReservationPeer::retrieveByPk($request->getParameter('id')), sprintf('Object reservation does not exist (%s).', $request->getParameter('id')));
			$this->reservationId = $request->getParameter('id');

			$this->formConfirm = new ReservationDeleteConfirmForm(array(), array(), false);
			$nameConfirm = $this->formConfirm->getName();

			$search = $request->getPostParameter($nameConfirm);
        	$deletion_choice = $search['deletion_choices'];
		} else
		{
			$this->reservationId = null;
		}

		if (is_null($this->sort_column))
		{
			$this->sort_column = 'date';
			$this->sort_direction = 'up';
		}

		if (is_null($this->offset))
		{
			$this->offset = 0;
		}

		if (is_null($this->limit) || ($this->limit <= 0))
		{
			$this->limit = $this->step;
		}

		$c = new Criteria();

		SortCriteria::addSortCriteria($c, $this->sort_column, ReservationPeer::getSortAliases(), $this->sort_direction);

		if ($request->getParameter('id') != null) {
			$this->forward404Unless($reservation = ReservationPeer::retrieveByPk($request->getParameter('id')), sprintf('Object reservation does not exist (%s).', $request->getParameter('id')));

			switch ($deletion_choice) {
				case 'this':
					$room_id = $reservation->getRoomProfile()->getRoomId();

					if (!$this->isAdmin() || !$reservation->isEditable())
					{
						if (!$this->getUser()->canDeleteReservation($reservation))
						{
							$this->getUser()->setFlash('deleteError', true);
							$this->redirect('reservation/index?roomId='.$room_id);
						}
					}

					$this->deleteAReservation($reservation);
					$this->redirect('reservation/index?roomId='.$room_id);
				break;

				case 'next';
					$this->reservation_list = ReservationPeer::reportRepeatNext($reservation);
				break;
				
				case 'previous';
					$this->reservation_list = ReservationPeer::reportRepeatPrevious($reservation);
				break;
				
				case 'all';
					$this->reservation_list = ReservationPeer::reportRepeatAll($reservation);
				break;
			}
		}

		$this->getUser()->syncParameters($this, 'reservationDelete', 'index', array('offset', 'limit', $formName, 'sort_column', 'sort_direction'), $request);

		$this->filtered = false;

		if (!is_null($this->$formName) && is_null($request->getParameter('id')))
		{
			$this->filtered = true;

			$this->form->bind($this->$formName, $request->getFiles($formName));
			if ($this->form->isValid())
			{
				$this->reservation_list = ReservationPeer::reportTime(
					$this->form->getValue('activity'),
					$this->form->getValue('rooms'),
					strtotime($this->form->getValue('start_date')),
					strtotime($this->form->getValue('end_date')),
					strtotime($this->form->getValue('start_hour')),
					strtotime($this->form->getValue('end_hour')),
					$this->form->getValue('periodicity'),
					$this->form->getValue('number'),
					$c
				);

				//
				$this->getUser()->setAttribute('stop_on_error_tmp', $this->form->getValue('stop_on_error'));
				// -----------------------------------

				$this->count = count($this->reservation_list);
				$this->reservation_list = array_slice($this->reservation_list, $this->offset, $this->limit);
			} else
			{
				$this->count = 0;
				$this->reservation_list = array();
			}
		} else
		{
			if (!isset($this->reservation_list))
			{
				$this->count = 0;
				$this->reservation_list = array();
			}
			
			$this->displayInfo = false;
		}

		//
		$this->getUser()->setAttribute('list_delete_tmp', $this->reservation_list);
		// -----------------------------------

		if (($this->offset < 0) || (($this->offset >= $this->count) && ($this->count > 0)))
		{
			$this->forward404('Invalid offset/count values.');
		}
	}

	public function executeDelete(sfWebRequest $request)
	{
		$this->linkRoom = true;
		
		if (is_null($this->getUser()->getAttribute('activityId')))
		{
			$this->linkRoom = false;
		}

		//
		$this->reservation_list = $this->getUser()->getAttribute('list_delete_tmp');
		$this->stop_on_error = $this->getUser()->getAttribute('stop_on_error_tmp');
		// -----------------------------------

		if (is_null($this->reservation_list))
		{
			$this->reservation_list = array();
		}

		$this->reservation_delete_list = array();
		$this->reservation_delete_fail = array();
		
		$sortie = false;
		
		$ids = array();
		foreach ($request->getPostParameters() as $id)
		{
			array_push($ids, $id);
		}
		
		if (empty($ids) || is_null($ids))
		{
			$this->redirect('reservationdelete/index');
		}
		
		$chain_list = array();
		
		foreach ($this->reservation_list as $key => $reservation)
		{
			$i=0;
			$checked = false;
			
			while (!$checked && $i < count($ids))
			{
				if($reservation->getId() == $ids[$i])
				{
					$checked = true;
				}
				$i++;
			}
			
			// Si la réservation est cochée
			if ($checked)
			{
				if (!$sortie)
				{
					$this->room_id = $reservation->getRoomProfile()->getRoomId();
					$candelete = true;
					
					if (!$this->isAdmin() || !$reservation->isEditable())
					{
						if (!$this->getUser()->canDeleteReservation($reservation))
						{
							array_push($this->reservation_delete_fail, $reservation);
							if ($this->stop_on_error)
							{
								$this->getUser()->setFlash('deleteError', true);
								$candelete = false;
								$sortie = true;
							} else
							{
								$candelete = false;
							}
						}
					}
					
					if ($reservation->hasDaughters() && $candelete)
					{
						$parent_reservation = $reservation->getReservationparentId();
						$daughter_reservation = $reservation->getReservationsRelatedByReservationparentId();
						
						if (!is_null($parent_reservation)) {
							$daughter_reservation[0]->setReservationparentId($parent_reservation);
							$daughter_reservation[0]->save();
							
							foreach ($this->reservation_list as $k => $r)
							{
								if ($r->getId() == $daughter_reservation[0]->getId())
								{
									$this->reservation_list[$k]->setReservationparentId($parent_reservation);
									break;
								}
							}
							
						} else {
							$daughter_reservation[0]->setReservationparentId(null);
							$daughter_reservation[0]->save();
							
							foreach ($this->reservation_list as $k => $r)
							{
								if ($r->getId() == $daughter_reservation[0]->getId())
								{
									$this->reservation_list[$k]->setReservationparentId(null);
									break;
								}
							}
						}
					}
					
					if ($candelete)
					{
						array_push($this->reservation_delete_list, $reservation);
					}

					$this->count_delete = count($this->reservation_delete_list);
					$this->count_fail = count($this->reservation_delete_fail);
					/* if (is_null($request->getParameter('repeat')))
					{
						$this->redirect('reservationdelete/index?clear=');
					} else {
						$this->redirect('reservation/index?roomId='.$this->room_id);
					} */
				}
			}
		}
		
		foreach ($this->reservation_delete_list as $reservation)
		{
			$reservation->delete();
		}
	}

	public function executeConfirm(sfWebRequest $request)
	{
		$this->forward404Unless($reservation = ReservationPeer::retrieveByPk($request->getParameter('id')), sprintf('Object reservation does not exist (%s).', $request->getParameter('id')));

		$this->reservation = $reservation;

		$this->form = new ReservationDeleteConfirmForm(array(), array(), false);
	}

	public function deleteAReservation($aReservation)
	{
		if ($aReservation->countReservationsRelatedByReservationparentId() > 0)
		{
			$parent_aReservation = $aReservation->getReservationRelatedByReservationparentId();
			$daughter_aReservation = $aReservation->getReservationsRelatedByReservationparentId();
			if ($parent_aReservation != null) {
				foreach ($daughter_aReservation as $res)
				{
					$res->setReservationparentId($parent_aReservation->getId());
					$res->save();
				}
			} else {
				foreach ($daughter_aReservation as $res)
				{
					$res->setReservationparentId(null);
					$res->save();
				}
			}
		}
		$aReservation->delete();
	}

	protected function isAdmin()
	{
		return $this->getUser()->hasCredential('admin', false);
	}
}
