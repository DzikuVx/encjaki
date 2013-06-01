<?php

session_start ();

ob_start ( "ob_gzhandler" );

require_once '../common.php';
require_once 'registerConfig.ini.php';

//@todo: logowanie i rejestracja - model uproszczony


$indexTemplate = new psTemplate ( 'templates/index.html' );
$indexTemplate->add ( 'systemVersion', $config ['version'] );

/*
 * Pobierz główny kontent strony
 */
if (empty ( $_REQUEST ['module'] ) || $_REQUEST ['module'] == 'empty') {
  $_REQUEST ['module'] = 'registerNewsRegistry';
}

if (empty ( $_REQUEST ['method'] )) {
  $_REQUEST ['method'] = 'get';
}

$mainText = '';

$mainText .= user::sLoginListener ( $_REQUEST );
$mainText .= user::sLogoutListener ( $_REQUEST );

if (class_exists ( $_REQUEST ['module'] ) && is_subclass_of ( $_REQUEST ['module'], 'registerBase' )) {
  
  try {
    
    $tClass = $_REQUEST ['module'];
    $tObject = new $tClass ( );
    
    if (method_exists ( $tObject, $_REQUEST ['method'] )) {
      
      $tMethod = $_REQUEST ['method'];
      
      $mainText .= $tObject->{$tMethod} ( $_REQUEST );
    } else {
      $mainText .= psDebug::displayBox ( 'Nieznany plugin', array ('attach' => false ) );
    }
    
    unset ( $tObject );
  
  } catch ( customException $e ) {
    /**
     * Przechwycenie customException w celu przekazania komunikatu
     */
    $mainText .= psDebug::displayBox ( $e->getMessage (), array ('attach' => false ) );
  } catch ( Exception $e ) {
    /*
     * Zwykły Exception
     */
    $mainText .= psDebug::cThrow ( null, $e );
  }
} else {
  $mainText .= psDebug::displayBox ( 'Nieznany plugin', array ('attach' => false ) );
}

$indexTemplate->add ( 'content', $mainText );

/*
 * Pobierz Header strony
 */
$generalData = new registerSpecialNews ( null, 'general' );
$indexTemplate->add ( 'header', $generalData->__toString () );

/*
 * Pobierz footer strony
 */
$generalData = new registerSpecialNews ( null, 'footer' );
$indexTemplate->add ( 'footer', $generalData->__toString () );

/**
 * Box logowania
 */
$loginBox = new registerLoginBox ( );
$indexTemplate->add ( 'loginBox', $loginBox->get ( $_REQUEST ) );

/**
 * Box linków
 */
$linkBox = new registerLinkBox ( );
$indexTemplate->add ( 'linksBox', $linkBox->get ( $_REQUEST ) );

echo $indexTemplate;

?>