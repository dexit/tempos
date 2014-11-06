<?php

/**
 * Message form.
 *
 * @package    tempos
 * @subpackage form
 * @author     ISLOG
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class MessageForm extends BaseMessageForm
{
  public function configure()
  {
		unset($this['recipient_id']);
		unset($this['sender_id']);
		unset($this['sender']);
		unset($this['created_at']);
		unset($this['was_read']);
		unset($this['owner_id']);

		$this->widgetSchema->setLabel('subject', 'Subject');
		$this->widgetSchema->setLabel('text', 'Message');
		$this->validatorSchema['subject'] =  new sfXSSValidatorString(array('max_length' => 256, 'required' => true));
		$this->validatorSchema['text'] =  new sfXSSValidatorString(array('max_length' => 8192, 'required' => true));
  }

	public function setRecipient($user)
	{
		$this->getObject()->setRecipientId($user->getId());
		$this->getObject()->setOwnerId($user->getId());
	}
	
	public function setSender($user)
	{
		$this->getObject()->setSenderId($user->getId());
		$this->getObject()->setSender($user->getFullName());
	}

	public function setSubject($subject)
	{
		$this->getObject()->setSubject($subject);
		$this->setDefault('subject', $subject);
	}

	public function setText($text)
	{
		$this->getObject()->setText($text);
		$this->setDefault('text', $text);
	}

	protected function doSave($con = null)
	{
		$message = parent::doSave($con);

		$message = $this->getObject();
		
		/* // Ajout des infos concernant la réservation lors de l'envoi du message
		$message->setText("TEST REUSSI\n\n\n--------------------\n\n\n".$message->getText()); */
				
		$recipient = $message->getRecipientUser();
		$to = $recipient->getEmailAddress();
		$sender = $message->getSenderUser();
		$from = null;

		if (ConfigurationHelper::getParameter('Email', 'use_mail', false))
		{
			if (!empty($to))
			{
				if (!is_null($sender))
				{
					$from = $sender->getEmailAddress();

					if (empty($from))
					{
						$from = null;
					}
				}

				MailHelper::send($to, $message->getSubject(), $message->getText(), $recipient->getFullName(), $from, $message->getSender());
			}
		}

		if (!is_null($sender))
		{
			$messageSent = $message->copy();
			$messageSent->setOwnerId($sender->getId());
			$messageSent->setWasRead(true);
			$messageSent->save();
		}

		return $message;
	}
}
