<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * UsergroupHasActivity filter form base class.
 *
 * @package    tempos
 * @subpackage filter
 * @author     ISLOG
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BaseUsergroupHasActivityFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
    ));

    $this->setValidators(array(
    ));

    $this->widgetSchema->setNameFormat('usergroup_has_activity_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UsergroupHasActivity';
  }

  public function getFields()
  {
    return array(
      'UserGroup_id' => 'ForeignKey',
      'Activity_id'  => 'ForeignKey',
    );
  }
}
