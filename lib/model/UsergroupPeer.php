<?php

class UsergroupPeer extends BaseUsergroupPeer
{
	static public function getSortAliases()
	{
		return array(
			'name' => self::NAME,
		);
	}
}
