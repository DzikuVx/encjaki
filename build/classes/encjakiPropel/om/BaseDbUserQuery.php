<?php


/**
 * Base class that represents a query for the 'user' table.
 *
 * 
 *
 * @method     DbUserQuery orderByUserid($order = Criteria::ASC) Order by the UserID column
 * @method     DbUserQuery orderByLogin($order = Criteria::ASC) Order by the Login column
 * @method     DbUserQuery orderByName($order = Criteria::ASC) Order by the Name column
 * @method     DbUserQuery orderByPassword($order = Criteria::ASC) Order by the Password column
 * @method     DbUserQuery orderByLocked($order = Criteria::ASC) Order by the Locked column
 * @method     DbUserQuery orderByCtime($order = Criteria::ASC) Order by the ctime__ column
 *
 * @method     DbUserQuery groupByUserid() Group by the UserID column
 * @method     DbUserQuery groupByLogin() Group by the Login column
 * @method     DbUserQuery groupByName() Group by the Name column
 * @method     DbUserQuery groupByPassword() Group by the Password column
 * @method     DbUserQuery groupByLocked() Group by the Locked column
 * @method     DbUserQuery groupByCtime() Group by the ctime__ column
 *
 * @method     DbUserQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     DbUserQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     DbUserQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     DbUserQuery leftJoinDbNews($relationAlias = null) Adds a LEFT JOIN clause to the query using the DbNews relation
 * @method     DbUserQuery rightJoinDbNews($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DbNews relation
 * @method     DbUserQuery innerJoinDbNews($relationAlias = null) Adds a INNER JOIN clause to the query using the DbNews relation
 *
 * @method     DbUserQuery leftJoinDbNews($relationAlias = null) Adds a LEFT JOIN clause to the query using the DbNews relation
 * @method     DbUserQuery rightJoinDbNews($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DbNews relation
 * @method     DbUserQuery innerJoinDbNews($relationAlias = null) Adds a INNER JOIN clause to the query using the DbNews relation
 *
 * @method     DbUser findOne(PropelPDO $con = null) Return the first DbUser matching the query
 * @method     DbUser findOneOrCreate(PropelPDO $con = null) Return the first DbUser matching the query, or a new DbUser object populated from the query conditions when no match is found
 *
 * @method     DbUser findOneByUserid(string $UserID) Return the first DbUser filtered by the UserID column
 * @method     DbUser findOneByLogin(string $Login) Return the first DbUser filtered by the Login column
 * @method     DbUser findOneByName(string $Name) Return the first DbUser filtered by the Name column
 * @method     DbUser findOneByPassword(string $Password) Return the first DbUser filtered by the Password column
 * @method     DbUser findOneByLocked(boolean $Locked) Return the first DbUser filtered by the Locked column
 * @method     DbUser findOneByCtime(string $ctime__) Return the first DbUser filtered by the ctime__ column
 *
 * @method     array findByUserid(string $UserID) Return DbUser objects filtered by the UserID column
 * @method     array findByLogin(string $Login) Return DbUser objects filtered by the Login column
 * @method     array findByName(string $Name) Return DbUser objects filtered by the Name column
 * @method     array findByPassword(string $Password) Return DbUser objects filtered by the Password column
 * @method     array findByLocked(boolean $Locked) Return DbUser objects filtered by the Locked column
 * @method     array findByCtime(string $ctime__) Return DbUser objects filtered by the ctime__ column
 *
 * @package    propel.generator.encjakiPropel.om
 */
abstract class BaseDbUserQuery extends ModelCriteria
{
	
	/**
	 * Initializes internal state of BaseDbUserQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'encjaki', $modelName = 'DbUser', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new DbUserQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    DbUserQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof DbUserQuery) {
			return $criteria;
		}
		$query = new DbUserQuery();
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
	 * @return    DbUser|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ($key === null) {
			return null;
		}
		if ((null !== ($obj = DbUserPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
			// the object is alredy in the instance pool
			return $obj;
		}
		if ($con === null) {
			$con = Propel::getConnection(DbUserPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
	 * @return    DbUser A model object, or null if the key is not found
	 */
	protected function findPkSimple($key, $con)
	{
		$sql = 'SELECT `USERID`, `LOGIN`, `NAME`, `PASSWORD`, `LOCKED`, `CTIME__` FROM `user` WHERE `USERID` = :p0';
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
			$obj = new DbUser();
			$obj->hydrate($row);
			DbUserPeer::addInstanceToPool($obj, (string) $row[0]);
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
	 * @return    DbUser|array|mixed the result, formatted by the current formatter
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
	 * @return    DbUserQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(DbUserPeer::USERID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    DbUserQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(DbUserPeer::USERID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the UserID column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByUserid(1234); // WHERE UserID = 1234
	 * $query->filterByUserid(array(12, 34)); // WHERE UserID IN (12, 34)
	 * $query->filterByUserid(array('min' => 12)); // WHERE UserID > 12
	 * </code>
	 *
	 * @param     mixed $userid The value to use as filter.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DbUserQuery The current query, for fluid interface
	 */
	public function filterByUserid($userid = null, $comparison = null)
	{
		if (is_array($userid) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(DbUserPeer::USERID, $userid, $comparison);
	}

	/**
	 * Filter the query on the Login column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByLogin('fooValue');   // WHERE Login = 'fooValue'
	 * $query->filterByLogin('%fooValue%'); // WHERE Login LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $login The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DbUserQuery The current query, for fluid interface
	 */
	public function filterByLogin($login = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($login)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $login)) {
				$login = str_replace('*', '%', $login);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(DbUserPeer::LOGIN, $login, $comparison);
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
	 * @return    DbUserQuery The current query, for fluid interface
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
		return $this->addUsingAlias(DbUserPeer::NAME, $name, $comparison);
	}

	/**
	 * Filter the query on the Password column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByPassword('fooValue');   // WHERE Password = 'fooValue'
	 * $query->filterByPassword('%fooValue%'); // WHERE Password LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $password The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DbUserQuery The current query, for fluid interface
	 */
	public function filterByPassword($password = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($password)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $password)) {
				$password = str_replace('*', '%', $password);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(DbUserPeer::PASSWORD, $password, $comparison);
	}

	/**
	 * Filter the query on the Locked column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByLocked(true); // WHERE Locked = true
	 * $query->filterByLocked('yes'); // WHERE Locked = true
	 * </code>
	 *
	 * @param     boolean|string $locked The value to use as filter.
	 *              Non-boolean arguments are converted using the following rules:
	 *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
	 *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
	 *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DbUserQuery The current query, for fluid interface
	 */
	public function filterByLocked($locked = null, $comparison = null)
	{
		if (is_string($locked)) {
			$Locked = in_array(strtolower($locked), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
		}
		return $this->addUsingAlias(DbUserPeer::LOCKED, $locked, $comparison);
	}

	/**
	 * Filter the query on the ctime__ column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByCtime('2011-03-14'); // WHERE ctime__ = '2011-03-14'
	 * $query->filterByCtime('now'); // WHERE ctime__ = '2011-03-14'
	 * $query->filterByCtime(array('max' => 'yesterday')); // WHERE ctime__ > '2011-03-13'
	 * </code>
	 *
	 * @param     mixed $ctime The value to use as filter.
	 *              Values can be integers (unix timestamps), DateTime objects, or strings.
	 *              Empty strings are treated as NULL.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DbUserQuery The current query, for fluid interface
	 */
	public function filterByCtime($ctime = null, $comparison = null)
	{
		if (is_array($ctime)) {
			$useMinMax = false;
			if (isset($ctime['min'])) {
				$this->addUsingAlias(DbUserPeer::CTIME__, $ctime['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($ctime['max'])) {
				$this->addUsingAlias(DbUserPeer::CTIME__, $ctime['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(DbUserPeer::CTIME__, $ctime, $comparison);
	}

	/**
	 * Filter the query by a related DbNews object
	 *
	 * @param     DbNews $dbNews  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DbUserQuery The current query, for fluid interface
	 */
	public function filterByDbNews($dbNews, $comparison = null)
	{
		if ($dbNews instanceof DbNews) {
			return $this
				->addUsingAlias(DbUserPeer::USERID, $dbNews->getUserid(), $comparison);
		} elseif ($dbNews instanceof PropelCollection) {
			return $this
				->useDbNewsQuery()
				->filterByPrimaryKeys($dbNews->getPrimaryKeys())
				->endUse();
		} else {
			throw new PropelException('filterByDbNews() only accepts arguments of type DbNews or PropelCollection');
		}
	}

	/**
	 * Adds a JOIN clause to the query using the DbNews relation
	 *
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    DbUserQuery The current query, for fluid interface
	 */
	public function joinDbNews($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('DbNews');

		// create a ModelJoin object for this join
		$join = new ModelJoin();
		$join->setJoinType($joinType);
		$join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
		if ($previousJoin = $this->getPreviousJoin()) {
			$join->setPreviousJoin($previousJoin);
		}

		// add the ModelJoin to the current object
		if($relationAlias) {
			$this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
			$this->addJoinObject($join, $relationAlias);
		} else {
			$this->addJoinObject($join, 'DbNews');
		}

		return $this;
	}

	/**
	 * Use the DbNews relation DbNews object
	 *
	 * @see       useQuery()
	 *
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    DbNewsQuery A secondary query class using the current class as primary query
	 */
	public function useDbNewsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinDbNews($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'DbNews', 'DbNewsQuery');
	}

	/**
	 * Filter the query by a related DbStatistics object
	 *
	 * @param     DbStatistics $dbStatistics  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DbUserQuery The current query, for fluid interface
	 */
	public function filterByDbNews($dbStatistics, $comparison = null)
	{
		if ($dbStatistics instanceof DbStatistics) {
			return $this
				->addUsingAlias(DbUserPeer::USERID, $dbStatistics->getUserid(), $comparison);
		} elseif ($dbStatistics instanceof PropelCollection) {
			return $this
				->useDbNewsQuery()
				->filterByPrimaryKeys($dbStatistics->getPrimaryKeys())
				->endUse();
		} else {
			throw new PropelException('filterByDbNews() only accepts arguments of type DbStatistics or PropelCollection');
		}
	}

	/**
	 * Adds a JOIN clause to the query using the DbNews relation
	 *
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    DbUserQuery The current query, for fluid interface
	 */
	public function joinDbNews($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('DbNews');

		// create a ModelJoin object for this join
		$join = new ModelJoin();
		$join->setJoinType($joinType);
		$join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
		if ($previousJoin = $this->getPreviousJoin()) {
			$join->setPreviousJoin($previousJoin);
		}

		// add the ModelJoin to the current object
		if($relationAlias) {
			$this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
			$this->addJoinObject($join, $relationAlias);
		} else {
			$this->addJoinObject($join, 'DbNews');
		}

		return $this;
	}

	/**
	 * Use the DbNews relation DbStatistics object
	 *
	 * @see       useQuery()
	 *
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    DbStatisticsQuery A secondary query class using the current class as primary query
	 */
	public function useDbNewsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinDbNews($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'DbNews', 'DbStatisticsQuery');
	}

	/**
	 * Exclude object from result
	 *
	 * @param     DbUser $dbUser Object to remove from the list of results
	 *
	 * @return    DbUserQuery The current query, for fluid interface
	 */
	public function prune($dbUser = null)
	{
		if ($dbUser) {
			$this->addUsingAlias(DbUserPeer::USERID, $dbUser->getUserid(), Criteria::NOT_EQUAL);
		}

		return $this;
	}

} // BaseDbUserQuery