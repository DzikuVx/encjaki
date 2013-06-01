<?php

class user extends item {
  
  protected $Role = 'reader';
  protected $Deleted = 'no';
  protected $Login = '';
  protected $Name = '';
  protected $Password = '';
  protected $Email = '';
  protected $Process = '';
  protected $Language = 'pl';
  public $UserID = null;

  //@TODO:  docelowo przenieść funckje do klasy common->user
  
  static function sLogoutListener() {

    if (isset ( $_REQUEST ['goLogout'] ) && $_REQUEST ['goLogout'] == 'true') {
      $_SESSION ['loggedUser'] = null;
    }
  
  }

  static function sLoginListener() {

    if (isset ( $_REQUEST ['goLogin'] ) && $_REQUEST ['goLogin'] == 'true') {
      global $db;
      
      $tQuery = "SELECT * FROM user WHERE Deleted='no' AND Login='{$_REQUEST['Login']}' AND Password='" . md5 ( $_REQUEST ['Password'] ) . "' LIMIT 1";
      $tQuery = $db->execute ( $tQuery );
      while ( $tResult = $db->fetch ( $tQuery ) ) {
        $_SESSION ['loggedUser'] = $tResult->UserID;
      
      }
    }
  
  }

  static function sCheckLoginExistence($login) {

    global $db;
    
    $tQuery = "SELECT COUNT(*) AS ILE FROM user WHERE UPPER(Login)='" . mb_strtoupper ( $login ) . "'";
    $tQuery = $db->execute ( $tQuery );
    while ( $tResult = $db->fetch ( $tQuery ) ) {
      if ($tResult->ILE == 0) {
        return false;
      } else {
        return true;
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
  public function getEmail() {

    return $this->Email;
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
  public function getLogin() {

    return $this->Login;
  }

  /**
   * @return unknown
   */
  public function getName() {

    return $this->Name;
  }

  /**
   * @return unknown
   */
  public function getPassword() {

    return $this->Password;
  }

  /**
   * @return unknown
   */
  public function getProcess() {

    return $this->Process;
  }

  /**
   * @return unknown
   */
  public function getRole() {

    return $this->Role;
  }

  /**
   * @param unknown_type $Deleted
   */
  public function setDeleted($Deleted) {

    $this->modified = true;
    $this->Deleted = $Deleted;
  }

  /**
   * @param unknown_type $Email
   */
  public function setEmail($Email) {

    $this->modified = true;
    $this->Email = $Email;
  }

  /**
   * @param unknown_type $Language
   */
  public function setLanguage($Language) {

    $this->modified = true;
    $this->Language = $Language;
  }

  /**
   * @param unknown_type $Login
   */
  public function setLogin($Login) {

    $this->modified = true;
    $this->Login = $Login;
  }

  /**
   * @param unknown_type $Name
   */
  public function setName($Name) {

    $this->modified = true;
    $this->Name = $Name;
  }

  /**
   * @param unknown_type $Password
   */
  public function setPassword($Password) {

    $this->modified = true;
    $this->Password = $Password;
  }

  /**
   * @param unknown_type $Process
   */
  public function setProcess($Process) {

    $this->modified = true;
    $this->Process = $Process;
  }

  /**
   * @param unknown_type $Role
   */
  public function setRole($Role) {

    $this->modified = true;
    $this->Role = $Role;
  }

  function __construct($id = null) {

    global $db;
    
    if ($id == null) {
      $this->UserID = $_SESSION ['adminLoggedUser'];
      $this->Role = $_SESSION ['adminLoggedUserRole'];
    } else {
      $tQuery = "SELECT * FROM user WHERE UserID='{$id}'";
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

  public function checkRole($name) {

    $retVal = false;
    
    switch ($name) {
      
      case 'admin' :
        if ($this->Role == 'admin') {
          $retVal = true;
        }
        break;
      
      case 'newsman' :
        if ($this->Role == 'newsman' || $this->Role == 'admin') {
          $retVal = true;
        }
        break;
      
      case 'reader' :
        if ($this->Role == 'reader') {
          $retVal = true;
        }
        break;
    
    }
    
    return $retVal;
  }

  public function delete(user $user, $params) {

    /*
     * Sprawdz prawa dostępu
     */
    if (! $user->checkRole ( 'admin' )) {
      return controls::displayErrorDialog ( 'Brak praw dostępu' );
    }
    
    return controls::displayDialog ( "Potwierdzenie", "Czy usunąć użytkownika?", "document.location='?action=execute&amp;module=user&amp;method=deleteExe&amp;id={$params['id']}'", "document.location='?action=execute&amp;module=user&amp;method=browse'" );
  }

  public function deleteExe(user $user, $params) {

    global $db;
    
    /*
     * Sprawdz prawa dostępu
     */
    if (! $user->checkRole ( 'admin' )) {
      return controls::displayErrorDialog ( 'Brak praw dostępu' );
    }
    
    $db->execute ( "UPDATE user SET Deleted='yes' WHERE UserID='" . $params ['id'] . "'" );
    
    return controls::displayConfirmDialog ( "Potwierdzenie", "Usunięto komentarz", "document.location='?action=execute&amp;module=user&amp;method=browse'" );
  }

  public function edit(user $user, $params) {

    global $config;
    
    $retVal = '';
    
    if (! $user->checkRole ( 'admin' )) {
      return controls::displayErrorDialog ( 'Brak praw dostępu' );
    }
    
    $retVal .= $this->renderTitle ( "Edytuj użytkownika" );
    $retVal .= $this->openForm ();
    
    $tUser = new user ( $params ['id'] );
    
    $retVal .= "<table border='0' width='100%'>";
    
    $retVal .= "<tr>";
    $retVal .= "<td style='width: 150px' class='header'>Język: </td>";
    $retVal .= "<td>" . controls::renderSelect ( 'Language', $tUser->getLanguage (), $config ['languages'] ) . "</td>";
    $retVal .= "</tr>";
    
    $retVal .= "<tr>";
    $retVal .= "<td style='width: 150px' class='header'>Nazwa: </td>";
    $retVal .= "<td>" . controls::renderInput ( 'text', $tUser->getName (), 'Name', '', 64 ) . "</td>";
    $retVal .= "</tr>";
    
    $retVal .= "<tr>";
    $retVal .= "<td style='width: 150px' class='header'>Login: </td>";
    $retVal .= "<td>" . controls::renderInput ( 'text', $tUser->getLogin (), 'Login', '', 64 ) . "</td>";
    $retVal .= "</tr>";
    
    $retVal .= "<tr>";
    $retVal .= "<td style='width: 150px' class='header'>Email: </td>";
    $retVal .= "<td>" . controls::renderInput ( 'text', $tUser->getEmail (), 'Email', '', 64 ) . "</td>";
    $retVal .= "</tr>";
    
    $retVal .= "<tr>";
    $retVal .= "<td style='width: 150px' class='header'>Hasło: </td>";
    $retVal .= "<td>" . controls::renderInput ( 'password', '', 'Password', '', 64 ) . "</td>";
    $retVal .= "</tr>";
    
    $retVal .= "<tr>";
    $retVal .= "<td style='width: 150px' class='header'>Hasło (powtórz): </td>";
    $retVal .= "<td>" . controls::renderInput ( 'password', '', 'Password2', '', 64 ) . "</td>";
    $retVal .= "</tr>";
    
    $tArray = array ();
    $tArray ['reader'] = 'Czytelnik';
    $tArray ['newsman'] = 'Redaktor';
    $tArray ['admin'] = 'Administrator';
    
    $retVal .= "<tr>";
    $retVal .= "<td style='width: 150px' class='header'>Prawa dostępu: </td>";
    $retVal .= "<td>" . controls::renderSelect ( 'Role', $tUser->getRole (), $tArray ) . "</td>";
    $retVal .= "</tr>";
    
    $retVal .= "</table>";
    $retVal .= controls::renderInput ( 'hidden', 'execute', 'action' );
    $retVal .= controls::renderInput ( 'hidden', 'user', 'module' );
    $retVal .= controls::renderInput ( 'hidden', 'editExe', 'method' );
    $retVal .= controls::renderInput ( 'hidden', $params ['id'], 'id' );
    $retVal .= "<div style='text-align: center;'>";
    $retVal .= controls::renderSubmitButton ( 'Zapisz', '', 'wymupdate formButton' );
    $retVal .= controls::renderButton ( "Anuluj", "document.location='?action=execute&amp;module=user&amp;method=browse'" );
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
        user
      SET
        Name = '{$params['Name']}', 
        Login = '{$params['Login']}', 
        Email = '{$params['Email']}', 
        Role = '{$params['Role']}', 
        Language = '{$params['Language']}'
      WHERE
        UserID='{$params['id']}'
      ";
    /*
     * Wykonaj zapytanie
     */
    $db->execute ( $tQuery );
    
    if ($params ['Password'] != '' && $params ['Password2'] == $params ['Password']) {
      $tQuery = "UPDATE 
        user
      SET
        Password = '{$params['Password']}'
      WHERE
        UserID='{$params['id']}'
      ";
      $db->execute ( $tQuery );
    }
    
    /*
     * Wygenreruj output
     */
    $retVal .= controls::displayConfirmDialog ( "Potwierdzenie", "Zmieniono użytkownika", "document.location='?action=execute&amp;module=user&amp;method=browse'" );
    
    return $retVal;
  
  }

}

?>