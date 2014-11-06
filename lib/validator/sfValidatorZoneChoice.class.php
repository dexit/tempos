<?php

class sfValidatorZoneChoice extends sfValidatorPropelChoice
{
	public function configure($options = array(), $messages = array())
	{
    $this->addOption('current_zone', null);

		parent::configure($options, $messages);

		$this->addOption('model', 'Zone');
		$this->addOption('column', 'id');

		$this->setMessage('invalid', 'This zone cannot be choosen as parent zone.');
	}

  protected function doClean($value)
  {
		$clean = parent::doClean($value);

		$current_zone = $this->getOption('current_zone');

		if (!is_null($current_zone))
		{
			if ($current_zone->isParentOf($value))
			{
				throw new sfValidatorError($this, 'invalid', array('value' => $value));
			}
		}

		return $clean;
	}
}

?>
