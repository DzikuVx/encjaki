<?php

/**
 * Klasa prostej obsługi XML
 *
 */
class xml {
  
  protected $xml;

  /**
   * Naprawa znaków HTML
   *
   * @param string $xml
   * @return string
   */
  function fixSpecialChars($xml) {

    $xml = htmlspecialchars ( $xml );
    return $xml;
  }

  /**
   * Ustawienie nowej wartości
   *
   * @param string $tag
   * @param string $value
   * @return boolean;
   */
  function setValue($tag, $value) {

    $start_mark = "<" . $tag . ">";
    $end_mark = "</" . $tag . ">";
    
    $this->xml .= $start_mark . $value . $end_mark;
    
    return true;
  }

  public function dummy() {

    return true;
  }

  /**
   * Pobranie wartości
   *
   * @param string $tag
   * @return string
   */
  function getValue($tag) {

    $out = null;
    
    $start_mark = "<" . $tag . ">";
    $end_mark = "</" . $tag . ">";
    
    $start = strpos ( $this->xml, $start_mark );
    $stop = strpos ( $this->xml, $end_mark );
    
    if (($start !== false) and ($stop !== false)) {
      $out = substr ( $this->xml, $start + strlen ( $start_mark ), $stop - $start - strlen ( $start_mark ) );
    }
    
    return $out;
  }

  /**
   * Konstruktor
   *
   * @param string $xml
   */
  function __construct($xml) {

    $xml = str_replace ( "'", "\'", $xml );
    
    $this->xml = $xml;
  }

  /**
   * Destruktor
   *
   */
  function destroy() {

    unset ( $this );
  }

  /**
   * Pobranie pełnego xml
   *
   * @return string
   */
  public function getRawData() {

    return $this->xml;
  }

}
?>