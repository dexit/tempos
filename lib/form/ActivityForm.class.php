<?php

/**
 * Activity form.
 *
 * @package    tempos
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class ActivityForm extends BaseActivityForm
{
  public function configure()
  {
		// Sort
		$roomSortCriteria = new Criteria();
		$roomSortCriteria->addAscendingOrderByColumn(RoomPeer::NAME);

		$featureSortCriteria = new Criteria();
		$featureSortCriteria->addAscendingOrderByColumn(FeaturePeer::NAME);

		// Layout
		$this->widgetSchema['room_has_activity_list']->setOption('expanded', true);
		$this->widgetSchema['room_has_activity_list']->setOption('criteria', $roomSortCriteria);
		$this->widgetSchema['activity_has_feature_list']->setOption('expanded', true);
		$this->widgetSchema['activity_has_feature_list']->setOption('criteria', $featureSortCriteria);

		// Validators
    $this->validatorSchema['name'] =  new sfXSSValidatorString(array('max_length' => 64));
    $this->validatorSchema['color'] =  new sfXSSValidatorString(array('max_length' => 16));
		$this->validatorSchema['minimum_occupation']->setOption('min', 1);
		$this->validatorSchema['maximum_occupation']->setOption('min', 1);
		$this->validatorSchema['minimum_delay']->setOption('min', 0);

		// Labels
		$this->widgetSchema->setLabel('room_has_activity_list', 'Rooms');
		$this->widgetSchema->setLabel('activity_has_feature_list', 'Features');
		$this->widgetSchema->setLabel('minimum_delay', 'Minimum delay (minutes)');


		// Post-validators
		$this->validatorSchema->setPostValidator(
			new sfValidatorAnd(array(
				new sfValidatorPropelUnique(array('model' => 'Activity', 'column' => array('name'))),
				new sfValidatorSchemaCompare('maximum_occupation', sfValidatorSchemaCompare::GREATER_THAN_EQUAL, 'minimum_occupation', array(),
					array(
						'invalid' => 'The maximum occupation must be greater than or equal to the minimum occupation.',
					)
				)
			))
		);
  }
}
