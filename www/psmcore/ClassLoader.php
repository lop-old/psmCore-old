<?php
namespace {
	if(!defined('psm\\INDEX_DEFINE') || \psm\INDEX_DEFINE !== TRUE) { echo '<header><meta http-equiv="refresh" content="1;url=../"></header>';
		echo '<font size="+2" style="color: black; background-color: white;">Access Denied!!</font>'; exit(1); }
	function __autoload($classname) {
		\psm\ClassLoader::autoload($classname);
	}
}



namespace psm {
class ClassLoader {

	// class paths
	private static $paths = array();

	// stats
	private static $class_count = 1;



	/**
	 * Stores a class path for later lookups.
	 *
	 * @param string $name  The top-level namespace to search.
	 * @param string $path  Path to the directory containing class files.
	 */
	public static function registerPath($name, $path) {
		self::$paths[$name] = $path;
	}



	/**
	 * Pass onto this function from __autoload().
	 *
	 * @param string $classpath  Namespace and class to be loaded.
	 * @return boolean TRUE if a matching class was found and loaded.
	 */
	public static function autoload($classpath) {
		$parts = explode('\\', $classpath);
		$group = '';
		if(count($parts) > 1) {
			$group = array_shift($parts);
			$name  = array_pop($parts);
		} else {
			$parts = array();
			$name = $classpath;
		}
		// namespace group exists
		if(!array_key_exists($group, self::$paths))
			fail('Namespace group not found: '.$classpath);
		// load class file
		$parts[] = $name;
		$file = self::$paths[$group].DIR_SEP.implode(DIR_SEP, $parts).CLASS_EXT;
		if(file_exists($file)) {
			try {
				include($file);
				self::increment();
				return TRUE;
			} catch (\Exception $ignore) {
				fail('Failed to load class: '.(\psm\portal::get()->debug() ? $file : $classpath));
			}
		}
		// self-titled class
		$parts[] = $name;
		$file2 = self::$paths[$group].DIR_SEP.implode(DIR_SEP, $parts).CLASS_EXT;
		if(file_exists($file2)) {
			try {
				include($file2);
				self::increment();
				return TRUE;
			} catch (\Exception $ignore) {
				fail('Failed to load class: '.(\psm\portal::get()->debug() ? $file : $classpath));
			}
		}
		// class not found
		fail('Unknown class: '.$classpath);
	}
	private static function loadfile($file) {
		if(!file_exists($file))
			return FALSE;
		try {
			include($file);
			self::$class_count++;
			return TRUE;
		} catch (\Exception $ignore) {
			fail('Failed to load class: '.$file);
			return FALSE;
		}
	}



	public static function increment() {
		self::$class_count++;
	}
	public static function getNumClasses() {
		return self::$class_count;
	}



}
}
?>