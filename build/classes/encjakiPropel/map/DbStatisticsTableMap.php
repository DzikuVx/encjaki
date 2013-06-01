<?php



/**
 * This class defines the structure of the 'statistics' table.
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
class DbStatisticsTableMap extends TableMap
{

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'encjakiPropel.map.DbStatisticsTableMap';

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
		$this->setName('statistics');
		$this->setPhpName('DbStatistics');
		$this->setClassname('DbStatistics');
		$this->setPackage('encjakiPropel');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('STATISTICSID', 'Statisticsid', 'BIGINT', true, null, null);
		$this->addForeignKey('USERID', 'Userid', 'BIGINT', 'user', 'USERID', true, null, null);
		$this->addColumn('BYCLASS', 'Byclass', 'VARCHAR', false, 24, null);
		$this->addColumn('TURN', 'Turn', 'INTEGER', true, null, null);
		$this->addColumn('POPULATION', 'Population', 'INTEGER', false, 24, null);
		$this->addColumn('PARAMETER', 'Parameter', 'VARCHAR', false, 32, null);
		$this->addColumn('VALUE', 'Value', 'INTEGER', true, null, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
		$this->addRelation('DbUser', 'DbUser', RelationMap::MANY_TO_ONE, array('UserID' => 'UserID', ), null, null);
	} // buildRelations()

} // DbStatisticsTableMap
