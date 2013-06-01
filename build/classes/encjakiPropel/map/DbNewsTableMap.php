<?php



/**
 * This class defines the structure of the 'news' table.
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
class DbNewsTableMap extends TableMap
{

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'encjakiPropel.map.DbNewsTableMap';

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
		$this->setName('news');
		$this->setPhpName('DbNews');
		$this->setClassname('DbNews');
		$this->setPackage('encjakiPropel');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('NEWSID', 'Newsid', 'BIGINT', true, null, null);
		$this->addForeignKey('USERID', 'Userid', 'BIGINT', 'user', 'USERID', true, null, null);
		$this->addColumn('CTIME__', 'Ctime', 'TIMESTAMP', false, null, null);
		$this->addColumn('TITLE', 'Title', 'VARCHAR', true, 255, null);
		$this->addColumn('TEXT', 'Text', 'CLOB', true, null, null);
		$this->addColumn('PUBLISHED', 'Published', 'BOOLEAN', true, 1, false);
		$this->addColumn('TYPE', 'Type', 'VARCHAR', true, 32, 'normal');
		$this->addColumn('LANGUAGE', 'Language', 'CHAR', true, 2, 'pl');
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
		$this->addRelation('DbUser', 'DbUser', RelationMap::MANY_TO_ONE, array('UserID' => 'UserID', ), null, null);
	} // buildRelations()

} // DbNewsTableMap
