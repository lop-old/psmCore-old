<?php namespace psm\pxdb;
if(!defined('psm\\INDEX_DEFINE') || \psm\INDEX_DEFINE !== TRUE) { echo '<header><meta http-equiv="refresh" content="1;url=../"></header>';
	echo '<font size="+2" style="color: black; background-color: white;">Access Denied!!</font>'; exit(1); }
class dbPool {

	private $name;
	private $host;
	private $port;
	private $user;
	private $pass;
	private $database;
	private $prefix;

	private $prepared = null;



	public function __construct($name, $host, $port, $user, $pass, $database, $prefix) {
		$this->name = $name;
		$this->host = $host;
		$this->port = (int) $port;
		$this->user = $user;
		$this->pass = $pass;
		$this->database = $database;
		$this->prefix = $prefix;
	}



	public function getName() {
		return $this->name;
	}



}
?>