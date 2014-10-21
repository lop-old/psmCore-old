<?php namespace psm;
if(!defined('psm\\INDEX_DEFINE') || \psm\INDEX_DEFINE !== TRUE) { echo '<header><meta http-equiv="refresh" content="1;url=../"></header>';
	echo '<font size="+2" style="color: black; background-color: white;">Access Denied!!</font>'; exit(1); }
class pxdb {

	protected static $pools = array();



	public static function add($pool) {
		$name = $pool->getName();
		if(isset(self::$pools[$name])) return;
		self::$pools[$name] = $pool;
	}
	public static function add_mysql($name, $host, $port, $user, $pass, $database, $prefix) {
		if(empty($name)) $name = 'main';
		// check for existing
		if(isset(self::$pools[$name])) return;
		// new pool instance
		self::$pools[$name] = new \psm\pxdb\dbPool(
			$name,
			$host,
			$port,
			$user,
			$pass,
			$database,
			$prefix
		);
	}



	public function get($name=NULL) {
		if(!isset(self::$pools[$name]))
			return NULL;
		return self::$pools[$name];
	}



}
?>