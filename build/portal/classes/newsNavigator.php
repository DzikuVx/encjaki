<?php

class newsNavigator extends pageRegistry {

  function __toString() {

    global $db, $config, $t;
    
    // dodaj ten naglowek w poprawny sposob :P
    $retVal .= '<div class="newsBodyTitle"><h3>' . $t->get ( 'news' ) . '</h3></div>';
    
    $tQuery = "SELECT * FROM news WHERE Type='normal' AND Published='yes' AND Language='{$_REQUEST['language']}' ORDER BY CreateTime DESC LIMIT 1, {$config['news2Page']}";
    $tQuery = $db->execute ( $tQuery );
    while ( $tResult = $db->fetch ( $tQuery ) ) {
      $retVal .= '<div class="newsBox">';
      
      $retVal .= '<div class="rightMenuDate">' . dataTypes::getDate ( $tResult->CreateTime ) . '</div>';
      $retVal .= '<div class="rightMenuTresc">';
      
      $retVal .= '<h4>' . $tResult->Title . '</h4>';
      $retVal .= '<div class="rightMenuTrescZajawka">' . strip_tags ( news::sGetShortText($tResult->Text) ) . '</div>';
      $retVal .= '<div class="textR"><a href="' . news::sFormatLink ( $tResult->NewsID ) . '">' . $t->get ( 'keepReading' ) . '</a></div>';
      $retVal .= '</div>';
      
      $retVal .= '</div>';
    }
    
    return $retVal;
  
  }

}

?>