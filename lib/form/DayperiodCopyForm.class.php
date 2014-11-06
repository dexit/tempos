<?php

/**
 * Dayperiod copy form.
 *
 * @package    tempos
 * @subpackage form
 * @author     ISLOG
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class DayperiodCopyForm extends sfForm
{
  public function configure()
  {
		// Sort
		$roomSortCriteria = new Criteria();
		$roomSortCriteria->addAscendingOrderByColumn(RoomPeer::NAME);

		$this->setWidgets(array(
			'copyRoom_id'	=> new sfWidgetFormPropelChoice(array('model' => 'Room', 'add_empty' => false, 'criteria' => $roomSortCriteria)),
		));

		$this->setValidators(array(
      'copyRoom_id'  => new sfValidatorPropelChoice(array('model' => 'Room', 'column' => 'id')),
		));

		$this->widgetSchema->setLabels(array(
			'copyRoom_id'	=> 'Room to copy from: ',
		));

    $this->widgetSchema->setNameFormat('dayperiodCopy[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

  }
}
