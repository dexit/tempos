<?php

class PropelLogic
{
	static public function getIdList($object_list)
	{
		$object_id_list = array();

		foreach ($object_list as $object)
		{
			$object_id_list[] = $object->getId();
		}

		return $object_id_list;
	}

}
