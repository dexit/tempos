<?php

/**
 * roomprofile actions.
 *
 * @package    tempos
 * @subpackage roomprofile
 * @author     ISLOG
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class roomprofileActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward404Unless($this->room = RoomPeer::retrieveByPk($request->getParameter('roomId')), sprintf('Object room does not exist (%s).', $request->getParameter('roomId')));
    $this->roomprofile_list = RoomprofilePeer::doSelectFromRoom($this->room->getId());
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->forward404Unless($this->room = RoomPeer::retrieveByPk($request->getParameter('roomId')), sprintf('Object room does not exist (%s).', $request->getParameter('roomId')));
    $this->form = new RoomprofileForm();
	$this->form->setDefaultRoom($this->room);
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    $this->forward404Unless($this->room = RoomPeer::retrieveByPk($request->getParameter('roomId')), sprintf('Object room does not exist (%s).', $request->getParameter('roomId')));

    $this->form = new RoomprofileForm();

    $this->processForm($request, $this->form);

	$this->form->setDefaultRoom($this->room);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($roomprofile = RoomprofilePeer::retrieveByPk($request->getParameter('id')), sprintf('Object roomprofile does not exist (%s).', $request->getParameter('id')));
	$this->room = $roomprofile->getRoom();
    $this->form = new RoomprofileForm($roomprofile);
	$this->form->setDefaultRoom($this->room);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));
    $this->forward404Unless($roomprofile = RoomprofilePeer::retrieveByPk($request->getParameter('id')), sprintf('Object roomprofile does not exist (%s).', $request->getParameter('id')));
    $this->form = new RoomprofileForm($roomprofile);
	$this->room = $roomprofile->getRoom();

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($roomprofile = RoomprofilePeer::retrieveByPk($request->getParameter('id')), sprintf('Object roomprofile does not exist (%s).', $request->getParameter('id')));
	$roomId = $roomprofile->getRoomId();
    $roomprofile->delete();

    $this->redirect('roomprofile/index?roomId='.$roomId);
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
	
    if ($form->isValid())
    {
	  $nb = $form->getValue('physical_access_controller');
	  	  
	  // Récupère le contrôle d'accès physique sélectionné (grâce à sa position dans le ConfigurationHelper)
	  $pac = ConfigurationHelper::getParameter(null, 'physical_access_controller'.($nb+1));
	  $pac .= ($nb+1);
	  
	  $controller = ConfigurationHelper::getParameter($pac, 'controller_name');
	  
	  $name = RoomprofilePeer::buildName($controller, $form->getvalue('name'));
  
	  $roomprofile = $form->save();
	  $roomprofile->setName($name);
	  $roomprofile->save();
	
	  $this->redirect('roomprofile/index?roomId='.$roomprofile->getRoomId());
    }
	
    $this->setTemplate('edit');
  }
}
