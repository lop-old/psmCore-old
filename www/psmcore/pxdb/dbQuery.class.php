<?php namespace psm\pxdb;
if(!defined('psm\\INDEX_DEFINE') || \psm\INDEX_DEFINE !== TRUE) { echo '<header><meta http-equiv="refresh" content="1;url=../"></header>';
	echo '<font size="+2" style="color: black; background-color: white;">Access Denied!!</font>'; exit(1); }
class dbQuery {

	protected $pool;
	protected $desc     = NULL;
	protected $st       = NULL;
	protected $rs       = NULL;
	protected $sql      = NULL;
	protected $args     = NULL;
	protected $count    = -1;
	protected $insertid = -1;

	const ARG_PRE   = '[';
	const ARG_DELIM = '|';
	const ARG_POST  = ']';



	public function __construct($pool) {
		$this->pool = $pool;
	}



	public function Prepare($sql) {
		if(empty($sql)) Fail('sql argument is empty');
		$this->sql = $sql;

		return $this;
	}
	public function Prep($sql) {
		if(empty($sql)) Fail('sql argument is empty');
		$this->sql = $sql;

		return TRUE;
	}



	public function Execute($sql=NULL) {
		return TRUE;
	}
	public function Exec($sql=NULL) {
		return TRUE;
	}



	public function desc($desc=NULL) {
		if(empty($desc))
			return $this->desc;
		$this->desc = $desc;
		return $this;
	}



	public function clean() {
		$this->st       = NULL;
//		$this->row      = NULL;
		$this->sql      = NULL;
		$this->args     = NULL;
		$this->count    = NULL;
		$this->insertid = NULL;
	}


	public function hasNext() {
	}




	public function getRowCount() {
		if($this->st == NULL) return -1;
		return $this->count;
	}
	public function getInsertId() {
		return $this->insertid;
	}



	public function setString($index, $value) {
	}
	public function setInt($index, $value) {
	}
	public function setDouble($index, $value) {
	}
	public function setLong($index, $value) {
	}
	public function setBool($index, $value) {
	}
	public function setDate($index, $value) {
	}



	public function getString($index) {
	}
	public function getInt($index) {
	}
	public function getDouble($index) {
	}
	public function getLong($index) {
	}
	public function getBool($index) {
	}
	public function getDate($index) {
	}



}
?>