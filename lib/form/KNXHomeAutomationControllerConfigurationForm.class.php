<?php

/**
 * KNXHomeAutomationControllerConfiguration form.
 *
 * @package    tempos
 * @subpackage form
 * @author     ISLOG
 */
class KNXHomeAutomationControllerConfigurationForm extends HomeAutomationControllerConfigurationForm
{
  public function configure()
  {
		parent::configure();

		$this->widgetSchema->setLabels(array(
			'host'		=> 'KNXnet/IP host',
			'service'	=> 'KNXnet/IP port',
		));

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
  }
}
