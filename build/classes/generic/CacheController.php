<?php
/**
 * Kontroler cache
 * Static
 * @author Paweł
 *
 */
class CacheController {

	/**
	 * Obiekt klasy cache
	 * @var mixed
	 */
	private static $cacheInstance;

	/**
	 * Konstruktor prywatny
	 */
	private function __construct() {

	}

	public static $method = 'apc';

	/*
	 * Metoda tworząca obiekt cache
	*/
	static private function create() {

		if (empty(self::$cacheInstance)) {

			switch (self::$method) {

				case 'apc':
					self::$cacheInstance = CacheOverApc::getInstance();
						
					break;

				default:
					self::$cacheInstance = BasicCache::getInstance();
				break;
					
			}

		}

	}

	/**
	 * Pobranie obiektu klasy cacheującej
	 * @throws Exception
	 * @return CacheOverApc
	 */
	static public function getInstance() {

		if (empty(self::$cacheInstance)) {
			self::create();
		}

		if (empty(self::$cacheInstance)) {
			throw new Exception('Cache object is not initialized');
		}

		return self::$cacheInstance;
	}

}