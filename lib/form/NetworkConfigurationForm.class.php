<?php

/**
 * Network Configuration form.
 *
 * @package    tempos
 * @subpackage form
 * @author     ISLOG
 */
class NetworkConfigurationForm extends sfForm
{
  public function configure()
  {
		$ipv4_types = array(
			'system' => sfContext::getInstance()->getI18N()->__('System'),
			'dhcp' => sfContext::getInstance()->getI18N()->__('DHCP'),
			'static' => sfContext::getInstance()->getI18N()->__('Static'),
		);

		$this->widgetSchema['ipv4_type'] = new sfWidgetFormChoice(array('choices' => $ipv4_types));
		$this->validatorSchema['ipv4_type'] = new sfValidatorChoice(array('choices' => array_keys($ipv4_types), 'required' => true));
		$this->widgetSchema->setLabel('ipv4_type', 'IPv4 type');

		$this->widgetSchema['ipv4_address'] = new sfWidgetFormInput();
		$this->validatorSchema['ipv4_address'] = new sfValidatorIPv4Address(array('required' => false));
		$this->widgetSchema->setLabel('ipv4_address', 'IPv4 address');

		$this->widgetSchema['ipv4_netmask'] = new sfWidgetFormInput();
		$this->validatorSchema['ipv4_netmask'] = new sfValidatorIPv4Address(array('required' => false));
		$this->widgetSchema->setLabel('ipv4_netmask', 'IPv4 netmask');

		$this->widgetSchema['ipv4_gateway'] = new sfWidgetFormInput();
		$this->validatorSchema['ipv4_gateway'] = new sfValidatorIPv4Address(array('required' => false));
		$this->widgetSchema->setLabel('ipv4_gateway', 'IPv4 gateway');

		$this->setDefaults(ConfigurationHelper::getNamespace('Network'));

    $this->widgetSchema->setNameFormat('networkConfiguration[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
  }

	public function filter($values)
	{
		$values['_need_update'] = true;

		return $values;
	}
}
