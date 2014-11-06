<?php

/**
 * configuration actions.
 *
 * @package    tempos
 * @subpackage configuration
 * @author     ISLOG
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class configurationActions extends sfActions
{
	/**
	* Executes home action
	*
	* @param sfRequest $request A request object
	*/
	public function executeHome(sfWebRequest $request)
	{
		$this->physical_access_controllers = BasePhysicalAccessController::getControllers();
		$this->home_automation_controllers = BaseHomeAutomationController::getControllers();
		$this->nb_controller = ConfigurationHelper::getParameter(null, 'number_of_physical_access');
		$this->nb_home_automation = ConfigurationHelper::getParameter(null, 'number_of_home_automation');
		$this->form = new ConfigurationForm();
	}

	public function executeUpdate(sfWebRequest $request)
	{
		$this->forward404Unless($request->isMethod('post'));
		
		$post_parameters = $request->getParameter('configuration');
		$this->nb_controller = intval($post_parameters['number_of_physical_access']);
		$this->nb_home_automation = intval($post_parameters['number_of_home_automation']);
		$this->physical_access_controllers = BasePhysicalAccessController::getControllers();
		$this->home_automation_controllers = BaseHomeAutomationController::getControllers();
		
		$this->form = new ConfigurationForm(null, array('nb_pac' => $this->nb_controller, 'nb_hac' => $this->nb_home_automation));
		
		if ($this->nb_controller > $this->form->getMaxControllers())
			$this->nb_controller = $this->form->getMaxControllers();
		
		if ($this->nb_home_automation > $this->form->getMaxHomeAutomation())
			$this->nb_home_automation = $this->form->getMaxHomeAutomation();
		
		// Gestion des contrôleurs d'accès supplémentaires et de leurs valeurs par défaut (si l'on augmente le nombre de contrôleurs d'accès)
		for ($i = 1; $i <= $this->nb_controller; $i++)
		{
			if (!isset($post_parameters['physical_access_controller'.$i]))
			{
				foreach ($this->physical_access_controllers as $pac => $name)
				{
					$controller = BasePhysicalAccessController::create($pac);
					$post_parameters[$pac.$i] = $controller->getDefaultValues();
					// On modifie le nom identifiant par défaut et on y ajoute i
					$post_parameters[$pac.$i]['controller_name'] = $controller->getName().$i;
				}
			}
		}
		
		// Gestion des contrôleurs domotiques supplémentaires et de leurs valeurs par défaut (si l'on augmente le nombre de contrôleurs domotiques)
		for ($i = 1; $i <= $this->nb_home_automation; $i++)
		{
			if (!isset($post_parameters['home_automation_controller'.$i]))
			{
				foreach ($this->home_automation_controllers as $hac => $name)
				{
					$controller = BaseHomeAutomationController::create($hac);
					$post_parameters[$hac.$i] = $controller->getDefaultValues();
					// On modifie le nom identifiant par défaut et on y ajoute i
					$post_parameters[$hac.$i]['controller_name'] = $controller->getName().$i;
				}
			}
		}
		
		// Gestion des contrôleurs d'accès en trop (si l'on réduit le nombre de contrôleurs d'accès)
		for ($i = $this->nb_controller + 1; $i <= $this->form->getMaxControllers(); $i++)
		{
			if (isset($post_parameters['physical_access_controller'.$i]))
			{
				unset($post_parameters['physical_access_controller'.$i]);
				foreach ($this->physical_access_controllers as $pac => $name)
				{
					unset($post_parameters[$pac.$i]);
				}
			}
		}
		
		// Gestion des contrôleurs domotiques en trop (si l'on réduit le nombre de contrôleurs domotiques)
		for ($i = $this->nb_home_automation + 1; $i <= $this->form->getMaxHomeAutomation(); $i++)
		{
			if (isset($post_parameters['home_automation_controller'.$i]))
			{
				unset($post_parameters['home_automation_controller'.$i]);
				foreach ($this->home_automation_controllers as $hac => $name)
				{
					unset($post_parameters[$hac.$i]);
				}
			}
		}
		
		$this->form->bind($post_parameters, $request->getFiles($this->form->getName()));
		if ($this->form->isValid())
		{
			$isout = false;
			
			// Modifie les noms de chaque profil d'accès si un nom identifiant change et vérifie que chaque nom est unique
			if (!empty($this->physical_access_controllers))
			{
				for ($i = 1; $i <= $this->nb_controller; $i++)
				{
					foreach ($this->physical_access_controllers as $key => $value)
					{
						$roomprofiles = array();
						$controller_name = $key.$i;
						// print  '<br/>------------------------------<br/>'.$controller_name.'<br />';
						$params = $post_parameters[$controller_name];
											
						$exname = ConfigurationHelper::getParameter($controller_name, 'controller_name');
						$newname = $params['controller_name'];
						
						// print 'ex-new : '.$exname.' - '.$newname.'<br />';
						
						if ($exname != $newname)
						{
							$roomprofiles = RoomprofilePeer::setAllNewIdentifierName($exname, $newname);
						}
						
						$this->checkError = ConfigurationHelper::checkControllersIdentifierName($controller_name, $newname, $this->nb_controller);
						
						if (!$this->checkError['valid'])
						{
							$isout = true;
							break;
						}
						
						if (isset($roomprofiles))
						{
							if (!empty($roomprofiles))
							{
								foreach ($roomprofiles as $roomprofile)
								{
									$roomprofile->save();
								}
							}
						}
					}
					
					if ($isout)
					{
						break;
					}
				}
			}
			
			// Modifie les noms de chaque contrôleur domotique si un nom identifiant change et vérifie que chaque nom est unique
			if (!empty($this->home_automation_controllers) && !$isout)
			{
				for ($i = 1; $i <= $this->nb_home_automation; $i++)
				{
					foreach ($this->home_automation_controllers as $key => $value)
					{
						$energyactions = array();
						$controller_name = $key.$i;
						// print  '<br/>------------------------------<br/>'.$controller_name.'<br />';
						$params = $post_parameters[$controller_name];
											
						$exname = ConfigurationHelper::getParameter($controller_name, 'controller_name');
						$newname = $params['controller_name'];
						
						// print 'ex-new : '.$exname.' - '.$newname.'<br />';
						
						if ($exname != $newname)
						{
							$energyactions = EnergyactionPeer::setAllNewIdentifierName($exname, $newname);
						}
						
						$this->checkError = ConfigurationHelper::checkAutomationsIdentifierName($controller_name, $newname, $this->nb_home_automation);
						
						if (!$this->checkError['valid'])
						{
							$isout = true;
							break;
						}
						
						if (isset($energyactions))
						{
							if (!empty($energyactions))
							{
								foreach ($energyactions as $energyaction)
								{
									$energyaction->save();
								}
							}
						}
					}
					
					if ($isout)
					{
						break;
					}
				}
			}
			
			if (!$isout)
			{
				$this->form->save();
				$this->saved = true;
			} else
			{
				$this->saved = false;
			}
		} else
		{
			$this->saved = false;
		}
		
		$this->setTemplate('home');
	}
}
