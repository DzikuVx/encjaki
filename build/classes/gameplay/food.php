<?php

class food {
  
  /**
   * Obecne
   *
   * @var int
   */
  public $Current = 0;
  
  /**
   * Maksymalna wartość
   *
   * @var int
   */
  public $Max = 0;
  
  /**
   * Ile regenerować
   *
   * @var int
   */
  public $Regeneration = 0;

  /**
   * Konstruktor
   *
   * @param int $current
   * @param int $max
   * @param int $regeneration
   */
  function __construct($current = 100, $max = 100, $regeneration = 1) {

    $this->Current = $current;
    $this->Max = $max;
    $this->Regeneration = $regeneration;
  
  }

}

?>