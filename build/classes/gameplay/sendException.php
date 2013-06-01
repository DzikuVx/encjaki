<?php

class sendException extends Exception {

  /**
   * Enter description here...
   *
   * @param int $LifeFormID
   * @param string $message
   */
  public function __construct($LifeFormID, $message) {

    psDebug::send ( $message . ' | Forma życia: ' . $LifeFormID );
    
    parent::__construct ( 'Mutacja' );
  }

}

?>