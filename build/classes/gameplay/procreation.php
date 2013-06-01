<?php

class procreation {
  
  /**
   * liczba tur za którą forma będzie mogła się ponownie rozmnożyć
   *
   * @var int
   */
  public $TurnsToProcreate = 0;
  
  /**
   * Liczba spłodzonych potomków
   *
   * @var int
   */
  public $ChildrenCount = 0;
  
  /**
   * Liczba tur co którą forma życia może się rozmanażać
   *
   * @var int
   */
  public $Threshold = 5;

  /**
   * Konstruktor
   *
   * @param int $turns
   * @param int $children
   * @param int $threshold
   */
  function __construct($turns = 0, $children = 0, $threshold = 5) {

    $this->TurnsToProcreate = $turns;
    $this->ChildrenCount = $children;
    $this->Threshold = $threshold;
  }

}

?>