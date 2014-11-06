<?php

class FTPHelper
{
	public static function backupFile($file)
	{
		$host = ConfigurationHelper::getParameter('Backup', 'ftp_host');
		$service = ConfigurationHelper::getParameter('Backup', 'ftp_service', 21);
		$username = ConfigurationHelper::getParameter('Backup', 'ftp_username');
		$password = ConfigurationHelper::getParameter('Backup', 'ftp_password');

		$result = false;

		$ch = ftp_connect($host, $service);

		if ($ch)
		{
			$lr = ftp_login($ch, $username, $password);

			if ($lr)
			{
				$result = ftp_put($ch, basename($file), $file, FTP_BINARY);
			}

			ftp_close($ch);
		}

		if (!$result)
		{
			throw new Exception('FTP backup file failed.');
		}
	}
}
