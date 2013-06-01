<?php

class registerNewsRegistry extends registerBase implements iRegisterBase {

  /**
   * Interface get
   *
   * @param array $params
   * @return string
   */
  public function get($params) {

    global $db, $sCache, $config;
    $retVal = '';
    
    if (empty ( $params ['skip'] )) {
      $params ['skip'] = 0;
    }
    
      $module = 'news::registerNewsRegistry::get';
      $property = $params ['skip'];
      
      if (! $sCache->check ( $module, $property )) {
        $tQuery = "SELECT * FROM news WHERE Deleted='no' AND Published='yes' AND Type='normal' AND Language='{$this->language}' ORDER BY CreateTime DESC LIMIT {$params['skip']},{$config['news2Page']}";
        $tQuery = $db->execute ( $tQuery );
        while ( $tResult = $db->fetch ( $tQuery ) ) {
          $tNews = new registerShortNews ( $tResult );
          $retVal .= $tNews;
        }
        $sCache->set ( $module, $property, $retVal, 7200 );
      } else {
        $retVal = $sCache->get ( $module, $property );
      }
      
      //@todo: nawigacja po stronach
      
    
    return $retVal;
  }

}

?>