<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * Feature filter form base class.
 *
 * @package    tempos
 * @subpackage filter
 * @author     ISLOG
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseFeatureFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'                      => new sfWidgetFormFilterInput(),
      'is_exclusive'              => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'activity_has_feature_list' => new sfWidgetFormPropelChoice(array('model' => 'Activity', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'name'                      => new sfValidatorPass(array('required' => false)),
      'is_exclusive'              => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'activity_has_feature_list' => new sfValidatorPropelChoice(array('model' => 'Activity', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('feature_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function addActivityHasFeatureListColumnCriteria(Criteria $criteria, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $criteria->addJoin(ActivityHasFeaturePeer::FEATURE_ID, FeaturePeer::ID);

    $value = array_pop($values);
    $criterion = $criteria->getNewCriterion(ActivityHasFeaturePeer::ACTIVITY_ID, $value);

    foreach ($values as $value)
    {
      $criterion->addOr($criteria->getNewCriterion(ActivityHasFeaturePeer::ACTIVITY_ID, $value));
    }

    $criteria->add($criterion);
  }

  public function getModelName()
  {
    return 'Feature';
  }

  public function getFields()
  {
    return array(
      'id'                        => 'Number',
      'name'                      => 'Text',
      'is_exclusive'              => 'Boolean',
      'activity_has_feature_list' => 'ManyKey',
    );
  }
}
