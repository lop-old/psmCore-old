<?php namespace psm\engine;
if(!defined('psm\\INDEX_DEFINE') || \psm\INDEX_DEFINE !== TRUE) { echo '<header><meta http-equiv="refresh" content="1;url=../"></header>';
	echo '<font size="+2" style="color: black; background-color: white;">Access Denied!!</font>'; exit(1); }
final class loader {
	private function __construct() {}



	public static function load($file) {
		if(empty($file)) return NULL;
		$found = self::find_file($file);
		if(empty($found)) return NULL;
		return self::load_exact($found);
	}
	public static function find_file($file) {
		// exact match
		$f = self::find_file_ext(
				$file
		);
		if($f != NULL) return $f;
		// entry
		$f = self::find_file_ext(
				\psm\paths::entry().DIR_SEP
				.$file
		);
		if($f != NULL) return $f;
		// entry - site/html/
		$f = self::find_file_ext(
				\psm\paths::site().DIR_SEP
				.'html'.DIR_SEP
				.$file
		);
		if($f != NULL) return $f;
		// core - psmcore/html/
		$f = self::find_file_ext(
				\psm\paths::core().DIR_SEP
				.html.DIR_SEP
				.$file
		);
		if($f != NULL) return $f;
		// not found
		fail('File not found: '.$file);
		return NULL;
	}
	private static function find_file_ext($file) {
		if(empty($file)) return NULL;
		// exact match
		if(\file_exists($file)) return $file;
		// .tpl.php
		$f = $file.'.tpl.php';
		if(\file_exists($f)) return $f;
		// .tpl
		$f = $file.'.tpl';
		if(\file_exists($f)) return $f;
		// not found
		return NULL;
	}
	public static function load_exact($file) {
		if(!\file_exists($file))
			fail('File not found: '.$file);
		$site = \psm\portal::get()->site();
		$entrypath = \psm\paths::entry();
		$name = $file;
		$pos = \max(
				\strrpos($name, '/'),
				\strrpos($name, '\\')
		);
		if($pos !== FALSE)
			$name = \substr($name, $pos + 1);
		$name = \trim($name);
		$name = \psm\utils\san::Filename($name);
		if(empty($name)) {
			fail('invalid file name, trimmed to empty');
			return NULL;
		}
		// .tpl.php
		if(\psm\utils\strings::EndsWith($file, '.tpl.php', FALSE)) {
			$name = \substr($name, 0, 0 - \strlen('.tpl.php'));
			include($file);
			// construct namespace from file path
			if(\psm\utils\strings::StartsWith($file, $entrypath)) {
				$str = \substr($file, \strlen($entrypath));
				$str = \str_replace('/', '\\', $str);
				$pos = \strrpos($str, '\\');
				if($pos !== FALSE)
					$str = \substr($str, 0, $pos);
				$str = \psm\utils\strings::forceStartsWith($str, '\\');
				$str = \psm\utils\strings::forceEndsWith($str, '\\');
				$clss = $str.$name.'_tpl';
				// guess at namespace
			} else {
				$clss = '\\'.$site.'\\html\\'.$name.'_tpl';
			}
			if(!\class_exists($clss))
				fail('Class '.$clss.' not found in .tpl.php file');
			$result = new $clss();
			if($result === NULL)
				fail('Failed to load class: '.$clss);
			if(method_exists($result, 'css'))
				\psm\engine::get()->css($result->css());
			if(method_exists($result, 'js'))
				\psm\engine::get()->css($result->js());
			// block instance
//			return \psm\engine\block::newInstance($result);
			return $result;
		} else
		// .tpl
		if(\psm\utils\strings::EndsWith($file, '.tpl', FALSE)) {
			$data = \file_get_contents($file);
			if($data === FALSE)
				fail('Failed to load .tpl contents: '.$file);
			// block instance
//			return \psm\engine\block::newInstance($data);
			return $data;
		}
		if($data != NULL) return $data;
		fail('Failed to load file: '.$file);
		return NULL;
	}



}
?>