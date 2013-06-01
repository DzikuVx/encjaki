<?php

header ( 'Content-Type: text/html; charset=utf-8' );

require_once 'propel/Propel.php';
Propel::init(dirname ( __FILE__ )."/conf/encjakiPropel-conf.php");
set_include_path(dirname ( __FILE__ )."/classes". PATH_SEPARATOR . get_include_path());


$config ['version'] = '0.1';
$config ['error'] = array ('display' => true, 'firebug' => false );
$config ['gameplayUri'] = 'http://encjaki.localhost/gameplay/';

ini_set ( 'date.timezone', 'Europe/Warsaw' );
ini_set ( 'date.default_latitude', '31.7667' );
ini_set ( 'date.default_longitude', '35.2333' );
ini_set ( 'date.sunrise_zenith', '90.583333' );
ini_set ( 'date.sunset_zenith', '90.583333' );

require_once 'db.ini.php';

spl_autoload_register('ClassAutoloader');

function ClassAutoloader($klasa) {

	if (file_exists ( dirname ( __FILE__ ) . '/classes/generic/' . $klasa . '.php' )) {
		require_once dirname ( __FILE__ ) . '/classes/generic/' . $klasa . '.php';
		return true;
	}
	
	if (file_exists ( dirname ( __FILE__ ) . '/classes/gameplay/' . $klasa . '.php' )) {
		require_once dirname ( __FILE__ ) . '/classes/gameplay/' . $klasa . '.php';
		return true;
	}

}

/*
 * Wymuszenie konfiguracji psDebug
*/
psDebug::$displayErrors = true;
psDebug::$displayTrace = true;
psDebug::$sendTrace = true;
psDebug::$errorHoldsExecution = true;
psDebug::$standardErrorText = 'Nastąpił nieoczekiwany błąd programu!';
psDebug::$additionalErrorText = 'Jeśli chcesz pomóc w rozwoju i ulepszaniu Encjaków, <a href="http://dev.spychalski.info/flyspray/index.php?do=newtask&project=2" title="Zgłoś błąd" target="_blank">zgłoś ten błąd <b>tutaj</b></a>';
psDebug::$senderConfig ['url'] = 'localhost';
psDebug::$senderConfig ['path'] = '/psHelpdesk/errorCatcher.php';
psDebug::$senderConfig ['sender'] = 'Encjaki';
/**
 * Rejestracja handlerów
 */
psDebug::create ();

$sCache = CacheController::getInstance();
$db = DataBaseController::getInstance();
$cache = new cache ( 100 );