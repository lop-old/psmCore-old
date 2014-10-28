<?php namespace psm\utils;
if(!defined('psm\\INDEX_DEFINE') || \psm\INDEX_DEFINE !== TRUE) { echo '<header><meta http-equiv="refresh" content="1;url=../"></header>';
	echo '<font size="+2" style="color: black; background-color: white;">Access Denied!!</font>'; exit(1); }
final class strings {
	private function __construct() {}



	/**
	 *
	 * @param string $text
	 * @param string|array $remove
	 * @param ...
	 * @return string
	 */
	public static function Trim($text) {
		$remove = \psm\utils::array_remove_empty(
			\psm\utils::func_args_array(\func_get_args(), 1)
		);
		if(\count($remove) == 0)
			$remove = array(' ', "\t", "\r", "\n");
		while(\in_array(\substr($text, 0, 1), $remove))
			$text = \substr($text, 1);
		while(\in_array(\substr($text, -1, 1), $remove))
			$text = \substr($text, 0, -1);
		return $text;
	}
	public static function TrimFront($text) {
		$remove = \psm\utils::array_remove_empty(
			\psm\utils::func_args_array(\func_get_args(), 1)
		);
		if(\count($remove) == 0)
			$remove = array(' ', "\t", "\r", "\n");
		while(\in_array(\substr($text, 0, 1), $remove))
			$text = \substr($text, 1);
		return $text;
	}
	public static function TrimEnd($text) {
		$remove = \psm\utils::array_remove_empty(
			\psm\utils::func_args_array(\func_get_args(), 1)
		);
		if(\count($remove) == 0)
			$remove = array(' ', "\t", "\r", "\n");
		while(\in_array(\substr($text, -1, 1), $remove))
			$text = \substr($text, 0, -1);
		return $text;
	}



	/**
	 * Removes paired quotes from a string.
	 * @param string $data - String in which to remove quotes.
	 * @return string - String with ' and " quotes removed.
	 */
	public static function TrimQuotes($data) {
		while(\strlen($data) > 2) {
			$f = \substr($data, 0, 1);
			$e = \substr($data, -1, 1);
			if( ($f == S_QUOTE && $e == S_QUOTE) ||
				($f == D_QUOTE && $e == D_QUOTE) ) {
				$data = \substr($data, 1, -2);
			} else {
				break;
			}
		}
		return $data;
	}



	/**
	 * Trims / and \ slashes from front and end of a path.
	 * @param string $path - Path string to trim.
	 * @return string
	 */
	function TrimPath($path) {
		return self::Trim($path, '/', '\\');
	}



	/**
	 *
	 * @param string $data - String to be parsed.
	 * @param string/array $delim - Deliminator or string used to split input string.
	 * @return string - Parsed token, or remaining data if $delim not found.
	 */
	public static function getToken(&$data, $delim=' ') {
		$token = self::peakToken($data, $delim);
		$len = \strlen($token);
		if($len == \strlen($data))
			$data = '';
		else
			$data = self::Trim(\substr($data, $len), $delim);
		return $token;
	}
	public static function peakToken($data, $delim=' ') {
		if(\is_array($delim)) {
			$pos = PHP_INT_MAX;
			foreach($delim as $d) {
				$p = \strpos($data, $d);
				if($p === FALSE)
					continue;
				if($p < $pos)
					$pos = $p;
			}
			// delim not found
			if($pos == PHP_INT_MAX)
				return $data;
		} else {
			$pos = \strpos($data, $delim);
		}
		if($pos === FALSE)
			return $data;
		return \substr($data, 0, $pos);
	}



	public static function Contains($haystack, $needle, $ignoreCase=FALSE) {
		if(empty($haystack) || empty($needle))
			return FALSE;
		if($ignoreCase) {
			$haystack = \strtolower($haystack);
			$needle   = \strtolower($needle);
		}
		return (\strpos($haystack, $needle) !== FALSE);
	}



	public static function getBetween(&$data, $first, $second, $remove=TRUE) {
		$pos1 = \strpos($data, $first);
		if($pos1 === FALSE) return NULL;
		$pos2 = \strpos($data, $second, $pos1);
		if($pos2 === FALSE) return NULL;
		$out = \substr(
			$data,
			$pos1 + \strlen($first),
			$pos2 - ($pos1 + \strlen($first))
		);
		if($remove)
			$data = \substr($data, 0, $pos1).
					\substr($data, $pos2 + \strlen($second));
		return $out;
	}



	public static function StartsWith($haystack, $needle, $ignoreCase=FALSE) {
		if(empty($haystack) || empty($needle))
			return FALSE;
		$length = \strlen($needle);
		if($length == 0 || $length > strlen($haystack))
			return FALSE;
		if($ignoreCase) {
			$haystack = \strtolower($haystack);
			$needle   = \strtolower($needle);
		}
		return (\substr($haystack, 0, $length) === $needle);
	}
	public static function EndsWith($haystack, $needle, $ignoreCase=FALSE) {
		if(empty($haystack) || empty($needle))
			return FALSE;
		$length = \strlen($needle);
		if($length == 0 || $length > \strlen($haystack))
			return FALSE;
		if($ignoreCase) {
			$haystack = \strtolower($haystack);
			$needle   = \strtolower($needle);
		}
		if($length == 0)
			return FALSE;
		return (\substr($haystack, 0-$length) === $needle);
	}



	public static function forceStartsWith($haystack, $append) {
		if(empty($haystack) || empty($append))
			return '';
		if(!self::StartsWith($haystack, $append))
			$haystack = $append.$haystack;
		return $haystack;
	}
	public static function forceEndsWith($haystack, $append) {
		if(empty($haystack) || empty($append))
			return '';
		if(!self::EndsWith($haystack, $append))
			$haystack = $haystack.$append;
		return $haystack;
	}



}
?>
