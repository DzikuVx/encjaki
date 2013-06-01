<?php

/**
 * Główna klasa gracza
 *
 */
class user extends psItem {
  
  /**
   * Get user name 
   *
   * @param int $id
   * @return string
   */
  static function sGetName($id) {

    //@todo przenieść na model ładowania pełnego obiektu
    

    global $db;
    
    $tQuery = "SELECT Name FROM user WHERE UserID='{$id}'";
    $tQuery = $db->execute ( $tQuery );
    
    return $db->fetch ( $tQuery )->Name;
  
  }

  /**
   * checks for login avaibility
   *
   * @param string $login
   * @return boolean
   */
  static function sLoginAvaible($login) {

    $retVal = false;
    
    global $db;
    
    $tQuery = "SELECT COUNT(*) AS Ile FROM user WHERE UPPER(Login) ='" . mb_strtoupper ( $login ) . "'";
    $tQuery = $db->execute ( $tQuery );
    if ($db->fetch ( $tQuery )->Ile == 0) {
      $retVal = true;
    }
    
    return $retVal;
  }

  /**
   * checks for email avaibility
   *
   * @param string $string
   * @return boolean
   */
  static function sEmailAvaible($string) {

    $retVal = false;
    
    global $db;
    
    $tQuery = "SELECT COUNT(*) AS Ile FROM user WHERE UPPER(Email) ='" . mb_strtoupper ( $string ) . "'";
    $tQuery = $db->execute ( $tQuery );
    if ($db->fetch ( $tQuery )->Ile == 0) {
      $retVal = true;
    }
    
    return $retVal;
  }

  /**
   * Listener wylogowania użytkownika
   *
   * @param array $params
   * @return string
   */
  static function sLogoutListener(&$params) {

    $retVal = '';
    
    if (! empty ( $params ['doLogout'] ) && $params ['doLogout'] == 'true') {
      
      //@todo komunikat o wylogowaniu
      $_SESSION ['loggedUserID'] = null;
    }
    
    return $retVal;
  }

  /**
   * Listener logowania użytkownika
   *
   * @param array $params
   * @return string
   */
  static function sLoginListener(&$params) {

    $retVal = '';
    
    if (! empty ( $params ['doLogin'] ) && $params ['doLogin'] == 'true') {
      
      try {
        
        $params ['doLogout'] = null;
        
        /*
       * Procedura logowania
       */
        
        global $db;
        
        $tResult = null;
        $tQuery = "SELECT * FROM user WHERE Deleted='no' AND Login='{$params['loginName']}' AND Password='" . md5 ( $params ['loginPassword'] ) . "'";
        $tQuery = $db->execute ( $tQuery );
        $tResult = $db->fetch ( $tQuery );
        
        if (empty ( $tResult )) {
          throw new loginErrorException ( );
        }
        
        //@todo: wymuś komunikat o zalogowaniu
        $_SESSION ['loggedUserID'] = $tResult->UserID;
      
      } catch ( loginErrorException $e ) {
        $retVal = controls::displayErrorDialog ( 'Nieznany login lub błędne hasło' );
      } catch ( Exception $e ) {
        $retVal = psDebug::cThrow ( null, $e, array ('display' => false ) );
      }
    }
    
    return $retVal;
  }

  /**
   * Rejestracja uzytkownika, rendering formularza
   *
   * @param array $params
   * @return string
   */
  static function sRegister($params) {

    $template = new psTemplate ( 'templates/register.html' );
    
    $template->add ( 'login', controls::renderInput ( 'text', '', 'Login', null, 32 ) );
    $template->add ( 'name', controls::renderInput ( 'text', '', 'Name', null, 32 ) );
    $template->add ( 'email', controls::renderInput ( 'text', '', 'Email', null, 32 ) );
    $template->add ( 'password1', controls::renderInput ( 'password', '', 'Password1', null, 32 ) );
    $template->add ( 'password2', controls::renderInput ( 'password', '', 'Password2', null, 32 ) );
    $template->add ( 'agreement', controls::renderInput ( 'checkbox', false, 'Agreement' ) );
    $template->add ( 'process', controls::renderInput ( 'checkbox', false, 'Process' ) );
    
    return $template->__toString ();
  }

  /**
   * rejestracja użytkownika, wykonanie 
   *
   * @param array $params
   * @return string
   */
  static public function sRegisterExecute($params) {

    global $db;
    
    $retVal = '';
    
    if (! self::sLoginAvaible ( $params ['Login'] )) {
      return psDebug::displayBox ( 'Twój login jest już wykorzystywany. Cofnij stronę i wybierz inny login', array ('attach' => false ) );
    }
    
    if (! self::sEmailAvaible ( $params ['Email'] )) {
      return psDebug::displayBox ( 'Twój email jest już wykorzystywany. Cofnij stronę i wybierz inny email', array ('attach' => false ) );
    }
    
    $tPassword = md5 ( $params ['Password1'] );
    
    if (! empty ( $params ['Process'] )) {
      $params ['Process'] = 'yes';
    } else {
      $params ['Process'] = 'no';
    }
    
    $tQuery = "
      INSERT INTO user (
        Login,
        Name,
        Password,
        Email,
        Process
      ) VALUES(
        '{$params['Login']}',
        '{$params['Name']}',
        '{$tPassword}',
        '{$params['Email']}',
        '{$params['Process']}'
      )
    ";
    $db->execute ( $tQuery );
    
    $retVal = controls::displayConfirmDialog ( 'Sukces', 'Twoje konto zostało utworzone', "document.location='?module=empty';" );
    
    return $retVal;
  }

  
static function sEdit($params) {
//@todo okomentować
    $template = new psTemplate ( 'templates/register.html' );
    
    $tObject = new user($params['id']);
    $tData = $tObject->getData();
    
    $template->add ( 'login', $tData->Login);
    $template->add ( 'name', controls::renderInput ( 'text', $tData->Name, 'Name', null, 32 ) );
    $template->add ( 'email', controls::renderInput ( 'text', $tData->Email, 'Email', null, 32 ) );
    $template->add ( 'password1', controls::renderInput ( 'password', '', 'Password1', null, 32 ) );
    $template->add ( 'password2', controls::renderInput ( 'password', '', 'Password2', null, 32 ) );
    $template->add ( 'agreement', 'Tak' );
    $template->add ( 'process', controls::renderInput ( 'checkbox', false, 'Process' ) );
    
    return $template->__toString ();
  }
  
  /**
   * Pobranie z bazy danych
   *
   * @param int $id
   */
  protected function load($id) {

    global $db, $sCache;
    
    $module = 'user::load';
    $property = $id;
    
    try {
      
      if (! $sCache->check ( $module, $property )) {
        $tQuery = "SELECT * FROM user WHERE UserID='{$id}'";
        $tQuery = $db->execute ( $tQuery );
        while ( $tResult = $db->fetch ( $tQuery ) ) {
          $this->data = $tResult;
        }
        $sCache->set ( $module, $property, $this->data, 7200 );
      } else {
        $this->data = $sCache->get ( $module, $property );
      }
    } catch ( Exception $e ) {
      psDebug::halt ( null, $e, array ('display' => false ) );
    }
  }

}