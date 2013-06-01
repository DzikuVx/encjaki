<?php
/**
 * Logowanie użytkownika
 */
if ($_POST ['action'] == "userLogin") {
  
  /**
   * Prawdź, czy taki użytkownik istnieje
   */
  $tQuery = $db->execute ( "SELECT 
	   user.*
	 FROM 
	   user
	 WHERE
	    (user.Role='newsman' OR user.Role='admin')AND
      user.Login='{$_POST['login']}' AND 
      user.Password = '{$_POST ['password']}'
	 LIMIT 1
	 " );
  while ( $tResult = $db->fetch ( $tQuery ) ) {
    $_SESSION ['adminLoggedUser'] = $tResult->UserID;
    $_SESSION ['adminLoggedUserRole'] = $tResult->Role;
  }
}

if ($_GET ['action'] == "userLogout" && $_POST ['action'] != "userLogin") {
  
  $_SESSION ['adminLoggedUser'] = null;
  $_SESSION ['adminLoggedUserRole'] = null;
  session_destroy ();
}
?>