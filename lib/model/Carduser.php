<?php

class Carduser extends BaseCarduser
{
	public function __toString()
	{
		return $this->getFullName();
	}

	public function getFullName()
	{
		return sprintf('%s %s', $this->getSurname(), $this->getFamilyName());
	}

	public function isEmpty()
	{
		$surname = $this->getSurname();
		$family_name = $this->getFamilyName();

		return (
			empty($surname) && 
			empty($family_name)
		);
	}
}
