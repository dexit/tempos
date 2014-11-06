<?php

class MailHelper
{
	public static function send($to, $subject, $body, $to_name = null, $from = null, $from_name = null)
	{
		$encryption_method = ConfigurationHelper::getParameter('Email', 'smtp_encryption_method', false);
		$host = ConfigurationHelper::getParameter('Email', 'smtp_host');
		$service = ConfigurationHelper::getParameter('Email', 'smtp_service');
		$use_authentication = ConfigurationHelper::getParameter('Email', 'smtp_use_authentication');
		$username = ConfigurationHelper::getParameter('Email', 'smtp_username');
		$password = ConfigurationHelper::getParameter('Email', 'smtp_password');
		$signature = ConfigurationHelper::getParameter('Email', 'signature');

		if (is_null($from) || ConfigurationHelper::getParameter('Email', 'override_from'))
		{
			$from = ConfigurationHelper::getParameter('Email', 'from');
		}

		switch ($encryption_method)
		{
			case 'ssl':
				{
					$encryption = Swift_Connection_SMTP::ENC_SSL;
					break;
				}
			case 'tls':
				{
					$encryption = Swift_Connection_SMTP::ENC_TLS;
					break;
				}
			case 'none':
			default:
				{
					$encryption = Swift_Connection_SMTP::ENC_OFF;
					break;
				}
		}

		$smtp = new Swift_Connection_SMTP($host, $service, $encryption);

		if ($use_authentication)
		{
			$smtp->setUsername($username);
			$smtp->setPassword($password);
		}

		$message = new Swift_Message($subject, sprintf("%s\n\n%s", $body, $signature));

		$swift = new Swift($smtp);

		if (!is_null($to_name))
		{
			$to = new Swift_Address($to, $to_name);
		} else
		{
			$to = new Swift_Address($to);
		}

		if (!is_null($from_name))
		{
			$from = new Swift_Address($from, $from_name);
		} else
		{
			$from = new Swift_Address($from);
		}

		$swift->send($message, $to, $from);
	}
}
