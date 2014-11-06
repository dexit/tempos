<?php

/**
 * VirtualHAHomeAutomationControllerConfiguration form.
 *
 * @package    tempos
 * @subpackage form
 * @author     ISLOG
 */
class VirtualHAHomeAutomationControllerConfigurationForm extends HomeAutomationControllerConfigurationForm
{
  public function configure()
  {
		parent::configure();

		$this->widgetSchema->setLabels(array(
		));

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
  }
}
