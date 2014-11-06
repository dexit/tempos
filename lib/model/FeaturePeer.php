<?php

class FeaturePeer extends BaseFeaturePeer
{
	public static function doSelectAllFeatures()
	{
		$c = new Criteria();
		$c->addAscendingOrderByColumn(self::NAME);

		return self::doSelect($c);
	}

	static public function getSortAliases()
	{
		return array(
			'name' => self::NAME,
			'is_exclusive' => self::IS_EXCLUSIVE,
		);
	}

}
