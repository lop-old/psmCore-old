<?php namespace psm;
/**
 * PSM Framework - ProSiteManager
 * The main portal interface for the website. Everything starts and is managed from here.
 *
 * @copyright 2004-2014
 * @license <<TBA>>
 * @author lorenzo at PoiXson.com
 * @link http://poixson.com/
 */



// defines for use in index.php
// this will attempt to force the setting
//define('psm\\DEBUG', TRUE);
//define('psm\\MODULE', 'mod');
//define('psm\\PAGE', 'home');



// ==================================================
// Please don't change anything below this line



\error_reporting(\E_ALL);
\ini_set('display_errors', 'On');
\ini_set('html_errors',    'On');
\ini_set('log_errors',     'On');
\ini_set('error_log',      'psm_error.log');
// prevent direct php file access
if(defined('psm\\INDEX_DEFINE')) {
	echo '<h2>Unknown state! core.php already loaded?</h2>';
	exit(1);
}
if(defined('psm\\PORTAL_LOADED')) {
	echo '<h2>Unknown state! portal already loaded?</h2>';
	exit(1);
}
define('psm\\INDEX_DEFINE', TRUE);



// paths dao
class paths {

	protected static $core  = '';
	protected static $entry = '';
	protected static $site  = '';


	public static function init() {
		self::$core  = __dir__;
		self::$entry = getcwd();
	}


	public static function core() {
		return self::$core;
	}
	public static function entry() {
		return self::$entry;
	}
	public static function site() {
		if(empty(self::$site))
			self::$site = self::entry().DIR_SEP.
				\psm\portal::get()->getWebsite()->siteName();
		return self::$site;
	}


}
paths::init();



// includes
require('inc.php');
// auto-class-loader
require('ClassLoader.php');
ClassLoader::increment(); // for inc.php
ClassLoader::increment(); // for vars.class.php
// register core classpath
ClassLoader::registerPath('psm', paths::core());
// register entry point
ClassLoader::registerPath('entry', paths::entry());



// load portal
portal::get();



?>