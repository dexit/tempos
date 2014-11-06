<?php

/**
 * Card Search form.
 *
 * @package    tempos
 * @subpackage form
 * @author     ISLOG
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class CardSearchForm extends sfForm
{
  public function configure()
  {
		$this->setWidgets(array(
			'numberPattern'	=> new sfWidgetFormInput(),
		));

		$this->setValidators(array(
			'numberPattern'	=> new sfValidatorString(array('max_length' => 128)),
		));

		$this->widgetSchema->setLabels(array(
			'numberPattern'	=> 'Card number',
		));
  }
}
