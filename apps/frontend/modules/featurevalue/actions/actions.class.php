<?php

/**
 * featurevalue actions.
 *
 * @package    tempos
 * @subpackage featurevalue
 * @author     ISLOG
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class featurevalueActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward404Unless($this->feature = FeaturePeer::retrieveByPk($request->getParameter('featureId')), sprintf('Object feature does not exist (%s).', $request->getParameter('featureId')));

    $this->featurevalue_list = FeaturevaluePeer::doSelectFromFeature($this->feature->getId());
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->forward404Unless($feature = FeaturePeer::retrieveByPk($request->getParameter('featureId')), sprintf('Object feature does not exist (%s).', $request->getParameter('featureId')));

    $this->form = new FeaturevalueForm(null, $feature);
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    $this->forward404Unless($feature = FeaturePeer::retrieveByPk($request->getParameter('featureId')), sprintf('Object feature does not exist (%s).', $request->getParameter('featureId')));

    $this->form = new FeaturevalueForm(null, $feature);

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($featurevalue = FeaturevaluePeer::retrieveByPk($request->getParameter('id')), sprintf('Object featurevalue does not exist (%s).', $request->getParameter('id')));

    $this->form = new FeaturevalueForm($featurevalue);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));
    $this->forward404Unless($featurevalue = FeaturevaluePeer::retrieveByPk($request->getParameter('id')), sprintf('Object featurevalue does not exist (%s).', $request->getParameter('id')));

    $this->form = new FeaturevalueForm($featurevalue);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($featurevalue = FeaturevaluePeer::retrieveByPk($request->getParameter('id')), sprintf('Object featurevalue does not exist (%s).', $request->getParameter('id')));
		$featureId = $featurevalue->getFeature()->getId();
    $featurevalue->delete();

    $this->redirect('featurevalue/index?featureId='.$featureId);
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $featurevalue = $form->save();

      $this->redirect('featurevalue/index?featureId='.$form->getFeature()->getId());
    }
  }
}
