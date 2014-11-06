<?php

/**
 * feature actions.
 *
 * @package    tempos
 * @subpackage feature
 * @author     ISLOG
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class featureActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
		$this->step = sfConfig::get('app_max_features_on_featurelist');

		$this->getUser()->syncParameters($this, 'feature', 'index', array('offset', 'limit', 'sort_column', 'sort_direction'), $request);

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

		SortCriteria::addSortCriteria($c, $this->sort_column, FeaturePeer::getSortAliases(), $this->sort_direction);

		$c->setOffset($this->offset);

		if ($this->limit >= 0)
		{
			$c->setLimit($this->limit);
		}

    $this->feature_list = FeaturePeer::doSelect($c);
		$this->count = FeaturePeer::doCount(new Criteria());

		if (($this->offset < 0) || (($this->offset >= $this->count) && ($this->count > 0)))
		{
			$this->forward404();
		}
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new FeatureForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));

    $this->form = new FeatureForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($feature = FeaturePeer::retrieveByPk($request->getParameter('id')), sprintf('Object feature does not exist (%s).', $request->getParameter('id')));
    $this->form = new FeatureForm($feature);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));
    $this->forward404Unless($feature = FeaturePeer::retrieveByPk($request->getParameter('id')), sprintf('Object feature does not exist (%s).', $request->getParameter('id')));
    $this->form = new FeatureForm($feature);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($feature = FeaturePeer::retrieveByPk($request->getParameter('id')), sprintf('Object feature does not exist (%s).', $request->getParameter('id')));
    $feature->delete();

    $this->redirect('feature/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $feature = $form->save();

      $this->redirect('feature/index');
    }
  }
}
