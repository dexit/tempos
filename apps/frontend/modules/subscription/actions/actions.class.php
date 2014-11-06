<?php

/**
* subscription actions.
*
* @package    tempos
* @subpackage subscription
* @author     ISLOG
* @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
*/
class subscriptionActions extends sfActions
{
	public function executeIndex(sfWebRequest $request)
	{
		if ($request->hasParameter('userId'))
		{
			$this->forward404Unless($this->user = UserPeer::retrieveByPk($request->getParameter('userId')), sprintf('Object user does not exist (%s).', $request->getParameter('userId')));
			$this->subscription_list = $this->user->getSubscriptions();
		}
		elseif ($request->hasParameter('cardId'))
		{
			$this->forward404Unless($this->card = CardPeer::retrieveByPk($request->getParameter('cardId')), sprintf('Object card does not exist (%s).', $request->getParameter('cardId')));
			$this->subscription_list = $this->card->getSubscriptions();
		} else
		{
			$this->forward404('No user or card specified.');
		}
	}

	public function executeNew(sfWebRequest $request)
	{
		if ($request->hasParameter('userId'))
		{
			$this->forward404Unless($this->user = UserPeer::retrieveByPk($request->getParameter('userId')), sprintf('Object user does not exist (%s).', $request->getParameter('userId')));
			$this->form = new SubscriptionForm();
			$this->form->setDefaultUser($this->user);
		}
		elseif ($request->hasParameter('cardId'))
		{
			$this->forward404Unless($this->card = CardPeer::retrieveByPk($request->getParameter('cardId')), sprintf('Object card does not exist (%s).', $request->getParameter('cardId')));
			$this->form = new SubscriptionForm();
			$this->form->setDefaultCard($this->card);
		} else
		{
			$this->forward404('No user or card specified.');
		}
	}

	public function executeCreate(sfWebRequest $request)
	{
		$this->forward404Unless($request->isMethod('post'));

		if ($request->hasParameter('userId'))
		{
			$this->forward404Unless($this->user = UserPeer::retrieveByPk($request->getParameter('userId')), sprintf('Object user does not exist (%s).', $request->getParameter('userId')));

			$this->form = new SubscriptionForm();

			$this->processForm($request, $this->form);

			$this->form->setDefaultUser($this->user);
		}
		elseif ($request->hasParameter('cardId'))
		{
			$this->forward404Unless($this->card = CardPeer::retrieveByPk($request->getParameter('cardId')), sprintf('Object card does not exist (%s).', $request->getParameter('cardId')));

			$this->form = new SubscriptionForm();

			$this->processForm($request, $this->form);

			$this->form->setDefaultCard($this->card);
		} else
		{
			$this->forward404('No user or card specified.');
		}

		$this->setTemplate('new');
	}

	public function executeUsergroupNew(sfWebRequest $request)
	{
		$this->forward404Unless($this->usergroup = UsergroupPeer::retrieveByPk($request->getParameter('usergroupId')), sprintf('Object usergroup does not exist (%s).', $request->getParameter('usergroupId')));

		$this->form = new UsergroupSubscriptionForm();
		$this->form->setDefaultUsergroup($this->usergroup);
	}

	public function executeUsergroupCreate(sfWebRequest $request)
	{
		$this->forward404Unless($request->isMethod('post'));

		if ($request->hasParameter('usergroupId'))
		{
			$this->forward404Unless($this->usergroup = UsergroupPeer::retrieveByPk($request->getParameter('usergroupId')), sprintf('Object usergroup does not exist (%s).', $request->getParameter('usergroupId')));

			$this->form = new UsergroupSubscriptionForm();

			$this->form->setDefaultUsergroup($this->usergroup);
			
			$this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));

			if ($this->form->isValid())
			{
				$this->form->save();

				$this->redirect('usergroup/index');
			}
		} else
		{
			$this->forward404('No usergroup specified.');
		}

		$this->setTemplate('usergroupNew');
	}

	public function executeActivate(sfWebRequest $request)
	{
		$this->forward404Unless($subscription = SubscriptionPeer::retrieveByPk($request->getParameter('id')), sprintf('Object subscription does not exist (%s).', $request->getParameter('id')));

		$subscription->setIsActive(true);
		$subscription->save();

		$this->redirectToIndex($subscription);
	}

	public function executeEdit(sfWebRequest $request)
	{
		$this->forward404Unless($subscription = SubscriptionPeer::retrieveByPk($request->getParameter('id')), sprintf('Object subscription does not exist (%s).', $request->getParameter('id')));

		if ($subscription->getUserId() != null)
		{
			$this->user = $subscription->getUser();
		}
		if ($subscription->getCardId() != null)
		{
			$this->card = $subscription->getCard();
		}

		$this->form = new SubscriptionForm($subscription);
	}

	public function executeUpdate(sfWebRequest $request)
	{
		$this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));
		$this->forward404Unless($subscription = SubscriptionPeer::retrieveByPk($request->getParameter('id')), sprintf('Object subscription does not exist (%s).', $request->getParameter('id')));

		if ($subscription->getUserId() != null)
		{
			$this->user = $subscription->getUser();
		}
		if ($subscription->getCardId() != null)
		{
			$this->card = $subscription->getCard();
		}

		$this->form = new SubscriptionForm($subscription);

		$this->processForm($request, $this->form);

		$this->setTemplate('edit');
	}

	public function executeDelete(sfWebRequest $request)
	{
		$request->checkCSRFProtection();

		$this->forward404Unless($subscription = SubscriptionPeer::retrieveByPk($request->getParameter('id')), sprintf('Object subscription does not exist (%s).', $request->getParameter('id')));

		if (!is_null($subscription->getUserId()))
		{
			$userId = $subscription->getUserId();
			$subscription->delete();

			$this->redirect('subscription/index?userId='.$userId);
		}
		elseif (!is_null($subscription->getCardId()))
		{
			$cardId = $subscription->getCardId();
			$subscription->delete();

			$this->redirect('subscription/index?cardId='.$cardId);
		} else
		{
			// Default goes to user list. Could be card list as well, it doesn't matter.
			$this->redirect('user/index');
		}
	}

	protected function processForm(sfWebRequest $request, sfForm $form)
	{
		$form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));

		if ($form->isValid())
		{
			$subscription = $form->save();

			$this->redirectToIndex($subscription);
		}
	}

	protected function redirectToIndex(Subscription $subscription)
	{
		if (!is_null($subscription->getUserId()))
		{
			$userId = $subscription->getUserId();
			$this->redirect('subscription/index?userId='.$userId);
		}
		elseif (!is_null($subscription->getCardId()))
		{
			$cardId = $subscription->getCardId();
			$this->redirect('subscription/index?cardId='.$cardId);
		} else
		{
			// Default goes to user list. Could be card list as well, it doesn't matter.
			$this->redirect('user/index');
		}
	}
}
