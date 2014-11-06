<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * Zone filter form base class.
 *
 * @package    tempos
 * @subpackage filter
 * @author     ISLOG
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseZoneFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'               => new sfWidgetFormFilterInput(),
      'parent_zone'        => new sfWidgetFormPropelChoice(array('model' => 'Zone', 'add_empty' => true)),
      'zone_has_room_list' => new sfWidgetFormPropelChoice(array('model' => 'Room', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'name'               => new sfValidatorPass(array('required' => false)),
      'parent_zone'        => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Zone', 'column' => 'id')),
      'zone_has_room_list' => new sfValidatorPropelChoice(array('model' => 'Room', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('zone_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function addZoneHasRoomListColumnCriteria(Criteria $criteria, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $criteria->addJoin(ZoneHasRoomPeer::ZONE_ID, ZonePeer::ID);

    $value = array_pop($values);
    $criterion = $criteria->getNewCriterion(ZoneHasRoomPeer::ROOM_ID, $value);

    foreach ($values as $value)
    {
      $criterion->addOr($criteria->getNewCriterion(ZoneHasRoomPeer::ROOM_ID, $value));
    }

    $criteria->add($criterion);
  }

  public function getModelName()
  {
    return 'Zone';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Number',
      'name'               => 'Text',
      'parent_zone'        => 'ForeignKey',
      'zone_has_room_list' => 'ManyKey',
    );
  }
}
