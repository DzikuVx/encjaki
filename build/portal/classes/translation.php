<?php

class translation {
  
  protected $list = null;

  function __construct($language = 'pl') {

    require 'translation.php';
    
    $this->list = $translationList [$language];
  
  }

  public function get($text) {

    if (isset ( $this->list [$text] )) {
      return $this->list [$text];
    } else {
      return '??' . $text . '??';
    }
  
  }

}

?>