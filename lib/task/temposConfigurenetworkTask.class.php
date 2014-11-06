<?php

class temposConfigurenetworkTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
    ));

    $this->namespace        = 'tempos';
    $this->name             = 'configure-network';
    $this->briefDescription = 'Configure the network interfaces';
    $this->detailedDescription = <<<EOF
The [tempos:configure-network|INFO] task configure network interfaces when needed.

  [php symfony tempos:configure-network|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
		$this->configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'prod', true);
		$this->context = sfContext::createInstance($this->configuration);

		ConfigurationHelper::load();

		if (ConfigurationHelper::getParameter('Network', '_need_update', false))
		{
			$this->logSection('tempos', 'Update required.', 1024);

			if ($this->writeInterfaces())
			{
				ConfigurationHelper::setParameter('Network', '_need_update', false);
				ConfigurationHelper::save();
			}
		} else
		{
			$this->logSection('tempos', 'No update required.', 1024);
		}
  }

	protected function writeInterfaces()
	{
		$network_interface = sfConfig::get('app_network_interface');
		$default_network_interfaces_file = sfConfig::get('app_default_network_interfaces_file', '/etc/network/interfaces');

		$values = ConfigurationHelper::getNamespace('Network');

		$ipv4_type = $values['ipv4_type'];

		if (empty($network_interface))
		{
			$this->logSection('tempos', 'No network interface defined in app.yml !', 1024, 'ERROR');
			return false;
		}

		if (!is_writeable($default_network_interfaces_file))
		{
			$this->logSection('tempos', sprintf('Cannot write to "%s".', $default_network_interfaces_file), 1024, 'ERROR');
			return false;
		}

		$this->logSection('tempos', sprintf('IPv4 type is: "%s".', $ipv4_type), 1024);

		if ($ipv4_type == 'system')
		{
			$this->logSection('tempos', 'Nothing to do.', 1024);
			return true;
		}

		$template = array();
		$template[] = sprintf('iface %s inet %s', $network_interface, $ipv4_type);

		if ($ipv4_type == 'static')
		{
			$ipv4_address = $values['ipv4_address'];
			$ipv4_netmask = $values['ipv4_netmask'];
			$ipv4_gateway = $values['ipv4_gateway'];
			
			$template[] = sprintf("\taddress %s", $ipv4_address);
			$template[] = sprintf("\tnetmask %s", $ipv4_netmask);
			$template[] = sprintf("\tgateway %s", $ipv4_gateway);
		}

		$template[] = "";
		$template[] = "";

		$file = file($default_network_interfaces_file);
		$newfile = array();

		$add_mode = true;

		foreach($file as $line)
		{
			if ($add_mode)
			{
				if (preg_match(sprintf('/^[\t]*iface[ \t]+%s/', $network_interface), $line))
				{
					$add_mode = false;
					$newfile[] = implode("\n", $template);
				} else
				{
					$newfile[] = $line;
				}
			} else
			{
				if (preg_match('/^[ \t]*(iface|mapping|auto|allow-)/', $line))
				{
					$add_mode = true;
					$newfile[] = $line;
				}
			}
		}

		$fp = fopen($default_network_interfaces_file, 'w');

		if ($fp)
		{
			fwrite($fp, implode('', $newfile));
			fclose($fp);
		} else
		{
			$this->logSection('tempos', sprintf('Cannot open "%s" for writing.', $default_network_interfaces_file), 1024, 'ERROR');
			return false;
		}

		return true;
	}
}
