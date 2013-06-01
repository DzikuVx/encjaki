<?php

class mutation extends Exception {

  public function __construct($LifeFormID) {

    global $mutationWatcher;
    
    $mutationWatcher->push ( $LifeFormID );
    
    parent::__construct ( 'Mutacja' );
  }

}

?>