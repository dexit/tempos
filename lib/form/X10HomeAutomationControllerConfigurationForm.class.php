<?php

/**
 * X10HomeAutomationControllerConfiguration form.
 *
 * @package    tempos
 * @subpackage form
 * @author     ISLOG
 */
class X10HomeAutomationControllerConfigurationForm extends HomeAutomationControllerConfigurationForm
{
  public function configure()
  {
		parent::configure();

		$this->widgetSchema->setLabels(array(
			'command'		=> 'Command',
			'force-status'	=> 'Force status',
		));

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
  }
}
