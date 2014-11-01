<?php namespace psm\utils;
if(!defined('psm\\INDEX_DEFINE') || \psm\INDEX_DEFINE !== TRUE) { echo '<header><meta http-equiv="refresh" content="1;url=../"></header>';
	echo '<font size="+2" style="color: black; background-color: white;">Access Denied!!</font>'; exit(1); }
final class san {
	private function __construct() {}



	// sanitize file names
	public static function Filename($filename) {
		if(is_array($filename))
			return array_map(__METHOD__, $filename);
		$filename = trim($filename);
		if(empty($filename))
			return '';
		// shouldn't contain /
		if(strpos($filename, '/') !== FALSE)
			fail('Invalid file name, cannot contain \'/\' in '.$filename);
		if(strpos($filename, '\\') !== FALSE)
			fail('Invalid file name, cannot contain \'\\\' in '.$filename);
		// hidden file, contains . dot
		if(\psm\utils\strings::StartsWith($filename, '.'))
			fail('Invalid file name, cannot start with . dot in '.$filename);
		// remove invalid characters
		$filter = '[:alnum:]\(\)\_\.\,\'\&\?\+\-\=\!';
		$newname = preg_replace('/[^'.$filter.']/', '', $filename);
		if($filename != $newname)
			fail('Invalid file name, contains illegal characters in: '.$newname);
		return $newname;
	}



//	/**
//	 * Sanitize string for MySQL.
//	 * @param string $text - String to be escaped.
//	 * @return string - Clean safe string.
//	 */
//	function mysql($text) {
//		global $db;
//		if(!$db) {
//			ConnectDB();
//			if(!$db) {
//				echo '<p>Database not connected..</p>';
//				exit;
//			}
//		}
//		// san an array
//		if(is_array($text))
//			return array_map(__METHOD__,$text);
//		if(empty($text))
//			return '';
//		return mysql_real_escape_string($text);
//	}



}
?>