<?php

/**
 * Room Search form.
 *
 * @package    tempos
 * @subpackage form
 * @author     ISLOG
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class RoomSearchForm extends sfForm
{
	protected $activity;

	public function __construct($activity = null)
	{
		if (is_null($activity))
		{
			$this->activity = null;

			// Was this.
			//throw new InvalidArgumentException('activity parameter cannot be null.');
		} else
		{
			$this->activity = $activity;
		}

		parent::__construct();
	}

  public function configure()
  {
		$active_choices = array(
			null => sfContext::getInstance()->getI18N()->__('Any'),
			true => sfContext::getInstance()->getI18N()->__('Yes'),
			false => sfContext::getInstance()->getI18N()->__('No'),
		);

		$this->setWidgets(array(
			'is_active'	=> new sfWidgetFormChoice(array('choices' => $active_choices)),
			'namePattern'	=> new sfWidgetFormInput(),
			'capacity'	=> new sfWidgetFormInput(),
			'addressPattern'	=> new sfWidgetFormInput(),
			'descriptionPattern'	=> new sfWidgetFormInput(),
		));

		$this->setValidators(array(
      'is_active'   => new sfValidatorChoice(array('choices' => array_keys($active_choices), 'required' => false)),
			'namePattern'	=> new sfValidatorString(array('max_length' => 64, 'required' => false)),
			'capacity'	=> new sfValidatorInteger(array('min' => 0, 'required' => false)),
			'addressPattern'	=> new sfValidatorString(array('max_length' => 256, 'required' => false)),
			'descriptionPattern'	=> new sfValidatorString(array('max_length' => 256, 'required' => false)),
		));

		$this->widgetSchema->setLabels(array(
			'is_active'	=> 'Is active',
			'namePattern'	=> 'Name',
			'capacity'	=> 'Minimal capacity',
			'addressPattern'	=> 'Address',
			'descriptionPattern'	=> 'Description',
		));

		if (is_null($this->activity))
		{
			$this->widgetSchema['Activity_id'] = new sfWidgetFormPropelChoice(array('model' => 'Activity', 'add_empty' => true));
		} else
		{
			$this->widgetSchema['Activity_id'] = new sfWidgetFormInputHidden();
			$this->setDefault('Activity_id', $this->activity->getId());
		}

		$this->validatorSchema['Activity_id'] = new sfValidatorPropelChoice(array('model' => 'Activity', 'required' => false));
		$this->widgetSchema->setLabel('Activity_id', 'Activity');

		foreach ($this->getFeatures() as $feature)
		{
			$field_name = $feature->getFieldName();
			$criteria = $feature->getFeaturevalueCriteria();
			$this->widgetSchema[$field_name] = new sfWidgetFormPropelChoice(array('model' => 'Featurevalue', 'add_empty' => true, 'criteria' => $criteria));
			$this->validatorSchema[$field_name] = new sfValidatorPropelChoiceMany(array('model' => 'Featurevalue', 'required' => false, 'criteria' => $criteria));
			$this->widgetSchema->setLabel($field_name, $feature->getName());
		}

    $this->widgetSchema->setNameFormat('roomSearch[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

  }

	public function getFeaturesFieldsValues()
	{
		$r = array();

		foreach ($this->getFeatures() as $feature)
		{
			$r[$feature->getId()] = $this->getValue($feature->getFieldName(), null);
		}

		return $r;
	}

	public function setIsActive($is_active)
	{
		$this->setDefault('is_active', $is_active);

		$this->widgetSchema['is_active'] = new sfWidgetFormInputHidden();
	}

	protected function getFeatures()
	{
		if (!is_null($this->activity))
		{
			$features = $this->activity->getFeatures();
		} else
		{
			$features = FeaturePeer::doSelectAllFeatures();
		}

		return $features;
	}
}
