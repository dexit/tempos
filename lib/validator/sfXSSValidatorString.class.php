<?php

class sfXSSValidatorString extends sfValidatorString
{
	public function configure($options = array(), $messages = array())
	{
		parent::configure($options, $messages);

		$this->setMessage('invalid', 'This field cannot contain <, > or & characters.');
	}

  protected function doClean($value)
  {
    $clean = parent::doClean($value);

		if ($clean != htmlspecialchars($clean, ENT_NOQUOTES))
		{
    	throw new sfValidatorError($this, 'invalid', array('value' => $value));
		}

		return $clean;
	}
}
