<?php

/**
 * zone actions.
 *
 * @package    tempos
 * @subpackage zone
 * @author     ISLOG
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class zoneActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->zone_list = ZonePeer::doSelectRoot();
		$this->zone_total_count = ZonePeer::doCount(new Criteria());
		$this->recursion = ZonePeer::getMaximumRecursion();
  }

  public function executeNew(sfWebRequest $request)
  {
		$parentId = $request->getParameter('parentId');

    $this->form = new ZoneForm();
		$this->form->setDefaultParentZone($parentId);
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));

    $this->form = new ZoneForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($zone = ZonePeer::retrieveByPk($request->getParameter('id')), sprintf('Object zone does not exist (%s).', $request->getParameter('id')));
    $this->form = new ZoneForm($zone);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));
    $this->forward404Unless($zone = ZonePeer::retrieveByPk($request->getParameter('id')), sprintf('Object zone does not exist (%s).', $request->getParameter('id')));
    $this->form = new ZoneForm($zone);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

	public function executeMoveUp(sfWebRequest $request)
	{
    $request->checkCSRFProtection();

    $this->forward404Unless($zone = ZonePeer::retrieveByPk($request->getParameter('id')), sprintf('Object zone does not exist (%s).', $request->getParameter('id')));
		$this->forward404Unless($zone->isOwned());

    $zone->moveUp();
		$zone->save();

    $this->redirect('zone/index');
	}

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($zone = ZonePeer::retrieveByPk($request->getParameter('id')), sprintf('Object zone does not exist (%s).', $request->getParameter('id')));
    $zone->delete();

    $this->redirect('zone/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $zone = $form->save();

      $this->redirect('zone/index');
    }
  }
}
