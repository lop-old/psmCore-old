<?php namespace psm\utils;
if(!defined('psm\\INDEX_DEFINE') || \psm\INDEX_DEFINE !== TRUE) { echo '<header><meta http-equiv="refresh" content="1;url=../"></header>';
	echo '<font size="+2" style="color: black; background-color: white;">Access Denied!!</font>'; exit(1); }
final class vars {
	private function __construct() {}



	/**
	 * Parses REQUEST_URI from http request header and inserts into $_GET array.
	 * @example:
	 * URL: http://example.com/page/home/?action=display
	 * // After processing, $_GET contains:
	 * array(
	 *     'page' => 'home',
	 *     'action' => 'display'
	 * );
	 */
	public static function Parse_Mod_Rewrite() {
		// parse mod_rewrite uri
		if(isset($_SERVER['REDIRECT_STATUS'])) {
			$data = $_SERVER['REQUEST_URI'];
			// parse ? query string
			if(strpos($data, '?') !== FALSE) {
				list($data, $query) = explode('?', $data, 2);
				if(!empty($query)) {
					//$arr = explode('&', $query);
					//echo 'query: ?'.$query.'<br />';
				}
			}
			// parse url path
			$data = array_values(\psm\utils::array_remove_empty(explode('/', $data)));
			// needs to be even
			if((count($data) % 2) != 0)
				$data[] = '';
			// merge values into GET
			for($i=0; $i<count($data); $i++)
				$_GET[$data[$i]] = $data[++$i];
		}
	}



	// get,post,cookie (highest priority last)
	/**
	 * Gets a value from a specific list of sources.
	 * @param string $name - Name or key requested.
	 * @param string $type - Casts value to this type.
	 *     Possible values: str, int, float, double, bool
	 * @param array/string $source - String or array of strings. (from least to greatest importance)
	 *     Possible values: get, post, cookie, session
	 * @return object - Returns the requested value, cast to requested type.
	 */
	public static function getVar($name, $type='str', $source=array('get','post')) {
		if(!is_array($source))
			$source = @explode(',', (string) $source);
		if(!is_array($source))
			return NULL;
		$value = NULL;
		foreach($source as $src) {
			$v = NULL;
			switch(\strtolower(\substr(\trim( (string) $src ), 0, 1))) {
				// get
				case 'g':
					$v = self::get($name, $type);
					break;
				// post
				case 'p':
					$v = self::post($name, $type);
					break;
				// cookie
				case 'c':
					$v = self::cookie($name, $type);
					break;
				// session
				case 's':
					$v = self::session($name, $type);
					break;
				default:
					fail('Unknown value source: '.$src);
			}
			// value found
			if($v !== NULL)
				$value = $v;
		}
		return $value;
	}



	// get var
	public static function get($name, $type) {
		if(isset($_GET[$name]))
			return self::castType($_GET[$name], $type);
		return NULL;
	}
	// post var
	public static function post($name, $type) {
		if(isset($_POST[$name]))
			return self::castType($_POST[$name], $type);
		return NULL;
	}
	// cookie var
	public static function cookie($name, $type) {
		if(isset($_COOKIE[$name]))
			return self::castType($_COOKIE[$name], $type);
		return NULL;
	}
	// php session var
	public static function session($name, $type) {
		if(isset($_SESSION[$name]))
			return self::castType($_SESSION[$name], $type);
		return NULL;
	}



	// cast variable type
	public static function castType($data, $type) {
		switch(strtolower(substr( (string) $type, 0, 1))) {
			// string
			case 's':
				return ((string) $data);
			// integer
			case 'i':
				return ((integer) $data);
			// float
			case 'f':
				return ((float) $data);
			// double
			case 'd':
				return ((double) $data);
			// boolean
			case 'b':
				return self::toBoolean($data);
			default:
				break;
		}
		return $data;
	}
	// convert to boolean
	public static function toBoolean($value) {
		if(gettype($value) === 'boolean')
			return $value;
		$val = strtolower(trim( (string) $value ));
		if($val == 'on')  return TRUE;
		if($val == 'off') return FALSE;
		switch(substr($val, 0, 1)) {
			case 't': // true
			case 'y': // yes
			case 'a': // allow
			case 'e': // enable
				return TRUE;
			case 'f': // false
			case 'n': // no
			case 'd': // deny/disable
				return FALSE;
		}
		return ((boolean) $value);
	}



//	// php session
//	if(function_exists('session_status'))
//		if(session_status() == PHP_SESSION_DISABLED){
//		echo '<p>PHP Sessions are disabled. This is a requirement, please enable this.</p>';
//		exit;
//	}
//	session_init();



	// init php sessions
	private static $session_init_had_run = FALSE;
	public static function session_init() {
		if(self::$session_init_had_run) return;
		\session_start();
		self::$session_init_had_run = TRUE;
	}



}
?>