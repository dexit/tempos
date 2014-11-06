<?php

class RoomprofilePeer extends BaseRoomprofilePeer
{
	public static function setAllNewIdentifierName($ex_controller_name, $new_controller_name)
	{
		$c = new Criteria();
		$roomprofiles = self::doSelect($c);
		$results = array();
		
		if (is_array($roomprofiles) && !empty($roomprofiles))
		{
			foreach ($roomprofiles as $roomprofile)
			{
				$configured_controller_name = $roomprofile->getConfiguredControllerName();
				
				// print '<br/>----------------------------------------------------<br/>configured_controller_name:<br/>';
				// var_dump($configured_controller_name);
				// print '<br />ex_controller_name:<br />';
				// var_dump($ex_controller_name);
				
				if ($ex_controller_name === $configured_controller_name)
				{
					$profile_name = $roomprofile->getName();
					$new_name = self::buildName($new_controller_name, $profile_name);
					// print '<br />newname:<br />';
					// var_dump($new_name);
					$roomprofile->setName($new_name);
					$results[] = $roomprofile;
				}
			}
		}
		
		return $results;
	}
	
	public static function doNameUpdate($newname, $prevname)
	{
		$size = count(explode(':', $prevname));
		
		if ($size <= 1)
			return buildName($newname, $prevname);
		
		return null;
	}
	
	public static function buildName($controller_name, $prevname)
	{
		$names = explode(':', $prevname);
		$size = count($names);
		
		if (empty($controller_name) || is_null($controller_name))
		{
			return $names[$size-1];
		} else
		{
			return $controller_name.':'.$names[$size-1];
		}
	}
	
	public static function doSelectFromId($id)
	{
		$c = new Criteria();
		$c->setLimit(1);
		$c->add(RoomprofilePeer::ID, $id , Criteria::EQUAL);
		
		$object = self::doSelect($c);
		
		if ($object)
		{
			return $object[0];
		}
		
		return null;
	}
	
	public static function doSelectFromRoom($roomId)
	{
		$c = self::getFromRoomCriteria($roomId);
		$c->addAscendingOrderByColumn(RoomprofilePeer::NAME);

		return self::doSelect($c);
	}
	
	public static function doSelectFromName($name)
	{
		$c = new Criteria();
		$c->add(RoomprofilePeer::NAME, $name, Criteria::EQUAL);

		return self::doSelect($c);
	}

	public static function getFromRoomCriteria($roomId)
	{
		$c = new Criteria();

		$c->add(RoomprofilePeer::ROOM_ID, $roomId, Criteria::EQUAL);
		$c->addAscendingOrderByColumn(RoomprofilePeer::NAME);

		return $c;
	}
}
