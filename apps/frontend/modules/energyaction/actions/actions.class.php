<?php

/**
 * energyaction actions.
 *
 * @package    tempos
 * @subpackage energyaction
 * @author     ISLOG
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class energyactionActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
		$this->step = sfConfig::get('app_max_features_on_featurelist');

		$this->getUser()->syncParameters($this, 'energyaction', 'index', array('offset', 'limit', 'sort_column', 'sort_direction'), $request);

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

		SortCriteria::addSortCriteria($c, $this->sort_column, EnergyactionPeer::getSortAliases(), $this->sort_direction);

		$c->setOffset($this->offset);

		if ($this->limit >= 0)
		{
			$c->setLimit($this->limit);
		}

    $this->energyaction_list = EnergyactionPeer::doSelect($c);
		$this->count = EnergyactionPeer::doCount(new Criteria());

		if (($this->offset < 0) || (($this->offset >= $this->count) && ($this->count > 0)))
		{
			$this->forward404();
		}
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new EnergyactionForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));

    $this->form = new EnergyactionForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($energyaction = EnergyactionPeer::retrieveByPk($request->getParameter('id')), sprintf('Object energyaction does not exist (%s).', $request->getParameter('id')));
    $this->form = new EnergyactionForm($energyaction);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));
    $this->forward404Unless($energyaction = EnergyactionPeer::retrieveByPk($request->getParameter('id')), sprintf('Object energyaction does not exist (%s).', $request->getParameter('id')));
    $this->form = new EnergyactionForm($energyaction);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($energyaction = EnergyactionPeer::retrieveByPk($request->getParameter('id')), sprintf('Object energyaction does not exist (%s).', $request->getParameter('id')));
    $energyaction->delete();

    $this->redirect('energyaction/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
	  if (!is_null($form->getValue('home_automation_controller')))
	  {
		$nb = $form->getValue('home_automation_controller');
		
		// Récupère le contrôleur domotique sélectionné (grâce à sa position dans le ConfigurationHelper)
		$hac = ConfigurationHelper::getParameter(null, 'home_automation_controller'.($nb+1));
		$hac .= ($nb+1);
		$controller = ConfigurationHelper::getParameter($hac, 'controller_name');

		$name = EnergyactionPeer::buildName($controller, $form->getvalue('name'));
		
		$energyaction = $form->save();
		$energyaction->setName($name);
		$energyaction->save();
	  } else
	  {
		$energyaction = $form->save();
	  }
	  
	  $this->redirect('energyaction/index');
    }
  }
}
