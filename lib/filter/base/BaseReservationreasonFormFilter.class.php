<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * Reservationreason filter form base class.
 *
 * @package    tempos
 * @subpackage filter
 * @author     ISLOG
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseReservationreasonFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'Activity_id' => new sfWidgetFormPropelChoice(array('model' => 'Activity', 'add_empty' => true)),
      'name'        => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'Activity_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Activity', 'column' => 'id')),
      'name'        => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('reservationreason_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Reservationreason';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'Activity_id' => 'ForeignKey',
      'name'        => 'Text',
    );
  }
}
