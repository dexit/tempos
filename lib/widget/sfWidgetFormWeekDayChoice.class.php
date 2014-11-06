<?php

/**
 * sfWidgetFormWeekDayChoice represents a week day choice widget.
 *
 * @package    temposnuevo
 * @author     ISLOG <julien.kauffmann@islog.eu>
 */
class sfWidgetFormWeekDayChoice extends sfWidgetFormChoice
{
  /**
   * @see sfWidget
   */
  public function __construct($options = array(), $attributes = array())
  {
    $options['choices'] = new sfCallable(array($this, 'getChoices'));

    parent::__construct($options, $attributes);
  }

  /**
   * Constructor.
   *
   * Available options:
   *
   *  * add_empty:   Whether to add a first empty value or not (false by default)
   *                 If the option is not a Boolean, the value will be used as the text value
   *
   * @see sfWidgetFormSelect
   */
  protected function configure($options = array(), $attributes = array())
  {
    $this->addOption('multiple', false);
    $this->addOption('add_empty', false);

    parent::configure($options, $attributes);
  }

  /**
   * Returns the choices associated.
   *
   * @return array An array of choices
   */
  public function getChoices()
  {
    $choices = array();
   
		$add_empty = $this->getOption('add_empty');

		if ($add_empty === true)
		{
			$choices[''] = __('');
		}

		for ($day = 0; $day < 7; $day++)
		{
			$choices[$day] = Dayperiod::dayOfWeekToName($day);
		}

    return $choices;
  }
}
