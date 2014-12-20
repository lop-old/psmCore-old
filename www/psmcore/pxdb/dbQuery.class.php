<?php namespace psm\pxdb;
if(!defined('psm\\INDEX_DEFINE') || \psm\INDEX_DEFINE !== TRUE) { echo '<header><meta http-equiv="refresh" content="1;url=../"></header>';
	echo '<font size="+2" style="color: black; background-color: white;">Access Denied!!</font>'; exit(1); }
class dbQuery {

	protected $pool = NULL;
	protected $conn = NULL;
	protected $tablePrefix = NULL;
	protected $inuse = FALSE;

	protected $st   = NULL;
	protected $rs   = NULL;
	protected $sql  = NULL;
	protected $desc = NULL;

	protected $row      = NULL;
	protected $args     = array();
	protected $rowcount = -1;
	protected $insertid = -1;

	const ARG_PRE   = '[';
	const ARG_DELIM = '|';
	const ARG_POST  = ']';



	public function __construct($pool, \PDO $conn) {
		if($pool == NULL) fail('pool cannot be null');
		if($conn == NULL) fail('conn cannot be null');
		$this->pool = $pool;
		$this->conn = $conn;
		$this->tablePrefix = $this->pool->getTablePrefix();
	}



	public function Prepare($sql) {
		if(empty($sql)) fail('sql cannot be empty');
		$this->clean();
		try {
			$this->sql = str_replace('_table_', (empty($this->tablePrefix) ? '' : $this->tablePrefix), $sql);
			// prepared statement
			$this->st = $this->conn->prepare($this->sql);
			return $this;
		} catch (\SQLException $e) {
			fail($e->getMessage());
		}
		return NULL;
	}
	public function prep($sql) {
		if(empty($sql)) fail('sql cannot be empty');
		try {
			if($this->Prepare($sql) != NULL)
				return TRUE;
		} catch (\SQLException $e) {
			fail($e->getMessage());
		}
		$this->clean();
		return FALSE;
	}



	public function Execute($sql=NULL) {
		if(!empty($sql))
			if($this->Prepare($sql) == NULL)
				return NULL;
		if($this->st == NULL) return NULL;
		if(empty($this->sql)) return NULL;
		try {
			$pos = \strpos(' ', $this->sql);
			$firstpart = \strtoupper(
				$pos == -1 ? $this->sql : \substr($this->sql, 0, $pos)
			);
			// run query
			if(!$this->st->execute())
				return NULL;
			// insert id
			if($firstpart === 'INSERT')
				$this->insertid = $this->db->lastInsertId();
			else
				$this->rowcount = $this->st->rowCount();
			return $this;
		} catch (\SQLException $e) {
			fail($e->getMessage());
		}
		return NULL;
	}
	public function exec($sql=NULL) {
		try {
			if($this->Execute($sql) != NULL)
				return TRUE;
		} catch (\SQLException $e) {
			fail($e->getMessage());
			return FALSE;
		}
		return TRUE;
	}



	public function desc($desc=NULL) {
		if(empty($desc))
			return $this->desc;
		$this->desc = $desc;
		return $this;
	}



	public function isLocked() {
		return $this->inuse;
	}
	public function getLock() {
		if($this->inuse)
			return FALSE;
		$this->inuse = TRUE;
		return TRUE;
	}
	public function free() {
		$this->clean();
		$this->inuse = FALSE;
	}
	public function clean() {
		$this->st       = NULL;
		$this->row      = NULL;
		$this->sql      = NULL;
		$this->args     = '';
		$this->rowcount = -1;
		$this->insertid = -1;
	}



	public function next() {
		if($this->st == NULL)
			return FALSE;
		try {
			$this->row = $this->st->fetch(\PDO::FETCH_ASSOC);
			if($this->row === FALSE) {
				$this->clean();
				return FALSE;
			}
			return $this->row;
		} catch (\SQLException $e) {
			fail($e->getMessage());
		}
		return FALSE;
	}



	public function getRowCount() {
		if($this->st == NULL)
			return -1;
		return $this->rowcount;
	}
	public function getInsertId() {
		return $this->insertid;
	}



	// ==================================================
	// query parameters
	public function setString($index, $value) {
		if($this->st == NULL)
			return NULL;
		try {
			$value = \psm\utils\vars::castType($value, 'str');
			$this->st->bindParam($index, $value);
			$this->args .= ' String: '.\psm\utils\vars::castType($value, 'str');
			return $this;
		} catch (\SQLException $e) {
			fail($e->getMessage());
		}
		return NULL;
	}
	public function setInt($index, $value) {
		if($this->st == NULL)
			return NULL;
		try {
			$value = \psm\utils\vars::castType($value, 'int');
			$this->st->bindParam($index, $value);
			$this->args .= ' Int: '.\psm\utils\vars::castType($value, 'str');
			return $this;
		} catch (\SQLException $e) {
			fail($e->getMessage());
		}
		return NULL;
	}
	public function setDouble($index, $value) {
		if($this->st == NULL)
			return NULL;
		try {
			$value = \psm\utils\vars::castType($value, 'double');
			$this->st->bindParam($index, $value);
			$this->args .= ' Double: '.\psm\utils\vars::castType($value, 'str');
			return $this;
		} catch (\SQLException $e) {
			fail($e->getMessage());
		}
		return NULL;
	}
	public function setLong($index, $value) {
		if($this->st == NULL)
			return NULL;
		try {
			$value = \psm\utils\vars::castType($value, 'long');
			$this->st->bindParam($index, $value);
			$this->args .= ' Long: '.\psm\utils\vars::castType($value, 'str');
			return $this;
		} catch (\SQLException $e) {
			fail($e->getMessage());
		}
		return NULL;
	}
	public function setBool($index, $value) {
		if($this->st == NULL)
			return NULL;
		try {
			$value = \psm\utils\vars::castType($value, 'bool');
			$this->st->bindParam($index, $value);
			$this->args .= ' Boolean: '.\psm\utils\vars::castType($value, 'str');
			return $this;
		} catch (\SQLException $e) {
			fail($e->getMessage());
		}
		return NULL;
	}
//	public function setDate($index, $value) {
//		if($this->st == NULL)
//			return NULL;
//		try {
//			$value = \psm\utils\vars::castType($value, 'int');
//			$this->st->bindParam($index, $value);
//			$this->args .= ' Date: '.\psm\utils\vars::castType($value, 'str');
//			return $this;
//		} catch (\SQLException $e) {
//			fail($e->getMessage());
//		}
//		return NULL;
//	}



	// ==================================================
	// get result
	public function getString($index) {
		if($this->row == NULL)         return FALSE;
		if(!isset($this->row[$index])) return FALSE;
		return \psm\utils\vars::castType($this->row[$index], 'str');
	}
	public function getInt($index) {
		if($this->row == NULL)         return FALSE;
		if(!isset($this->row[$index])) return FALSE;
		return \psm\utils\vars::castType($this->row[$index], 'int');
	}
	public function getDouble($index) {
		if($this->row == NULL)         return FALSE;
		if(!isset($this->row[$index])) return FALSE;
		return \psm\utils\vars::castType($this->row[$index], 'double');
	}
	public function getLong($index) {
		if($this->row == NULL)         return FALSE;
		if(!isset($this->row[$index])) return FALSE;
		return \psm\utils\vars::castType($this->row[$index], 'long');
	}
	public function getBool($index) {
		if($this->row == NULL)         return FALSE;
		if(!isset($this->row[$index])) return FALSE;
		return \psm\utils\vars::castType($this->row[$index], 'bool');
	}
	public function getDate($index, $format=NULL) {
		if($this->row == NULL)         return FALSE;
		if(!isset($this->row[$index])) return FALSE;
		$val = $this->getInt($index);
		if($val === FALSE) return FALSE;
		if(empty($format))
			$format = 'Y-m-d H:i:s';
		return date($format, $val);
	}



}
?>