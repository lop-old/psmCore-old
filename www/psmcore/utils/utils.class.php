<?php namespace psm;
if(!defined('psm\\INDEX_DEFINE') || \psm\INDEX_DEFINE !== TRUE) { echo '<header><meta http-equiv="refresh" content="1;url=../"></header>';
	echo '<font size="+2" style="color: black; background-color: white;">Access Denied!!</font>'; exit(1); }
final class utils {
	private function __construct() {}



	private static $qtime = -1;
	/**
	 * Initializes render time calculator.
	 */
	public static function initRenderTime() {
		if(self::$qtime <= 0.0)
			self::$qtime = self::getTimestamp();
	}
	/**
	 * Returns the seconds elapsed since rendering began.
	 * @param int $places - Number of decimal places in which to round.
	 * @return double - Number of seconds elapsed.
	 */
	public static function getRenderTime($places=3) {
		if(self::$qtime <= 0.0)
			return NULL;
		return
			round(
				(self::getTimestamp() - self::$qtime) * 1000.0,
				$places
			);
	}



	public static function getNumClasses() {
		return \psm\ClassLoader::getNumClasses();
	}
	public static function getNumQueries() {
//		return \psm\utils\db::getNumQueries();
		return 0;
	}



	/**
	 * @return double - Returns current timestamp in milliseconds.
	 */
	public static function getTimestamp() {
		$time = explode(' ', \microtime(), 2);
		return ( ((double) $time[0]) + ((double) $time[1]) );
	}



	/**
	 * Sends http headers to disable page caching.
	 * @return boolean - TRUE if successful; FALSE if headers already sent.
	 */
	public static function NoPageCache() {
		if(self::$hasrun_NoPageCache)
			return FALSE;
		if(\headers_sent()) return FALSE;
		@header('Expires: Mon, 26 Jul 1990 05:00:00 GMT');
		@header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
		@header('Cache-Control: no-store, no-cache, must-revalidate');
		@header('Cache-Control: post-check=0, pre-check=0', false);
		@header('Pragma: no-cache');
		self::$hasrun_NoPageCache = TRUE;
		return TRUE;
	}
	private static $hasrun_NoPageCache = FALSE;



	/**
	 * Forward to provided url.
	 * @param string $url - The url/address in which to forward to.
	 * @param number $delay - Optional delay in seconds before forwarding.
	 */
	public static function ForwardTo($url, $delay=0) {
		if(headers_sent() || $delay != 0) {
			echo '<header><meta http-equiv="refresh" content="'.((int) $delay).';url='.$url.'"></header>';
			echo '<p><a href="'.$url.'"><font size="+1">Continue..</font></a></p>';
		} else {
			header('HTTP/1.0 302 Found');
			header('Location: '.$url);
		}
		exit;
	}



	/**
	 * Scroll to the bottom of the page.
	 * @param string $id - Optional id of element in which to scroll.
	 */
	public static function ScrollToBottom($id='') {
		if(empty($id)) $id = 'document';
		echo CRLF.'<!-- ScrollToBottom() -->'.CRLF.
			'<script type="text/javascript"><!--//'.CRLF.
			$id.'.scrollTop='.$id.'.scrollHeight; '.
			'window.scroll(0,document.body.offsetHeight); '.
			'//--></script>'.CRLF.CRLF;
	}



	/**
	 * Sleep execution for x milliseconds.
	 * @param int $ms - Milliseconds to sleep.
	 */
	public static function sleep($ms) {
		usleep( ((int) $ms) * 1000 );
	}



	/**
	 * Checks for GD support.
	 * @return boolean - TRUE if GD functions are available.
	 */
	public static function GDSupported() {
		return function_exists('gd_info');
	}



	/**
	 * Prepares dynamic function arguments into a single array.
	 * @param array $args - Results of func_get_args()
	 * @param number $ignoreFirst - Drops the first x arguments.
	 * @return array - Flattened array of arguments for use by the parent function.
	 * @see #array_flatten(array)
	 * @example:
	 * <pre>{
	 *     example(1, 2, 3, array(4, 5));
	 *     function example($first) {
	 *         $args = \psm\utils::func_args_array(func_get_args(), 1);
	 *         // $first contains 1
	 *         // $args contains array(2, 3, 4, 5)
	 *     }
	 * }</pre>
	 */
	public static function func_args_array($args, $ignoreFirst=0) {
		for($i=0; $i < $ignoreFirst; $i++)
			array_shift($args);
		$args = self::array_flatten($args);
		return $args;
	}



	/**
	 * Flattens all sub-arrays into a single dimensional array.
	 * @param array $data - Array or any combination of sub-arrays.
	 * @return Flattened array of strings or objects.
	 * @example:
	 * <pre>{
	 *     $flat = \psm\utils::array_flatten(
	 *         array(
	 *             1,
	 *             2,
	 *             array(
	 *                 3,
	 *                 4,
	 *             ),
	 *             5,
	 *         )
	 *     );
	 *     // $flat contains array(1, 2, 3, 4, 5)
	 * }</pre>
	 */
	public static function array_flatten($data) {
		if(!is_array($data)) return NULL;
		$num = count($data);
		if($num == 0)
			return array();
		$output = array();
		foreach($data as $key => $val) {
			if(is_array($val)) {
				$output = array_merge(
					$output,
					self::array_flatten($val)
				);
			} else {
				$output[$key] = $val;
			}
		}
		return $output;
	}



	public static function array_append(&$array, &$data, $appendtop=FALSE) {
		// append to top
		if($appendtop)
			array_unshift($array, $data);
		// append to bottom
		else
			array_push($array, $data);
	}



	/**
	 * Removes empty elements from an array. (maintains existing keys)
	 * @param array $data - Array to be cleaned.
	 * @return array - Cleaned array with no empty values.
	 * @example:
	 * <pre>{
	 *     $clean = \psm\utils::array_remove_empty(
	 *         array(
	 *             0 => 'A',
	 *             1 => NULL,
	 *             2 => '',
	 *             3 => 'B',
	 *         )
	 *     );
	 *     // $clean contains array(0 => 'A', 3 => 'B')
	 * }</pre>
	 */
	public static function array_remove_empty($data) {
		if(!is_array($data)) return NULL;
		if(count($data) == 0) return array();
		$output = array();
		foreach($data as $key => $val)
			if(!empty($val))
				$output[$key] = $val;
		return $output;
	}



	/**
	 * Validates an object by class name.
	 * @param string $className - Name of class to look for.
	 * @param object $object - Object to validate.
	 * @return boolean - TRUE if object matches class name.
	 */
	public static function instance_of_class($className, $object) {
		if(empty($className)) return FALSE;
		if($object == NULL)   return FALSE;
		//echo '<p>$className - '.$className.'</p>';
		//echo '<p>get_class($clss) - '.get_class($clss).'</p>';
		//echo '<p>get_parent_class($clss) - '.get_parent_class($clss).'</p>';
		return
			get_class($object) == $className ||
//			get_parent_class($clss) == $className ||
			is_subclass_of($object, $className);
	}
	/**
	 * Validates an object by class name, throwing an exception if invalid.
	 * @param string $className - Name of class to check for.
	 * @param object $object - Object to validate.
	 */
	public static function validate_class($className, $object) {
		if(empty($classname)) throw new InvalidArgumentException('classname not defined');
		if($object == NULL)   throw new InvalidArgumentException('object not defined');
		if(!self::isClass($classname, $object))
			throw new InvalidArgumentException('Class object isn\'t of type '.$classname);
	}



}
?>