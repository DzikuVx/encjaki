<?php
/**
 * Kontroler bazy danych
 * @author Paweł
 */
class DataBaseController {

	private function __construct() {
	}

	private static $instance = null;

	private static $chatInstance = null;

	/**
	 * Pobranie obiektu bazy danych gameplay
	 * @throws Exception
	 * @return dataBase
	 */
	public static function getInstance() {

		if (empty(self::$instance)) {
			self::connect();
		}

		if (empty(self::$instance)) {
			throw new Exception('Data Base object failed to initialize');
		}

		return self::$instance;

	}

	/**
	 * Połączenie z bazą danych gameplay
	 */
	private static function connect() {
		global $dbConfig;

		self::$instance = new dataBase ( $dbConfig );

	}

}