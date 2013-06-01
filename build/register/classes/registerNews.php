<?php

class registerNews extends registerBase implements iRegisterBase {
  
  /**
   * Czy pokazywać pełny tekst, czy tylko do znacznika |more|
   *
   * @var boolean
   */
  protected $showFullText = true;
  
  /**
   * Czy wyświetlony jest tekst skrócony i istenieje |more|
   *
   * @var boolean
   */
  protected $renderFollowLink = false;

  /**
   * Konstruktor
   *
   * @param stdClass $tData
   * @param int $id
   */
  public function __construct($tData = null, $params = null) {

    global $db;
    
    try {
      
      if ($params != null) {
        $this->load ( $params ['id'] );
      } elseif ($tData != null) {
        $this->data = $tData;
      }
      $this->prepare ();
    } catch ( Exception $e ) {
      psDebug::halt ( null, $e, array ('display' => false ) );
    }
  }

  protected function prepare() {

    if (! empty ( $this->data )) {
      if (! $this->showFullText) {
        
        $tString = registerNews::sGetShortText ( $this->data->Text );
        
        if ($tString != $this->data->Text) {
          $this->renderFollowLink = true;
        }
        
        $this->data->Text = $tString;
      } else {
        $this->data->Text = registerNews::sRemoveMarks ( $this->data->Text );
      }
    }
    
    if ($this->renderFollowLink) {
      $this->data->Text .= '<a class="newsFollow" href="?module=registerNews&id=' . $this->data->NewsID . '" title="Czytaj dalej...">&gt;&gt;Czytaj dalej...</a>';
    }
  }

  /**
   * Pobranie z bazy danych
   *
   * @param int $id
   */
  protected function load($id) {

    global $db, $sCache;
    
    $module = 'news::load';
    $property = $id;
    
    try {
      
      if (! $sCache->check ( $module, $property )) {
        $tQuery = "SELECT * FROM news WHERE NewsID='{$id}'";
        $tQuery = $db->execute ( $tQuery );
        while ( $tResult = $db->fetch ( $tQuery ) ) {
          $this->data = $tResult;
        }
        $sCache->set ( $module, $property, $this->data, 7200 );
      } else {
        $this->data = $sCache->get ( $module, $property );
      }
    } catch ( Exception $e ) {
      psDebug::halt ( null, $e, array ('display' => false ) );
    }
  }

  /**
   * Pobranie treści skróconej newsa, przed znacznikiem |more|
   *
   * @param string $text
   * @return string
   */
  static function sGetShortText($text) {

    $pos = mb_strpos ( $text, '|more' );
    
    if ($pos === false) {
      return $text;
    } else {
      
      return mb_substr ( $text, 0, $pos );
    }
  }

  /**
   * Usunięcie znacznika |more|
   *
   * @param unknown_type $text
   * @return unknown
   */
  static function sRemoveMarks($text) {

    $retVal = str_replace ( '|more|', '', $text );
    return $retVal;
  }

  /**
   * Renderowanie newsa
   *
   * @return string
   */
  public function __toString() {

    $retVal = '';
    $template = new psTemplate ( 'templates/news.html' );
    $this->data->CreateTime = dataTypes::formatDateTime ( $this->data->CreateTime );
    $template->add ( $this->data );
    $retVal .= $template;
    
    return $retVal;
  }

  /**
   * Funckja interface
   *
   * @param array $params
   * @return string
   */
  public function get($params) {

    try {
      
      $this->load ( $params ['id'] );
      $this->prepare ();
    
    } catch ( Exception $e ) {
      return psDebug::cThrow ( null, $e, array ('display' => false ) );
    }
    return $this->__toString ();
  }

}

?>