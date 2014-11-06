<?php

/**
* user actions.
*
* @package    tempos
* @subpackage user
* @author     ISLOG
* @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
*/
class userActions extends sfActions
{
	public function executeIndex(sfWebRequest $request)
	{
		$this->is_admin = $this->getUser()->hasCredential('admin', false);

		$this->form = new UserSearchForm();
		$formName = $this->form->getName();

		$this->step = sfConfig::get('app_max_users_on_userlist');

		$this->getUser()->syncParameters($this, 'user', 'index', array('offset', 'limit', $formName, 'sort_column', 'sort_direction'), $request);

		if (is_null($this->sort_column))
		{
			$this->sort_column = 'name';
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

		SortCriteria::addSortCriteria($c, $this->sort_column, UserPeer::getSortAliases(), $this->sort_direction);

		if (!is_null($this->$formName))
		{
			$this->filtered = true;
			$this->form->bind($this->$formName, $request->getFiles($formName));

			if ($this->form->isValid())
			{
				$this->user_list = UserPeer::searchUsers(
				$this->form->getValue('login'),
				$this->form->getValue('family_name'),
				$this->form->getValue('surname'),
				$this->form->getValue('usergroupsAsLeader'),
				$this->form->getValue('usergroupsAsMember'),
				$this->form->getValue('activities'),
				$this->form->getValue('is_active'),
				$this->form->getValue('card_number'),
				strtotime($this->form->getValue('begin_date')),
				strtotime($this->form->getValue('end_date')),
				$this->form->getValue('email_address'),
				$this->form->getValue('address'),
				$this->form->getValue('phone_number'),
				$c
				);

				$this->count = count($this->user_list);
				$this->user_list = array_slice($this->user_list, $this->offset, $this->limit);
			} else
			{
				$this->setTemplate('search');
			}
		} else
		{
			$c->setOffset($this->offset);

			if ($this->limit >= 0)
			{
				$c->setLimit($this->limit);
			}

			$this->filtered = false;
			$this->user_list = UserPeer::doSelectMembers($c);
			$this->count = UserPeer::doCountMembers(new Criteria());
		}

		if (($this->offset < 0) || (($this->offset >= $this->count) && ($this->count > 0)))
		{
			$this->forward404('Invalid offset/count values.');
		}
	}

	public function executeSearch(sfWebRequest $request)
	{
		$this->form = new UserSearchForm();
	}

	public function executeImport(sfWebRequest $request)
	{
		$this->form = new UserImportForm();
	}

	public function executePrepareImport(sfWebRequest $request)
	{
		$this->forward404Unless($this->getUser()->hasCredential('admin', false), 'Only admin can import users');

		$user = $this->getUser()->getTemposUser();

		$this->forward404Unless(!is_null($user), 'No user logged in');

		$this->form = new UserImportForm();

		$this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));

		if ($this->form->isValid())
		{
			$this->import_count = $this->form->import($user->getId());
		}

		$this->setTemplate('import');
	}

	public function executeIndexVisitors(sfWebRequest $request)
	{
		$this->offset = $request->getParameter('offset');

		if (is_null($this->offset))
		{
			$this->offset = 0;
		}

		$c = UserPeer::getSortByCreationDateAscCriteria();
		$this->user = UserPeer::doSelectVisitor($this->offset, $c);
		$this->count = UserPeer::getVisitorsCount();

		if (($this->offset < 0) || (($this->offset >= $this->count) && ($this->count > 0)))
		{
			$this->forward404();
		}

		if (!is_null($this->user))
		{
			$this->form = new VisitorForm($this->user);

			if (!$this->getUser()->hasCredential('admin'))
			{
				$this->form->disableRoles();
			}
		}
	}

	public function executeNew(sfWebRequest $request)
	{
		$this->form = new UserForm();

		if (!$this->getUser()->hasCredential('admin'))
		{
			$this->form->disableRoles();
		}
	}

	public function executeCreate(sfWebRequest $request)
	{
		$this->forward404Unless($request->isMethod('post'));

		$this->form = new UserForm();

		if (!$this->getUser()->hasCredential('admin'))
		{
			$this->form->disableRoles();
		}

		$this->getUser()->setFlash('created', true);
		$this->processForm($request, $this->form);

		$this->setTemplate('new');
	}

	public function executeCreateVisitor(sfWebRequest $request)
	{
		$this->forward404Unless($request->isMethod('post'));

		$this->form = new VisitorForm();

		if (!$this->getUser()->hasCredential('admin'))
		{
			$this->form->disableRoles();
		}

		$this->processFormVisitor($request, $this->form);

		$this->setTemplate('indexVisitors');
	}

	public function executeActivate(sfWebRequest $request)
	{
		$this->forward404Unless($user = UserPeer::retrieveByPk($request->getParameter('id')), sprintf('Object user does not exist (%s).', $request->getParameter('id')));

		$user->setIsActive(true);
		$user->save();

		$this->redirect('user/index');
	}

	public function executeEdit(sfWebRequest $request)
	{
		$this->forward404Unless($user = UserPeer::retrieveByPk($request->getParameter('id')), sprintf('Object user does not exist (%s).', $request->getParameter('id')));
		$this->forward404Unless($user->getIsMember() != 0);
		$this->form = new UserForm($user);

		if (!$this->getUser()->hasCredential('admin'))
		{
			$this->form->disableRoles();
		}

		$this->created = $this->getUser()->getFlash('created', false);
	}

	public function executeUpdate(sfWebRequest $request)
	{
		$this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));
		$this->forward404Unless($user = UserPeer::retrieveByPk($request->getParameter('id')), sprintf('Object user does not exist (%s).', $request->getParameter('id')));
		$this->form = new UserForm($user);

		if (!$this->getUser()->hasCredential('admin'))
		{
			$this->form->disableRoles();
		}

		$this->processForm($request, $this->form, false);

		$this->setTemplate('edit');
	}

	public function executeUpdateVisitor(sfWebRequest $request)
	{
		$this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));
		$this->forward404Unless($user = UserPeer::retrieveByPk($request->getParameter('id')), sprintf('Object user does not exist (%s).', $request->getParameter('id')));
		$this->form = new VisitorForm($user);

		if (!$this->getUser()->hasCredential('admin'))
		{
			$this->form->disableRoles();
		}

		$this->processFormVisitor($request, $this->form);

		$this->offset = $request->getParameter('offset');

		if (is_null($this->offset))
		{
			$this->offset = 0;
		}

		$this->user = $user;
		$this->count = UserPeer::getVisitorsCount();

		$this->setTemplate('indexVisitors');
	}

	public function executeDelete(sfWebRequest $request)
	{
		$request->checkCSRFProtection();

		$this->forward404Unless($user = UserPeer::retrieveByPk($request->getParameter('id')), sprintf('Object user does not exist (%s).', $request->getParameter('id')));

		if ($this->getUser()->hasCredential('admin') || !$user->hasRole('admin'))
		{
			if ($this->getUser()->getTemposUser()->getId() != $user->getId())
			{
				$user->delete();
			}
		}

		$this->redirect('user/index');
	}

	public function executeDeleteVisitor(sfWebRequest $request)
	{
		$request->checkCSRFProtection();

		$this->forward404Unless($user = UserPeer::retrieveByPk($request->getParameter('id')), sprintf('Object user does not exist (%s).', $request->getParameter('id')));
		$user->delete();

		$this->redirect('user/indexVisitors');
	}

	protected function processForm(sfWebRequest $request, sfForm $form, $new = true)
	{
		$form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));

		if ($form->isValid())
		{
			$user = $form->save();

			if ($new)
			{
				$this->redirect('user/edit?id='.$user->getId());
			} else
			{
				$this->redirect('user/index');
			}
		}
	}

	protected function processFormVisitor(sfWebRequest $request, sfForm $form)
	{
		$form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));

		if ($form->isValid())
		{
			$user = $form->save();

			if (!is_null($user->getEmailAddress()))
			{
				$password = $user->autoSetPassword();

				if (!is_null($password))
				{
					$user->save();

					$message = ConfigurationHelper::getParameter('General', 'registration_granted_message');
					$message = preg_replace(
					array(
					'/%username%/',
					'/%password%/',
					), array(
					$user->getLogin(),
					$password,
					), $message
					);

					try
					{
						if (!empty($message))
						{
							MailHelper::send($user->getEmailAddress(), 'Tempo\'s', $message);
						}
					}
					catch (Exception $ex)
					{
						throw $ex;
					}
				}
			}

			$this->redirect('user/indexVisitors');
		}
	}
}
