<?php

class parcel extends item {
  
  /**
   * Pozycja
   *
   * @var position
   */
  public $Position;
  
  /**
   * Pożywienie w polu
   *
   * @var food
   */
  public $Food;
  public $LifeFormCount = 0;

  static function sGetSummary($params) {

    global $db;
    
    $retVal = '';
    
    $parcelSaver = new itemSaver ( 'parcels', $params ['loggedUserID'] );
    
    $parcelArray = array ();
    
    $parcelSaver->get ( $parcelArray );
    
    $parcel = $parcelArray [$params ['X']] [$params ['Y']];
    
    unset ( $parcelArray );
    
    $template = new psTemplate ( '../templates/parcelDetail.html' );
    $template->add ( 'PositionX', $parcel->Position->X );
    $template->add ( 'PositionY', $parcel->Position->Y );
    $template->add ( 'FoodCurrent', $parcel->Food->Current );
    $template->add ( 'FoodMax', $parcel->Food->Max );
    $template->add ( 'FoodRegeneration', $parcel->Food->Regeneration );
    
    $tString = '';
    $lifeFormSaver = new itemSaver ( 'lifeforms', $params ['loggedUserID'] );
    $lifeFormSaver->get ( $encjakArray );
    
    /*
     * Znajdź formy życia 
     */
    foreach ( $encjakArray as $tEncjak ) {
      if ($tEncjak->getPosition ()->X == $parcel->Position->X && $tEncjak->getPosition ()->Y == $parcel->Position->Y) {
        $row = new psTemplate ( '../templates/parcelDetailRow.html' );
        $row->add ( 'LifeFormID', $tEncjak->LifeFormID );
        $row->add ( 'ageCurrent', $tEncjak->getAge ()->Current );
        $row->add ( 'healthCurrent', $tEncjak->getHealth ()->Current );
        $row->add ( 'healthMax', $tEncjak->getHealth ()->Max );
        $row->add ( 'className', get_class ( $tEncjak ) );
        $tString .= $row;
      }
    }
    $template->add ( 'tableRows', $tString );
    
    $retVal .= $template;
    
    return $retVal;
  }

  static function sGetLifeFormHealth(position $position, $class) {

    global $cache, $encjakArray;
    
    if (empty ( $encjakArray )) {
      $lifeFormSaver = new itemSaver ( 'lifeforms', $_SESSION ['loggedUserID'] );
      $lifeFormSaver->get ( $encjakArray );
    }
    
    $module = 'healthClass';
    $property = md5 ( serialize ( $position ) . $class );
    
    $retVal = 0;
    
    if (! $cache->check ( $module, $property )) {
      
      foreach ( $encjakArray as $tEncjak ) {
        
        if (get_class ( $tEncjak ) == $class && $position->X == $tEncjak->getPosition ()->X && $position->Y == $tEncjak->getPosition ()->Y) {
          $retVal += $tEncjak->getHealth ()->Current;
        }
      
      }
      $cache->set ( $module, $property, $retVal );
    } else {
      $retVal = $cache->get ( $module, $property );
    }
    
    return $retVal;
  
  }

  static function sGetLifeFormPopulation(position $position, $class) {

    global $cache, $encjakArray;
    
    if (empty ( $encjakArray )) {
      $lifeFormSaver = new itemSaver ( 'lifeforms', $_SESSION ['loggedUserID'] );
      $encjakArray = array ();
      $lifeFormSaver->get ( $encjakArray );
    }
    
    $module = 'populationClass';
    $property = md5 ( serialize ( $position ) . $class );
    
    //@todo zamiast za każdym razem szukać w całej tabeli, na początku skryptu dokonać agregacji
    
    $retVal = 0;
    
    if (! $cache->check ( $module, $property )) {
      
      foreach ( $encjakArray as $tEncjak ) {
        
        if (get_class ( $tEncjak ) == $class && $position->X == $tEncjak->getPosition ()->X && $position->Y == $tEncjak->getPosition ()->Y) {
          $retVal ++;
        }
      
      }
      $cache->set ( $module, $property, $retVal );
    } else {
      $retVal = $cache->get ( $module, $property );
    }
    
    return $retVal;
  
  }

  /**
   * pobranie populacji
   *
   * @param position $position
   * @return int
   */
  static function sGetPopulation(position $position) {

    global $parcelArray;
    
    return $parcelArray [$position->X] [$position->Y]->LifeFormCount;
  }

  /**
   * Zmniejszenie populacji w polu
   *
   * @param position $position
   */
  static function sDecPopulation(position $position, &$tArray = null) {

    if ($tArray == null) {
      global $parcelArray;
      $parcelArray [$position->X] [$position->Y]->LifeFormCount --;
    } else {
      $tArray [$position->X] [$position->Y]->LifeFormCount --;
    }
  
  }

  /**
   * Zwiększenie populacji w polu
   *
   * @param position $position
   */
  static function sIncPopulation(position $position, &$tArray = null) {

    global $parcelArray;
    
    if ($tArray == null) {
      global $parcelArray;
      $parcelArray [$position->X] [$position->Y]->LifeFormCount ++;
    } else {
      $tArray [$position->X] [$position->Y]->LifeFormCount ++;
    }
  
  }

  /**
   * Zmniejszenie pożywienia w polu
   *
   * @param position $postition
   * @param int $amount
   */
  static function sDecFood(position $postition, $amount = 1) {

    global $parcelArray;
    
    $parcelArray [$postition->X] [$postition->Y]->decFood ( $amount );
  }

  /**
   * Zwiększenie pożywienia w polu
   *
   * @param position $position
   * @param int $amount
   */
  static function sIncFood(position $position, $amount = 1) {

    global $parcelArray;
    
    $parcelArray [$position->X] [$position->Y]->incFood ( $amount );
  }

  /**
   * Pobranie jedzenia w wybranej parceli
   *
   * @param position $position
   * @return int
   */
  static function sGetFood(position $position) {

    global $parcelArray;
    
    return $parcelArray [$position->X] [$position->Y]->getFood ()->Current;
  }

  /**
   * Ustawienie jedzenia w wybranej parceli
   *
   * @param position $position
   * @param int $value
   * @return boolean
   */
  static function sSetFood(position $position, $value) {

    global $parcelArray;
    
    $parcelArray [$position->X] [$position->Y]->setFood ( $value );
    return true;
  }

  /**
   * @return int
   */
  public function getFood() {

    return $this->Food;
  }

  /**
   * @param int $Food
   */
  public function setFood($Food) {

    $this->Food->Current = $Food;
    if ($this->Food->Current > $this->Food->Max) {
      $this->Food->Current = $this->Food->Max;
    }
  }

  /**
   * Konstruktor
   *
   * @param int $X
   * @param int $Y
   * @param int $Food
   * @param int $FoodMax
   * @param int $FoodRegeneration
   */
  function __construct($X = null, $Y = null, $Food = 100, $FoodMax = 100, $FoodRegeneration = 1) {

    $this->Position = new position ( $X, $Y );
    /*
			 * Przepisz z parametrów
			 */
    $this->Food = new food ( $Food, $FoodMax, $FoodRegeneration );
  
  }

  /**
   * Inicjalizacja tablicy parceli
   *
   * @param array $parcelArray
   * @param int $size
   * @param int $averageMax
   * @param int $averageRegeneration
   */
  static function sQuickInit(&$parcelArray, $size, $averageMax = 100, $averageRegeneration = 7) {

    $tImage = imagecreatetruecolor ( $size, $size );
    
    for($tIndexX = 0; $tIndexX < $size; $tIndexX ++) {
      for($tIndexY = 0; $tIndexY < $size; $tIndexY ++) {
        imagesetpixel ( $tImage, $tIndexX, $tIndexY, imagecolorallocate ( $tImage, rand ( 1, 255 ), rand ( 1, 255 ), 0 ) );
      }
    }
    
    $gaussian = array (array (1.0, 2.0, 1.0 ), array (2.0, 4.0, 2.0 ), array (1.0, 2.0, 1.0 ) );
    
    imageconvolution ( $tImage, $gaussian, 16, 0 );
    
    imagepng ( $tImage, 'this.png' );
    
    $MaxSum = 0;
    $RegenerationSum = 0;
    
    $parcelArray = array ();
    for($tIndexX = 0; $tIndexX < $size; $tIndexX ++) {
      for($tIndexY = 0; $tIndexY < $size; $tIndexY ++) {
        $tColor = imagecolorat ( $tImage, $tIndexX, $tIndexY );
        $r = ($tColor >> 16) & 0xFF;
        $g = ($tColor >> 8) & 0xFF;
        $MaxSum += $r;
        $RegenerationSum += $g;
        $tItem = new parcel ( $tIndexX + 1, $tIndexY + 1, 0, $r, $g );
        $parcelArray [$tIndexX + 1] [$tIndexY + 1] = $tItem;
      }
    }
    
    $MaxDivider = $averageMax / ($MaxSum / ($size * $size));
    $RegenerationDivider = $averageRegeneration / ($RegenerationSum / ($size * $size));
    
    /*
     * Dokonaj normalizacji pól
     */
    for($tIndexX = 0; $tIndexX < $size; $tIndexX ++) {
      for($tIndexY = 0; $tIndexY < $size; $tIndexY ++) {
        $tItem = $parcelArray [$tIndexX + 1] [$tIndexY + 1];
        $tItem->Food->Max = round ( $tItem->Food->Max * $MaxDivider );
        $tItem->Food->Regeneration = round ( $tItem->Food->Regeneration * $RegenerationDivider );
        $tItem->Food->Current = $tItem->Food->Max;
      }
    }
  
  }

  public function incFood($amount = 1) {

    $this->Food->Current += $amount;
    if ($this->Food->Current > $this->Food->Max) {
      $this->Food->Current = $this->Food->Max;
    }
  }

  public function decFood($amount = 1) {

    $this->Food->Current -= $amount;
    if ($this->Food->Current < 0) {
      $this->Food->Current = 0;
    }
  }

  static function sGenerateFood(&$parcelArray) {

    foreach ( $parcelArray as $Value ) {
      foreach ( $Value as $tParcel ) {
        $tParcel->incFood ( $tParcel->Food->Regeneration );
      }
    }
  }

  /**
   * Naprawa liczby form życia
   *
   */
  static function sFixPopulationCount(&$parcelArray, &$encjakArray) {

    foreach ( $parcelArray as $Value ) {
      foreach ( $Value as $tParcel ) {
        $tParcel->LifeFormCount = 0;
      }
    }
    
    foreach ( $encjakArray as $tEncjak ) {
      parcel::sIncPopulation ( $tEncjak->Position );
    }
  }

  /**
   * Ustawienie pożyciwenia na max w każdym polu
   *
   * @param array $parcelArray
   */
  static function sQuickSetMaxFood(&$parcelArray) {

    foreach ( $parcelArray as $Value ) {
      foreach ( $Value as $tParcel ) {
        $tParcel->Food->Current = $tParcel->Food->Max;
      }
    }
  }

  /**
   * Ustawienie pożywienia na max, wywoływane przez ajax
   *
   * @param array $params
   */
  static function sSetMaxFood($params) {

    $parcelSaver = new itemSaver ( 'parcels', $_SESSION ['loggedUserID'] );
    $parcelArray = array ();
    $parcelSaver->get ( $parcelArray );
    parcel::sQuickSetMaxFood ( $parcelArray );
    $parcelSaver->put ( $parcelArray );
  
  }

}

?>