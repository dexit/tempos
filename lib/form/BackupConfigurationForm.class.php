<?php

/**
 * Backup Configuration form.
 *
 * @package    tempos
 * @subpackage form
 * @author     ISLOG
 */
class BackupConfigurationForm extends sfForm
{
  public function configure()
  {
		$backup_methods = array(
			'none' => sfContext::getInstance()->getI18N()->__('No backup'),
			'ftp' => sfContext::getInstance()->getI18N()->__('FTP'),
		);

		$this->widgetSchema['backup_method'] = new sfWidgetFormChoice(array('choices' => $backup_methods));
		$this->validatorSchema['backup_method'] = new sfValidatorChoice(array('choices' => array_keys($backup_methods), 'required' => true));
		$this->widgetSchema->setLabel('backup_method', 'Backup method');

		$this->widgetSchema['ftp_host'] = new sfWidgetFormInput();
		$this->validatorSchema['ftp_host'] = new sfValidatorString(array('required' => false));
		$this->widgetSchema->setLabel('ftp_host', 'FTP host');

		$this->widgetSchema['ftp_service'] = new sfWidgetFormInput();
		$this->validatorSchema['ftp_service'] = new sfValidatorInteger(array('min' => 1, 'max' => 65535, 'required' => false));
		$this->widgetSchema->setLabel('ftp_service', 'FTP port');

		$this->widgetSchema['ftp_username'] = new sfWidgetFormInput();
		$this->validatorSchema['ftp_username'] = new sfValidatorString(array('required' => false));
		$this->widgetSchema->setLabel('ftp_username', 'Username');

		$this->widgetSchema['ftp_password'] = new sfWidgetFormInputPassword();
		$this->validatorSchema['ftp_password'] = new sfValidatorString(array('required' => false));
		$this->widgetSchema->setLabel('ftp_password', 'Password');

		if (!ConfigurationHelper::hasParameter('Backup', 'backup_method'))
		{
			ConfigurationHelper::setParameter('Backup', 'backup_method', 'none');
		}

		if (!ConfigurationHelper::hasParameter('Backup', 'ftp_host'))
		{
			ConfigurationHelper::setParameter('Backup', 'ftp_host', 'ftp');
		}

		if (!ConfigurationHelper::hasParameter('Backup', 'ftp_service'))
		{
			ConfigurationHelper::setParameter('Backup', 'ftp_service', '21');
		}

		$this->setDefaults(ConfigurationHelper::getNamespace('Backup'));

    $this->widgetSchema->setNameFormat('backupConfiguration[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
  }

	public function filter($values)
	{
		if (empty($values['ftp_password']) && ConfigurationHelper::hasParameter('Backup', 'ftp_password'))
		{
			$values['ftp_password'] = ConfigurationHelper::getParameter('Backup', 'ftp_password');
		}

		return $values;
	}
}
