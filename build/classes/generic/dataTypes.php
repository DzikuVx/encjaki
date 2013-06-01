<?php

/**
 * Klasa formatująca typy danych
 */
class dataTypes {

  static function parseBoolean($value) {

    if ($value == 'yes') {
      return 'Tak';
    } else {
      return 'Nie';
    }
  }

  static function fixCheckboxValue(&$value) {

    if (! isset ( $value )) {
      $value = 'no';
    }
  
  }

  static function strip($string) {

    $string = str_replace ( "'", '&quot;', $string );
    $string = str_replace ( '"', '&quot;', $string );
    return $string;
  }

  /**
   * Sformatowanie nazyw zgłoszenia na podstawie jego ID
   *
   * @param int $eventID
   * @return string
   */
  static function formatEventName($eventID) {

    $retVal = $eventID;
    
    $toGo = 8 - strlen ( $eventID );
    
    for($tIndex = 0; $tIndex < $toGo; $tIndex ++) {
      $retVal = "0" . $retVal;
    }
    
    return $retVal;
  }

  /**
   * Zamiana pustego ciągu na &nbsp;
   *
   * @param string $tString
   * @return string
   */
  static function keepNbsp($tString) {

    if (trim ( $tString ) == "" || $tString == " ") {
      return "&nbsp;";
    } else {
      return $tString;
    }
  
  }

  /**
   * Funkcja formatująca datę do postaci YYYY-MM-DD
   * @param $date
   * @return sformatowana data
   */
  static function formatDate($date) {

    $retVal = date ( "Y-m-d", strtotime ( $date ) );
    return $retVal;
  }

  /**
   * Funkcja formatująca datę do postaci YYYY-MM-DD HH:ii
   * @param $date
   * @return sformatowana data
   */
  static function formatDateTime($date) {

    if (! is_numeric($date)) {
      $date = strtotime($date);
    }
    
    $retVal = date ( "Y-m-d H:i", $date);
    return $retVal;
  }

  /**
   * Funkcja formatująca datę do postaci HH-ii
   * @param $date
   * @return sformatowana data
   */
  static function formatTime($date) {

    $retVal = date ( "H:i", strtotime ( $date ) );
    return $retVal;
  }

  /**
   * Funkcja zwaracająca datę w postaci YYYY-MM-DD z UNIX Timestam
   * @param $date
   * @return sformatowana data
   */
  static function getDate($date) {

    $retVal = date ( "Y-m-d", $date );
    return $retVal;
  }

  /**
   * Funkcja zwaracająca datę w postaci YYYY-MM-DD HH:ii z UNIX Timestam
   * @param $date
   * @return sformatowana data
   */
  static function getDateTime($date) {

    if ($date == null) {
      return '-';
    }
    
    $retVal = date ( "Y-m-d H:i", $date );
    return $retVal;
  }

  /**
   * Funkcja formatująca wartość do postaci xxx xxx,xx
   * @param $value
   * @param $unit jednostka wartości
   * @return sformatowana wartość
   */
  static function formatValue($value, $unit = "zł") {

    $retVal = number_format ( $value, 2, ",", " " ) . " " . $unit;
    return $retVal;
  }

  /**
   * Formatowanie liczby całokowitej
   *
   * @param int $value
   * @return string
   */
  static function formatCount($value) {

    $retVal = number_format ( $value, 0, "", " " );
    return $retVal;
  }

  /**
   * Funkcja zwracająca ilość dni w miesiącu
   *
   * @param unknown_type $year
   * @param unknown_type $month
   * @return unknown
   */
  static function getDaysInMonth($year, $month) {

    $go = 0;
    $day_in_month = 31;
    while ( $go == 0 )
      if (checkdate ( $month, $day_in_month, $year ) == true) {
        $go = 1;
      } else {
        $day_in_month --;
      }
    return $day_in_month;
  }

  /**
   * Zwraca liczbę minut z sekund (zaokrąglenie do góry)
   *
   * @param int $length
   * @return int
   */
  static function getMinutesFromSeconds($length) {

    return ceil ( $length / 60 );
  }

}
?>