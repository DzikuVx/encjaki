<?php



/**
 * This class defines the structure of the 'user' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.encjakiPropel.map
 */
class DbUserTableMap extends TableMap
{

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'encjakiPropel.map.DbUserTableMap';

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
		$this->setName('user');
		$this->setPhpName('DbUser');
		$this->setClassname('DbUser');
		$this->setPackage('encjakiPropel');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('USERID', 'Userid', 'BIGINT', true, null, null);
		$this->addColumn('LOGIN', 'Login', 'VARCHAR', true, 255, null);
		$this->addColumn('NAME', 'Name', 'VARCHAR', true, 255, null);
		$this->addColumn('PASSWORD', 'Password', 'VARCHAR', true, 255, null);
		$this->addColumn('LOCKED', 'Locked', 'BOOLEAN', true, 1, false);
		$this->addColumn('CTIME__', 'Ctime', 'TIMESTAMP', false, null, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
		$this->addRelation('DbNews', 'DbNews', RelationMap::ONE_TO_MANY, array('UserID' => 'UserID', ), null, null, 'DbNewss');
		$this->addRelation('DbNews', 'DbStatistics', RelationMap::ONE_TO_MANY, array('UserID' => 'UserID', ), null, null, 'DbNewss');
	} // buildRelations()

} // DbUserTableMap
