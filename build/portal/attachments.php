<?php
session_start ();

if (! isset ( $_SESSION ['adminLoggedUser'] )) {
  $_SESSION ['adminLoggedUser'] = null;
}

if ($_SESSION ['adminLoggedUser'] == null) {
  exit ();
}

require_once 'config.inc.php';
require_once 'db.inc.php';
$db = new dataBase ( $dbConfig );

$user = new user ( $_SESSION ['adminLoggedUser'] );

if (! isset ( $_REQUEST ['upload'] )) {
  
  ?>
<form enctype="multipart/form-data" method='post'
  action='attachments.php'><input type="hidden" name="MAX_FILE_SIZE"
  value="500000" /> <input TYPE="hidden" name="productID"
  value="<?php
  echo $_REQUEST ['productID'];
  ?>" /> <input TYPE="hidden" name="upload" value="true" />
<h3 style="color: red">Wyślij plik:</h3>
<div><input NAME="userfile" TYPE="file" /></div>
<div><input type="submit" value="Wyślij" /></div>
</form>
<?php
} else {
  
  $tArray = explode ( '.', $_FILES ['userfile'] ['name'] );
  $tCount = count ( $tArray );
  
  $tExtension = $tArray [$tCount - 1];
  
  $tNewName = uniqid () . '.' . $tExtension;
  
  move_uploaded_file ( $_FILES ['userfile'] ['tmp_name'], "images/" . $tNewName );
  
  $tQuery = "INSERT INTO attachments(ProductID, FileName) VALUES('{$_REQUEST['productID']}','$tNewName')";
  $db->execute ( $tQuery );
  
  image::sResize("images/" . $tNewName, "images/thumbs/" . $tNewName, 200, 200);
  
  echo '<script>';
  echo 'opener.window.location.reload();';
  echo 'window.close();';
  echo '</script>';
}

?>