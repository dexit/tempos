<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * Carduser filter form base class.
 *
 * @package    tempos
 * @subpackage filter
 * @author     ISLOG
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseCarduserFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'family_name' => new sfWidgetFormFilterInput(),
      'surname'     => new sfWidgetFormFilterInput(),
      'birthdate'   => new sfWidgetFormFilterDate(),
    ));

    $this->setValidators(array(
      'family_name' => new sfValidatorPass(array('required' => false)),
      'surname'     => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('carduser_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Carduser';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'family_name' => 'Text',
      'surname'     => 'Text',
      'birthdate'   => 'Date',
    );
  }
}
