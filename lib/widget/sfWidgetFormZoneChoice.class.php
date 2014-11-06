<?php

/**
 * sfWidgetFormZoneChoice represents a zone choice widget for a model.
 *
 * @package    temposnuevo
 * @author     ISLOG <julien.kauffmann@islog.eu>
 */
class sfWidgetFormZoneChoice extends sfWidgetFormTree
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
   *  * model:       The model class (required)
   *  * add_empty:   Whether to add a first empty value or not (false by default)
   *                 If the option is not a Boolean, the value will be used as the text value
   *  * method:      The method to use to display object values (__toString by default)
   *  * key_method:  The method to use to display the object keys (getPrimaryKey by default) 
   *  * order_by:    An array composed of two fields:
   *                   * The column to order by the results (must be in the PhpName format)
   *                   * asc or desc
   *  * criteria:    A criteria to use when retrieving objects
   *  * connection:  The Propel connection to use (null by default)
   *  * multiple:    true if the select tag must allow multiple selections
   *  * peer_method: The peer method to use to fetch objects
   *
   * @see sfWidgetFormSelect
   */
  protected function configure($options = array(), $attributes = array())
  {
    $this->addOption('multiple', false);
    $this->addOption('add_empty', true);
    $this->addOption('current_zone', null);

    parent::configure($options, $attributes);
  }

  /**
   * Returns the choices associated to the model.
   *
   * @return array An array of choices
   */
  public function getChoices()
  {
    $choices = array();
   
		$add_empty = $this->getOption('add_empty');

		if ($add_empty === true)
		{
			$choices[''] = array('level' => 0, 'label' => __('Root'));
		}

    $root_zones = ZonePeer::doSelectRoot();
		$current_zone = $this->getOption('current_zone', null);

    foreach ($root_zones as $root_zone)
    {
			$this->addZoneChoice($choices, $root_zone, $current_zone, $add_empty ? 1 : 0);
    }

    return $choices;
  }

	private function addZoneChoice(&$choices, $zone, $current_zone, $recursion_level = 0)
	{
		if (!is_null($current_zone))
		{
			if ($zone->isChildOfObject($current_zone))
			{
				return;
			}
		}

		$choices[$zone->getId()] = array('level' => $recursion_level, 'label' => $zone->__toString());

		$child_zones = $zone->getChildrenZoneObjects();

		foreach($child_zones as $child_zone)
		{
			$this->addZoneChoice($choices, $child_zone, $current_zone, $recursion_level + 1);
		}
	}
}
