<?php
namespace {
if(!defined('psm\\INDEX_DEFINE') || \psm\INDEX_DEFINE !== TRUE) { echo '<header><meta http-equiv="refresh" content="1;url=../"></header>';
	echo '<font size="+2" style="color: black; background-color: white;">Access Denied!!</font>'; exit(1); }



require('utils/vars.class.php');



// global defines
define('DIR_SEP',   DIRECTORY_SEPARATOR);
define('CLASS_EXT', '.class.php');
define('NEWLINE',   "\n");
define('TAB',       "\t");
define('S_QUOTE',   '\'');
define('D_QUOTE',   "\"");
define('PHP_INT_MIN', ~PHP_INT_MAX);
define('TCP_MAX_PORT', 65535);

// number of seconds
define('S_MS',     0.001         );
define('S_SECOND', 1             );
define('S_MINUTE', S_SECOND * 60 );
define('S_HOUR',   S_MINUTE * 60 );
define('S_DAY',    S_HOUR * 24   );
define('S_WEEK',   S_DAY * 7     );
define('S_MONTH',  S_DAY * 30    );
define('S_YEAR',   S_DAY * 365   );



// php version 5.3 required
if(PHP_VERSION_ID < 50300) {
	echo 'PHP 5.3 or newer is required.'; exit(1);
}



// timezone
//TODO: will make a config entry for this
try {
	if(!@date_default_timezone_get())
		@date_default_timezone_set('America/New_York');
} catch(\Exception $ignore) {}



// ==================================================
// global functions



function dump($var) {
	d($var);
}
// d()
if(!function_exists('d')) {
	function d($var) {
		echo '<pre style="color: black; background-color: #dfc0c0; padding: 10px;">';
		\var_dump($var);
		echo '</pre>';
	}
}
// dd()
if(!function_exists('dd')) {
	function dd($var) {
		d($var);
		die();
	}
}



function ExitNow($code=NULL) {
	\psm\portal::hasRendered(TRUE);
	if($code !== NULL)
		exit($code);
	exit(1);
}
function fail($msg) {
	echo '<pre style="color: black; background-color: #ffaaaa; padding: 10px;"><font size="+2">FATAL: '.$msg.'</font></pre>';
	if(\psm\portal::get()->debug())
		backtrace();
	ExitNow(1);
}
function backtrace() {
	$trace = debug_backtrace();
	$ignore = array(
		'inc.php' => array(
			'fail',
			'backtrace',
			'autoload',
			'__autoload',
		),
	);
//	$ignore = array();
	foreach($trace as $index => $tr) {
		$file = basename($tr['file']);
		if(isset($ignore[$file])) {
			$func = $tr['function'];
			if(in_array($func, $ignore[$file]))
				unset($trace[$index]);
		}
	}
	echo '<table style="background-color: #ffeedd; padding: 10px; border-width: 1px; border-style: solid; border-color: #aaaaaa;">'.NEWLINE;
	$first = TRUE;
	$evenodd = FALSE;
	foreach($trace as $num => $tr) {
		if(!$first)
			echo '<tr><td height="20">&nbsp;</td></tr>';
		$evenodd = ! $evenodd;
		$bgcolor = ($evenodd ? '#ffe0d0' : '#fff8e8');
		$first = FALSE;
		echo '<tr style="background-color: '.$bgcolor.';">'.NEWLINE;
		echo TAB.'<td><font size="-2">#'.((int) $num).'</font></td>'.NEWLINE;
		echo TAB.'<td>'.$tr['file'].'</td>'.NEWLINE;
		echo '</tr>'.NEWLINE;
		echo '<tr style="background-color: '.$bgcolor.';">'.NEWLINE;
		echo TAB.'<td></td>'.NEWLINE;
		$args = '';
		foreach($tr['args'] as $arg) {
			if(!empty($args))
				$args .= ', ';
			if(is_string($arg)) {
				if(strpos($arg, NEWLINE))
					$args .= '<pre>'.$arg.'</pre>';
				else
					$args .= $arg;
			} else {
				$args .= print_r($arg, TRUE);
			}
		}
		echo TAB.'<td>'.
				'<i>'.basename($tr['file']).'</i> '.
				'<font size="-1">--&gt;</font> '.
				'<b>'.$tr['function'].'</b> '.
				'( '.$args.' ) '.
				'<font size="-1">line: '.$tr['line'].'</font>'.
				'</td>'.NEWLINE;
		echo '</tr>'.NEWLINE;
	}
	echo '</table>'.NEWLINE;
	//dump($trace);
}



}
namespace psm {



// ==================================================
// debug

define('psm\\DEBUG_COOKIE', 'psm_debug');



/*
// Kint backtracer
if(file_exists(\psm\Paths::getLocal('portal').DIR_SEP.'kint.php')) {
	include(\psm\Paths::getLocal('portal').DIR_SEP.'kint.php');
}
// php_error
if(file_exists(\psm\Paths::getLocal('portal').DIR_SEP.'php_error.php')) {
	include(\psm\Paths::getLocal('portal').DIR_SEP.'php_error.php');
}
// Kint backtracer
$kintPath = \psm\Paths::getLocal('portal').DIR_SEP.'debug'.DIR_SEP.'kint'.DIR_SEP.'Kint.class.php';
if(file_exists($kintPath)) {
	//global $GLOBALS;
	//if(!@is_array(@$GLOBALS)) $GLOBALS = array();
	//$_kintSettings = &$GLOBALS['_kint_settings'];
	//$_kintSettings['traceCleanupCallback'] = function($traceStep) {
	//echo '<pre>';print_r($traceStep);exit();
	//	if(isset($traceStep['class']) && $traceStep['class'] === 'Kint')
	//		return null;
	//	if(isset($traceStep['function']) && \strtolower($traceStep['function']) === '__tostring')
	//		$traceStep['function'] = '[object converted to string]';
	//	return $traceStep;
	//};
	//echo '<pre>';print_r($_kintSettings);exit();
	include($kintPath);
	}
	// php_error
	$phpErrorPath = \psm\Paths::getLocal('portal').DIR_SEP.'debug'.DIR_SEP.'php_error.php';
	if(file_exists($phpErrorPath))
		include($phpErrorPath);
		if(function_exists('php_error\\reportErrors')) {
			$reportErrors = '\\php_error\\reportErrors';
			$reportErrors(array(
					'catch_ajax_errors'      => TRUE,
					'catch_supressed_errors' => FALSE,
					'catch_class_not_found'  => FALSE,
					'snippet_num_lines'      => 11,
					'application_root'       => __DIR__,
					'background_text'        => 'PSM',
			));
		}
	}
}
*/



// debug mode
global $PSM_DEBUG;
$PSM_DEBUG = NULL;
function debug($debug=NULL) {
	global $PSM_DEBUG;
	if($debug !== NULL) {
		$last = $PSM_DEBUG;
		$PSM_DEBUG = \psm\utils\vars::toBoolean($debug);
		// update debug mode
		if($PSM_DEBUG != $last) {
			// enabled
			if($PSM_DEBUG) {
				\error_reporting(\E_ALL | \E_STRICT);
				\ini_set('display_errors', 'On');
				\ini_set('html_errors',    'On');
			// disabled
			} else {
				error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
				\ini_set('display_errors', 'Off');
			}
		}
	}
	// default to false
	if($PSM_DEBUG === NULL)
		debug(FALSE);
	return $PSM_DEBUG;
}
// by define
if(defined('\DEBUG'))
	debug(\DEBUG);
if(defined('psm\\DEBUG'))
	debug(\psm\DEBUG);
// by url
$val = \psm\utils\vars::getVar('debug', 'bool');
if($val !== NULL) {
	// set cookie
	\setcookie(\psm\DEBUG_COOKIE, ($val ? '1' : '0'), 0);
	debug($val);
} else {
	// from cookie
	$val = \psm\utils\vars::getVar(\psm\DEBUG_COOKIE, 'bool', 'cookie');
	if($val === TRUE)
		debug($val);
}
unset($val);
// ensure inited
debug();



// ==================================================
// psm classes



/*
$psm_paths = NULL;
function paths() {
	global $psm_paths;
	if($psm_paths == NULL)
		$psm_paths = new pathsDAO();
	return $psm_paths;
}
class pathsDAO {

	private $path_root = NULL;
	public function root() {
		if(empty($this->path_root))
			$this->path_root = realpath(__DIR__.DIR_SEP.'..');
		return $this->path_root;
	}
	public function setRoot($path) {
		$this->path_root = $path;
	}

	private $path_portal = NULL;
	public function portal() {
		if(empty($this->path_portal))
			$this->path_portal = __DIR__;
		return $this->path_portal;
	}

	private $path_static = NULL;
	public function statics() {
		if(empty($this->path_static))
			$this->path_static = self::root().'/static';
		return $this->path_static;
	}

}
*/



/*
$psm_config = NULL;
function config() {
	global $psm_config;
	if($psm_config == NULL)
		$psm_config = new configDAO();
	return $psm_config;
}
class configDAO {

	private $data = array(
		'debug' => FALSE,
	);


	public function &get() {
		return $this->data;
	}


	public function debug($value=NULL) {
		if($value != NULL)
			$this->data['debug'] = \psm\utils\vars::toBoolean($value);
		return $this->data['debug'];
	}

}
*/



$psm_logger = NULL;
function log() {
	global $psm_logger;
	if($psm_logger == NULL)
		$psm_logger = new logger();
	return $psm_logger;
}
class logger {
//TODO:
}



}
?>