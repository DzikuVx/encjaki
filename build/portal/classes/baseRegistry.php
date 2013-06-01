<?php

abstract class baseRegistry {
  
  /**
   * Nazwa sesji
   *
   * @var string
   */
  protected $sessionName;
  
  /**
   * Czy używać zaawansowanych selektów
   *
   * @var array
   */
  protected $useSearchSelects = array ();
  
  /**
   * Tablica pomocnicza selektora wyszukiwania
   *
   * @var array
   */
  protected $tSearchArray = array ();
  
  /**
   * Params
   *
   * @var array
   */
  protected $params;
  
  /**
   * User
   *
   * @var users
   */
  protected $user;
  
  /**
   * Czy istnieje metoda show dla pozycji rejestru
   *
   * @var boolean
   */
  protected $allowDetail = true;
  
  /**
   * Definicja klasy obsługującej pozycje rejestru
   * @var string
   */
  protected $itemClass = "";
  
  /**
   * Ustawienia praw do rejestru
   *
   * @var array
   */
  protected $rightsSet = array ();
  
  /**
   * Tytuł rejestru
   *
   * @var string
   */
  protected $registryTitle = "";
  
  /**
   * Nazwa pola bazy danych identyfikującego rejestr
   *
   * @var string
   */
  protected $registryIdField = "";
  
  /**
   * Warunek WHERE zapytania
   *
   * @var string
   */
  protected $selectCondition = "";
  
  /**
   * Warunek ORDER BY zapytania
   *
   * @var string
   */
  protected $sortCondition = "";
  
  /*
	 * Lista pól zwaracanych z bazy danych
	 */
  protected $selectList = "";
  
  /*
	 * Lista tabel uczestniczących w zapytaniu (warunek FROM)
	 */
  protected $tableList = "";
  
  protected $tableDateField = "";
  
  /*
	 * Lista kolumn tabeli rejestru
	 */
  protected $tableColumns = array ();
  
  /*
	 * Pole tabeli używane do zliczania liczby rekordów
	 */
  protected $selectCountField = "";
  
  /*
	 * Dodatkowy, stały warunek zapytania (WHERE)
	 */
  protected $extraList = "";
  
  /**
   * Lista kolumn użytych do wyszukiwania
   *
   * @var array
   */
  protected $searchTable = array ();
  
  /**
   * Lista kolumn użytych do sortowania
   *
   * @var array
   */
  protected $sortTable = array ();
  
  /**
   * Przesunięcie zapytania
   *
   * @var int
   */
  protected $limitSkip = 0;
  
  /**
   * LIczba wyników na stronę
   *
   * @var int
   */
  protected $limitNumber = 30;
  
  /**
   * Czy stosować nawigację pomiędzy stronami
   *
   * @var boolean
   */
  protected $usePageNav = true;
  
  /**
   * Liczba rekordów w rejestrze
   *
   * @var int
   */
  protected $resultCount = 0;
  
  /*
	 * Domyśla kolumna używana w sortowaniu
	 */
  protected $defaultSorting = "";
  
  /*
	 * Domyślny kierunek sortowania
	 */
  protected $defaultSortingDirection = "ASC";
  
  protected $queryResult;
  
  /**
   * Czy uruchomić wyszukiwanie w rejestrze
   *
   * @var boolean
   */
  protected $enableSearch = true;

  /**
   * Konstruktor
   *
   * @param dataBase $db
   */
  function __construct() {

    $this->sessionName = get_class ( $this ) . "Search";
  }

  /**
   * Przeglądanie rejestru
   *
   * @param users $user
   * @param xml $xml
   * @return string
   */
  public function browse($user, $params) {

    $retVal = "";
    
    $this->user = $user;
    $this->params = $params;
    
    /*
		 * Inicjacja danych rejestru
		 */
    $this->prepare ();
    
    /*
		 * Przygotuj tablicę pomocniczą wyszukiwania
		 */
    $this->useSearchSelects ['search'] = false;
    $this->useSearchSelects ['dateSelect'] = false;
    $this->useSearchSelects ['sort'] = false;
    
    if ($this->sortTable != null) {
      $this->useSearchSelects ['sort'] = true;
    }
    
    if ($this->searchTable != null) {
      foreach ( $this->searchTable as $tKey => $tValue ) {
        
        /*
       * Ustalenie typów
       */
        switch ($tKey) {
          
          case "__DateSelect__" :
            $this->useSearchSelects ['dateSelect'] = true;
            
            break;
          
          default :
            $this->useSearchSelects ['search'] = true;
            $this->tSearchArray [$tKey] = $tValue;
            break;
        }
      }
    }
    
    /*
		 * Jeśli nie ma ustawionej sesji, zainicjuj
		 */
    if (! isset ( $_SESSION [$this->sessionName] )) {
      $_SESSION [$this->sessionName] ['searchValue'] = "";
      $_SESSION [$this->sessionName] ['searchIn'] = "";
      $_SESSION [$this->sessionName] ['limitSkip'] = 0;
      $_SESSION [$this->sessionName] ['startDate'] = time () - 2592000;
      $_SESSION [$this->sessionName] ['endDate'] = strtotime ( date ( "Y-m-d", time () ) . " 23:59:59" );
      $_SESSION [$this->sessionName] ['SearchSortBy'] = $this->defaultSorting;
      $_SESSION [$this->sessionName] ['SearchSortDirection'] = $this->defaultSortingDirection;
    }
    
    /*
		 * Przepisz pojedyncze dane do sesji
		 */
    if (isset ( $this->params ['limitSkip'] )) {
      $_SESSION [$this->sessionName] ['limitSkip'] = $params ['limitSkip'];
    }
    
    if (isset ( $this->params ['searchText'] )) {
      $_SESSION [$this->sessionName] ['searchValue'] = $params ['searchText'];
      $_SESSION [$this->sessionName] ['searchIn'] = $params ['searchSelect'];
    }
    
    if (isset ( $this->params ['startDate'] )) {
      $_SESSION [$this->sessionName] ['startDate'] = strtotime ( $this->params ['startDate'] . " 00:00:00" );
    }
    
    if (isset ( $this->params ['endDate'] )) {
      $_SESSION [$this->sessionName] ['endDate'] = strtotime ( $this->params ['endDate'] . " 23:59:59" );
    }
    
    if (isset ( $this->params ['SearchSortBy'] )) {
      $_SESSION [$this->sessionName] ['SearchSortBy'] = $this->params ['SearchSortBy'];
      $_SESSION [$this->sessionName] ['SearchSortDirection'] = $this->params ['SearchSortDirection'];
    }
    
    $this->prepareCondition ();
    $this->prepareSorting ();
    $this->getCount ();
    $this->getResults ();
    $retVal .= $this->renderTitle ();
    $retVal .= $this->renderTopButtons ();
    
    $retVal .= $this->renderSearch ();
    
    if ($this->usePageNav)
      $retVal .= $this->renderPageNav ();
    
    $retVal .= $this->openTable ();
    $retVal .= $this->populateTable ();
    $retVal .= $this->closeTable ();
    
    if ($this->usePageNav)
      $retVal .= $this->renderPageNav ();
    
    return $retVal;
  }

  protected function renderSearch() {

    $retVal = "";
    
    if ($this->searchTable == null) {
      return $retVal;
    }
    
    /*
		 * Wstępne parsowanie danych wyszukiwania
		 */
    
    if ($this->rightsSet ['allowAdd'] && $this->user->checkRole ( $this->rightsSet ['editRight'] )) {
      $retVal .= '<div style="float: right;">';
      $retVal .= controls::renderButton ( 'Dodaj', "document.location='?action=execute&amp;module=" . $this->itemClass . "&amp;method=add'" );
      $retVal .= '</div>';
    }
    
    $retVal .= "<form method='get' action='' name='searchForm'>";
    $retVal .= "<div>";
    if ($this->useSearchSelects ['search']) {
      /*
		   * Formularz wyszukiwania
		   */
      $retVal .= "<b>Szukaj: </b>";
      $retVal .= controls::renderInput ( 'text', $_SESSION [$this->sessionName] ['searchValue'], 'searchText', 'searchText' );
      $retVal .= " <b>w</b> ";
      
      $tOptions ['id'] = 'searchSelect';
      $retVal .= controls::renderSelect ( 'searchSelect', $_SESSION [$this->sessionName] ['searchIn'], $this->tSearchArray, $tOptions );
      $retVal .= controls::renderButton ( 'Filtruj', "document.searchForm.submit(); return true;", 'font-size: 8pt; padding-top: 1px; padding-bottom: 1px;' );
      $retVal .= controls::renderButton ( 'Usuń filtr', "clearSearch()", 'font-size: 8pt; padding-top: 1px; padding-bottom: 1px;' );
    }
    $retVal .= "</div>";
    
    /*
		 * Seletory zaawansowane
		 */
    if ($this->useSearchSelects ['dateSelect']) {
      $retVal .= "<div>";
      
      /*
			 * Selektor dat
			 */
      if ($this->useSearchSelects ['dateSelect']) {
        $retVal .= "<b>Od: </b><span id='startDate'>" . date ( "Y-m-d", $_SESSION [$this->sessionName] ['startDate'] ) . "</span>" . controls::renderImgButton ( 'popupOpen', "dateSelect(" . date ( "Y", $_SESSION [$this->sessionName] ['startDate'] ) . "," . date ( "n", $_SESSION [$this->sessionName] ['startDate'] ) . "," . date ( "j", $_SESSION [$this->sessionName] ['startDate'] ) . ",'startDate',0,0);", 'Wybierz datę' );
        $retVal .= " <b>Do: </b><span id='endDate'>" . date ( "Y-m-d", $_SESSION [$this->sessionName] ['endDate'] ) . "</span>" . controls::renderImgButton ( 'popupOpen', "dateSelect(" . date ( "Y", $_SESSION [$this->sessionName] ['endDate'] ) . "," . date ( "n", $_SESSION [$this->sessionName] ['endDate'] ) . "," . date ( "j", $_SESSION [$this->sessionName] ['endDate'] ) . ",'endDate',0,0);", 'Wybierz datę' );
        $retVal .= "<div id='datePicker'>&nbsp;</div>";
      }
      
      $retVal .= "<div>";
    }
    
    /*
		 * Sortowanie
		 */
    if ($this->useSearchSelects ['sort']) {
      $retVal .= "<div>";
      
      $retVal .= "<b>Sortuj według: </b>";
      
      $tOptions ['id'] = 'SearchSortBy';
      $retVal .= controls::renderSelect ( 'sortForm', $_SESSION [$this->sessionName] ['SearchSortBy'], $this->sortTable, $tOptions );
      
      $retVal .= "<select id='SearchSortDirection'>";
      if ($_SESSION [$this->sessionName] ['SearchSortDirection'] == 'ASC') {
        $tString1 = "selected";
        $tString2 = "";
      } else {
        $tString2 = "selected";
        $tString1 = "";
      }
      $retVal .= "<option value='ASC' $tString1>Rosnąco</option>";
      $retVal .= "<option value='DESC' $tString2>Malejąco</option>";
      $retVal .= "</select>";
      
      $retVal .= "</div>";
    }
    
    $retVal .= controls::renderInput ( 'hidden', 'execute', 'action' );
    $retVal .= controls::renderInput ( 'hidden', $this->itemClass, 'module' );
    $retVal .= controls::renderInput ( 'hidden', 'browse', 'method' );
    
    $retVal .= "</form>";
    
    return $retVal;
  }

  /**
   * Wyrenderowanie nawigacji pomiędzy stronami
   *
   * @return string
   */
  protected function renderPageNav() {

    $retVal = "<div style='text-align: center;'>";
    
    /*
		 * Oblicz łączną liczbę stron
		 */
    $tPageCount = ceil ( $this->resultCount / $this->limitNumber );
    if ($this->resultCount == 0)
      $tPageCount = 1;
    
    $tCurrentPage = ceil ( $this->limitSkip / $this->limitNumber ) + 1;
    
    /*
		 * Poprzednia strona
		 */
    if ($this->limitSkip > 0) {
      $tPrevSkip = $this->limitSkip - $this->limitNumber;
      if ($tPrevSkip < 0)
        $tPrevSkip = 0;
      $retVal .= controls::renderImgButton ( 'left', "document.location='?action=execute&amp;module=" . $this->itemClass . "&amp;method=browse&amp;limitSkip=" . $tPrevSkip . "'", "Poprzednia strona" );
    }
    
    $retVal .= "Strona " . $tCurrentPage . " z " . $tPageCount;
    
    /*
		 * Następna strona
		 */
    if ($this->limitSkip + $this->limitNumber < $this->resultCount) {
      $tNextSkip = $this->limitSkip + $this->limitNumber;
      $retVal .= controls::renderImgButton ( 'right', "document.location='?action=execute&amp;module=" . $this->itemClass . "&amp;method=browse&amp;limitSkip=" . $tNextSkip . "'", "Następna strona" );
    }
    
    $retVal .= "</div>";
    
    return $retVal;
  }

  /**
   * Wyświetlenie nazwy rejestru
   *
   * @return string
   */
  protected function renderTitle() {

    return "<div class=\"registryTitle\">{$this->registryTitle}</div>";
  }

  /**
   * Naprawienie przypadku gdy warunek WHERE jest pusty
   *
   * @return string
   */
  protected function fixEmptyWhere() {

    $retVal = "";
    
    if ($this->extraList == "" && $this->selectCondition == "") {
      $retVal = "1";
    } elseif ($this->extraList != "" && $this->selectCondition == "") {
      $retVal = $this->extraList;
    } elseif ($this->extraList == "" && $this->selectCondition != "") {
      $retVal = $this->selectCondition;
    } elseif ($this->extraList != "" && $this->selectCondition != "") {
      $retVal = $this->extraList . " AND " . $this->selectCondition;
    }
    
    return $retVal;
  }

  /**
   * Pobranie rejestru z bazy danych
   *
   */
  protected function getResults() {

    global $db;
    
    $tQuery = "SELECT {$this->selectList} FROM {$this->tableList} WHERE {$this->fixEmptyWhere()} ORDER BY {$this->sortCondition} LIMIT {$this->limitSkip},{$this->limitNumber} ";
    //		echo $tQuery;
    $this->queryResult = $db->execute ( $tQuery );
  }

  /**
   * Pobranie liczby elementów w rejestrze
   *
   */
  protected function getCount() {

    global $db;
    
    $tQuery = $db->execute ( "SELECT COUNT($this->selectCountField) AS ile FROM {$this->tableList} WHERE {$this->fixEmptyWhere()}" );
    while ( $tResult = $db->fetch ( $tQuery ) ) {
      $this->resultCount = $tResult->ile;
    }
  }

  /**
   * Przygotowanie warunku sortowania
   *
   */
  protected function prepareSorting() {

    $this->sortCondition = $_SESSION [$this->sessionName] ['SearchSortBy'] . " " . $_SESSION [$this->sessionName] ['SearchSortDirection'];
  
  }

  /**
   * Przygotowanie warunku dla rejestru
   *
   */
  protected function prepareCondition() {

    /*
     * Warunek kolejnej strony
     */
    $this->limitSkip = $_SESSION [$this->sessionName] ['limitSkip'];
    
    /*
		 * Warunek wyszukiwania w rejestrze
		 */
    $set = false;
    if ($_SESSION [$this->sessionName] ['searchValue'] != '') {
      $this->selectCondition .= $_SESSION [$this->sessionName] ['searchIn'] . " LIKE '%" . $_SESSION [$this->sessionName] ['searchValue'] . "%'";
      $set = true;
    }
    
    /*
		 * Selektor dat
		 */
    if ($this->useSearchSelects ['dateSelect']) {
      if ($set) {
        $this->selectCondition .= " AND ";
      }
      $this->selectCondition .= $this->tableDateField . " >= '" . $_SESSION [$this->sessionName] ['startDate'] . "' AND " . $this->tableDateField . " <= '" . $_SESSION [$this->sessionName] ['endDate'] . "'";
      $set = true;
    }
  
  }

  /**
   * Wygenerowanie łacza do szegółów rejestru
   * @param stdClass $tResult
   * @return string
   */
  protected function generateDetailString($tResult) {

    $retVal = "";
    
    if ($this->allowDetail) {
      $retVal = "onclick=\"document.location='?action=execute&amp;module={$this->itemClass}&amp;method=detail&amp;id={$tResult->{$this->registryIdField}}'";
      $retVal .= "\"";
    }
    
    return $retVal;
  }

  /*
	 * DUMMY
	 */
  protected function populateTable() {

    global $db;
    
    $retVal = "";
    
    $tIndex = $this->limitSkip;
    
    while ( $tResult = $db->fetch ( $this->queryResult ) ) {
      $tIndex ++;
      $retVal .= "<tr>";
      
      /*
			 * Generowanie stringu detail
			 */
      $detailString = $this->generateDetailString ( $tResult );
      
      $tArrayKeys = array_keys ( $this->tableColumns );
      
      foreach ( $tArrayKeys as $tKey ) {
        switch ($tKey) {
          
          case "#" :
            $retVal .= "<td $detailString>" . $tIndex . "</td>";
            break;
          
          case "__operations__" :
            $retVal .= "<td>" . $this->renderOperationsColumn ( $tResult->{$this->registryIdField}, $tResult ) . "</td>";
            break;
          
          case "CreateTime" :
            $retVal .= "<td $detailString>" . dataTypes::getDateTime ( $tResult->{$tKey} ) . "</td>";
            break;
          
          case "ProductImage" :
            $retVal .= "<td $detailString><a href='images/{$tResult->FileName}' lightboxed='true' title='" . product::sGetName ( $tResult->ProductID ) . "'><img alt='' title='' src='images/thumbs/{$tResult->FileName}' /></a>" . "</td>";
            break;
          
          default :
            $retVal .= "<td $detailString>" . $tResult->{$tKey} . "</td>";
            break;
        }
      }
      
      $retVal .= "</tr>";
    }
    return $retVal;
  }

  /**
   * Renderuje pole operacji na pozycji rejestru
   *
   * @param int $ID
   * @return string
   */
  protected function renderOperationsColumn($ID, $tResult = null) {

    $retVal = "";
    
    if ($this->rightsSet ['allowEdit'] && $this->user->checkRole ( $this->rightsSet ['editRight'] )) {
      $retVal .= controls::renderImgButton ( "edit", "document.location='?action=execute&amp;module={$this->itemClass}&amp;method=edit&amp;id={$ID}'", "Edytuj" );
    }
    
    if ($this->rightsSet ['allowDelete'] && $this->user->checkRole ( $this->rightsSet ['deleteRight'] )) {
      $retVal .= controls::renderImgButton ( "delete", "document.location='?action=execute&amp;module={$this->itemClass}&amp;method=delete&amp;id={$ID}'", "Usuń" );
    }
    
    return $retVal;
  }

  /**
   * Zamknięcie tabeli rejestru
   *
   * @return string
   */
  protected function closeTable() {

    $retVal = "</tbody>";
    $retVal .= "</table>";
    return $retVal;
  }

  /**
   * Otwarcie tabeli rejestru
   *
   * @return string
   */
  protected function openTable() {

    $retVal = "<table class='registry'>";
    $retVal .= "<thead>";
    $retVal .= "<tr>";
    
    foreach ( $this->tableColumns as $tKey => $tValue ) {
      
      switch ($tKey) {
        
        case "#" :
          $retVal .= "<th style=\"width: 1em;\">" . $tValue . "</th>";
          break;
        
        case "__operations__" :
          $retVal .= "<th style=\"width: 8em;\">" . $tValue . "</th>";
          break;
        
        default :
          $retVal .= "<th>" . $tValue . "</th>";
          break;
      }
    }
    
    $retVal .= "</tr>";
    $retVal .= "</thead>";
    $retVal .= "<tbody>";
    
    return $retVal;
  }

  /*
	 * DUMMY
	 */
  protected function prepare() {

  }

  /**
   * Destruktor
   *
   */
  public function destroy() {

    unset ( $this );
  }

  /**
   * Wyrenderowanie przycisków górnych - DUMMY
   *
   * @return string
   */
  protected function renderTopButtons() {

    return "";
  }

}
?>