<?php

/**
 * VirtualPhysicalAccessControllerConfiguration form.
 *
 * @package    tempos
 * @subpackage form
 * @author     ISLOG
 */
class VirtualPhysicalAccessControllerConfigurationForm extends PhysicalAccessControllerConfigurationForm
{
	public function configure()
	{
		parent::configure();

		$this->widgetSchema->setLabels(array(
			'status'	=> 'Status'
		));

		$this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
	}
}
