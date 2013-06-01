<?php

/**
 * MySQL data base interface class
 * 
 * @author Pawel Spychalski <pawel@spychalski.info>
 * @link http://www.spychalski.info
 * @category  universal
 * @version 0.95
 * @see dbException
 * @copyright 2009 Lynx-IT Pawel Stanislaw Spychalski
 * 
 */
class dataBase {
  protected $dbHandle = null;
  protected $dbConfig;
  protected $queryCount = 0;
  
  /**
   * Czy nastąpiło połączenie do bazy danych
   *
   * @var boolean
   */
  protected $connected = false;

  /**
   * Cytowanie stringa do mysqla
   *
   * @param string $string
   * @return string
   */
  public function quote($string) {

    return mysql_real_escape_string ( $string, $this->dbHandle );
  }

  /**
   * Zwraca liczbę wierszy zapytania
   *
   * @param resource $query
   * @return int
   */
  public function count($query) {

    return mysql_num_rows ( $query );
  }

  /**
   * Zwraca ID z ostatniego wstawienia
   *
   * @return int
   */
  public function lastUsedID() {

    return mysql_insert_id ();
  }

  /**
   * Wykonanie zapytania bazy danych
   *
   * @param string $query
   * @return resource
   */
  public function execute($query) {

    if (! $this->connected) {
      $this->connect ();
    }
    
    if ($this->dbHandle == null)
      return false;
    $this->queryCount ++;
    
    $tResult = mysql_query ( $query, $this->dbHandle );
    if (! $tResult) {
      throw new dbException ( mysql_error () );
    }
    
    return $tResult;
  }

  /**
   * Pobranie kolejnych pól z wyniku zapytania
   *
   * @param resource $result
   * @return obiekt zawierający zwracana pola
   */
  public function fetch($result) {

    if ($this->dbHandle == null)
      return false;
    $tResult = mysql_fetch_object ( $result );
    return $tResult;
  }

  /**
   * Wybór bazy danych na serwerze
   *
   * @return true
   */
  public function selectDB() {

    if (! $this->connected) {
      $this->connect ();
    }
    
    mysql_select_db ( $this->dbConfig ['database'] );
    return true;
  }

  protected function connect() {

    try {
      
      if ($this->dbConfig ['persistent']) {
        $this->dbHandle = mysql_pconnect ( $this->dbConfig ['host'], $this->dbConfig ['login'], $this->dbConfig ['password'] );
      } else {
        $this->dbHandle = mysql_connect ( $this->dbConfig ['host'], $this->dbConfig ['login'], $this->dbConfig ['password'] );
      }
      
      if (empty ( $this->dbHandle )) {
        throw new dbException ( 'No connection' );
      }
      
      $this->connected = true;
      $this->dbConfig ['handle'] = $this->dbHandle;
      
      //Wybierz bazę danych
      $this->selectDB ();
      
      //Ustaw parametry połącznia
      $this->execute ( "SET NAMES 'UTF8'" );
      $this->execute ( "SET CHARACTER SET 'UTF8'" );
    
    } catch ( Exception $e ) {
      psDebug::halt ( 'Brak połączenia z bazą danych', $e, array ('display' => false ) );
    }
  }

  /**
   * Konstruktor bazy danych
   *
   * @param array konfiguracja połączenia
   * @return boolean
   */
  public function __construct($dbConfig) {

    $this->dbConfig = $dbConfig;
    
    return true;
  }

  /**
   * Pobranie uchwytu do bazy danych
   *
   * @return resource
   */
  public function getHandle() {

    if ($this->dbHandle == null)
      return false;
    return $this->dbHandle;
  }

  /**
   * Zamknięcie połączenia z bazą danych
   *
   */
  public function close() {

    mysql_close ( $this->dbHandle );
    return true;
  }

}

/**
 * Wyjątek dla błędów bazy danych
 * 
 * @see dataBase
 *
 */
class dbException extends Exception {

}

?>