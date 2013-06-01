<?php

class health {
  
  public $Current = 100;
  public $Max = 100;

  function __construct($current, $max) {

    $this->Current = $current;
    $this->Max = $max;
  }

}

?>