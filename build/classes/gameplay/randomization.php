<?php

class randomization {
  public $Min = 0;
  public $Max = 1;
  public $Divider = 1;

  /**
   * Konstruktor
   *
   * @param int $min
   * @param int $max
   * @param int $divider
   */
  function __construct($min = 0, $max = 1, $divider = 1) {

    $this->Min = $min;
    $this->Max = $max;
    $this->Divider = $divider;
  }

}

?>