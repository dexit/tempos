<?php

/**
 * Feature form base class.
 *
 * @package    tempos
 * @subpackage form
 * @author     ISLOG
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseFeatureForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                        => new sfWidgetFormInputHidden(),
      'name'                      => new sfWidgetFormInput(),
      'is_exclusive'              => new sfWidgetFormInputCheckbox(),
      'activity_has_feature_list' => new sfWidgetFormPropelChoiceMany(array('model' => 'Activity')),
    ));

    $this->setValidators(array(
      'id'                        => new sfValidatorPropelChoice(array('model' => 'Feature', 'column' => 'id', 'required' => false)),
      'name'                      => new sfValidatorString(array('max_length' => 128)),
      'is_exclusive'              => new sfValidatorBoolean(),
      'activity_has_feature_list' => new sfValidatorPropelChoiceMany(array('model' => 'Activity', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('feature[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Feature';
  }


  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['activity_has_feature_list']))
    {
      $values = array();
      foreach ($this->object->getActivityHasFeatures() as $obj)
      {
        $values[] = $obj->getActivityId();
      }

      $this->setDefault('activity_has_feature_list', $values);
    }

  }

  protected function doSave($con = null)
  {
    parent::doSave($con);

    $this->saveActivityHasFeatureList($con);
  }

  public function saveActivityHasFeatureList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['activity_has_feature_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (is_null($con))
    {
      $con = $this->getConnection();
    }

    $c = new Criteria();
    $c->add(ActivityHasFeaturePeer::FEATURE_ID, $this->object->getPrimaryKey());
    ActivityHasFeaturePeer::doDelete($c, $con);

    $values = $this->getValue('activity_has_feature_list');
    if (is_array($values))
    {
      foreach ($values as $value)
      {
        $obj = new ActivityHasFeature();
        $obj->setFeatureId($this->object->getPrimaryKey());
        $obj->setActivityId($value);
        $obj->save();
      }
    }
  }

}
