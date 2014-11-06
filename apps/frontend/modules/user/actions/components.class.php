<?php
class userComponents extends sfComponents
{
	public function executeMenuItems()
	{
		$this->visitorsCount = UserPeer::getVisitorsCount();
	}
}
?>
