<?php


/**
 * This class defines the structure of the 'Room_has_EnergyAction' table.
 *
 *
 * This class was autogenerated by Propel 1.4.2 on:
 *
 * Thu Sep 30 06:33:12 2010
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    lib.model.map
 */
class RoomHasEnergyactionTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.RoomHasEnergyactionTableMap';

	/**
	 * Initialize the table attributes, columns and validators
	 * Relations are not initialized by this method since they are lazy loaded
	 *
	 * @return     void
	 * @throws     PropelException
	 */
	public function initialize()
	{
	  // attributes
		$this->setName('Room_has_EnergyAction');
		$this->setPhpName('RoomHasEnergyaction');
		$this->setClassname('RoomHasEnergyaction');
		$this->setPackage('lib.model');
		$this->setUseIdGenerator(false);
		// columns
		$this->addForeignPrimaryKey('ROOM_ID', 'RoomId', 'INTEGER' , 'Room', 'ID', true, null, null);
		$this->addForeignPrimaryKey('ENERGYACTION_ID', 'EnergyactionId', 'INTEGER' , 'EnergyAction', 'ID', true, null, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('Room', 'Room', RelationMap::MANY_TO_ONE, array('Room_id' => 'id', ), 'CASCADE', null);
    $this->addRelation('Energyaction', 'Energyaction', RelationMap::MANY_TO_ONE, array('EnergyAction_id' => 'id', ), 'CASCADE', null);
	} // buildRelations()

} // RoomHasEnergyactionTableMap
