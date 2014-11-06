<?php


/**
 * This class adds structure of 'Reservation' table to 'propel' DatabaseMap object.
 *
 *
 * This class was autogenerated by Propel 1.3.0-dev on:
 *
 * 19/12/2012 10:11:01
 *
 *
 * These statically-built map classes are used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    lib.model.map
 */
class ReservationMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.ReservationMapBuilder';

	/**
	 * The database map.
	 */
	private $dbMap;

	/**
	 * Tells us if this DatabaseMapBuilder is built so that we
	 * don't have to re-build it every time.
	 *
	 * @return     boolean true if this DatabaseMapBuilder is built, false otherwise.
	 */
	public function isBuilt()
	{
		return ($this->dbMap !== null);
	}

	/**
	 * Gets the databasemap this map builder built.
	 *
	 * @return     the databasemap
	 */
	public function getDatabaseMap()
	{
		return $this->dbMap;
	}

	/**
	 * The doBuild() method builds the DatabaseMap
	 *
	 * @return     void
	 * @throws     PropelException
	 */
	public function doBuild()
	{
		$this->dbMap = Propel::getDatabaseMap(ReservationPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(ReservationPeer::TABLE_NAME);
		$tMap->setPhpName('Reservation');
		$tMap->setClassname('Reservation');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, 11);

		$tMap->addForeignKey('ROOMPROFILE_ID', 'RoomprofileId', 'INTEGER', 'RoomProfile', 'ID', true, 11);

		$tMap->addForeignKey('ACTIVITY_ID', 'ActivityId', 'INTEGER', 'Activity', 'ID', true, 11);

		$tMap->addColumn('DATE', 'Date', 'TIMESTAMP', true, null);

		$tMap->addColumn('DURATION', 'Duration', 'INTEGER', true, 11);

		$tMap->addColumn('IS_ACTIVATED', 'IsActivated', 'TINYINT', true, 4);

		$tMap->addForeignKey('RESERVATIONREASON_ID', 'ReservationreasonId', 'INTEGER', 'ReservationReason', 'ID', false, 11);

		$tMap->addColumn('COMMENT', 'Comment', 'VARCHAR', false, 256);

		$tMap->addForeignKey('USERGROUP_ID', 'UsergroupId', 'INTEGER', 'UserGroup', 'ID', false, 11);

		$tMap->addForeignKey('CARD_ID', 'CardId', 'INTEGER', 'Card', 'ID', false, 11);

		$tMap->addForeignKey('USER_ID', 'UserId', 'INTEGER', 'User', 'ID', false, 11);

		$tMap->addForeignKey('RESERVATIONPARENT_ID', 'ReservationparentId', 'INTEGER', 'Reservation', 'ID', false, null);

		$tMap->addColumn('MEMBERS_COUNT', 'MembersCount', 'INTEGER', true, 11);

		$tMap->addColumn('GUESTS_COUNT', 'GuestsCount', 'INTEGER', true, 11);

		$tMap->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null);

		$tMap->addColumn('UPDATED_AT', 'UpdatedAt', 'TIMESTAMP', false, null);

		$tMap->addColumn('STATUS', 'Status', 'INTEGER', true, 11);

		$tMap->addColumn('PRICE', 'Price', 'INTEGER', false, 11);

		$tMap->addColumn('CUSTOM_1', 'Custom1', 'VARCHAR', false, 256);

		$tMap->addColumn('CUSTOM_2', 'Custom2', 'VARCHAR', false, 256);

		$tMap->addColumn('CUSTOM_3', 'Custom3', 'VARCHAR', false, 256);

	} // doBuild()

} // ReservationMapBuilder
