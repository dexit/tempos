<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * ZoneHasRoom filter form base class.
 *
 * @package    tempos
 * @subpackage filter
 * @author     ISLOG
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseZoneHasRoomFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
    ));

    $this->setValidators(array(
    ));

    $this->widgetSchema->setNameFormat('zone_has_room_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'ZoneHasRoom';
  }

  public function getFields()
  {
    return array(
      'Zone_id' => 'ForeignKey',
      'Room_id' => 'ForeignKey',
    );
  }
}
