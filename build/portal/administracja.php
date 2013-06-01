<?php

header('Content-Type: text/html; charset=utf-8'); 

session_start ();

if (! isset ( $_SESSION ['adminLoggedUser'] )) {
  $_SESSION ['adminLoggedUser'] = null;
}

if (empty($_POST ['action'])) {
  $_POST ['action'] = null;
}

ob_start ( "ob_gzhandler" );

require_once '../common.php';
require_once 'config.inc.php';

$db = new dataBase ( $dbConfig );

$cache = new cache ( 100 );
$leftMenu = new leftMenu ( );
require 'engine/userLogin.php';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="pl" xml:lang="pl">
<head>
<title>Encjaki - Administracja</title>
<meta name="robots" content="index, follow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/admin.css" />
<link rel="stylesheet" type="text/css" href="css/jquery.lightbox.css" />
<script type="text/javascript" src="js/engine.js"></script>
<script type="text/javascript" src="../common/js/jquery.js"></script>
<script type="text/javascript" src="../common/js/jquery.lightbox.js"></script>
<script type="text/javascript" src="../common/js/wymeditor/jquery.wymeditor.js"></script>

<script type='text/javascript'>
jQuery().ready(function(){
  $("#HTMLEdit").wymeditor({
    lang: 'pl',
    stylesheet: 'editstyles.css' 
  });
  
  $('[lightboxed=true]').lightbox();
  
});
</script>
</head>
<body>

<?php

if ($_SESSION ['adminLoggedUser'] == null) {
  ?>
<div style="text-align: center;">
<center>
<div class="centerBox" style="width: 350px; margin-top: 100px;">
<div class="boxTitle">Witamy w "Zarządzaniu stroną"</div>
<form method="post" action="" name="login">
<div class="boxSpacer" style="width: 200px; height: 24px;"><input onkeypress="checkLoginSubmit(event)" 
	class="box" style="float: right;" type="text" name="login" size="16" />Login:
</div>
<div class="boxSpacer" style="width: 200px; height: 24px;"><input
	class="box" style="float: right;" onkeypress="checkLoginSubmit(event)" type="password" name="password"
	size="16" />Hasło:</div>
<div><span class="boxButton" onclick="document.forms['login'].submit();">Zaloguj</span>
<input type="hidden" name="action" value="userLogin" /></div>
</form>
</div>
</center>
</div>
<?php
} else {
  
  $user = new user ( $_SESSION ['adminLoggedUser'] );
  
  ?>
    <div id='leftMenuContainer'>
  <?php
  
  echo $leftMenu->populate ( $user );
  
  ?>
  </div>
<div id='mainPanelContainer'>
<?php
  /*
   * Tutaj idzie główny engine
   */
  
  if ($_POST ['action'] == "execute" || $_GET ['action'] == "execute") {
    
    $class = $_REQUEST ['module'];
    $method = $_REQUEST ['method'];
    
    $item = new $class ( );
    
    echo $item->{$method} ( $user, $_REQUEST );
  
  }
  
  ?>
</div>
<?php
}
?>
</body>
</html>