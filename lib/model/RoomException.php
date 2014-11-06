<?php

class RoomException extends Exception
{
	protected $room = null;
	protected $originalMessage = null;

	public function __construct(Room $room, $message = null, $code = 0)
	{
		$this->room = $room;
		$this->originalMessage = $message;

		parent::__construct(self::getNewMessage($room, $message), $code);
	}

	protected function getNewMessage($message)
	{
		return sprintf('%s: %s', $this->room->getName(), $message);
	}

	public function getRoom()
	{
		return $this->room;
	}

	public function getOriginalMessage()
	{
		return $this->originalMessage;
	}
}
