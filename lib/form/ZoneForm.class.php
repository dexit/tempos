<?php

/**
 * Zone form.
 *
 * @package    tempos
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class ZoneForm extends BaseZoneForm
{
  public function configure()
  {
		unset($this['zone_has_room_list']);

    $this->widgetSchema['parent_zone'] = new sfWidgetFormZoneChoice(array('current_zone' => $this->object));

		// Validators
    $this->validatorSchema['name'] =  new sfXSSValidatorString(array('max_length' => 64));
    $this->validatorSchema['parent_zone'] = new sfValidatorZoneChoice(array('current_zone' => $this->object, 'required' => false));

		// Post-validators
		$this->validatorSchema->setPostValidator(
			new sfValidatorAnd(array(
				new sfValidatorPropelUnique(array('model' => 'Zone', 'column' => array('name'))),
			))
		);
  }

	public function setDefaultParentZone($parentZoneId)
	{
		$this->getObject()->setParentZone($parentZoneId);
		$this->setDefault('parent_zone', $parentZoneId);
		$this->widgetSchema['parent_zone'] = new sfWidgetFormInputHidden();
	}
}
