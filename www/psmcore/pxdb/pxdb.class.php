<?php namespace psm;
if(!defined('psm\\INDEX_DEFINE') || \psm\INDEX_DEFINE !== TRUE) { echo '<header><meta http-equiv="refresh" content="1;url=../"></header>';
	echo '<font size="+2" style="color: black; background-color: white;">Access Denied!!</font>'; exit(1); }
class pxdb {

	protected static $pools = array();



	public static function add(\psm\pxdb\dbPool $pool) {
		$name = $pool->getName();
		if(isset(self::$pools[$name])) return;
		self::$pools[$name] = $pool;
	}
	public static function add_mysql($name, $host, $port, $user, $pass, $database, $prefix) {
		if(empty($name))
			$name = 'main';
		$name = (string) $name;
		// check for existing
		if(isset(self::$pools[$name])) return;
		// new pool instance
		self::$pools[$name] = new \psm\pxdb\dbPool(
			$name,
			'mysql',
			$host,
			$port,
			$user,
			$pass,
			$database,
			$prefix
		);
	}



	// get dbPool instance
	public static function getPool($name=NULL) {
		if(empty($name))
			$name = 'main';
		$name = (string) $name;
		if(isset(self::$pools[$name]))
			return self::$pools[$name];
		return NULL;
	}
	// get connection instance
	public static function get($name=NULL) {
		$pool = self::getPool($name);
		if($pool == NULL)
			return NULL;
		return $pool->getConnection();
	}



}
?>