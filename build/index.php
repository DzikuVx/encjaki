<?php
session_start();

require_once 'common.php';

if (! empty ( $_SESSION ['loggedUserID'] ) || !empty($_REQUEST ['forceGameplay'])) {
  header ( 'Location: gameplay.php' );
  $_SESSION ['loggedUserID'] = $_REQUEST ['forceGameplay'];
}elseif (empty ( $_SESSION ['loggedUserID'] ) || (!empty($_REQUEST ['forceRegister']) && $_REQUEST ['forceRegister'] == 'true')) {
  header ( 'Location: register.php' );
} else {
  exit ();
}

