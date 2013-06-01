<?php


/**
 * Base class that represents a query for the 'link' table.
 *
 * 
 *
 * @method     DbLinkQuery orderByLinkid($order = Criteria::ASC) Order by the LinkID column
 * @method     DbLinkQuery orderByLanguage($order = Criteria::ASC) Order by the Language column
 * @method     DbLinkQuery orderByName($order = Criteria::ASC) Order by the Name column
 * @method     DbLinkQuery orderByLink($order = Criteria::ASC) Order by the Link column
 *
 * @method     DbLinkQuery groupByLinkid() Group by the LinkID column
 * @method     DbLinkQuery groupByLanguage() Group by the Language column
 * @method     DbLinkQuery groupByName() Group by the Name column
 * @method     DbLinkQuery groupByLink() Group by the Link column
 *
 * @method     DbLinkQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     DbLinkQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     DbLinkQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     DbLink findOne(PropelPDO $con = null) Return the first DbLink matching the query
 * @method     DbLink findOneOrCreate(PropelPDO $con = null) Return the first DbLink matching the query, or a new DbLink object populated from the query conditions when no match is found
 *
 * @method     DbLink findOneByLinkid(string $LinkID) Return the first DbLink filtered by the LinkID column
 * @method     DbLink findOneByLanguage(string $Language) Return the first DbLink filtered by the Language column
 * @method     DbLink findOneByName(string $Name) Return the first DbLink filtered by the Name column
 * @method     DbLink findOneByLink(string $Link) Return the first DbLink filtered by the Link column
 *
 * @method     array findByLinkid(string $LinkID) Return DbLink objects filtered by the LinkID column
 * @method     array findByLanguage(string $Language) Return DbLink objects filtered by the Language column
 * @method     array findByName(string $Name) Return DbLink objects filtered by the Name column
 * @method     array findByLink(string $Link) Return DbLink objects filtered by the Link column
 *
 * @package    propel.generator.encjakiPropel.om
 */
abstract class BaseDbLinkQuery extends ModelCriteria
{
	
	/**
	 * Initializes internal state of BaseDbLinkQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'encjaki', $modelName = 'DbLink', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new DbLinkQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    DbLinkQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof DbLinkQuery) {
			return $criteria;
		}
		$query = new DbLinkQuery();
		if (null !== $modelAlias) {
			$query->setModelAlias($modelAlias);
		}
		if ($criteria instanceof Criteria) {
			$query->mergeWith($criteria);
		}
		return $query;
	}

	/**
	 * Find object by primary key.
	 * Propel uses the instance pool to skip the database if the object exists.
	 * Go fast if the query is untouched.
	 *
	 * <code>
	 * $obj  = $c->findPk(12, $con);
	 * </code>
	 *
	 * @param     mixed $key Primary key to use for the query
	 * @param     PropelPDO $con an optional connection object
	 *
	 * @return    DbLink|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ($key === null) {
			return null;
		}
		if ((null !== ($obj = DbLinkPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
			// the object is alredy in the instance pool
			return $obj;
		}
		if ($con === null) {
			$con = Propel::getConnection(DbLinkPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
		$this->basePreSelect($con);
		if ($this->formatter || $this->modelAlias || $this->with || $this->select
		 || $this->selectColumns || $this->asColumns || $this->selectModifiers
		 || $this->map || $this->having || $this->joins) {
			return $this->findPkComplex($key, $con);
		} else {
			return $this->findPkSimple($key, $con);
		}
	}

	/**
	 * Find object by primary key using raw SQL to go fast.
	 * Bypass doSelect() and the object formatter by using generated code.
	 *
	 * @param     mixed $key Primary key to use for the query
	 * @param     PropelPDO $con A connection object
	 *
	 * @return    DbLink A model object, or null if the key is not found
	 */
	protected function findPkSimple($key, $con)
	{
		$sql = 'SELECT `LINKID`, `LANGUAGE`, `NAME`, `LINK` FROM `link` WHERE `LINKID` = :p0';
		try {
			$stmt = $con->prepare($sql);
			$stmt->bindValue(':p0', $key, PDO::PARAM_INT);
			$stmt->execute();
		} catch (Exception $e) {
			Propel::log($e->getMessage(), Propel::LOG_ERR);
			throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
		}
		$obj = null;
		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$obj = new DbLink();
			$obj->hydrate($row);
			DbLinkPeer::addInstanceToPool($obj, (string) $row[0]);
		}
		$stmt->closeCursor();

		return $obj;
	}

	/**
	 * Find object by primary key.
	 *
	 * @param     mixed $key Primary key to use for the query
	 * @param     PropelPDO $con A connection object
	 *
	 * @return    DbLink|array|mixed the result, formatted by the current formatter
	 */
	protected function findPkComplex($key, $con)
	{
		// As the query uses a PK condition, no limit(1) is necessary.
		$criteria = $this->isKeepQuery() ? clone $this : $this;
		$stmt = $criteria
			->filterByPrimaryKey($key)
			->doSelect($con);
		return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
	}

	/**
	 * Find objects by primary key
	 * <code>
	 * $objs = $c->findPks(array(12, 56, 832), $con);
	 * </code>
	 * @param     array $keys Primary keys to use for the query
	 * @param     PropelPDO $con an optional connection object
	 *
	 * @return    PropelObjectCollection|array|mixed the list of results, formatted by the current formatter
	 */
	public function findPks($keys, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection($this->getDbName(), Propel::CONNECTION_READ);
		}
		$this->basePreSelect($con);
		$criteria = $this->isKeepQuery() ? clone $this : $this;
		$stmt = $criteria
			->filterByPrimaryKeys($keys)
			->doSelect($con);
		return $criteria->getFormatter()->init($criteria)->format($stmt);
	}

	/**
	 * Filter the query by primary key
	 *
	 * @param     mixed $key Primary key to use for the query
	 *
	 * @return    DbLinkQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(DbLinkPeer::LINKID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    DbLinkQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(DbLinkPeer::LINKID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the LinkID column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByLinkid(1234); // WHERE LinkID = 1234
	 * $query->filterByLinkid(array(12, 34)); // WHERE LinkID IN (12, 34)
	 * $query->filterByLinkid(array('min' => 12)); // WHERE LinkID > 12
	 * </code>
	 *
	 * @param     mixed $linkid The value to use as filter.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DbLinkQuery The current query, for fluid interface
	 */
	public function filterByLinkid($linkid = null, $comparison = null)
	{
		if (is_array($linkid) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(DbLinkPeer::LINKID, $linkid, $comparison);
	}

	/**
	 * Filter the query on the Language column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByLanguage('fooValue');   // WHERE Language = 'fooValue'
	 * $query->filterByLanguage('%fooValue%'); // WHERE Language LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $language The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DbLinkQuery The current query, for fluid interface
	 */
	public function filterByLanguage($language = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($language)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $language)) {
				$language = str_replace('*', '%', $language);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(DbLinkPeer::LANGUAGE, $language, $comparison);
	}

	/**
	 * Filter the query on the Name column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByName('fooValue');   // WHERE Name = 'fooValue'
	 * $query->filterByName('%fooValue%'); // WHERE Name LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $name The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DbLinkQuery The current query, for fluid interface
	 */
	public function filterByName($name = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($name)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $name)) {
				$name = str_replace('*', '%', $name);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(DbLinkPeer::NAME, $name, $comparison);
	}

	/**
	 * Filter the query on the Link column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByLink('fooValue');   // WHERE Link = 'fooValue'
	 * $query->filterByLink('%fooValue%'); // WHERE Link LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $link The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DbLinkQuery The current query, for fluid interface
	 */
	public function filterByLink($link = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($link)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $link)) {
				$link = str_replace('*', '%', $link);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(DbLinkPeer::LINK, $link, $comparison);
	}

	/**
	 * Exclude object from result
	 *
	 * @param     DbLink $dbLink Object to remove from the list of results
	 *
	 * @return    DbLinkQuery The current query, for fluid interface
	 */
	public function prune($dbLink = null)
	{
		if ($dbLink) {
			$this->addUsingAlias(DbLinkPeer::LINKID, $dbLink->getLinkid(), Criteria::NOT_EQUAL);
		}

		return $this;
	}

} // BaseDbLinkQuery