<?php

/**
 * Klasa zapisująca obiekty w plików
 *
 */
class itemSaver {

	private $fileName = null;
	private $userID = null;
	private $contentHash = null;

	private $mode = 'file'; // file / db
	private $type = '';
	private $useCompression = false;

	static public $mongo = array();

	private $server;
	private $db;
	private $collection;

	private function connectMongo() {

		/**
		 * Połącz do serwera
		 */
		$this->server = new Mongo(self::$mongo['server'].":".self::$mongo['port']);

		if (empty($this->server)) {
			throw new Exception('Error while connecting to mongoDB server');
		}

		/*
		 * Połącz do bazy danych
		*/
		$this->db = $this->server->selectDB(self::$mongo['dbName']);

		if (empty($this->db)) {
			throw new Exception('Error while connecting to mongoDB database');
		}

		/*
		 * Wybierz kolekcję
		*/
		$this->collection = $this->db->selectCollection($this->type);

		if (empty($this->collection)) {
			throw new Exception('Error while connecting to mongoDB collection');
		}

	}

	private function putDb($object, $onlyIfChanged = true) {

		global $db;

		$tObjectSerial = serialize ( $object );

		if ($onlyIfChanged && md5 ( $tObjectSerial ) == $this->contentHash) {
			return true;
		}

		$tQuery = "SELECT COUNT(*) AS ILE FROM itemsaver WHERE UserID='{$this->userID}' AND Type='{$this->type}'";
		$tQuery = $db->execute($tQuery);
		while ($tResult = $db->fetch($tQuery)) {
			$tCount = $tResult->ILE;
		}

		if ($tCount == 0) {
			$tQuery = "INSERT INTO itemsaver(UserID, Type, Data) VALUES('{$this->userID}','{$this->type}','{$tObjectSerial}')";
		}else {
			$tQuery = "UPDATE itemsaver SET Data='{$tObjectSerial}' WHERE UserID='{$this->userID}' AND Type='{$this->type}'";
		}
		$db->execute($tQuery);

		return true;
	}

	private function putFile($object, $onlyIfChanged = true) {

		$tObjectSerial = serialize ( $object );

		if ($onlyIfChanged && md5 ( $tObjectSerial ) == $this->contentHash) {
			return true;
		}

		$tCounter = 0;

		$tFile = fopen ( $this->fileName, 'a' );
		while ( ! flock ( $tFile, LOCK_EX ) ) {
			usleep ( 10 );
			$tCounter ++;
			if ($tCounter == 100) {
				return false;
			}
		}

		ftruncate ( $tFile, 0 );

		if ($this->useCompression) {
			$tObjectSerial = gzcompress($tObjectSerial,1);
		}

		fputs ( $tFile, $tObjectSerial );

		flock ( $tFile, LOCK_UN );
		fclose ( $tFile );

		return true;

	}

	/**
	 * Pobranie danych z bazy mongoDB
	 * @param mixed $object
	 * @throws Exception
	 * @return boolean
	 */
	private function getMongo(&$object) {
			
		$retVal = $this->collection->findOne(array('userID' => $this->userID));
			
		$object = unserialize ( $retVal['object'] );

		if (empty($object)) {
			throw new Exception('Data retrieval failed');
		}
			
		return true;
	}

	/**
	 * Zapisanie danych do mongoDB
	 * @param mixed $object
	 * @param boolean $onlyIfChanged
	 * @return boolean
	 * @throws itemSaverException
	 */
	private function putMongo($object, $onlyIfChanged = true) {

		$tObjectSerial = serialize ( $object );

		if ($onlyIfChanged && md5 ( $tObjectSerial ) == $this->contentHash) {
			return true;
		}

		$data = array();
		$data['userID'] = $this->userID;
		$data['object'] = $tObjectSerial;
			
		$result = $this->collection->update(array("userID" => $this->userID), $data, array('safe'=>true, "upsert" => true));

		/*
		 * W przypadku będu wstawienia, rzuć wyjątkiem
		*/
		if (empty($result['ok'])) {
			$this->putFile($object, $onlyIfChanged);
			
			throw new itemSaverException($result['err'], $result['code']);
		}

		return true;

	}

	private function getFile(&$object) {

		$tCounter = 0;

		$tFile = fopen ( $this->fileName, 'rb' );

		if (! $tFile) {
			return false;
		}

		while ( ! flock ( $tFile, LOCK_SH ) ) {
			usleep ( 10 );
			$tCounter ++;
			if ($tCounter == 100) {
				return false;
			}
		}
		$retVal = fread ( $tFile, filesize ( $this->fileName ) );

		if ($this->useCompression) {
			$retVal = gzuncompress($retVal,1);
		}

		$this->contentHash = md5 ( $retVal );

		$object = unserialize ( $retVal );

		flock ( $tFile, LOCK_UN );
		fclose ( $tFile );
		return true;
	}

	/**
	 * Konstruktor
	 *
	 * @param string $type
	 * @param int $userID
	 */
	public function __construct($type, $userID, $mode = 'mongo', $useCompression = false) {

		$this->userID = $userID;
		$this->fileName = dirname ( __FILE__ ) . "/../../userData/" . $type . "." . $this->userID . ".isf";
		$this->type = $type;
		$this->mode = $mode;
		$this->useCompression = $useCompression;

		if ($mode == 'mongo') {
			$this->connectMongo();
		}

	}

	/**
	 * Zapisanie obiektu do pliku
	 *
	 * @param mixed $object
	 * @param boolean $onlyIfChanged
	 */
	public function put($object, $onlyIfChanged = true) {

		switch ($this->mode) {

			case 'mongo' :

				return $this->putMongo( $object, $onlyIfChanged);

				break;

			case 'file' :
				return $this->putFile ( $object, $onlyIfChanged );
				break;

			case 'db' :
				return $this->putDb ( $object, $onlyIfChanged );
				break;

		}

	}

	/**
	 * Pobranie obiektu z pliku
	 *
	 * @param mixed $object
	 * @return boolean
	 */
	public function get(&$object) {
		//@todo: itemSaver na bazie danych
		switch ($this->mode) {

			case 'file' :
				$this->getFile ( $object );
				break;

			case 'mongo' :
				$this->getMongo($object);
				break;

		}
	}

}

class itemSaverException extends Exception {

}