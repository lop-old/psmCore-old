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


}
paths::init();



?>