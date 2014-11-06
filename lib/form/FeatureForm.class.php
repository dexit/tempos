<?php

/**
* Feature form.
*
* @package    tempos
* @subpackage form
* @author     ISLOG
* @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
*/
class FeatureForm extends BaseFeatureForm
{
	public function configure()
	{
		// Sort
		$sortCriteria = new Criteria();
		$sortCriteria->addAscendingOrderByColumn(ActivityPeer::NAME);

		// Layout
		$this->widgetSchema['activity_has_feature_list']->setOption('expanded', true);
		$this->widgetSchema['activity_has_feature_list']->setOption('criteria', $sortCriteria);

		// Validators
		$this->validatorSchema['name'] =  new sfXSSValidatorString(array('max_length' => 128));

		// Labels
		$activityItem = ConfigurationHelper::getParameter('Rename', 'activity_label');
		if (is_null($activityItem) || empty($activityItem))
		{
			$activityItem = 'Activities';
		}
		$this->widgetSchema->setLabel('activity_has_feature_list', $activityItem);

		// Post-validators
		$this->validatorSchema->setPostValidator(
		new sfValidatorAnd(array(
		new sfValidatorPropelUnique(array('model' => 'Feature', 'column' => array('name'))),
		))
		);
	}
}
