<?php


/**
 * Base class that represents a query for the 'news' table.
 *
 * 
 *
 * @method     DbNewsQuery orderByNewsid($order = Criteria::ASC) Order by the NewsID column
 * @method     DbNewsQuery orderByUserid($order = Criteria::ASC) Order by the UserID column
 * @method     DbNewsQuery orderByCtime($order = Criteria::ASC) Order by the ctime__ column
 * @method     DbNewsQuery orderByTitle($order = Criteria::ASC) Order by the Title column
 * @method     DbNewsQuery orderByText($order = Criteria::ASC) Order by the Text column
 * @method     DbNewsQuery orderByPublished($order = Criteria::ASC) Order by the Published column
 * @method     DbNewsQuery orderByType($order = Criteria::ASC) Order by the Type column
 * @method     DbNewsQuery orderByLanguage($order = Criteria::ASC) Order by the Language column
 *
 * @method     DbNewsQuery groupByNewsid() Group by the NewsID column
 * @method     DbNewsQuery groupByUserid() Group by the UserID column
 * @method     DbNewsQuery groupByCtime() Group by the ctime__ column
 * @method     DbNewsQuery groupByTitle() Group by the Title column
 * @method     DbNewsQuery groupByText() Group by the Text column
 * @method     DbNewsQuery groupByPublished() Group by the Published column
 * @method     DbNewsQuery groupByType() Group by the Type column
 * @method     DbNewsQuery groupByLanguage() Group by the Language column
 *
 * @method     DbNewsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     DbNewsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     DbNewsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     DbNewsQuery leftJoinDbUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the DbUser relation
 * @method     DbNewsQuery rightJoinDbUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DbUser relation
 * @method     DbNewsQuery innerJoinDbUser($relationAlias = null) Adds a INNER JOIN clause to the query using the DbUser relation
 *
 * @method     DbNews findOne(PropelPDO $con = null) Return the first DbNews matching the query
 * @method     DbNews findOneOrCreate(PropelPDO $con = null) Return the first DbNews matching the query, or a new DbNews object populated from the query conditions when no match is found
 *
 * @method     DbNews findOneByNewsid(string $NewsID) Return the first DbNews filtered by the NewsID column
 * @method     DbNews findOneByUserid(string $UserID) Return the first DbNews filtered by the UserID column
 * @method     DbNews findOneByCtime(string $ctime__) Return the first DbNews filtered by the ctime__ column
 * @method     DbNews findOneByTitle(string $Title) Return the first DbNews filtered by the Title column
 * @method     DbNews findOneByText(string $Text) Return the first DbNews filtered by the Text column
 * @method     DbNews findOneByPublished(boolean $Published) Return the first DbNews filtered by the Published column
 * @method     DbNews findOneByType(string $Type) Return the first DbNews filtered by the Type column
 * @method     DbNews findOneByLanguage(string $Language) Return the first DbNews filtered by the Language column
 *
 * @method     array findByNewsid(string $NewsID) Return DbNews objects filtered by the NewsID column
 * @method     array findByUserid(string $UserID) Return DbNews objects filtered by the UserID column
 * @method     array findByCtime(string $ctime__) Return DbNews objects filtered by the ctime__ column
 * @method     array findByTitle(string $Title) Return DbNews objects filtered by the Title column
 * @method     array findByText(string $Text) Return DbNews objects filtered by the Text column
 * @method     array findByPublished(boolean $Published) Return DbNews objects filtered by the Published column
 * @method     array findByType(string $Type) Return DbNews objects filtered by the Type column
 * @method     array findByLanguage(string $Language) Return DbNews objects filtered by the Language column
 *
 * @package    propel.generator.encjakiPropel.om
 */
abstract class BaseDbNewsQuery extends ModelCriteria
{
	
	/**
	 * Initializes internal state of BaseDbNewsQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'encjaki', $modelName = 'DbNews', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new DbNewsQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    DbNewsQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof DbNewsQuery) {
			return $criteria;
		}
		$query = new DbNewsQuery();
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
	 * @return    DbNews|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ($key === null) {
			return null;
		}
		if ((null !== ($obj = DbNewsPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
			// the object is alredy in the instance pool
			return $obj;
		}
		if ($con === null) {
			$con = Propel::getConnection(DbNewsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
	 * @return    DbNews A model object, or null if the key is not found
	 */
	protected function findPkSimple($key, $con)
	{
		$sql = 'SELECT `NEWSID`, `USERID`, `CTIME__`, `TITLE`, `TEXT`, `PUBLISHED`, `TYPE`, `LANGUAGE` FROM `news` WHERE `NEWSID` = :p0';
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
			$obj = new DbNews();
			$obj->hydrate($row);
			DbNewsPeer::addInstanceToPool($obj, (string) $row[0]);
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
	 * @return    DbNews|array|mixed the result, formatted by the current formatter
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
	 * @return    DbNewsQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(DbNewsPeer::NEWSID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    DbNewsQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(DbNewsPeer::NEWSID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the NewsID column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByNewsid(1234); // WHERE NewsID = 1234
	 * $query->filterByNewsid(array(12, 34)); // WHERE NewsID IN (12, 34)
	 * $query->filterByNewsid(array('min' => 12)); // WHERE NewsID > 12
	 * </code>
	 *
	 * @param     mixed $newsid The value to use as filter.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DbNewsQuery The current query, for fluid interface
	 */
	public function filterByNewsid($newsid = null, $comparison = null)
	{
		if (is_array($newsid) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(DbNewsPeer::NEWSID, $newsid, $comparison);
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
	 * @return    DbNewsQuery The current query, for fluid interface
	 */
	public function filterByUserid($userid = null, $comparison = null)
	{
		if (is_array($userid)) {
			$useMinMax = false;
			if (isset($userid['min'])) {
				$this->addUsingAlias(DbNewsPeer::USERID, $userid['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($userid['max'])) {
				$this->addUsingAlias(DbNewsPeer::USERID, $userid['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(DbNewsPeer::USERID, $userid, $comparison);
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
	 * @return    DbNewsQuery The current query, for fluid interface
	 */
	public function filterByCtime($ctime = null, $comparison = null)
	{
		if (is_array($ctime)) {
			$useMinMax = false;
			if (isset($ctime['min'])) {
				$this->addUsingAlias(DbNewsPeer::CTIME__, $ctime['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($ctime['max'])) {
				$this->addUsingAlias(DbNewsPeer::CTIME__, $ctime['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(DbNewsPeer::CTIME__, $ctime, $comparison);
	}

	/**
	 * Filter the query on the Title column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByTitle('fooValue');   // WHERE Title = 'fooValue'
	 * $query->filterByTitle('%fooValue%'); // WHERE Title LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $title The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DbNewsQuery The current query, for fluid interface
	 */
	public function filterByTitle($title = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($title)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $title)) {
				$title = str_replace('*', '%', $title);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(DbNewsPeer::TITLE, $title, $comparison);
	}

	/**
	 * Filter the query on the Text column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByText('fooValue');   // WHERE Text = 'fooValue'
	 * $query->filterByText('%fooValue%'); // WHERE Text LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $text The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DbNewsQuery The current query, for fluid interface
	 */
	public function filterByText($text = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($text)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $text)) {
				$text = str_replace('*', '%', $text);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(DbNewsPeer::TEXT, $text, $comparison);
	}

	/**
	 * Filter the query on the Published column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByPublished(true); // WHERE Published = true
	 * $query->filterByPublished('yes'); // WHERE Published = true
	 * </code>
	 *
	 * @param     boolean|string $published The value to use as filter.
	 *              Non-boolean arguments are converted using the following rules:
	 *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
	 *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
	 *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DbNewsQuery The current query, for fluid interface
	 */
	public function filterByPublished($published = null, $comparison = null)
	{
		if (is_string($published)) {
			$Published = in_array(strtolower($published), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
		}
		return $this->addUsingAlias(DbNewsPeer::PUBLISHED, $published, $comparison);
	}

	/**
	 * Filter the query on the Type column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByType('fooValue');   // WHERE Type = 'fooValue'
	 * $query->filterByType('%fooValue%'); // WHERE Type LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $type The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DbNewsQuery The current query, for fluid interface
	 */
	public function filterByType($type = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($type)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $type)) {
				$type = str_replace('*', '%', $type);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(DbNewsPeer::TYPE, $type, $comparison);
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
	 * @return    DbNewsQuery The current query, for fluid interface
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
		return $this->addUsingAlias(DbNewsPeer::LANGUAGE, $language, $comparison);
	}

	/**
	 * Filter the query by a related DbUser object
	 *
	 * @param     DbUser|PropelCollection $dbUser The related object(s) to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    DbNewsQuery The current query, for fluid interface
	 */
	public function filterByDbUser($dbUser, $comparison = null)
	{
		if ($dbUser instanceof DbUser) {
			return $this
				->addUsingAlias(DbNewsPeer::USERID, $dbUser->getUserid(), $comparison);
		} elseif ($dbUser instanceof PropelCollection) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
			return $this
				->addUsingAlias(DbNewsPeer::USERID, $dbUser->toKeyValue('PrimaryKey', 'Userid'), $comparison);
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
	 * @return    DbNewsQuery The current query, for fluid interface
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
	 * @param     DbNews $dbNews Object to remove from the list of results
	 *
	 * @return    DbNewsQuery The current query, for fluid interface
	 */
	public function prune($dbNews = null)
	{
		if ($dbNews) {
			$this->addUsingAlias(DbNewsPeer::NEWSID, $dbNews->getNewsid(), Criteria::NOT_EQUAL);
		}

		return $this;
	}

} // BaseDbNewsQuery