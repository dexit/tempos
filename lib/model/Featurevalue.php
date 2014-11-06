<?php

class Featurevalue extends BaseFeaturevalue
{
	public function __toString()
	{
		return $this->getValue();
	}
}
