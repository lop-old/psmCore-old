<?php namespace psm\pxdb;
if(!defined('psm\\INDEX_DEFINE') || \psm\INDEX_DEFINE !== TRUE) { echo '<header><meta http-equiv="refresh" content="1;url=../"></header>';
	echo '<font size="+2" style="color: black; background-color: white;">Access Denied!!</font>'; exit(1); }
class dbPool {

	private static $max_connections = 5;

	private $name;
	private $dbtype;
	private $host;
	private $port;
	private $user;
	private $pass;
	private $database;
	private $prefix;

	private $workers = array();



	public function __construct($name, $dbtype, $host, $port, $user, $pass, $database, $prefix) {
		$this->name = (string) $name;
		$this->dbtype=(string) $dbtype;
		$this->host = (string) $host;
		$this->port = (int)    $port;
		$this->user = (string) $user;
		$this->pass = \base64_encode( (string) $pass );
		$this->database = (string) $database;
		$this->prefix = (string) $prefix;
	}



	public function getName() {
		return $this->name;
	}
	public function getTablePrefix() {
		return $this->prefix;
	}



	public function getWorkerCount() {
		return \count($this->workers);
	}



	public function isConnected() {
		$worker = $this->getExisting();
		if($worker == NULL)
			return FALSE;
		$worker->free();
		return TRUE;
	}



	public function getConnection() {
		$worker = $this->getExisting();
		if($worker != NULL)
			return $worker;
		// max connections
		if($this->getWorkerCount() >= self::$max_connections) {
			fail('Max db connections reached! [ '.$this->getWorkerCount().' ]');
			return NULL;
		}
		// new connection
		$worker = $this->newConnection();
		$this->workers[] = $worker;
		if(!$worker->getLock())
			return NULL;
		return $worker;
	}
	public function getExisting() {
		// loop existing connections
		foreach($this->workers as $worker) {
			if($worker->getLock())
				return $worker;
		}
		return NULL;
	}
	protected function newConnection() {
		try {
			$dsn = \strtolower($this->dbtype).':'.
				'dbname='.$this->database.';'.
				'host='.$this->host;
			if($this->port > 0 && $this->port != 3306)
				$dsn .= ';port='.$this->port;
			$conn = new \PDO(
				$dsn,
				$this->user,
				\base64_decode($this->pass)
			);
		} catch (\PDOException $e) {
			fail($e->getMessage());
			return NULL;
		}
		$worker = new dbQuery($this, $conn);
		return $worker;
	}



}
?>