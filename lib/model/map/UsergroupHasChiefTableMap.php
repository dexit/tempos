<?php


/**
 * This class defines the structure of the 'UserGroup_has_Chief' table.
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
class UsergroupHasChiefTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.UsergroupHasChiefTableMap';

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
		$this->setName('UserGroup_has_Chief');
		$this->setPhpName('UsergroupHasChief');
		$this->setClassname('UsergroupHasChief');
		$this->setPackage('lib.model');
		$this->setUseIdGenerator(false);
		// columns
		$this->addForeignPrimaryKey('USERGROUP_ID', 'UsergroupId', 'INTEGER' , 'UserGroup', 'ID', true, null, null);
		$this->addForeignPrimaryKey('USER_ID', 'UserId', 'INTEGER' , 'User', 'ID', true, null, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('Usergroup', 'Usergroup', RelationMap::MANY_TO_ONE, array('UserGroup_id' => 'id', ), 'CASCADE', null);
    $this->addRelation('User', 'User', RelationMap::MANY_TO_ONE, array('User_id' => 'id', ), 'CASCADE', null);
	} // buildRelations()

} // UsergroupHasChiefTableMap
