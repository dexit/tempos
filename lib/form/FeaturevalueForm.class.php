<?php

/**
 * Featurevalue form.
 *
 * @package    tempos
 * @subpackage form
 * @author     ISLOG
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class FeaturevalueForm extends BaseFeaturevalueForm
{
	protected $feature = null;

	public function __construct(Featurevalue $featurevalue = null, Feature $feature = null)
	{
		if (is_null($feature))
		{
			if (is_null($featurevalue))
			{
				throw new Exception('Cannot create a '.__CLASS__.' without featurevalue and feature');
			} else
			{
				$this->feature = $featurevalue->getFeature();
			}
		} else
		{
			$this->feature = $feature;

			if (!is_null($featurevalue))
			{
				$featurevalue->setFeature($this->feature);
			}
		}

		parent::__construct($featurevalue);
	}

  public function configure()
  {
		unset($this['room_has_featurevalue_list']);

		if (!is_null($this->feature))
		{
			$this->widgetSchema['Feature_id'] = new sfWidgetFormInputHidden();
			$this->setDefault('Feature_id', $this->feature->getId());
		}

		// Validators
    $this->validatorSchema['value'] =  new sfXSSValidatorString(array('max_length' => 128));
  }

	public function getFeature()
	{
		return $this->feature;
	}
}
