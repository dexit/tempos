<?php

class Message extends BaseMessage
{	
	public function getRecipientUser()
	{
		if (!is_null($this->getRecipientId()))
		{
			return UserPeer::retrieveByPk($this->getRecipientId());
		}

		return null;
	}

	public function getSenderUser()
	{
		if (!is_null($this->getSenderId()))
		{
			return UserPeer::retrieveByPk($this->getSenderId());
		}

		return null;
	}

	public function getOwnerUser()
	{
		if (!is_null($this->getOwnerId()))
		{
			return UserPeer::retrieveByPk($this->getOwnerId());
		}

		return null;
	}

	public function processRead()
	{
		if (!$this->getWasRead())
		{
			$this->setWasRead(true);
			$this->save();
		}
	}

	public function isOwned($userId)
	{
		return ($this->getOwnerId() == $userId);
	}
}
