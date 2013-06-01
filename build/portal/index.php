<?php

header('Content-Type: text/html; charset=utf-8'); 

session_start ();

$_REQUEST ['language'] = 'pl';

if (! isset ( $_SESSION ['loggedUser'] )) {
  $_SESSION ['loggedUser'] = null;
}

ob_start ( "ob_gzhandler" );

require_once '../common.php';
require_once 'config.inc.php';

$db = new dataBase ( $dbConfig );

$cache = new cache ( 100 );

if (isset ( $_REQUEST ['language'] )) {
  
  switch ($_REQUEST ['language']) {
    
    case 'de' :
      $_REQUEST ['language'] = 'de';
      break;
    case 'en' :
      $_REQUEST ['language'] = 'en';
      break;
    
    default :
    case 'pl' :
      $_REQUEST ['language'] = 'pl';
      break;
  }
  $_SESSION ['language'] = $_REQUEST ['language'];
} else {
  if (isset ( $_SESSION ['language'] )) {
    $_REQUEST ['language'] = $_SESSION ['language'];
  }
}

$t = new translation ( $_REQUEST ['language'] );

comment::sAddListener ();
user::sLoginListener();
user::sLogoutListener();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="pl" xml:lang="pl">
<head>
<title>Encjaki -> Portal</title>
<meta name="robots" content="index, follow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php

  if (isset($_REQUEST ['language'])) {
    echo '<link rel="alternate" type="application/rss+xml" title="RSS" href="rss/news_'.$_REQUEST ['language'].'.xml" /> ';
  }

?>

<link rel="stylesheet" type="text/css" href="css/jquery.lightbox.css" />
<link rel="stylesheet" type="text/css" href="css/index.css" />
<link href="gfx/favicon.ico" type="image/ico" rel="shortcut icon" />
<script type="text/javascript" src="js/engine.js"></script>
<script type="text/javascript" src="../common/js/mask.js"></script>
<script type="text/javascript" src="../common/js/jquery.js"></script>
<script type="text/javascript" src="../common/js/jquery.lightbox.js"></script>

<script type='text/javascript'>
  jQuery().ready(function(){
   $('[lightboxed=true]').lightbox();
   
   var offset = $('#rightMenu').offset();
   
   $('#footer').css('top',offset.top + $('#rightMenu').height() + 30);
   $('#footer').css('width',$('#contentBox').width() - 40);
   
  });
</script>

</head>
<body>
<?php

  require 'template/mainPage.php';

?>
</body>
</html>