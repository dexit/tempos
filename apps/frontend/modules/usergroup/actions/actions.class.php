<?php

/**
* usergroup actions.
*
* @package    tempos
* @subpackage usergroup
* @author     ISLOG
* @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
*/
class usergroupActions extends sfActions
{
	public function executeIndex(sfWebRequest $request)
	{
		$this->step = sfConfig::get('app_max_usergroups_on_usergrouplist');

		$this->getUser()->syncParameters($this, 'usergroup', 'index', array('offset', 'limit', 'sort_column', 'sort_direction'), $request);

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

		SortCriteria::addSortCriteria($c, $this->sort_column, UsergroupPeer::getSortAliases(), $this->sort_direction);

		$c->setOffset($this->offset);

		if ($this->limit >= 0)
		{
			$c->setLimit($this->limit);
		}

		$this->usergroup_list = UsergroupPeer::doSelect($c);
		
		$c2 = new Criteria();
		
		$this->count = UsergroupPeer::doCount($c2);

		if (($this->offset < 0) || (($this->offset >= $this->count) && ($this->count > 0)))
		{
			$this->forward404();
		}
	}

	public function executeNew(sfWebRequest $request)
	{
		$this->form = new UsergroupForm();

		if ($request->hasParameter('users'))
		{
			$this->form->addUsers(explode(',', $request->getParameter('users')));
		}
	}

	public function executeCreate(sfWebRequest $request)
	{
		$this->forward404Unless($request->isMethod('post'));

		$this->form = new UsergroupForm();

		$this->processForm($request, $this->form);

		$this->setTemplate('new');
	}

	public function executeEdit(sfWebRequest $request)
	{
		$this->forward404Unless($usergroup = UsergroupPeer::retrieveByPk($request->getParameter('id')), sprintf('Object usergroup does not exist (%s).', $request->getParameter('id')));
		$this->form = new UsergroupForm($usergroup);

		if ($request->hasParameter('users'))
		{
			$this->form->addUsers(explode(',', $request->getParameter('users')));
		}
	}

	public function executeUpdate(sfWebRequest $request)
	{
		$this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));
		$this->forward404Unless($usergroup = UsergroupPeer::retrieveByPk($request->getParameter('id')), sprintf('Object usergroup does not exist (%s).', $request->getParameter('id')));
		$this->form = new UsergroupForm($usergroup);

		$this->processForm($request, $this->form);

		$this->setTemplate('edit');
	}

	public function executeAddUsers(sfWebRequest $request)
	{
		$request->checkCSRFProtection();
		$this->forward404Unless($request->hasParameter('users'), 'users parameter is mandatory');

		$this->form = new UsergroupUsersForm();
		$this->form->addUsers(explode(',', $request->getParameter('users')));
	}

	public function executeAddUsersProcess(sfWebRequest $request)
	{
		$this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));

		$this->form = new UsergroupUsersForm();

		$this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));

		if ($this->form->isValid())
		{
			$this->redirect('usergroup/edit?id='.$this->form->getValue('Usergroup_id').'&users='.implode(',', $this->form->getValue('usergroup_has_user_list')));
		}

		$this->setTemplate('addUsers');
	}

	public function executeDelete(sfWebRequest $request)
	{
		$request->checkCSRFProtection();

		$this->forward404Unless($usergroup = UsergroupPeer::retrieveByPk($request->getParameter('id')), sprintf('Object usergroup does not exist (%s).', $request->getParameter('id')));
		$usergroup->delete();

		$this->redirect('usergroup/index');
	}

	protected function processForm(sfWebRequest $request, sfForm $form)
	{
		$form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
		if ($form->isValid())
		{
			$usergroup = $form->save();

			$this->redirect('usergroup/index');
		}
	}
}
