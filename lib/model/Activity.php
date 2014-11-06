<?php

class Activity extends BaseActivity
{
	public function __toString()
	{
		return $this->getName();
	}

	public function getNameAndOccupation($capacity = null)
	{
		if (!$this->isCapacitySuitable($capacity))
		{
			return sprintf('%s (%s-%s)', $this->getName(), $this->getMinimumOccupation(), $this->getMaximumOccupation());
		} else
		{
			return $this->getName();
		}
	}

	public function isCapacitySuitable($capacity)
	{
		if (is_null($capacity))
		{
			return true;
		} else
		{
			return ($this->getMaximumOccupation() <= $capacity);
		}
	}

	public function getFeatures()
	{
		$c = new Criteria();

		$c->addJoin(FeaturePeer::ID, ActivityHasFeaturePeer::FEATURE_ID);
		$c->add(ActivityHasFeaturePeer::ACTIVITY_ID, $this->getId());
		$c->addAscendingOrderByColumn(FeaturePeer::NAME);

		return FeaturePeer::doSelect($c);
	}

	public function getMinimumDate($tst = null)
	{
		if (empty($tst))
		{
			$tst = time();
		}

		return strtotime(date('Y-m-d H:i:s', $tst).' + '.$this->getMinimumDelay(). ' minutes');
	}

	public function getReservationreasons($criteria = null, PropelPDO $con = null)
	{
		if (is_null($criteria))
		{
			$criteria = new Criteria();
			$criteria->addAscendingOrderByColumn(ReservationreasonPeer::NAME);
		}

		return parent::getReservationreasons($criteria, $con);
	}
}
