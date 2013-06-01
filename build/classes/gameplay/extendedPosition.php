<?php

class extendedPosition extends position {
  
  /**
   * Parcela
   *
   * @var parcel
   */
  public $Parcel;

  function __construct($X, $Y) {

    parent::__construct ( $X, $Y );
    
    global $parcelArray;
    
    $this->Parcel = $parcelArray [$X] [$Y];
  
  }

}

?>