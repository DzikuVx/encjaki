<?php

class userFrontend extends user {

  public function addExe($params) {

    global $db, $t;
    
    $retVal = array ();
    $retVal ['success'] = true;
    $retVal ['text'] = '';
    
    if (user::sCheckLoginExistence ( $params ['Login'] )) {
      $retVal ['success'] = false;
      $retVal ['text'] = '<div class="komunikat">'.$t->get ( 'duplicateUser' ).'</div>';
      return $retVal;
    }
    
    if ($params ['Password'] != $params ['Password2']) {
      $retVal ['success'] = false;
      $retVal ['text'] = '<div class="komunikat">'.$t->get ( 'differentPasswords' ).'</div>';
      return $retVal;
    }
    
    if (mb_strlen ( $params ['Password'] ) < 5) {
      $retVal ['success'] = false;
      $retVal ['text'] = '<div class="komunikat">'.$t->get ( 'shortPassword' ).'</div>';
      return $retVal;
    }
    
    $tQuery = "INSERT INTO user(
      Login,
      Name,
      Email,
      Password,
      Process,
      Language
    ) VALUES(
      '{$params['Login']}',    
      '{$params['Name']}',    
      '{$params['Email']}',    
      '{$params['Password']}',    
      '{$params['Process']}',    
      '{$params['Language']}'
    )";
    
    $db->execute ( $tQuery );
    
    $retVal ['text'] =  '<div class="komunikat">'.$t->get ( 'newUserSuccess' ).'</div>';
    
    return $retVal;
  
  }

  public function add($params) {

    global $t, $config;
    
    $retVal = '';
    
    $retVal .= '<div class="newsBodyTitle"><h3>'.$this->renderTitle ( $t->get ( 'register' ) ).'</h3></div>';
    $retVal .= $this->openForm ();
    
    $retVal .= "<table border='0' class='userTB' cellspacing='10'>";
    
    $retVal .= "<tr>";
    $retVal .= "<td class='userTBcell'>" . $t->get ( 'language' ) . ": </td>";
    $retVal .= "<td>" . controls::renderSelect ( 'Language', $params ['Language'], $config ['languages'] ) . "</td>";
    $retVal .= "</tr>";
    
    $retVal .= "<tr>";
    $retVal .= "<td class='userTBcell'>" . $t->get ( 'loginName' ) . ": </td>";
    $retVal .= "<td>" . controls::renderInput ( 'text', $params ['Login'], 'Login', '', 64 ) . "</td>";
    $retVal .= "</tr>";
    
    $retVal .= "<tr>";
    $retVal .= "<td class='userTBcell'>" . $t->get ( 'password' ) . ": </td>";
    $retVal .= "<td>" . controls::renderInput ( 'password', $params ['Password'], 'Password', '', 64 ) . "</td>";
    $retVal .= "</tr>";
    
    $retVal .= "<tr>";
    $retVal .= "<td class='userTBcell'>" . $t->get ( 'password2' ) . ": </td>";
    $retVal .= "<td>" . controls::renderInput ( 'password', $params ['Password2'], 'Password2', '', 64 ) . "</td>";
    $retVal .= "</tr>";
    
    $retVal .= "<tr>";
    $retVal .= "<td class='userTBcell'>" . $t->get ( 'name' ) . ": </td>";
    $retVal .= "<td>" . controls::renderInput ( 'text', $params ['Name'], 'Name', '', 64 ) . "</td>";
    $retVal .= "</tr>";
    
    $retVal .= "<tr>";
    $retVal .= "<td class='userTBcell'>" . $t->get ( 'email' ) . ": </td>";
    $retVal .= "<td>" . controls::renderInput ( 'text', $params ['Email'], 'Email', '', 64 ) . "</td>";
    $retVal .= "</tr>";
    
    $retVal .= "<tr>";
    $retVal .= "<td class='userTBcell'>" . $t->get ( 'processAgreed' ) . ": </td>";
    $retVal .= "<td>" . controls::renderSelect ( 'Process', $params ['Process'], array ('yes' => 'Tak', 'no' => 'Nie' ) ) . "</td>";
    $retVal .= "</tr>";

    $retVal .= "<tr>";
    $retVal .= "<td class='userTBcell'>&nbsp;</td>";
    $retVal .= "<td class='submit'>" ;
	$retVal .= controls::renderInput ( 'hidden', $_REQUEST ['language'], 'language' );
    $retVal .= controls::renderInput ( 'hidden', 'execute', 'newAccount' );
    $retVal .= controls::renderSubmitButton ( $t->get ( 'save' ) );
	$retVal .= "</td>";
    $retVal .= "</tr>";
	    
    $retVal .= "</table>";


    
    $retVal .= $this->closeForm ();
    
    return $retVal;
  
  }

  public function editExe($params) {

    global $db, $t;
    
    $retVal = array ();
    $retVal ['success'] = true;
    $retVal ['text'] = '';
    
    if ($params ['Password'] != $params ['Password2']) {
      $retVal ['success'] = false;
	  $retVal ['text']= '<div class="komunikat">'.$t->get ( 'differentPasswords' ).'</div>';
      return $retVal;
    }
    
    if (mb_strlen ( $params ['Password'] ) < 5) {
      $retVal ['success'] = false;
      $retVal ['text'] =  '<div class="komunikat">'.$t->get ( 'shortPassword' ).'</div>';
      return $retVal;
    }
    
    $tQuery = "UPDATE user SET 
      Name = '{$params['Name']}',
      Email = '{$params['Email']}',
      Password = '{$params['Password']}',
      Process = '{$params['Process']}',
      Language = '{$params['Language']}'
    WHERE
      UserID = '{$_SESSION['loggedUser']}'  
    ";
    $db->execute ( $tQuery );
    
    $retVal ['text'] =  '<div class="komunikat">'.$t->get ( 'editUserSuccess' ).'</div>';
    
    return $retVal;
  }

  public function edit($params) {

    global $t, $config;
    
    $retVal = '';
    
    $retVal .= '<div class="newsBodyTitle"><h3>'.$this->renderTitle ( $t->get ( 'editAccount' ) ).'</h3></div>';
    $retVal .= $this->openForm ();
    
    $retVal .= "<table border='0' class='userTB' cellspacing='10'>";
    
    $retVal .= "<tr>";
    $retVal .= "<td class='userTBcell'>" . $t->get ( 'language' ) . ": </td>";
    $retVal .= "<td>" . controls::renderSelect ( 'Language', $this->getLanguage (), $config ['languages'] ) . "</td>";
    $retVal .= "</tr>";
    
    $retVal .= "<tr>";
    $retVal .= "<td class='userTBcell'>" . $t->get ( 'loginName' ) . ": </td>";
    $retVal .= "<td class='fontB'>" . $this->getLogin () . "</td>";
    $retVal .= "</tr>";
    
    $retVal .= "<tr>";
    $retVal .= "<td class='userTBcell'>" . $t->get ( 'name' ) . ": </td>";
    $retVal .= "<td>" . controls::renderInput ( 'text', $this->getName (), 'Name', '', 64 ) . "</td>";
    $retVal .= "</tr>";
    
    $retVal .= "<tr>";
    $retVal .= "<td class='userTBcell'>" . $t->get ( 'email' ) . ": </td>";
    $retVal .= "<td>" . controls::renderInput ( 'text', $this->getEmail (), 'Email', '', 64 ) . "</td>";
    $retVal .= "</tr>";
    
    $retVal .= "<tr>";
    $retVal .= "<td class='userTBcell'>" . $t->get ( 'processAgreed' ) . ": </td>";
    $retVal .= "<td>" . controls::renderSelect ( 'Process', $this->getProcess (), array ('yes' => 'Tak', 'no' => 'Nie' ) ) . "</td>";
    $retVal .= "</tr>";
    
    $retVal .= "<tr>";
    $retVal .= "<td class='userTBcell'>" . $t->get ( 'password' ) . ": </td>";
    $retVal .= "<td>" . controls::renderInput ( 'password', $this->getPassword (), 'Password', '', 64 ) . "</td>";
    $retVal .= "</tr>";
    
    $retVal .= "<tr>";
    $retVal .= "<td class='userTBcell'>" . $t->get ( 'password2' ) . ": </td>";
    $retVal .= "<td>" . controls::renderInput ( 'password', $this->getPassword (), 'Password2', '', 64 ) . "</td>";
    $retVal .= "</tr>";
	
    $retVal .= "<tr>";
    $retVal .= "<td class='userTBcell'>&nbsp;</td>";
    $retVal .= "<td class='submit'>" ;
	$retVal .= controls::renderInput ( 'hidden', $_REQUEST ['language'], 'language' );
    $retVal .= controls::renderInput ( 'hidden', 'execute', 'goEdit' );
    $retVal .= controls::renderSubmitButton ( $t->get ( 'save' ) );
	$retVal .= "</td>";
    $retVal .= "</tr>";
    
    $retVal .= "</table>";
    
    $retVal .= $this->closeForm ();
    
    return $retVal;
  
  }

  function __construct($id = null) {

    global $db;
    
    if ($id != null) {
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

  function __toString() {

    global $t;
    
    $retVal = '';
    
    if ($this->UserID == null) {
      /**
       * Okno logowanie
       */
      $retVal .= '<div class="newsBodyTitle linkiH"><h3>' . $t->get ( 'login' ) . '</h3></div>';
      
      $retVal .= '<form method="post" action="">';
      
      $retVal .= '<div class="logincolC textR">';
      $retVal .= '<label>' . $t->get ( 'loginName' ) . ':</label><input type="text" name="Login" />';
      $retVal .= '</div>';
      $retVal .= '<div  class="logincolC textR">';
      $retVal .= '<label>' . $t->get ( 'password' ) . ':</label><input type="password" name="Password" />';
      $retVal .= '</div><div  class="logincolC submit textR">';
      $retVal .= '<input type="hidden" name="goLogin" value="true" />';
      $retVal .= '<input type="hidden" name="goLogout" value="false" />';
      $retVal .= '<input type="hidden" name="language" value="' . $_REQUEST ['language'] . '" />';
      $retVal .= '<input type="submit" value="' . $t->get ( 'loginGo' ) . '" /></div>';
      $retVal .= '</form>';
      $retVal .= '<div  class="logincolC textC mrgT10">' . $t->get ( 'noAccount' ) . '</div>';
    } else {
      /*
       * Okno wylogowania
       */
      $retVal .= '<div class="newsBodyTitle"><h3>' . $t->get ( 'login' ) . '</h3></div>';
	  $retVal .= '<div class="mrgL70">';
      $retVal .= '<div class="logincolC"><strong>' . $t->get ( 'hello' ) . ' ' . $this->getName (). '</strong></div>';
      $retVal .= '<div class="logincolC">' . $t->get ( 'doEditAccount' ) . '</div>';
      $retVal .= '<div class="logincolC">' . $t->get ( 'goLogout' ) . '</div>';
	  $retVal .= '</div>';
    }
    
    return $retVal;
  
  }

}

?>