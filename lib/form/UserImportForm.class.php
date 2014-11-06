<?php

/**
 * User Import form.
 *
 * @package    tempos
 * @subpackage form
 * @author     ISLOG
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class UserImportForm extends sfForm
{
	const ADD = 0;
	const REPLACE = 1;

  public function configure()
  {
		$add_method = array(
			self::ADD => sfContext::getInstance()->getI18N()->__('Add'),
			self::REPLACE => sfContext::getInstance()->getI18N()->__('Replace'),
		);

		$this->setWidgets(array(
			'add_method'	=> new sfWidgetFormChoice(array('choices' => $add_method)),
			'file'				=> new sfWidgetFormInputFile(),
			'delimiter'		=> new sfWidgetFormInput(),
			'skip_header'	=> new sfWidgetFormInputCheckbox(),
		));

		$this->setDefault('add_method', self::ADD);

    $this->setValidators(array(
      'add_method'	=> new sfValidatorChoice(array('choices' => array_keys($add_method))),
      'file'				=> new sfValidatorFile(array('required' => true)),
      'delimiter'		=> new sfValidatorString(array('min_length' => 1, 'max_length' => 1, 'required' => true)),
			'skip_header' => new sfValidatorBoolean(),
    ));

		$this->widgetSchema->setLabels(array(
			'add_method'	=> 'Add method',
			'file'	=> 'File',
			'delimiter'	=> 'Delimiter',
			'skip_header'	=> 'Skip header',
		));

		$this->setDefault('delimiter', ';');
		$this->setDefault('skip_header', true);

    $this->widgetSchema->setNameFormat('userImport[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
  }

	public function import($adminUserId)
	{
		$count = 0;
		$add_method = $this->getValue('add_method');
		$file = $this->getValue('file');
		$delimiter = $this->getValue('delimiter');
		$skipHeader = $this->getValue('skipHeader');

		$fp = fopen($file->getTempName(), 'r');

		if ($fp)
		{
			if ($add_method == self::REPLACE)
			{
				$c = new Criteria();

				$c->add(UserPeer::ID, $adminUserId, Criteria::NOT_EQUAL);

				UserPeer::doDelete($c);
			}

			while ($data = fgetcsv($fp, 0, $delimiter))
			{
				if (empty($data[0]) || (count($data) < 7))
				{
					continue;
				}

				if ($skipHeader)
				{
					$skipHeader = false;

					continue;
				}

				try
				{
					$user = new User();

					$user->setFamilyName($data[0]);
					$user->setSurname($data[1]);
					$user->setBirthdate($data[2]);
					$user->setCardNumber($data[3]);
					$user->setEmailAddress($data[4]);
					$user->setAddress($data[5]);
					$user->setPhoneNumber($data[6]);
					$user->autoCorrectNames();
					$user->autoSetLogin();

					$user->save();
					++$count;
				}
				catch (Exception $ex)
				{
				}
			}

			fclose($fp);

			return $count;
		}

		return false;
	}
}
