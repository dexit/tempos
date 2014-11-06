<?php

/**
 * card actions.
 *
 * @package    tempos
 * @subpackage card
 * @author     ISLOG
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class cardActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
		$this->step = sfConfig::get('app_max_cards_on_cardlist');

		$this->getUser()->syncParameters($this, 'card', 'index', array('offset', 'limit', 'numberPattern', 'sort_column', 'sort_direction'), $request);

		if (is_null($this->sort_column))
		{
			$this->sort_column = 'card_number';
			$this->sort_direction = 'up';
		}

		$this->searchForm = new CardSearchForm();

		$c = CardPeer::getSortByNumberAscCriteria();
		SortCriteria::addSortCriteria($c, $this->sort_column, CardPeer::getSortAliases(), $this->sort_direction);

		if (is_null($this->offset))
		{
			$this->offset = 0;
		}

		if (is_null($this->limit) || ($this->limit <= 0))
		{
			$this->limit = $this->step;
		}

		if (!empty($this->numberPattern))
		{
			$tmpc = CardPeer::getSearchNumberPatternCriteria($this->numberPattern);
			$this->searchCount = CardPeer::getCardsCount($tmpc);
			$c = CardPeer::getSearchNumberPatternCriteria($this->numberPattern, $c);
		}

    $this->card_list = CardPeer::doSelectCards($this->offset, $this->limit, $c);
		$this->count = CardPeer::getCardsCount();

		if (($this->offset < 0) || (($this->offset >= $this->count) && ($this->count > 0)))
		{
			$this->forward404();
		}
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new CardForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));

    $this->form = new CardForm();

		$this->getUser()->setFlash('created', true);
    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

	public function executeActivate(sfWebRequest $request)
	{
    $this->forward404Unless($card = CardPeer::retrieveByPk($request->getParameter('id')), sprintf('Object card does not exist (%s).', $request->getParameter('id')));

		$card->setIsActive(true);
		$card->save();

		$this->redirect('card/index');
	}

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($card = CardPeer::retrieveByPk($request->getParameter('id')), sprintf('Object card does not exist (%s).', $request->getParameter('id')));
    $this->form = new CardForm($card);

		$this->updated = $this->getUser()->getFlash('updated', false);
		$this->created = $this->getUser()->getFlash('created', false);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));
    $this->forward404Unless($card = CardPeer::retrieveByPk($request->getParameter('id')), sprintf('Object card does not exist (%s).', $request->getParameter('id')));
    $this->form = new CardForm($card);

		$this->getUser()->setFlash('updated', true);
    $this->processForm($request, $this->form, false);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($card = CardPeer::retrieveByPk($request->getParameter('id')), sprintf('Object card does not exist (%s).', $request->getParameter('id')));
    $card->delete();

    $this->redirect('card/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form, $new = true)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $card = $form->save();

			if ($new)
			{
      	$this->redirect('card/edit?id='.$card->getId());
			} else
			{
      	$this->redirect('card/index');
			}
    }
  }
}
