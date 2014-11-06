<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * Activity filter form base class.
 *
 * @package    tempos
 * @subpackage filter
 * @author     ISLOG
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseActivityFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'                        => new sfWidgetFormFilterInput(),
      'color'                       => new sfWidgetFormFilterInput(),
      'minimum_occupation'          => new sfWidgetFormFilterInput(),
      'maximum_occupation'          => new sfWidgetFormFilterInput(),
      'minimum_delay'               => new sfWidgetFormFilterInput(),
      'room_has_activity_list'      => new sfWidgetFormPropelChoice(array('model' => 'Room', 'add_empty' => true)),
      'usergroup_has_activity_list' => new sfWidgetFormPropelChoice(array('model' => 'Usergroup', 'add_empty' => true)),
      'activity_has_feature_list'   => new sfWidgetFormPropelChoice(array('model' => 'Feature', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'name'                        => new sfValidatorPass(array('required' => false)),
      'color'                       => new sfValidatorPass(array('required' => false)),
      'minimum_occupation'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'maximum_occupation'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'minimum_delay'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'room_has_activity_list'      => new sfValidatorPropelChoice(array('model' => 'Room', 'required' => false)),
      'usergroup_has_activity_list' => new sfValidatorPropelChoice(array('model' => 'Usergroup', 'required' => false)),
      'activity_has_feature_list'   => new sfValidatorPropelChoice(array('model' => 'Feature', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('activity_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function addRoomHasActivityListColumnCriteria(Criteria $criteria, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $criteria->addJoin(RoomHasActivityPeer::ACTIVITY_ID, ActivityPeer::ID);

    $value = array_pop($values);
    $criterion = $criteria->getNewCriterion(RoomHasActivityPeer::ROOM_ID, $value);

    foreach ($values as $value)
    {
      $criterion->addOr($criteria->getNewCriterion(RoomHasActivityPeer::ROOM_ID, $value));
    }

    $criteria->add($criterion);
  }

  public function addUsergroupHasActivityListColumnCriteria(Criteria $criteria, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $criteria->addJoin(UsergroupHasActivityPeer::ACTIVITY_ID, ActivityPeer::ID);

    $value = array_pop($values);
    $criterion = $criteria->getNewCriterion(UsergroupHasActivityPeer::USERGROUP_ID, $value);

    foreach ($values as $value)
    {
      $criterion->addOr($criteria->getNewCriterion(UsergroupHasActivityPeer::USERGROUP_ID, $value));
    }

    $criteria->add($criterion);
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

    $criteria->addJoin(ActivityHasFeaturePeer::ACTIVITY_ID, ActivityPeer::ID);

    $value = array_pop($values);
    $criterion = $criteria->getNewCriterion(ActivityHasFeaturePeer::FEATURE_ID, $value);

    foreach ($values as $value)
    {
      $criterion->addOr($criteria->getNewCriterion(ActivityHasFeaturePeer::FEATURE_ID, $value));
    }

    $criteria->add($criterion);
  }

  public function getModelName()
  {
    return 'Activity';
  }

  public function getFields()
  {
    return array(
      'id'                          => 'Number',
      'name'                        => 'Text',
      'color'                       => 'Text',
      'minimum_occupation'          => 'Number',
      'maximum_occupation'          => 'Number',
      'minimum_delay'               => 'Number',
      'room_has_activity_list'      => 'ManyKey',
      'usergroup_has_activity_list' => 'ManyKey',
      'activity_has_feature_list'   => 'ManyKey',
    );
  }
}
