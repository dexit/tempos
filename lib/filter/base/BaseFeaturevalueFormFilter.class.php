<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * Featurevalue filter form base class.
 *
 * @package    tempos
 * @subpackage filter
 * @author     ISLOG
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseFeaturevalueFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'value'                      => new sfWidgetFormFilterInput(),
      'Feature_id'                 => new sfWidgetFormPropelChoice(array('model' => 'Feature', 'add_empty' => true)),
      'room_has_featurevalue_list' => new sfWidgetFormPropelChoice(array('model' => 'Room', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'value'                      => new sfValidatorPass(array('required' => false)),
      'Feature_id'                 => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Feature', 'column' => 'id')),
      'room_has_featurevalue_list' => new sfValidatorPropelChoice(array('model' => 'Room', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('featurevalue_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function addRoomHasFeaturevalueListColumnCriteria(Criteria $criteria, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $criteria->addJoin(RoomHasFeaturevaluePeer::FEATUREVALUE_ID, FeaturevaluePeer::ID);

    $value = array_pop($values);
    $criterion = $criteria->getNewCriterion(RoomHasFeaturevaluePeer::ROOM_ID, $value);

    foreach ($values as $value)
    {
      $criterion->addOr($criteria->getNewCriterion(RoomHasFeaturevaluePeer::ROOM_ID, $value));
    }

    $criteria->add($criterion);
  }

  public function getModelName()
  {
    return 'Featurevalue';
  }

  public function getFields()
  {
    return array(
      'id'                         => 'Number',
      'value'                      => 'Text',
      'Feature_id'                 => 'ForeignKey',
      'room_has_featurevalue_list' => 'ManyKey',
    );
  }
}
