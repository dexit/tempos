<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * Roomprofile filter form base class.
 *
 * @package    tempos
 * @subpackage filter
 * @author     ISLOG
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseRoomprofileFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'               => new sfWidgetFormFilterInput(),
      'physical_access_id' => new sfWidgetFormFilterInput(),
      'Room_id'            => new sfWidgetFormPropelChoice(array('model' => 'Room', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'name'               => new sfValidatorPass(array('required' => false)),
      'physical_access_id' => new sfValidatorPass(array('required' => false)),
      'Room_id'            => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Room', 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('roomprofile_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Roomprofile';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Number',
      'name'               => 'Text',
      'physical_access_id' => 'Text',
      'Room_id'            => 'ForeignKey',
    );
  }
}
