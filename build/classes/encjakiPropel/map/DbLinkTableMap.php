<?php



/**
 * This class defines the structure of the 'link' table.
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
class DbLinkTableMap extends TableMap
{

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'encjakiPropel.map.DbLinkTableMap';

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
		$this->setName('link');
		$this->setPhpName('DbLink');
		$this->setClassname('DbLink');
		$this->setPackage('encjakiPropel');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('LINKID', 'Linkid', 'BIGINT', true, null, null);
		$this->addColumn('LANGUAGE', 'Language', 'CHAR', true, 2, 'pl');
		$this->addColumn('NAME', 'Name', 'VARCHAR', true, 255, null);
		$this->addColumn('LINK', 'Link', 'VARCHAR', true, 255, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
	} // buildRelations()

} // DbLinkTableMap
