<?php

/**
 * message actions.
 *
 * @package    tempos
 * @subpackage message
 * @author     ISLOG
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class messageActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
		$this->user = $this->getUser()->getTemposUser();
		$this->forward404Unless($this->user, 'Only users can access this page.');

		$this->step = sfConfig::get('app_max_messages_on_messagelist');

		$this->getUser()->syncParameters($this, 'message', 'index', array('offset', 'limit', 'sort_column', 'sort_direction'), $request);

		if (is_null($this->sort_column))
		{
			$this->sort_column = 'created_at';
			$this->sort_direction = 'down';
		}

		if (is_null($this->offset))
		{
			$this->offset = 0;
		}

		if (is_null($this->limit) || ($this->limit <= 0))
		{
			$this->limit = $this->step;
		}

		$c = MessagePeer::getUserCriteria($this->user->getId());
		$cb = clone $c;

		SortCriteria::addSortCriteria($c, $this->sort_column, MessagePeer::getSortAliases(), $this->sort_direction);

		$c->setOffset($this->offset);

		if ($this->limit >= 0)
		{
			$c->setLimit($this->limit);
		}

		$this->message_list = MessagePeer::doSelect($c);
		$this->count = MessagePeer::doCount($cb);
		$this->unread_count = MessagePeer::doCountUserUnreadMessages($this->user->getId());

		if (($this->offset < 0) || (($this->offset >= $this->count) && ($this->count > 0)))
		{
			$this->forward404('Invalid offset/count values.');
		}
	}

	public function executeView(sfWebRequest $request)
	{
		$this->user = $this->getUser()->getTemposUser();
		$this->forward404Unless($this->user, 'Only users can access this page.');

		$this->forward404Unless($this->message = MessagePeer::retrieveByPk($request->getParameter('id')), sprintf('Object message does not exist (%s).', $request->getParameter('id')));
		$this->forward404Unless($this->message->isOwned($this->user->getId()), 'You cannot see other people messages.');

		$this->message->processRead();
	}

  public function executeReply(sfWebRequest $request)
  {
		$this->user = $this->getUser()->getTemposUser();
		$this->forward404Unless($this->user, 'Only users can access this page.');

		$this->forward404Unless($this->message = MessagePeer::retrieveByPk($request->getParameter('messageId')), sprintf('Object message does not exist (%s).', $request->getParameter('messageId')));
		$this->forward404Unless($this->message->isOwned($this->user->getId()), 'You cannot see other people messages.');
		$this->forward404Unless($this->message->getSenderUser(), 'No user information: can\'t reply.');
		$this->forward404Unless($this->message->getSenderId() !== $this->message->getOwnerId(), 'You can\'t reply to a message you sent.');

		$this->form = new MessageForm();
		$this->form->setRecipient($this->message->getSenderUser());
		$this->form->setSender($this->user);
		$this->form->setSubject(sprintf("Re: %s", $this->message->getSubject()));
		$this->form->setText(sprintf("\n\n\n--------------------\n\n%s", $this->message->getText()));
  }

  public function executeProcessReply(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));

	$this->executeReply($request);

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
			throw $ex;
		}

		if ($this->succeeded)
		{
			$this->redirect('message/index');
		}
	}
	
	$this->setTemplate('reply');
  }

  public function executeDelete(sfWebRequest $request)
  {
	$this->user = $this->getUser()->getTemposUser();
	$this->forward404Unless($this->user, 'Only users can access this page.');

    $request->checkCSRFProtection();

    $this->forward404Unless($message = MessagePeer::retrieveByPk($request->getParameter('id')), sprintf('Object message does not exist (%s).', $request->getParameter('id')));
		$this->forward404Unless($message->isOwned($this->user->getId()), 'You cannot delete other people messages.');

    $message->delete();

    $this->redirect('message/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $message = $form->save();

      $this->redirect('message/index');
    }
  }
}
