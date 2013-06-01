<?php

/**
 * Klasa użytkownika.
 * W rzeczywistości klasa jest nakładką klasy głównej user
 * 
 * @see common/classes/user.php
 * @author Paweł Spychalski
 *
 */
class registerUser extends registerBase implements iRegisterBase {

  /**
   * 
   * @see iRegisterBase::get()
   */
  public function get($params) {

    //TODO - Dummy, wymyśleć czy nie wstawić tutaj czegoś konkretnego
  }

  public function edit($params) {

    /**
     * Wywołaj właściwą metodę z klasy user
     */
    $params ['id'] = $_SESSION ['loggedUserID'];
    return user::sEdit ( $params );
  }

  /**
   * Rejestracja użytkownika - formularz
   *
   * @param array $params
   * @return string
   */
  public function register($params) {

    /**
     * Wywołaj właściwą metodę z klasy user
     */
    return user::sRegister ( $params );
  }

  /**
   * Rejestracja użytkownika - zapis do bazy danych
   *
   * @param array $params
   * @return string
   */
  public function registerExecute($params) {

    /**
     * Wywołaj właściwą metodę z klasy user
     */
    return user::sRegisterExecute ( $params );
  }

}

?>