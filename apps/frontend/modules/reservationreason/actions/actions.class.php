<?php

/**
 * reservationreason actions.
 *
 * @package    tempos
 * @subpackage reservationreason
 * @author     ISLOG
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class reservationreasonActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward404Unless($this->activity = ActivityPeer::retrieveByPk($request->getParameter('activityId')), sprintf('Object activity does not exist (%s).', $request->getParameter('activityId')));
    $this->reservationreason_list = ReservationreasonPeer::doSelectFromActivity($this->activity->getId());

		if (count($this->reservationreason_list) <= 0)
		{
			$this->redirect('activity/index');
		}
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->forward404Unless($this->activity = ActivityPeer::retrieveByPk($request->getParameter('activityId')), sprintf('Object activity does not exist (%s).', $request->getParameter('activityId')));
    $this->form = new ReservationreasonForm();
		$this->form->setDefaultActivity($this->activity);
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    $this->forward404Unless($this->activity = ActivityPeer::retrieveByPk($request->getParameter('activityId')), sprintf('Object activity does not exist (%s).', $request->getParameter('activityId')));

    $this->form = new ReservationreasonForm();

    $this->processForm($request, $this->form);

		$this->form->setDefaultActivity($this->activity);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($reservationreason = ReservationreasonPeer::retrieveByPk($request->getParameter('id')), sprintf('Object reservationreason does not exist (%s).', $request->getParameter('id')));
		$this->activity = $reservationreason->getActivity();
    $this->form = new ReservationreasonForm($reservationreason);
		$this->form->setDefaultActivity($this->activity);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));
    $this->forward404Unless($reservationreason = ReservationreasonPeer::retrieveByPk($request->getParameter('id')), sprintf('Object reservationreason does not exist (%s).', $request->getParameter('id')));
    $this->form = new ReservationreasonForm($reservationreason);
		$this->activity = $reservationreason->getActivity();

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($reservationreason = ReservationreasonPeer::retrieveByPk($request->getParameter('id')), sprintf('Object reservationreason does not exist (%s).', $request->getParameter('id')));
		$activityId = $reservationreason->getActivityId();
    $reservationreason->delete();

    $this->redirect('reservationreason/index?activityId='.$activityId);
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $reservationreason = $form->save();

      $this->redirect('reservationreason/index?activityId='.$reservationreason->getActivityId());
    }
  }
}
