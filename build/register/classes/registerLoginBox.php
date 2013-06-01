<?php

class registerLoginBox extends registerBase implements iRegisterBase {

  public function get($params) {

    global $config;
    $retVal = '';
    
    if (empty($_SESSION['loggedUserID'])) {
      $template = new psTemplate('templates/login.html');
      $retVal .= $template;
    }else {
      $template = new psTemplate('templates/loggedUser.html');
      $template->add('userName',user::sGetName($_SESSION['loggedUserID']));
      $template->add('gameplayUri',$config ['gameplayUri']);
      $retVal .= $template;
    }
    
    return $retVal;
  }
}

?>