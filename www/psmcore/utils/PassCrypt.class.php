<?php namespace psm\utils;
if(!defined('psm\\INDEX_DEFINE') || \psm\INDEX_DEFINE !== TRUE) { echo '<header><meta http-equiv="refresh" content="1;url=../"></header>';
	echo '<font size="+2" style="color: black; background-color: white;">Access Denied!!</font>'; exit(1); }
class PassCrypt {
//methods:
// loop=x action	- loop action x times
// md5				- standard md5
// rev				- reverse the hash/string
// splitrev=x		- reverse last x chars
// revsplit=x		- reverse first x chars
// setsalt=value	- set salt to value
// salt				- insert salt
// salt=x			- insert salt to position x
// saltcenter		- insert salt in center
// saltfront		- insert salt at front
// saltend			- insert salt at end
//examples:
// 'md5'
//    - standard md5 only
// 'loop=500 md5'
//    - md5 hash 500 times
// 'md5 salt md5'
//    - md5, salt, then md5 once more
// 'setsalt=salt loop=1000 md5 revsplit=10 salt=6 salt=-6 loop=5 md5 salt splitrev=8 md5'
//    - loop 1000 times the following:
//      md5, reverse first 10 chars, insert salt to position 6, insert salt to position 6 from end,
//      and loop 5 times each iteration: md5, insert salt, reverse last 8 chars, and finally md5



	public static function DefaultHash($data) {
		return
			self::ExecHash(
				$data,
				'setsalt=salt loop=1000 md5 revsplit=10 salt=6 salt=-6 loop=5 md5 salt splitrev=8 md5'
			);
	}
	public static function ExecHash($data, $commands) {
		$crypt = new self($data);
		$crypt->Exec($commands);
		return $crypt->toString();
	}



	private $text = NULL;
	private $salt = NULL;
	private $lastWasSalt = FALSE;



	public function __construct($text=NULL, $salt=NULL) {
		if(!empty($text))
			$this->text = $text;
		if(!empty($salt))
			$this->salt = $salt;
	}



	public function loop($count) {
		if($count < 1) return NULL;
		$commands = \psm\utils::array_remove_empty(
			\psm\utils::func_args_array(func_get_args(), 1)
		);
		if($commands == NULL || count($commands) == 0)
			fail('Loop requires a command');
		for($i=0; $i<$count; $i++) {
			foreach($commands as $command)
				$this->exec($command);
		}
		return $this;
	}



	public function exec($commands) {
		if(empty($commands)) return NULL;
		$delim = ' ';
		$token = \psm\utils\strings::getToken($commands, $delim);
		// command has argument
		if(strpos($token, '=') !== FALSE)
			list($token, $arg) = explode('=', $token, 2);
		else
			$arg = NULL;
		switch(str_replace(array(' ', '_'), '', strtolower($token))) {
			// loop
			case 'loop':
				$count = \psm\utils\vars::castType($arg, 'int');
				$this->loop($count, $commands);
				$commands = '';
				break;
			// md5
			case 'md5':
				$this->md5();
				break;
			// reverse string
			case 'reverse':
			case 'rev':
				$this->reverse();
				break;
			case 'revsplit':
			case 'reversesplit':
				if($arg == NULL) {
					$this->$reverse_split();
				} else {
					$pos = \psm\utils\vars::castType($arg, 'int');
					$this->reverse_split($pos);
				}
				break;
			case 'splitrev':
			case 'splitreverse':
				if($arg == NULL) {
					$this->$split_reverse();
				} else {
					$pos = \psm\utils\vars::castType($arg, 'int');
					$this->split_reverse($pos);
				}
				break;
			// salt
			case 'setsalt':
				$salt = \psm\utils\vars::castType($arg, 'str');
				$salt = \psm\utils\strings::TrimQuotes($salt);
				$this->setSalt($salt);
				break;
			case 'salt':
				if($arg == NULL) {
					$this->salt();
				} else {
					$pos = \psm\utils\vars::castType($arg, 'int');
					$this->salt($pos);
				}
				break;
			case 'saltcenter':
			case 'saltmid':
				$this->salt_center();
				break;
			case 'saltfront':
				$this->salt_front();
				break;
			case 'saltend':
				$this->salt_end();
				break;
			// unknown
			default:
				fail('Unknown crypt method: '.$token);
		}
		// execute more commands
		if(!empty($commands))
			$this->exec($commands);
		return $this;
	}



	public function md5() {
		$this->validNotEmpty();
		$this->text = md5($this->text);
		$this->lastWasSalt = FALSE;
		return $this;
	}



	public function reverse() {
		$this->validNotEmpty();
		$this->text = strrev($this->text);
		return $this;
	}
	public function rev() {
		return $this->reverse();
	}



	public function reverse_split($pos=0) {
		$this->validNotEmpty();
		// split in half
		if($pos == 0) $pos = floor( ((double)strlen($this->text)) / 2.0 );
		$this->text =
			strrev(substr($this->text, 0, $pos)).
			substr($this->text, $pos);
		return $this;
	}
	public function split_reverse($pos=0) {
		$this->validNotEmpty();
		// split in half
		if($pos == 0) $pos = floor( ((double) strlen($this->text)) / 2.0 );
		$this->text =
			substr($this->text, 0, $pos).
			strrev(substr($this->text, $pos));
		return $this;
	}



	public function setSalt($salt) {
		$this->salt = $salt;
		return $this;
	}
	public function salt($pos=0) {
		$this->validSalt();
		if($pos < 0) $pos = 0 - $pos;
		if($pos == 0) {
			$this->text =
				$this->salt.
				$this->text;
		} else {
			$this->text =
				substr($this->text, 0, $pos).
				$this->salt.
				substr($this->text, $pos);
		}
		$this->lastWasSalt = TRUE;
		return $this;
	}
	public function salt_center() {
		return
			$this->salt(
				floor(
					((double) strlen($this->text)) / 2.0
				)
			);
	}
	public function salt_front() {
		$this->validSalt();
		$this->text = $this->salt.$this->text;
		$this->lastWasSalt = TRUE;
		return $this;
	}
	public function salt_end() {
		$this->validSalt();
		$this->text = $this->text.$this->salt;
		$this->lastWasSalt = TRUE;
		return $this;
	}



	private function validNotEmpty() {
		if(empty($this->text))
			fail('Input text not set for PassCrypt');
	}
	private function validSalt() {
		if(empty($this->salt))
			fail('Salt not set');
	}



	public function toString() {
//		if($this->lastWasSalt)
//			fail('Last action was salt');
		return $this->text;
	}



}
?>