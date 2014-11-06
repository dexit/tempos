<?php

class sfValidatorIPv4Address extends sfValidatorString
{
	public function configure($options = array(), $messages = array())
	{
		parent::configure($options, $messages);

		$this->setMessage('invalid', 'Please supply a valid IPv4 address');
	}

  protected function doClean($value)
  {
    $clean = parent::doClean($value);

		if ($clean != long2ip(ip2long($clean)))
		{
    	throw new sfValidatorError($this, 'invalid', array('value' => $value));
		}

		return $clean;
	}
}
