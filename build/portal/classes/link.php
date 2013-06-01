<?php

class link extends item {
  
  protected $LinkID = null;
  protected $Deleted = 'no';
  protected $Language = 'pl';
  protected $Name = '';
  protected $Link = '';

  function __toString() {

    global $db, $config, $t;
    
    // dodaj ten naglowek w poprawny sposob :P
    $retVal .= '<div class="newsBodyTitle linkiH"><h3>' . $t->get ( 'links' ) . '</h3></div>';
    
    $tQuery = "SELECT * FROM link WHERE Language='{$_REQUEST['language']}' AND Deleted='no' ORDER BY Name";
    $tQuery = $db->execute ( $tQuery );
    while ( $tResult = $db->fetch ( $tQuery ) ) {
      $retVal .= '<div class="linkC"><a href="' . $tResult->Link . '" class="wborder" title="' . $tResult->Name . '" target="_blank">';
      $retVal .= $tResult->Name;
      $retVal .= '</a></div>';
    }
    
    return $retVal;
  
  }

  public function add(user $user, $params) {

    global $config;
    
    $retVal = '';
    
    if (! $user->checkRole ( 'admin' )) {
      return controls::displayErrorDialog ( 'Brak praw dostępu' );
    }
    
    $retVal .= $this->renderTitle ( "Dodaj link" );
    $retVal .= $this->openForm ();
    
    $retVal .= "<table border='0' width='100%'>";
    
    $retVal .= "<tr>";
    $retVal .= "<td style='width: 150px' class='header'>Tytuł: </td>";
    $retVal .= "<td>" . controls::renderInput ( 'text', '', 'Name', '', 255 ) . "</td>";
    $retVal .= "</tr>";
    
    $retVal .= "<tr>";
    $retVal .= "<td style='width: 150px' class='header'>Adres: </td>";
    $retVal .= "<td>" . controls::renderInput ( 'text', '', 'Link', '', 255 ) . "</td>";
    $retVal .= "</tr>";
    
    $retVal .= "</table>";
    $retVal .= controls::renderInput ( 'hidden', 'execute', 'action' );
    $retVal .= controls::renderInput ( 'hidden', 'link', 'module' );
    $retVal .= controls::renderInput ( 'hidden', 'addExe', 'method' );
    $retVal .= "<div style='text-align: center;'>";
    $retVal .= controls::renderSubmitButton ( 'Zapisz', '', 'wymupdate formButton' );
    $retVal .= controls::renderButton ( "Anuluj", "document.location='?action=execute&amp;module=link&amp;method=browse'" );
    $retVal .= "</div>";
    
    $retVal .= $this->closeForm ();
    
    return $retVal;
  }

  public function edit(user $user, $params) {

    global $config;
    
    $retVal = '';
    
    if (! $user->checkRole ( 'admin' )) {
      return controls::displayErrorDialog ( 'Brak praw dostępu' );
    }
    
    $retVal .= $this->renderTitle ( "Edytuj link" );
    $retVal .= $this->openForm ();
    
    $tLink = new link ( $params ['id'] );
    
    $retVal .= "<table border='0' width='100%'>";
    
    $retVal .= "<tr>";
    $retVal .= "<td style='width: 150px' class='header'>Tytuł: </td>";
    $retVal .= "<td>" . controls::renderInput ( 'text', $tLink->getName (), 'Name', '', 255 ) . "</td>";
    $retVal .= "</tr>";
    
    $retVal .= "<tr>";
    $retVal .= "<td style='width: 150px' class='header'>Adres: </td>";
    $retVal .= "<td>" . controls::renderInput ( 'text', $tLink->getLink (), 'Link', '', 255 ) . "</td>";
    $retVal .= "</tr>";
    
    $retVal .= "</table>";
    $retVal .= controls::renderInput ( 'hidden', 'execute', 'action' );
    $retVal .= controls::renderInput ( 'hidden', 'link', 'module' );
    $retVal .= controls::renderInput ( 'hidden', $params ['id'], 'id' );
    $retVal .= controls::renderInput ( 'hidden', 'editExe', 'method' );
    $retVal .= "<div style='text-align: center;'>";
    $retVal .= controls::renderSubmitButton ( 'Zapisz', '', 'wymupdate formButton' );
    $retVal .= controls::renderButton ( "Anuluj", "document.location='?action=execute&amp;module=link&amp;method=browse'" );
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
        link 
      SET
        Name = '{$params['Name']}', 
        Link = '{$params['Link']}'
      WHERE
        LinkID = '{$params['id']}'
      ";
    /*
     * Wykonaj zapytanie
     */
    $db->execute ( $tQuery );
    
    /*
     * Wygenreruj output
     */
    $retVal .= controls::displayConfirmDialog ( "Potwierdzenie", "Zmieniono link", "document.location='?action=execute&amp;module=link&amp;method=browse'" );
    
    global $sCache;
    $sCache->clearClassCache ( 'link' );
    
    return $retVal;
  
  }

  public function addExe(user $user, $params) {

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
    $tQuery = "INSERT INTO link(
        Name, 
        Link
      ) 
      VALUES(
        '{$params['Name']}', 
        '{$params['Link']}'
      )";
    /*
     * Wykonaj zapytanie
     */
    $db->execute ( $tQuery );
    
    /*
     * Wygenreruj output
     */
    $retVal .= controls::displayConfirmDialog ( "Potwierdzenie", "Dodano link", "document.location='?action=execute&amp;module=link&amp;method=browse'" );
    
    global $sCache;
    $sCache->clearClassCache ( 'link' );
    
    return $retVal;
  
  }

  public function delete(user $user, $params) {

    /*
     * Sprawdz prawa dostępu
     */
    if (! $user->checkRole ( 'admin' )) {
      return controls::displayErrorDialog ( 'Brak praw dostępu' );
    }
    
    return controls::displayDialog ( "Potwierdzenie", "Czy usunąć link?", "document.location='?action=execute&amp;module=link&amp;method=deleteExe&amp;id={$params['id']}'", "document.location='?action=execute&amp;module=link&amp;method=browse'" );
  }

  public function deleteExe(user $user, $params) {

    global $db;
    
    /*
     * Sprawdz prawa dostępu
     */
    if (! $user->checkRole ( 'admin' )) {
      return controls::displayErrorDialog ( 'Brak praw dostępu' );
    }
    
    $db->execute ( "UPDATE link SET Deleted='yes' WHERE LinkID='" . $params ['id'] . "'" );
    
    global $sCache;
    $sCache->clearClassCache ( 'link' );
    
    return controls::displayConfirmDialog ( "Potwierdzenie", "Usunięto link", "document.location='?action=execute&amp;module=link&amp;method=browse'" );
  }

  function __construct($id = null) {

    global $db;
    
    if ($id != null) {
      
      $tQuery = "SELECT * FROM link WHERE LinkID='{$id}'";
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

  /**
   * @return unknown
   */
  public function getDeleted() {

    return $this->Deleted;
  }

  /**
   * @return unknown
   */
  public function getLanguage() {

    return $this->Language;
  }

  /**
   * @return unknown
   */
  public function getLink() {

    return $this->Link;
  }

  /**
   * @return unknown
   */
  public function getLinkID() {

    return $this->LinkID;
  }

  /**
   * @return unknown
   */
  public function getName() {

    return $this->Name;
  }

  /**
   * @param unknown_type $Deleted
   */
  public function setDeleted($Deleted) {

    $this->Deleted = $Deleted;
  }

  /**
   * @param unknown_type $Language
   */
  public function setLanguage($Language) {

    $this->Language = $Language;
  }

  /**
   * @param unknown_type $Link
   */
  public function setLink($Link) {

    $this->Link = $Link;
  }

  /**
   * @param unknown_type $Name
   */
  public function setName($Name) {

    $this->Name = $Name;
  }

}

?>