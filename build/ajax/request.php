<?php
session_start ();

require_once '../common.php';
require_once '../config.inc.php';

ob_start ( "ob_gzhandler" );

if (empty ( $_SESSION ['loggedUserID'] )) {
  exit ();
}

$db = new dataBase ( $dbConfig );
$cache = new cache ( 100 );

$dataSaver = new itemSaver ( 'data', $_SESSION ['loggedUserID'] );
$dataSaver->get ( $dataArray );

$parcelSaver = new itemSaver ( 'parcels', $_SESSION ['loggedUserID'] );
$parcelArray = array ();
$parcelSaver->get ( $parcelArray );

if ($_REQUEST ['brand'] == 'static') {
  echo call_user_func_array ( array ($_REQUEST ['requestClass'], $_REQUEST ['requestMethod'] ), array ($_REQUEST ) );
}