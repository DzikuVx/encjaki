<?php

class lastNews extends news {

  function __construct($displayComments = false) {

    global $db;
    
    $this->displayComments = $displayComments;
    
    $tQuery = "SELECT * FROM news WHERE Type='normal' AND Language='{$_REQUEST['language']}' ORDER BY NewsID DESC LIMIT 1";
    $tQuery = $db->execute ( $tQuery );
    while ( $tResult = $db->fetch ( $tQuery ) ) {
      foreach ( $tResult as $tKey => $tValue ) {
        if (property_exists ( $this, $tKey )) {
          $this->{$tKey} = $tValue;
        }
      }
    }
  }

}

?>