<?php

class IPX800HomeAutomationControllerConfigurationForm extends HomeAutomationControllerConfigurationForm
{
  public function configure()
  {
		parent::configure();

		$this->widgetSchema->setLabels(array(
			'host'		=> 'IPX800 host',
			'service'	=> 'IPX800 port',
			'preset'	=> 'Preset URL'
		));

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
  }
}
