<?php


/**
 * Base class that represents a query for the 'statistics' table.
 *
 * 
 *
 * @method     DbStatisticsQuery orderByStatisticsid($order = Criteria::ASC) Order by the StatisticsID column
 * @method     DbStatisticsQuery orderByUserid($order = Criteria::ASC) Order by the UserID column
 * @method     DbStatisticsQuery orderByByclass($order = Criteria::ASC) Order by the ByClass column
 * @method     DbStatisticsQuery orderByTurn($order = Criteria::ASC) Order by the Turn column
 * @method     DbStatisticsQuery orderByPopulation($order = Criteria::ASC) Order by the Population column
 * @method     DbStatisticsQuery orderByParameter($order = Criteria::ASC) Order by the Parameter column
 * @method     DbStatisticsQuery orderByValue($order = Criteria::ASC) Order by the Value column
 *
 * @method     DbStatisticsQuery groupByStatisticsid() Group by the StatisticsID column
 * @method     DbStatisticsQuery groupByUserid() Group by the UserID column
 * @method     DbStatisticsQuery groupByByclass() Group by the ByClass column
 * @method     DbStatisticsQuery groupByTurn() Group by the Turn column
 * @method     DbStatisticsQuery groupByPopulation() Group by the Population column
 * @method     DbStatisticsQuery groupByParameter() Group by the Parameter column
 * @method     DbStatisticsQuery groupByValue() Group by the Value column
 *
 * @method     DbStatisticsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     DbStatisticsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     DbStatisticsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     DbStatisticsQuery leftJoinDbUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the DbUser relation
 * @method     DbStatisticsQuery rightJoinDbUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DbUser relation
 * @method     DbStatisticsQuery innerJoinDbUser($relationAlias = null) Adds a INNER JOIN clause to the query using the DbUser relation
 *
 * @method     DbStatistics findOne(PropelPDO $con = null) Return the first DbStatistics matching the query
 * @method     DbStatistics findOneOrCreate(PropelPDO $con = null) Return the first DbStatistics matching the query, or a new DbStatistics object populated from the query conditions when no match is found
 *
 * @method     DbStatistics findOneByStatisticsid(string $StatisticsID) Return the first DbStatistics filtered by the StatisticsID column
 * @method     DbStatistics findOneByUserid(string $UserID) Return the first DbStatistics filtered by the UserID column
 * @method     DbStatistics findOneByByclass(string $ByClass) Return the first DbStatistics filtered by the ByClass column
 * @method     DbStatistics findOneByTurn(int $Turn) Return the first DbStatistics filtered by the Turn column
 * @method     DbStatistics findOneByPopulation(int $Population) Return the first DbStatistics filtered by the Population column
 * @method     DbStatistics findOneByParameter(string $Parameter) Return the first DbStatistics filtered by the Parameter column
 * @method     DbStatistics findOneByValue(int $Value) Return the first DbStatistics filtered by the Value column
 *
 * @method     array findByStatisticsid(string $StatisticsID) Return DbStatistics objects filtered by the StatisticsID column
 * @method     array findByUserid(string $UserID) Return DbStatistics objects filtered by the UserID column
 * @method     array findByByclass(string $ByClass) Return DbStatistics objects filtered by the ByClass column
 * @method     array findByTurn(int $Turn) Return DbStatistics objects filtered by the Turn column
 * @method     array findByPopulation(int $Population) Return DbStatistics objects filtered by the Population column
 * @method     array findByParameter(string $Parameter) Return DbStatistics objects filtered by the Parameter column
 * @method     array findByValue(int $Value) Return DbStatistics objects filtered by the Value column
 *
 * @package    propel.generator.encjakiPropel.om
 */
abstract class BaseDbStatisticsQuery extends ModelCriteria
{
	
	/**
	 * Initializes internal state of BaseDbStatisticsQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'encjaki', $modelName = 'DbStatistics', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new DbStatisticsQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    DbStatisticsQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof DbStatisticsQuery) {
			return $criteria;
		}
		$query = new DbStatisticsQuery();
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
	 * @return    DbStatistics|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ($key === null) {
			return null;
		}
		if ((null !== ($obj = DbStatisticsPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
			// the object is alredy in the instance pool
			return $obj;
		}
		if ($con === null) {
			$con = Propel::getConnection(DbStatisticsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
	 * @return    DbStatistics A model object, or null if the key is not found
	 */
	protected function findPkSimple($key, $con)
	{
		$sql = 'SELECT `STATISTICSID`, `USERID`, `BYCLASS`, `TURN`, `POPULATION`, `PARAMETER`, `VALUE` FROM `statistics` WHERE `STATISTICSID` = :p0';
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
			$obj = new DbStatistics();
			$obj->hydrate($row);
			DbStatisticsPeer::addInstanceToPool($obj, (string) $row[0]);
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
	 * @return    DbStatistics|array|mixed the result, formatted by the current formatter
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
	 * @return    DbStatisticsQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(DbStatisticsPeer::STATISTICSID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    DbStatisticsQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(DbStatisticsPeer::STATISTICSID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the StatisticsID column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByStatisticsid(1234); // WHERE StatisticsID = 1234
	 * $query->filterByStatisticsid(array(12, 34)); // WHERE StatisticsID IN (12, 34)
	 * $query->filterByStatisticsid(array('min' => 12)); // WHERE StatisticsID > 12
	 * </code>
	 *
	 * @param     mixed $statisticsid The value to use as filter.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DbStatisticsQuery The current query, for fluid interface
	 */
	public function filterByStatisticsid($statisticsid = null, $comparison = null)
	{
		if (is_array($statisticsid) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(DbStatisticsPeer::STATISTICSID, $statisticsid, $comparison);
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
	 * @see       filterByDbUser()
	 *
	 * @param     mixed $userid The value to use as filter.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DbStatisticsQuery The current query, for fluid interface
	 */
	public function filterByUserid($userid = null, $comparison = null)
	{
		if (is_array($userid)) {
			$useMinMax = false;
			if (isset($userid['min'])) {
				$this->addUsingAlias(DbStatisticsPeer::USERID, $userid['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userid['max'])) {
				$this->addUsingAlias(DbStatisticsPeer::USERID, $userid['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(DbStatisticsPeer::USERID, $userid, $comparison);
	}

	/**
	 * Filter the query on the ByClass column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByByclass('fooValue');   // WHERE ByClass = 'fooValue'
	 * $query->filterByByclass('%fooValue%'); // WHERE ByClass LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $byclass The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DbStatisticsQuery The current query, for fluid interface
	 */
	public function filterByByclass($byclass = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($byclass)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $byclass)) {
				$byclass = str_replace('*', '%', $byclass);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(DbStatisticsPeer::BYCLASS, $byclass, $comparison);
	}

	/**
	 * Filter the query on the Turn column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByTurn(1234); // WHERE Turn = 1234
	 * $query->filterByTurn(array(12, 34)); // WHERE Turn IN (12, 34)
	 * $query->filterByTurn(array('min' => 12)); // WHERE Turn > 12
	 * </code>
	 *
	 * @param     mixed $turn The value to use as filter.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DbStatisticsQuery The current query, for fluid interface
	 */
	public function filterByTurn($turn = null, $comparison = null)
	{
		if (is_array($turn)) {
			$useMinMax = false;
			if (isset($turn['min'])) {
				$this->addUsingAlias(DbStatisticsPeer::TURN, $turn['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($turn['max'])) {
				$this->addUsingAlias(DbStatisticsPeer::TURN, $turn['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(DbStatisticsPeer::TURN, $turn, $comparison);
	}

	/**
	 * Filter the query on the Population column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByPopulation(1234); // WHERE Population = 1234
	 * $query->filterByPopulation(array(12, 34)); // WHERE Population IN (12, 34)
	 * $query->filterByPopulation(array('min' => 12)); // WHERE Population > 12
	 * </code>
	 *
	 * @param     mixed $population The value to use as filter.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DbStatisticsQuery The current query, for fluid interface
	 */
	public function filterByPopulation($population = null, $comparison = null)
	{
		if (is_array($population)) {
			$useMinMax = false;
			if (isset($population['min'])) {
				$this->addUsingAlias(DbStatisticsPeer::POPULATION, $population['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($population['max'])) {
				$this->addUsingAlias(DbStatisticsPeer::POPULATION, $population['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(DbStatisticsPeer::POPULATION, $population, $comparison);
	}

	/**
	 * Filter the query on the Parameter column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByParameter('fooValue');   // WHERE Parameter = 'fooValue'
	 * $query->filterByParameter('%fooValue%'); // WHERE Parameter LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $parameter The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DbStatisticsQuery The current query, for fluid interface
	 */
	public function filterByParameter($parameter = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($parameter)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $parameter)) {
				$parameter = str_replace('*', '%', $parameter);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(DbStatisticsPeer::PARAMETER, $parameter, $comparison);
	}

	/**
	 * Filter the query on the Value column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByValue(1234); // WHERE Value = 1234
	 * $query->filterByValue(array(12, 34)); // WHERE Value IN (12, 34)
	 * $query->filterByValue(array('min' => 12)); // WHERE Value > 12
	 * </code>
	 *
	 * @param     mixed $value The value to use as filter.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DbStatisticsQuery The current query, for fluid interface
	 */
	public function filterByValue($value = null, $comparison = null)
	{
		if (is_array($value)) {
			$useMinMax = false;
			if (isset($value['min'])) {
				$this->addUsingAlias(DbStatisticsPeer::VALUE, $value['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($value['max'])) {
				$this->addUsingAlias(DbStatisticsPeer::VALUE, $value['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(DbStatisticsPeer::VALUE, $value, $comparison);
	}

	/**
	 * Filter the query by a related DbUser object
	 *
	 * @param     DbUser|PropelCollection $dbUser The related object(s) to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DbStatisticsQuery The current query, for fluid interface
	 */
	public function filterByDbUser($dbUser, $comparison = null)
	{
		if ($dbUser instanceof DbUser) {
			return $this
				->addUsingAlias(DbStatisticsPeer::USERID, $dbUser->getUserid(), $comparison);
		} elseif ($dbUser instanceof PropelCollection) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
			return $this
				->addUsingAlias(DbStatisticsPeer::USERID, $dbUser->toKeyValue('PrimaryKey', 'Userid'), $comparison);
		} else {
			throw new PropelException('filterByDbUser() only accepts arguments of type DbUser or PropelCollection');
		}
	}

	/**
	 * Adds a JOIN clause to the query using the DbUser relation
	 *
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    DbStatisticsQuery The current query, for fluid interface
	 */
	public function joinDbUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('DbUser');

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
			$this->addJoinObject($join, 'DbUser');
		}

		return $this;
	}

	/**
	 * Use the DbUser relation DbUser object
	 *
	 * @see       useQuery()
	 *
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    DbUserQuery A secondary query class using the current class as primary query
	 */
	public function useDbUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinDbUser($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'DbUser', 'DbUserQuery');
	}

	/**
	 * Exclude object from result
	 *
	 * @param     DbStatistics $dbStatistics Object to remove from the list of results
	 *
	 * @return    DbStatisticsQuery The current query, for fluid interface
	 */
	public function prune($dbStatistics = null)
	{
		if ($dbStatistics) {
			$this->addUsingAlias(DbStatisticsPeer::STATISTICSID, $dbStatistics->getStatisticsid(), Criteria::NOT_EQUAL);
		}

		return $this;
	}

} // BaseDbStatisticsQuery