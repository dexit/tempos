<?php

/**
 * closeperiod actions.
 *
 * @package    tempos
 * @subpackage closeperiod
 * @author     ISLOG
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class closeperiodActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward404Unless($this->room = RoomPeer::retrieveByPk($request->getParameter('roomId')), sprintf('Object room does not exist (%s).', $request->getParameter('roomId')));

		$this->step = sfConfig::get('app_max_closeperiods_on_closeperiodlist');

		$this->getUser()->syncParameters($this, 'closeperiod', 'index', array('offset', 'limit', 'sort_column', 'sort_direction'), $request);

		if (is_null($this->sort_column))
		{
			$this->sort_column = 'start';
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

		SortCriteria::addSortCriteria($c, $this->sort_column, CloseperiodPeer::getSortAliases(), $this->sort_direction);

		$c->setOffset($this->offset);

		if ($this->limit >= 0)
		{
			$c->setLimit($this->limit);
		}

    $this->closeperiod_list = CloseperiodPeer::doSelectFromRoom($this->room->getId(), $c);
		$this->count = CloseperiodPeer::doCount(CloseperiodPeer::getFromRoomCriteria($this->room->getId()));

		if (($this->offset < 0) || (($this->offset >= $this->count) && ($this->count > 0)))
		{
			$this->forward404();
		}
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->forward404Unless($this->room = RoomPeer::retrieveByPk($request->getParameter('roomId')), sprintf('Object room does not exist (%s).', $request->getParameter('roomId')));
    $this->form = new CloseperiodForm();
		$this->form->setDefaultRoom($this->room);
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    $this->forward404Unless($this->room = RoomPeer::retrieveByPk($request->getParameter('roomId')), sprintf('Object room does not exist (%s).', $request->getParameter('roomId')));

    $this->form = new CloseperiodForm();

    $this->processForm($request, $this->form);

		$this->form->setDefaultRoom($this->room);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($closeperiod = CloseperiodPeer::retrieveByPk($request->getParameter('id')), sprintf('Object closeperiod does not exist (%s).', $request->getParameter('id')));
		$this->room = $closeperiod->getRoom();
    $this->form = new CloseperiodForm($closeperiod);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));
    $this->forward404Unless($closeperiod = CloseperiodPeer::retrieveByPk($request->getParameter('id')), sprintf('Object closeperiod does not exist (%s).', $request->getParameter('id')));
		$this->room = $closeperiod->getRoom();
    $this->form = new CloseperiodForm($closeperiod);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($closeperiod = CloseperiodPeer::retrieveByPk($request->getParameter('id')), sprintf('Object closeperiod does not exist (%s).', $request->getParameter('id')));
		$roomId = $closeperiod->getRoomId();
    $closeperiod->delete();

    $this->redirect('closeperiod/index?roomId='.$roomId);
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $closeperiod = $form->save();

			$this->redirect('closeperiod/index?roomId='.$closeperiod->getRoomId());
    }
  }
}
