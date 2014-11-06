<?php

/**
 * Activity form base class.
 *
 * @package    tempos
 * @subpackage form
 * @author     ISLOG
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseActivityForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                          => new sfWidgetFormInputHidden(),
      'name'                        => new sfWidgetFormInput(),
      'color'                       => new sfWidgetFormInput(),
      'minimum_occupation'          => new sfWidgetFormInput(),
      'maximum_occupation'          => new sfWidgetFormInput(),
      'minimum_delay'               => new sfWidgetFormInput(),
      'room_has_activity_list'      => new sfWidgetFormPropelChoiceMany(array('model' => 'Room')),
      'usergroup_has_activity_list' => new sfWidgetFormPropelChoiceMany(array('model' => 'Usergroup')),
      'activity_has_feature_list'   => new sfWidgetFormPropelChoiceMany(array('model' => 'Feature')),
    ));

    $this->setValidators(array(
      'id'                          => new sfValidatorPropelChoice(array('model' => 'Activity', 'column' => 'id', 'required' => false)),
      'name'                        => new sfValidatorString(array('max_length' => 64)),
      'color'                       => new sfValidatorString(array('max_length' => 16)),
      'minimum_occupation'          => new sfValidatorInteger(),
      'maximum_occupation'          => new sfValidatorInteger(),
      'minimum_delay'               => new sfValidatorInteger(),
      'room_has_activity_list'      => new sfValidatorPropelChoiceMany(array('model' => 'Room', 'required' => false)),
      'usergroup_has_activity_list' => new sfValidatorPropelChoiceMany(array('model' => 'Usergroup', 'required' => false)),
      'activity_has_feature_list'   => new sfValidatorPropelChoiceMany(array('model' => 'Feature', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('activity[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Activity';
  }


  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['room_has_activity_list']))
    {
      $values = array();
      foreach ($this->object->getRoomHasActivitys() as $obj)
      {
        $values[] = $obj->getRoomId();
      }

      $this->setDefault('room_has_activity_list', $values);
    }

    if (isset($this->widgetSchema['usergroup_has_activity_list']))
    {
      $values = array();
      foreach ($this->object->getUsergroupHasActivitys() as $obj)
      {
        $values[] = $obj->getUsergroupId();
      }

      $this->setDefault('usergroup_has_activity_list', $values);
    }

    if (isset($this->widgetSchema['activity_has_feature_list']))
    {
      $values = array();
      foreach ($this->object->getActivityHasFeatures() as $obj)
      {
        $values[] = $obj->getFeatureId();
      }

      $this->setDefault('activity_has_feature_list', $values);
    }

  }

  protected function doSave($con = null)
  {
    parent::doSave($con);

    $this->saveRoomHasActivityList($con);
    $this->saveUsergroupHasActivityList($con);
    $this->saveActivityHasFeatureList($con);
  }

  public function saveRoomHasActivityList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['room_has_activity_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (is_null($con))
    {
      $con = $this->getConnection();
    }

    $c = new Criteria();
    $c->add(RoomHasActivityPeer::ACTIVITY_ID, $this->object->getPrimaryKey());
    RoomHasActivityPeer::doDelete($c, $con);

    $values = $this->getValue('room_has_activity_list');
    if (is_array($values))
    {
      foreach ($values as $value)
      {
        $obj = new RoomHasActivity();
        $obj->setActivityId($this->object->getPrimaryKey());
        $obj->setRoomId($value);
        $obj->save();
      }
    }
  }

  public function saveUsergroupHasActivityList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['usergroup_has_activity_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (is_null($con))
    {
      $con = $this->getConnection();
    }

    $c = new Criteria();
    $c->add(UsergroupHasActivityPeer::ACTIVITY_ID, $this->object->getPrimaryKey());
    UsergroupHasActivityPeer::doDelete($c, $con);

    $values = $this->getValue('usergroup_has_activity_list');
    if (is_array($values))
    {
      foreach ($values as $value)
      {
        $obj = new UsergroupHasActivity();
        $obj->setActivityId($this->object->getPrimaryKey());
        $obj->setUsergroupId($value);
        $obj->save();
      }
    }
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
    $c->add(ActivityHasFeaturePeer::ACTIVITY_ID, $this->object->getPrimaryKey());
    ActivityHasFeaturePeer::doDelete($c, $con);

    $values = $this->getValue('activity_has_feature_list');
    if (is_array($values))
    {
      foreach ($values as $value)
      {
        $obj = new ActivityHasFeature();
        $obj->setActivityId($this->object->getPrimaryKey());
        $obj->setFeatureId($value);
        $obj->save();
      }
    }
  }

}
