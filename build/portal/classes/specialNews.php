<?php

class specialNews extends news {

  function __construct($id = null) {

    global $db;
    if ($id != null) {
      $tQuery = "SELECT * FROM news WHERE Type='{$id}' AND Language='{$_REQUEST['language']}'";
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

}

?>