<?php

/**
 * ActivityHasFeature form base class.
 *
 * @package    tempos
 * @subpackage form
 * @author     ISLOG
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseActivityHasFeatureForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'Activity_id' => new sfWidgetFormInputHidden(),
      'Feature_id'  => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'Activity_id' => new sfValidatorPropelChoice(array('model' => 'Activity', 'column' => 'id', 'required' => false)),
      'Feature_id'  => new sfValidatorPropelChoice(array('model' => 'Feature', 'column' => 'id', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('activity_has_feature[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'ActivityHasFeature';
  }


}
