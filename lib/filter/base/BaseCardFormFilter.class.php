<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * Card filter form base class.
 *
 * @package    tempos
 * @subpackage filter
 * @author     ISLOG
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseCardFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'card_number' => new sfWidgetFormFilterInput(),
      'pin_code'    => new sfWidgetFormFilterInput(),
      'is_active'   => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'owner'       => new sfWidgetFormPropelChoice(array('model' => 'Carduser', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'card_number' => new sfValidatorPass(array('required' => false)),
      'pin_code'    => new sfValidatorPass(array('required' => false)),
      'is_active'   => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'owner'       => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Carduser', 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('card_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Card';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'card_number' => 'Text',
      'pin_code'    => 'Text',
      'is_active'   => 'Boolean',
      'owner'       => 'ForeignKey',
    );
  }
}
