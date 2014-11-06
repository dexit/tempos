<?php

class FeaturevaluePeer extends BaseFeaturevaluePeer
{
	public static function doSelectFromFeature($featureId)
	{
		$c = self::getFromFeatureCriteria($featureId);
		$c->addAscendingOrderByColumn(FeaturevaluePeer::VALUE);

		return self::doSelect($c);
	}

	public static function getFromFeatureCriteria($featureId)
	{
		$c = new Criteria();

		$c->add(FeaturevaluePeer::FEATURE_ID, $featureId, Criteria::EQUAL);

		return $c;
	}
}
