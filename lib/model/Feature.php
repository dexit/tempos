<?php

class Feature extends BaseFeature
{
	public function __toString()
	{
		return $this->getName();
	}

	public function getActivities()
	{
		$c = new Criteria();

		$c->addJoin(ActivityPeer::ID, ActivityHasFeaturePeer::ACTIVITY_ID);
		$c->add(ActivityHasFeaturePeer::FEATURE_ID, $this->getId(), Criteria::EQUAL);
		$c->addAscendingOrderByColumn(ActivityPeer::NAME);

		return ActivityPeer::doSelect($c);
	}

	public function getFieldName()
	{
		return "feature-".$this->getId();
	}

	public static function getFeatureIdFromFieldName($fieldName)
	{
		$count = 0;

		$result = preg_replace('/feature-(\d+)/', '$1', $fieldName, 1, $count);

		if ($count == 1)
		{
			return $result;
		}

		return null;
	}

	public function getFeaturevalueCriteria()
	{
		$c = new Criteria();

		$c->add(FeaturevaluePeer::FEATURE_ID, $this->getId(), Criteria::EQUAL);

		return $c;
	}

	public function getFeaturevalues($criteria = null, PropelPDO $con = null)
	{
		if (is_null($criteria))
		{
			$criteria = new Criteria();
			$criteria->addAscendingOrderByColumn(FeaturevaluePeer::VALUE);
		}

		return parent::getFeaturevalues($criteria, $con);
	}
}
