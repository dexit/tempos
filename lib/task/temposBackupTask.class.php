<?php

class temposBackupTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
    ));

    $this->namespace        = 'tempos';
    $this->name             = 'backup';
    $this->briefDescription = 'Backup the database and the configuration';
    $this->detailedDescription = <<<EOF
The [tempos:backup|INFO] task backups the database and the configuration using the configured backup method.

  [./symfony tempos:backup|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
		$this->configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'prod', true);
		$this->context = sfContext::createInstance($this->configuration);

    $databaseManager = new sfDatabaseManager($this->configuration);

		$dbdsn = $databaseManager->getDatabase('propel')->getParameter('dsn');
		$dbusername = $databaseManager->getDatabase('propel')->getParameter('username');
		$dbpassword = $databaseManager->getDatabase('propel')->getParameter('password');
		$dbname = preg_replace('/^.*dbname=([^;=]+).*$/', '${1}', $dbdsn);

		ConfigurationHelper::load();

		$backup_method = ConfigurationHelper::getParameter('Backup', 'backup_method');

		$this->logSection('tempos', sprintf('Backup method: %s', $backup_method), 1024);

		if ($backup_method == 'ftp')
		{
			$backupname = 'tempos-backup.sql';

			$configname = ConfigurationHelper::getDefaultConfigurationFileName();
			$configpath = ConfigurationHelper::getDefaultConfigurationFilePath();

			copy($configpath, '/tmp/'.$configname);
			
			chdir('/tmp');

			system(sprintf('mysqldump --user=%s --password=%s %s > %s', escapeshellarg($dbusername), escapeshellarg($dbpassword), escapeshellarg($dbname), escapeshellarg($backupname)));

			$tmpfilename = 'tempos-backup-'.date('Y-m-d').'.tar.gz';

			system(sprintf('tar zcf %s %s %s', escapeshellarg($tmpfilename), escapeshellarg($backupname), escapeshellarg($configname)));

			unlink($backupname);
			unlink($configname);

			try
			{
				FTPHelper::backupFile($tmpfilename);
			} catch (Exception $ex)
			{
				unlink($tmpfilename);

				throw $ex;
			}

			unlink($tmpfilename);
		}
  }
}
