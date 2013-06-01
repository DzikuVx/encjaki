<?php

class news extends item {
  
  /**
   * NewsID
   *
   * @var int
   */
  protected $NewsID = null;
  
  protected $displayComments = true;
  
  /**
   * Tytuł
   *
   * @var string
   */
  protected $Title = null;
  
  protected $Text = null;
  
  protected $UserID = null;
  
  protected $UserName = null;
  
  protected $CreateTime = null;
  
  protected $Published = null;
  
  protected $Type = null;
  
  protected $Language = null;

  static function sFormatLink($id) {

    return '?language=' . $_REQUEST ['language'] . '&amp;news=' . $id;
  
  }

  static function sRemoveMarks($text) {

    $retVal = str_replace ( '|more|', '', $text );
    return $retVal;
  }

  static function sCreateRss($limit = 10) {

    global $config, $db;
    
    $tLangs = array_keys ( $config ['languages'] );
    
    foreach ( $tLangs as $tKey ) {
      
      $rss = new rss ( 'rss/news_' . $tKey . '.xml', 'Profes', $config ['pageUrl'] );
      $_REQUEST ['language'] = $tKey;
      $tQuery = "SELECT * FROM news WHERE Deleted='no' AND Language='{$tKey}' AND Type='normal' AND Published='yes' ORDER BY CreateTime DESC LIMIT {$limit}";
      $tQuery = $db->execute ( $tQuery );
      while ( $tResult = $db->fetch ( $tQuery ) ) {
        $tResult->Text = news::sRemoveMarks ( $tResult->Text );
        $rss->put ( $tResult->NewsID, $tResult->Title, news::sFormatLink ( $tResult->NewsID ), $tResult->CreateTime, $tResult->Text );
      }
      $rss->close ();
    }
  }

  /**
   * @return unknown
   */
  public function getLanguage() {

    return $this->Language;
  }

  /**
   * @param unknown_type $Language
   */
  public function setLanguage($Language) {

    $this->Language = $Language;
  }

  function __construct($id = null, $displayComments = true) {

    global $db;
    
    $this->displayComments = $displayComments;
    
    if ($id != null) {
      
      $tQuery = "SELECT * FROM news WHERE NewsID='{$id}'";
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

  public function __toString() {

    $retVal = '<div class="newsBody">';
    $retVal .= '<div class="newsBodyDate">' . dataTypes::getDateTime ( $this->getCreateTime () ) . '</div>';
    $retVal .= '<div class="newsBodyTitle"><h3>' . $this->Title . '</h3></div>';
    $retVal .= '<div class="newsBodyTresc">' . news::sRemoveMarks($this->Text) . '</div>';
    $retVal .= '</div>';
    if ($this->displayComments) {
      $retVal .= comment::sGet ( $this->NewsID );
    }
    return $retVal;
  }

  /**
   * @return int
   */
  public function getCreateTime() {

    return $this->CreateTime;
  }

  /**
   * @return int
   */
  public function getNewsID() {

    return $this->NewsID;
  }

  /**
   * @return string
   */
  public function getPublished() {

    return $this->Published;
  }

  /**
   * @return string
   */
  public function getText() {

    return news::sRemoveMarks($this->Text);
  }

  static function sGetShortText($text) {

    $pos = mb_strpos ( $text, '|more' );
    
    if ($pos === false) {
      return $text;
    } else {
      
      return mb_substr ( $text, 0, $pos );
    }
  }

  /**
   * Enter description here...
   *
   * @return string
   */
  public function getShortText() {

    return news::sGetShortText ( $this->Text );
  
  }

  /**
   * Pobranie tytulu newsa
   * 
   * @param boolean $withLink
   * @return string
   */
  public function getTitle($withLink = false, $link = '') {

    if ($withLink) {
      
      if ($link == '') {
        
        return '<a href="' . news::sFormatLink ( $this->NewsID ) . '" title="' . $this->Title . '">' . $this->Title . '</a>';
      } else {
        return '<a href="' . $link . '" title="' . $this->Title . '">' . $this->Title . '</a>';
      }
    } else {
      return $this->Title;
    }
  }

  /**
   * @return string
   */
  public function getType() {

    return $this->Type;
  }

  /**
   * @return int
   */
  public function getUserID() {

    return $this->UserID;
  }

  /**
   * @return unknown
   */
  public function getUserName() {

    return $this->UserName;
  }

  /**
   * @param int $CreateTime
   */
  public function setCreateTime($CreateTime) {

    $this->modified = true;
    
    $this->CreateTime = $CreateTime;
  }

  /**
   * @param unknown_type $NewsID
   */
  public function setNewsID($NewsID) {

    $this->modified = true;
    
    $this->NewsID = $NewsID;
  }

  /**
   * @param unknown_type $Published
   */
  public function setPublished($Published) {

    $this->modified = true;
    
    $this->Published = $Published;
  }

  /**
   * @param unknown_type $Text
   */
  public function setText($Text) {

    $this->modified = true;
    
    $this->Text = $Text;
  }

  /**
   * @param unknown_type $Title
   */
  public function setTitle($Title) {

    $this->modified = true;
    
    $this->Title = $Title;
  }

  /**
   * @param unknown_type $Type
   */
  public function setType($Type) {

    $this->modified = true;
    
    $this->Type = $Type;
  }

  /**
   * @param unknown_type $UserID
   */
  public function setUserID($UserID) {

    $this->modified = true;
    
    $this->UserID = $UserID;
  }

  /**
   * @param unknown_type $UserName
   */
  public function setUserName($UserName) {

    $this->modified = true;
    
    $this->UserName = $UserName;
  }

  public function detail(user $user, $params) {

    $retVal = '';
    
    if (! $user->checkRole ( 'admin' )) {
      return controls::displayErrorDialog ( 'Brak praw dostępu' );
    }
    
    $tNews = new news ( $params ['id'] );
    
    $retVal .= $this->renderTitle ( "Szczegóły newsa" );
    
    $retVal .= "<table border='0' width='100%'>";
    
    $retVal .= "<tr>";
    $retVal .= "<td style='width: 150px' class='header'>Tytuł: </td>";
    $retVal .= "<td>" . $tNews->getTitle () . "</td>";
    $retVal .= "</tr>";
    
    $retVal .= "<tr>";
    $retVal .= "<td style='width: 150px' class='header'>Treść: </td>";
    $retVal .= "<td>" . $tNews->getText () . "</td>";
    $retVal .= "</tr>";
    
    $retVal .= "</table>";
    $retVal .= "<div style='text-align: center;'>";
    $retVal .= controls::renderButton ( "Zamknij", "document.location='?action=execute&amp;module=news&amp;method=browse'" );
    $retVal .= "</div>";
    
    return $retVal;
  }

  public function edit(user $user, $params) {

    global $config;
    
    $retVal = '';
    
    if (! $user->checkRole ( 'admin' )) {
      return controls::displayErrorDialog ( 'Brak praw dostępu' );
    }
    
    $tNews = new news ( $params ['id'] );
    
    $retVal .= $this->renderTitle ( "Edytuj newsa" );
    $retVal .= $this->openForm ();
    
    $retVal .= "<table border='0' width='100%'>";
    
    $retVal .= "<tr>";
    $retVal .= "<td style='width: 150px' class='header'>Tytuł: </td>";
    $retVal .= "<td>" . controls::renderInput ( 'text', $tNews->getTitle (), 'Title', '', 255 ) . "</td>";
    $retVal .= "</tr>";
    
    $retVal .= "<tr>";
    $retVal .= "<td style='width: 150px' class='header'>Treść: </td>";
    $retVal .= "<td>" . controls::renderInput ( 'html', $tNews->getText (), 'Text', 'HTMLEdit', 255 ) . "</td>";
    $retVal .= "</tr>";
    
    $retVal .= "</table>";
    $retVal .= controls::renderInput ( 'hidden', 'execute', 'action' );
    $retVal .= controls::renderInput ( 'hidden', get_class ( $this ), 'module' );
    $retVal .= controls::renderInput ( 'hidden', 'editExe', 'method' );
    $retVal .= controls::renderInput ( 'hidden', $params ['id'], 'id' );
    $retVal .= "<div style='text-align: center;'>";
    $retVal .= controls::renderSubmitButton ( 'Zapisz', '', 'wymupdate formButton' );
    $retVal .= controls::renderButton ( "Anuluj", "document.location='?action=execute&amp;module=" . get_class ( $this ) . "&amp;method=browse'" );
    $retVal .= "</div>";
    
    $retVal .= $this->closeForm ();
    
    return $retVal;
  }

  public function editExe(user $user, $params) {

    global $db;
    
    $retVal = "";
    
    /*
     * Sprawdz prawa dostępu
     */
    if (! $user->checkRole ( 'admin' )) {
      return controls::displayErrorDialog ( 'Brak praw dostępu' );
    }
    
    /*
     * Przygotuj zapytania
     */
    $tQuery = "UPDATE 
        news
      SET
        Title = '{$params['Title']}', 
        Text = '{$params['Text']}'
      WHERE
        NewsID='{$params['id']}'
      ";
    /*
     * Wykonaj zapytanie
     */
    $db->execute ( $tQuery );
    
    news::sCreateRss ();
    
    /*
     * Wygenreruj output
     */
    $retVal .= controls::displayConfirmDialog ( "Potwierdzenie", "Zmieniono newsa", "document.location='?action=execute&amp;module=" . get_class ( $this ) . "&amp;method=browse'" );
    
    global $sCache;
    $sCache->clearClassCache('news');
    
    return $retVal;
  
  }

  public function add(user $user, $params) {

    global $config;
    
    $retVal = '';
    
    if (! $user->checkRole ( 'newsman' )) {
      return controls::displayErrorDialog ( 'Brak praw dostępu' );
    }
    
    $retVal .= $this->renderTitle ( "Dodaj newsa" );
    $retVal .= $this->openForm ();
    
    $retVal .= "<table border='0' width='100%'>";
    
    $retVal .= "<tr>";
    $retVal .= "<td style='width: 150px' class='header'>Tytuł: </td>";
    $retVal .= "<td>" . controls::renderInput ( 'text', '', 'Title', '', 255 ) . "</td>";
    $retVal .= "</tr>";
    
    $retVal .= "<tr>";
    $retVal .= "<td style='width: 150px' class='header'>Treść: </td>";
    $retVal .= "<td>" . controls::renderInput ( 'html', '', 'Text', 'HTMLEdit', 255 ) . "</td>";
    $retVal .= "</tr>";
    
    $retVal .= "</table>";
    $retVal .= controls::renderInput ( 'hidden', 'execute', 'action' );
    $retVal .= controls::renderInput ( 'hidden', get_class ( $this ), 'module' );
    $retVal .= controls::renderInput ( 'hidden', 'addExe', 'method' );
    $retVal .= "<div style='text-align: center;'>";
    $retVal .= controls::renderSubmitButton ( 'Zapisz', '', 'wymupdate formButton' );
    $retVal .= controls::renderButton ( "Anuluj", "document.location='?action=execute&amp;module=" . get_class ( $this ) . "&amp;method=browse'" );
    $retVal .= "</div>";
    
    $retVal .= $this->closeForm ();
    
    return $retVal;
  }

  public function addExe(user $user, $params) {

    global $db, $sCache;
    
    $retVal = "";
    
    /*
     * Sprawdz prawa dostępu
     */
    if (! $user->checkRole ( 'newsman' )) {
      return controls::displayErrorDialog ( 'Brak praw dostępu' );
    }
    
    /*
     * Przygotuj zapytania
     */
    $tQuery = "INSERT INTO news(
        Title, 
        Text, 
        UserID,
        CreateTime
      ) 
      VALUES(
        '{$params['Title']}', 
        '{$params['Text']}', 
        '{$user->ID}',
        '" . time () . "'
      )";
    /*
     * Wykonaj zapytanie
     */
    $db->execute ( $tQuery );
    
    news::sCreateRss ();
    
    /*
     * Wygenreruj output
     */
    $retVal .= controls::displayConfirmDialog ( "Potwierdzenie", "Dodano newsa", "document.location='?action=execute&amp;module=" . get_class ( $this ) . "&amp;method=browse'" );
    
    $sCache->clearClassCache('news');
    
    return $retVal;
  
  }

  public function delete(user $user, $params) {

    /*
     * Sprawdz prawa dostępu
     */
    if (! $user->checkRole ( 'admin' )) {
      return controls::displayErrorDialog ( 'Brak praw dostępu' );
    }
    
    return controls::displayDialog ( "Potwierdzenie", "Czy usunąć newsa?", "document.location='?action=execute&amp;module=" . get_class ( $this ) . "&amp;method=deleteExe&amp;id={$params['id']}'", "document.location='?action=execute&amp;module=news&amp;method=browse'" );
  }

  public function deleteExe(user $user, $params) {

    global $db;
    
    /*
     * Sprawdz prawa dostępu
     */
    if (! $user->checkRole ( 'admin' )) {
      return controls::displayErrorDialog ( 'Brak praw dostępu' );
    }
    
    $db->execute ( "UPDATE news SET Deleted='yes' WHERE NewsID='" . $params ['id'] . "'" );
    
    global $sCache;
    $sCache->clearClassCache('news');
    
    return controls::displayConfirmDialog ( "Potwierdzenie", "Usunięto newsa", "document.location='?action=execute&amp;module=" . get_class ( $this ) . "&amp;method=browse'" );
  }

}

?>