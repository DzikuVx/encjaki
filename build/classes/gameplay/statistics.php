<?php

class statistics {

	/**
	 * Liczba narodzin
	 *
	 * @var int
	 */
	protected $bornCount = 0;
	protected $bornArray = array ();

	/**
	 * Liczba śmierci
	 *
	 * @var int
	 */
	protected $deathCount = 0;
	protected $deathArray = array ();

	/**
	 * Wykres populacji
	 *
	 * @param int $tUserID
	 * @return string
	 */
	static function sGenPopulationChart($tUserID) {

		$stat = new baseStat ( );
		$stat->chartType = 'lines';
		$stat->chartWidth = 400;
		$stat->chartHeight = 150;
		$stat->use3D = true;

		$tUsedClasses = array ();

		$query = DbStatisticsQuery::create()->filterByUserid($tUserID)->filterByByclass(null, Criteria::ISNOTNULL)->setLimit(300)->addDescendingOrderByColumn('Turn')->find();
		
		foreach ($query as $tResult) {
			$tUsedClasses [$tResult->getByclass()] = true;
			$tValue [$tResult->getTurn()] [$tResult->getByclass()] = ( int ) $tResult->getPopulation();
		}

		if (empty($tValue)) {
			return '';
		}
		
		$tValue = array_reverse ( $tValue );

		$tKeys = array_keys ( $tUsedClasses );

		$data = array ();
		foreach ( $tValue as $key => $value ) {
			$data [$key] ['AXIS_X'] = $key;
			foreach ( $tKeys as $tKey ) {
				if (isset ( $value [$tKey] )) {
					$data [$key] [$tKey] = $value [$tKey];
				} else {
					$data [$key] [$tKey] = 0;
				}
			}
		}

		foreach ( $tKeys as $tKey ) {
			$stat->columns [$tKey] = array ('title' => $tKey, 'type' => 'numeric' );
		}

		$stat->data = $data;

		$stat->plugin = 'population';
		$stat->genChart ();
		return $stat->chartHTML ();
	}

	/**
	 * wykres pożywienia
	 *
	 * @param int $tUserID
	 * @return string
	 */
	static function sGenFoodChart($tUserID) {

		global $db;

		$stat = new baseStat ( );
		$stat->chartType = 'lines';
		$stat->chartWidth = 400;
		$stat->chartHeight = 150;
		$stat->use3D = true;

		$tUsedClasses = array ();

		$tQuery = "SELECT * FROM statistics WHERE UserID='{$tUserID}' AND ByClass IS NULL AND (Parameter='FoodProduction' OR Parameter='FoodUsage') ORDER BY Turn DESC LIMIT 300";
		$tQuery = $db->execute ( $tQuery );
		while ( $tResult = $db->fetch ( $tQuery ) ) {
			$tUsedClasses [$tResult->Parameter] = true;
			$tValue [$tResult->Turn] [$tResult->Parameter] = ( int ) $tResult->Value;
		}

		if (empty($tValue)) {
			return '';
		}
		
		$tValue = array_reverse ( $tValue );

		$tKeys = array_keys ( $tUsedClasses );

		$data = array ();
		foreach ( $tValue as $key => $value ) {
			$data [$key] ['AXIS_X'] = $key;
			foreach ( $tKeys as $tKey ) {
				if (isset ( $value [$tKey] )) {
					$data [$key] [$tKey] = $value [$tKey];
				} else {
					$data [$key] [$tKey] = 0;
				}
			}
		}

		foreach ( $tKeys as $tKey ) {
			$stat->columns [$tKey] = array ('title' => $tKey, 'type' => 'numeric' );
		}

		$stat->data = $data;

		$stat->plugin = 'food';
		$stat->genChart ();
		return $stat->chartHTML ();
	}

	static function sGenFoodAvaibleChart($tUserID) {

		$stat = new baseStat ( );
		$stat->chartType = 'lines';
		$stat->chartWidth = 400;
		$stat->chartHeight = 150;
		$stat->use3D = true;

		$tUsedClasses = array ();

		$query = DbStatisticsQuery::create()->filterByUserid($tUserID)->filterByByclass(null, Criteria::ISNULL)->filterByParameter('FoodCurrent')->setLimit(300)->addDescendingOrderByColumn('Turn')->find();
		
		foreach ($query as $tResult) {
			$tUsedClasses [$tResult->getParameter()] = true;
			$tValue [$tResult->getTurn()] [$tResult->getParameter()] = ( int ) $tResult->getValue();
		}
		
		if (empty($tValue)) {
			return '';
		}
		
		$tValue = array_reverse ( $tValue );

		$tKeys = array_keys ( $tUsedClasses );

		$data = array ();
		foreach ( $tValue as $key => $value ) {
			$data [$key] ['AXIS_X'] = $key;
			foreach ( $tKeys as $tKey ) {
				if (isset ( $value [$tKey] )) {
					$data [$key] [$tKey] = $value [$tKey];
				} else {
					$data [$key] [$tKey] = 0;
				}
			}
		}

		foreach ( $tKeys as $tKey ) {
			$stat->columns [$tKey] = array ('title' => $tKey, 'type' => 'numeric' );
		}

		$stat->data = $data;
		$stat->plugin = 'foodCurrent';
		$stat->genChart ();
		return $stat->chartHTML ();
	}

	/**
	 * Zwiększenie numeru tury
	 *
	 * @param int $currentTurn
	 * @param int $userID
	 * @return int
	 */
	static function sIncCurrentTurn($currentTurn, $userID) {

		global $sCache;

		$retVal = $currentTurn + 1;

		$module = 'statistics::sGetCurrentTurn';
		$property = $userID;
		$sCache->set ( $module, $property, $retVal );

		return $retVal;
	}

	/**
	 * Pobranie aktualnego numeru tury
	 *
	 * @param int $userID
	 * @return int
	 */
	static function sGetCurrentTurn($userID) {

		$module = 'statistics::sGetCurrentTurn';
		$property = $userID;

		$retVal = 0;

		if (! CacheController::getInstance()->check ( $module, $property )) {
			$tQuery = "SELECT MAX(Turn) AS ILE FROM statistics WHERE UserID='{$userID}'";
			$tQuery = DataBaseController::getInstance()->execute ( $tQuery );
			while ( $tResult = DataBaseController::getInstance()->fetch ( $tQuery ) ) {
				$retVal = $tResult->ILE;
			}
			CacheController::getInstance()->set ( $module, $property, $retVal );
		} else {
			$retVal = CacheController::getInstance()->get ( $module, $property );
		}

		return $retVal;
	}

	/**
	 * @return int
	 */
	public function getBornCount() {

		return $this->bornCount;
	}

	/**
	 * @return int
	 */
	public function getDeathCount() {

		return $this->deathCount;
	}

	public function getBornArrayCount($name) {

		if (isset ( $this->bornArray [$name] )) {
			return $this->bornArray [$name];
		} else {
			return 0;
		}
	}

	public function getDeathArrayCount($name) {

		if (isset ( $this->deathArray [$name] )) {
			return $this->deathArray [$name];
		} else {
			return 0;
		}
	}

	public function incBorn($object = null) {

		$this->bornCount ++;
		if ($object != null) {
			if (isset ( $this->bornArray [get_class ( $object )] )) {
				$this->bornArray [get_class ( $object )] ++;
			} else {
				$this->bornArray [get_class ( $object )] = 1;
			}
		}
	}

	public function incDeath($object = null) {

		$this->deathCount ++;
		if ($object != null) {
			if (isset ( $this->deathArray [get_class ( $object )] )) {
				$this->deathArray [get_class ( $object )] ++;
			} else {
				$this->deathArray [get_class ( $object )] = 1;
			}
		}
	}

	/**
	 * Globalne statystyki
	 *
	 * @param int $UserID
	 * @return string
	 */
	static function sGetStatistics($UserID) {

		global $statistics, $encjakArray, $parcelArray;

		if (empty ( $encjakArray )) {
			$lifeFormSaver = new itemSaver ( 'lifeforms', $UserID );
			$lifeFormSaver->get ( $encjakArray );
		}

		$retVal = '';

		$tStatistics = array ();

		$tStatistics ['global'] = new statisticsItem ( );

		$tStatistics ['global']->LF_SUM = count ( $encjakArray );
		foreach ( $encjakArray as $tEncjak ) {
			$tStatistics ['global']->FOOD_USAGE += $tEncjak->FoodPerTurn;

			if (! isset ( $tStatistics [get_class ( $tEncjak )] )) {
				$tStatistics [get_class ( $tEncjak )] = new statisticsItem ( );
			}

			$tStatistics [get_class ( $tEncjak )]->LF_SUM ++;
			$tStatistics [get_class ( $tEncjak )]->FOOD_USAGE += $tEncjak->FoodPerTurn;
			$tStatistics [get_class ( $tEncjak )]->AGE_AVG += $tEncjak->getAge ()->Current;
			$tStatistics ['global']->AGE_AVG += $tEncjak->getAge ()->Current;
			$tStatistics [get_class ( $tEncjak )]->HEALTH_AVG += $tEncjak->getHealth ()->Current;
			$tStatistics ['global']->HEALTH_AVG += $tEncjak->getHealth ()->Current;

			$tStatistics [get_class ( $tEncjak )]->HEALTH_MAX_AVG += $tEncjak->getHealth ()->Max;
			$tStatistics ['global']->HEALTH_MAX_AVG += $tEncjak->getHealth ()->Max;

			$tStatistics [get_class ( $tEncjak )]->AGE_MAX_AVG += $tEncjak->getAge ()->Max;
			$tStatistics ['global']->AGE_MAX_AVG += $tEncjak->getAge ()->Max;

			if ($tEncjak->getAge ()->Current < $tStatistics [get_class ( $tEncjak )]->AGE_MIN) {
				$tStatistics [get_class ( $tEncjak )]->AGE_MIN = $tEncjak->getAge ()->Current;
			}
			if ($tEncjak->getAge ()->Current < $tStatistics ['global']->AGE_MIN) {
				$tStatistics ['global']->AGE_MIN = $tEncjak->getAge ()->Current;
			}
			if ($tEncjak->getAge ()->Current > $tStatistics [get_class ( $tEncjak )]->AGE_MAX) {
				$tStatistics [get_class ( $tEncjak )]->AGE_MAX = $tEncjak->getAge ()->Current;
			}
			if ($tEncjak->getAge ()->Current > $tStatistics ['global']->AGE_MAX) {
				$tStatistics ['global']->AGE_MAX = $tEncjak->getAge ()->Current;
			}

			if ($tEncjak->getHealth ()->Current < $tStatistics [get_class ( $tEncjak )]->HEALTH_MIN) {
				$tStatistics [get_class ( $tEncjak )]->HEALTH_MIN = $tEncjak->getHealth ()->Current;
			}
			if ($tEncjak->getHealth ()->Current < $tStatistics ['global']->HEALTH_MIN) {
				$tStatistics ['global']->HEALTH_MIN = $tEncjak->getHealth ()->Current;
			}
			if ($tEncjak->getHealth ()->Current > $tStatistics [get_class ( $tEncjak )]->HEALTH_MAX) {
				$tStatistics [get_class ( $tEncjak )]->HEALTH_MAX = $tEncjak->getHealth ()->Current;
			}
			if ($tEncjak->getHealth ()->Current > $tStatistics ['global']->HEALTH_MAX) {
				$tStatistics ['global']->HEALTH_MAX = $tEncjak->getHealth ()->Current;
			}

		}

		foreach ( $tStatistics as $tKey => $tValue ) {
			$tStatistics [$tKey]->AGE_AVG = $tStatistics [$tKey]->AGE_AVG / $tStatistics [$tKey]->LF_SUM;
			$tStatistics [$tKey]->HEALTH_AVG = $tStatistics [$tKey]->HEALTH_AVG / $tStatistics [$tKey]->LF_SUM;
			$tStatistics [$tKey]->HEALTH_MAX_AVG = $tStatistics [$tKey]->HEALTH_MAX_AVG / $tStatistics [$tKey]->LF_SUM;
			$tStatistics [$tKey]->AGE_MAX_AVG = $tStatistics [$tKey]->AGE_MAX_AVG / $tStatistics [$tKey]->LF_SUM;
		}

		$tStatistics ['global']->DeathCount = $statistics->getDeathCount ();
		$tStatistics ['global']->BornCount = $statistics->getBornCount ();

		$tParcelStatistics = new stdClass();

		$tParcelStatistics->FOOD_GENERATION = 0;
		$tParcelStatistics->FOOD_CURRENT = 0;

		foreach ( $parcelArray as $t1 ) {
			foreach ( $t1 as $tParcel ) {
				$tParcelStatistics->FOOD_GENERATION += $tParcel->getFood ()->Regeneration;
				$tParcelStatistics->FOOD_CURRENT += $tParcel->getFood ()->Current;
			}
		}

		$ItemCount = count ( array_keys ( $tStatistics ) ) + 1;

		$retVal .= '<table class="statistics" style="width: ' . floor ( $ItemCount * 120 ) . 'px;">';
		$retVal .= '<thead>';
		$retVal .= '<tr>';
		$retVal .= '<th>Parametr</th>';
		$tKeys = array_keys ( $tStatistics );
		foreach ( $tKeys as $tValue ) {
			$retVal .= '<th>' . $tValue . '</th>';
		}
		$retVal .= '</tr>';
		$retVal .= '</thead>';
		$retVal .= '<tbody>';

		$retVal .= '<tr>';
		$retVal .= '<th>Form życia:</th>';
		foreach ( $tStatistics as $tValue ) {
			$retVal .= '<td>' . $tValue->LF_SUM . '</td>';
		}
		$retVal .= '</tr>';
		$retVal .= '<tr>';
		$retVal .= '<th>Średni wiek:</th>';
		foreach ( $tStatistics as $tValue ) {
			$retVal .= '<td>' . dataTypes::formatValue ( $tValue->AGE_AVG, '' ) . '</td>';
		}
		$retVal .= '</tr>';
		$retVal .= '<tr>';
		$retVal .= '<th>Najmłodszy:</th>';
		foreach ( $tStatistics as $tValue ) {
			$retVal .= '<td>' . $tValue->AGE_MIN . '</td>';
		}
		$retVal .= '</tr>';
		$retVal .= '<tr>';
		$retVal .= '<th>Średnia wieku maksymalnego:</th>';
		foreach ( $tStatistics as $tValue ) {
			$retVal .= '<td>' . dataTypes::formatValue ( $tValue->AGE_MAX_AVG, '' ) . '</td>';
		}
		$retVal .= '</tr>';
		$retVal .= '<tr>';
		$retVal .= '<th>Najstarszy:</th>';
		foreach ( $tStatistics as $tValue ) {
			$retVal .= '<td>' . $tValue->AGE_MAX . '</td>';
		}
		$retVal .= '</tr>';
		$retVal .= '<tr>';
		$retVal .= '<th>Średnie zdrowie:</th>';
		foreach ( $tStatistics as $tValue ) {
			$retVal .= '<td>' . dataTypes::formatValue ( $tValue->HEALTH_AVG, '' ) . '</td>';
		}
		$retVal .= '</tr>';
		$retVal .= '<tr>';
		$retVal .= '<th>Najsłabszy:</th>';
		foreach ( $tStatistics as $tValue ) {
			$retVal .= '<td>' . $tValue->HEALTH_MIN . '</td>';
		}
		$retVal .= '</tr>';
		$retVal .= '<tr>';
		$retVal .= '<th>Najzdrowszy:</th>';
		foreach ( $tStatistics as $tValue ) {
			$retVal .= '<td>' . $tValue->HEALTH_MAX . '</td>';
		}
		$retVal .= '</tr>';
		$retVal .= '<tr>';
		$retVal .= '<th>Średnie max zdrowie:</th>';
		foreach ( $tStatistics as $tValue ) {
			$retVal .= '<td>' . dataTypes::formatValue ( $tValue->HEALTH_MAX_AVG, '' ) . '</td>';
		}
		$retVal .= '</tr>';
		$retVal .= '<tr>';
		$retVal .= '<th>Zużycie pożywienia:</th>';
		foreach ( $tStatistics as $tValue ) {
			$retVal .= '<td>' . $tValue->FOOD_USAGE . '</td>';
		}
		$retVal .= '</tr>';
		$retVal .= '<tr>';
		$retVal .= '<th>Narodzin:</th>';
		foreach ( $tStatistics as $tKey => $tValue ) {
			if ($tKey == 'global') {
				$retVal .= '<td>' . $statistics->getBornCount () . '</td>';
			} else {
				$retVal .= '<td>' . $statistics->getBornArrayCount ( $tKey ) . '</td>';
			}
		}
		$retVal .= '</tr>';
		$retVal .= '<tr>';
		$retVal .= '<th>Śmierci:</th>';
		foreach ( $tStatistics as $tKey => $tValue ) {
			if ($tKey == 'global') {
				$retVal .= '<td>' . $statistics->getDeathCount () . '</td>';
			} else {
				$retVal .= '<td>' . $statistics->getDeathArrayCount ( $tKey ) . '</td>';
			}
		}
		$retVal .= '</tr>';
		$retVal .= '<tr>';
		$retVal .= '<th>Produkcja pożywienia:</th>';
		foreach ( $tStatistics as $tValue ) {
			$retVal .= '<td>' . $tParcelStatistics->FOOD_GENERATION . '</td>';
		}
		$retVal .= '</tr>';
		$retVal .= '<tr>';
		$retVal .= '<th>Dostępne pożywienie:</th>';
		foreach ( $tStatistics as $tValue ) {
			$retVal .= '<td>' . $tParcelStatistics->FOOD_CURRENT . '</td>';
		}
		$retVal .= '</tr>';

		$retVal .= '</tbody>';
		$retVal .= '</table>';

		$currentTurn = statistics::sGetCurrentTurn ( $UserID );
		$currentTurn = statistics::sIncCurrentTurn ( $currentTurn, $UserID );

		statistics::sSave ( $tStatistics, $currentTurn, $UserID, $tParcelStatistics );

		return $retVal;
	}

	/**
	 * Zapisanie statystyk do bazy danych
	 *
	 * @param array $data
	 * @param int $turn
	 * @param int $userID
	 */
	static function sSave($data, $turn, $userID, $tParcelStatistics) {

		$db = DataBaseController::getInstance();
		
		$retVal = '';

		try {

			foreach ( $data as $tKey => $tValue ) {

				$entry = new DbStatistics();
				$entry->setUserid($userID);
				$entry->setByclass($tKey);
				$entry->setTurn($turn);
				$entry->setPopulation($tValue->LF_SUM);
				$entry->save();
				
				if ($tKey == 'global') {

					$entry = new DbStatistics();
					$entry->setUserid($userID);
					$entry->setTurn($turn);
					$entry->setParameter('FoodUsage');
					$entry->setValue($tValue->FOOD_USAGE);
					$entry->save();
					
				}

			}

			/*
			 * Zapisz produkcję pożywienia
			 */
			$entry = new DbStatistics();
			$entry->setUserid($userID);
			$entry->setTurn($turn);
			$entry->setParameter('FoodProduction');
			$entry->setValue($tParcelStatistics->FOOD_GENERATION);
			$entry->save();
			
			/*
			* Zapisz dostępne pożywienie
			*/
			$entry = new DbStatistics();
			$entry->setUserid($userID);
			$entry->setTurn($turn);
			$entry->setParameter('FoodCurrent');
			$entry->setValue($tParcelStatistics->FOOD_CURRENT);
			$entry->save();

		} catch ( Exception $e ) {
			$retVal = 'Błąd zapisu statystyk';
			psDebug::cThrow(null, $e, array('display'=>false));
		}

		return $retVal;
	}

}