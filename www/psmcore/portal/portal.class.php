<?php namespace psm;
if(!defined('psm\\INDEX_DEFINE') || \psm\INDEX_DEFINE !== TRUE) { echo '<header><meta http-equiv="refresh" content="1;url=../"></header>';
	echo '<font size="+2" style="color: black; background-color: white;">Access Denied!!</font>'; exit(1); }
class portal {
	private static $VERSION = "3.4.x";

	// portal instance
	private static $instance = NULL;

	// website modules
	private $availableMods = array();
	private $module = NULL;

	// page
	private $pageStr = NULL;
	private $page = NULL;
	private $pageDefault = 'home';
	// action
	private $action = NULL;

	private $engine = NULL;

	private $debug = FALSE;



	// get portal
	public static function get() {
		if(self::$instance == NULL)
			self::$instance = new self();
		return self::$instance;
	}
	// new portal instance
	private function __construct() {
		if(defined('PORTAL_LOADED')) {
			echo '<p>Portal class already loaded?!</p>';
			exit(1);
		}
		define('psm\\PORTAL_LOADED', TRUE);
		\psm\utils::NoPageCache();
		// forced settings
//		if(defined('psm\\MODULE'))
//		if(defined('psm\\PAGE'))
		// load html engine
		$this->engine = \psm\engine\manager::get();
		// shutdown hook
		\register_shutdown_function('psm\\portal::shutdown');
	}



	// shutdown hook
	public static function shutdown() {
		$portal = self::get();
		// hasn't rendered yet, do it now
		if(!$portal->hasRendered()) {
			$portal->render();
		}
		unset($portal);
	}



	// error page
	public static function Error($msg) {
		echo '<div style="background-color: #ffbbbb;">'.NEWLINE;
		if(!empty($msg))
			echo '<h4>Error: '.$msg.'</h4>'.NEWLINE;
		echo '<h3>Backtrace:</h3>'.NEWLINE;
//		if(\method_exists('Kint', 'trace'))
//			\Kint::trace();
//		else
			echo '<pre>'.print_r(\debug_backtrace(), TRUE).'</pre>';
		echo '</div>'.NEWLINE;
//		\psm\Portal::Unload();
		exit(1);
	}



	// scan for available modules
	public static function ScanModules() {
		$portal = self::get();


		unset($portal);
	}



	public function debug($debug=NULL) {
		return \psm\debug($debug);
	}
	public function version() {
		return self::$VERSION;
	}



	public function hasRendered($rendered=NULL) {
		return $this->engine->hasRendered();
	}
	public function render() {
		$this->engine->render();
	}



}
?>