<?php

class registerLinkBox extends registerBase implements iRegisterBase {

  public function get($params) {

    global $sCache, $db;
    
    $retVal = '';
    
    $module = 'link::registerLinkBox::__construct';
    $property = null;
    
    try {
      
      if (! $sCache->check ( $module, $property )) {
        
        $retVal .= '<ul>';
        
        $tQuery = "SELECT * FROM link WHERE Deleted='no' AND Language='{$this->language}' ORDER BY Name";
        $tQuery = $db->execute ( $tQuery );
        while ( $tResult = $db->fetch ( $tQuery ) ) {
          $retVal .= '<li><a href="' . $tResult->Link . '" title="' . $tResult->Name . '" target="_blank">' . $tResult->Name . '</a></li>';
        }
        
        $retVal .= '</ul>';
        
        $sCache->set ( $module, $property, $retVal, 7200 );
      } else {
        $retVal = $sCache->get ( $module, $property );
      }
    
    } catch ( Exception $e ) {
      return psDebug::cThrow ( null, $e, array ('display' => false ) );
    }
    
    return $retVal;
  }

}

?>