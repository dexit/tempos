<?php

class Roomprofile extends BaseRoomprofile
{	
	public function __toString()
	{
		return $this->getName();
	}
	
	public function getConfiguredControllerName()
	{
		$names = explode(':', $this->getName());
		
		return $names[0];
	}
}
