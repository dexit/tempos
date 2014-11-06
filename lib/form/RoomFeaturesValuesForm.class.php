<?php

/**
 * Room features values form.
 *
 * @package    tempos
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class RoomFeaturesValuesForm extends BaseFormPropel
{
  public function getModelName()
  {
    return 'Room';
  }

  public function configure()
  {
    $this->setWidgets(array(
      'id'		=> new sfWidgetFormInputHidden(),
		));

    $this->setValidators(array(
      'id'		=> new sfValidatorPropelChoice(array('model' => 'Room', 'column' => 'id', 'required' => false)),
		));

		if (!is_null($this->object))
		{
			$features = $this->object->getFeatures();

			foreach ($features as $feature)
			{
				$this->widgetSchema[$feature->getFieldName()] = new sfWidgetFormPropelChoice(
					array(
						'model' => 'Featurevalue',
						'criteria' => FeaturevaluePeer::getFromFeatureCriteria($feature->getId()),
						'expanded' => true,
						'multiple' => !$feature->getIsExclusive(),
					)
				);

				$this->validatorSchema[$feature->getFieldName()] = new sfValidatorPropelChoiceMany(
					array(
						'model' => 'Featurevalue',
						'multiple' => !$feature->getIsExclusive(),
						'required' => $feature->getIsExclusive(),
					)
				);

				$this->widgetSchema->setLabel($feature->getFieldName(), $feature->getName());

				$values = $this->object->getFeaturevaluesFromFeatureAsIdArray($feature->getId());

				if ($feature->getIsExclusive())
				{
					if (count($values) > 0)
					{
						$this->setDefault($feature->getFieldName(), $values[0]);
					}
				} else
				{
					$this->setDefault($feature->getFieldName(), $values);
				}

			}
		}

    $this->widgetSchema->setNameFormat('room[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  protected function doSave($con = null)
  {
		if (!is_null($this->object))
		{
			$features = $this->object->getFeatures();

			$this->object->clearFeaturevalues();

			foreach ($features as $feature)
			{
				if ($feature->getIsExclusive())
				{
					$value = $this->getValue($feature->getFieldName());
					$values = array($value);
				} else
				{
					$values = $this->getValue($feature->getFieldName());
				}

				$this->object->addFeaturevaluesOfFeature($feature->getId(), $values);
			}
		}

    parent::doSave($con);
	}
}

