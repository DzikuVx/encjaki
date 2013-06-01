<?php

class newsSearch extends newsNavigator {

  protected $searchString;
  
  function __construct($params) {
    
    $this->searchString = $params['searchText'];
    
  }
  
  function __toString() {

    global $db, $config, $t;
    
    $retVal .= '<div class="newsBodyTitle"><h3>' . $t->get ( 'searchResults' ) . '</h3></div>';
    $tQuery = "SELECT * FROM news WHERE ( MATCH(news.Title) AGAINST('{$this->searchString}') OR  MATCH(news.Text) AGAINST('{$this->searchString}')) AND Type='normal' AND Published='yes' AND Language='{$_REQUEST['language']}' ORDER BY CreateTime DESC LIMIT 0, {$config['search2Page']}";
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