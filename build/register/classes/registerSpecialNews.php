<?php

class registerSpecialNews extends registerNews implements iRegisterBase {

  public function __construct($tData = null, $id = null) {

    global $db, $sCache;
    try {
      
      if ($id != null) {
        
        $module = 'news::registerSpecialNews::__construct';
        $property = $id;
        
        if (! $sCache->check ( $module, $property )) {
          
          $tQuery = "SELECT * FROM news WHERE Type='{$id}' LIMIT 1";
          $tQuery = $db->execute ( $tQuery );
          while ( $tResult = $db->fetch ( $tQuery ) ) {
            $this->data = $tResult;
          }
          $sCache->set ( $module, $property, clone $this->data, 7200 );
        } else {
          $this->data = $sCache->get ( $module, $property );
        }
      } elseif ($tData != null) {
        $this->data = $tData;
      }
    
    } catch ( Exception $e ) {
      $this->data->Text = psDebug::cThrow ( null, $e, array ('display' => false ) );
    }
  }

  public function __toString() {

    return $this->data->Text;
  }

}

?>