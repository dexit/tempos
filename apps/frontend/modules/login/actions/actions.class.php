<?php

/**
 * login actions.
 *
 * @package    tempos
 * @subpackage login
 * @author     ISLOG
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class loginActions extends sfActions
{
 /**
  * Executes login action
  */
  public function executeLogin(sfWebRequest $request)
  {
		if ($this->getUser()->isAuthenticated()) {
			$this->redirect('home/index');
			$request->setAttribute('referer', $request->getReferer());
		}

		$this->form = new LoginForm();

		$this->loginError = $this->getUser()->hasFlash('loginError');
  }

  public function executeCardLogin(sfWebRequest $request)
  {
		if ($this->getUser()->isAuthenticated()) {
			$this->redirect('home/index');
			$request->setAttribute('referer', $request->getReferer());
		}

		$this->form = new CardLoginForm();

		$this->cardLoginError = $this->getUser()->hasFlash('cardLoginError');
  }

	public function executeRegister(sfWebRequest $request)
	{
		$this->forward404Unless(ConfigurationHelper::getParameter('General', 'allow_registration', true), 'Registration disabled.');

		if ($this->getUser()->isAuthenticated()) {
			$this->redirect('home/index');
		}

		$this->form = new RegisterForm();
	}

	public function executeRegistrationDone(sfWebRequest $request)
	{
	}

	public function executeLoginRequired(sfWebRequest $request)
	{
	}

	public function executeInsufficientCredentials(sfWebRequest $request)
	{
	}

	public function executePrepareLogin(sfWebRequest $request)
	{
		$this->forward404Unless($request->isMethod('post'));

		if (!$this->getUser()->isAuthenticated()) {
			$username = $request->getParameter('username');
			$password = $request->getParameter('password');
            $user = null;

            if (ConfigurationHelper::getParameter('General', 'auth_ldap_choice', false)) {
                // Need to authenticate with LDAP server
                $server = ConfigurationHelper::getParameter('General', 'auth_ldap_host', '');
                $domain = ConfigurationHelper::getParameter('General', 'auth_ldap_domain', '');

                $ldap = @ldap_connect($server); // ex: myldapserver.company.com
                if ($ldap) {
                    ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
                    ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

                    // Process the username for good domain name
                    $ldpa_username = $username . '@' . $domain;     // ex: Administrator@MYDOMAIN.com

                    $bind = @ldap_bind($ldap, $ldpa_username, $password);

                    if ($bind) {
                        $user = UserPeer::retrieveByLogin($username);
                    }

                    ldap_close($ldap); 
                }
            } else {
                $user = UserPeer::authenticate($username, $password);
            }
            
            if (!empty($user) && $user->getIsActive()) {
            
                $this->getUser()->setTemposUser($user);
                $referer = $request->getParameter('referer');

                if (empty($referer)) {
                    $referer = 'home/index';
                }

                $this->redirect($referer);

            } else {
                $this->getUser()->setFlash('loginError', true);
            }
		}

		$this->redirect('login/login');
	}

	public function executePrepareCardLogin(sfWebRequest $request)
	{
		$this->forward404Unless($request->isMethod('post'));

		if (!$this->getUser()->isAuthenticated()) {
			$card_number = $request->getParameter('card_number');
			$pincode = $request->getParameter('pincode');

			$card = CardPeer::authenticate($card_number, $pincode);

			if (!is_null($card)) {
				$this->getUser()->setTemposCard($card);
				
				$referer = $request->getParameter('referer');

				if (empty($referer)) {
					$referer = 'home/index';
				}

				$this->redirect($referer);

			} else {
				$this->getUser()->setFlash('cardLoginError', true);
			}
		}

		$this->redirect('login/cardLogin');
	}

	public function executePrepareLogout(sfWebRequest $request)
	{
		$this->getUser()->setTemposUser(null);
		$this->getUser()->setTemposCard(null);
		$this->getUser()->clearSavedParameters();
		$this->getUser()->getAttributeHolder()->clear();

		$this->redirect('login/login');
	}

	public function executePrepareRegister(sfWebRequest $request)
	{
        $this->forward404Unless($request->isMethod('post'));
        $this->form = new RegisterForm();
		$this->processForm($request, $this->form);
        $this->setTemplate('register');
	}

    protected function processForm(sfWebRequest $request, sfForm $form)
    {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $user = $form->save();
            $this->redirect('login/registrationDone');
        }
    }
}
