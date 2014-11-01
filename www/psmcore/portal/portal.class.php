<?php namespace psm;
if(!defined('psm\\INDEX_DEFINE') || \psm\INDEX_DEFINE !== TRUE) { echo '<header><meta http-equiv="refresh" content="1;url=../"></header>';
	echo '<font size="+2" style="color: black; background-color: white;">Access Denied!!</font>'; exit(1); }
class portal {
	private static $VERSION = "3.4.x";

	// portal instance
	private static $instance = NULL;

	// website modules
	private static $available = array();
	private $website = NULL;
	private $rendered = FALSE;

	// page
	private $page = NULL;
	// action
	private $action = NULL;



	// get portal
	public static function get() {
		if(self::$instance == NULL)
			new self();
		return self::$instance;
	}
	// new portal instance
	private function __construct() {
		if(defined('psm\\PORTAL_LOADED')) {
			echo '<p>Portal class already loaded?!</p>';
			exit(1);
		}
		define('psm\\PORTAL_LOADED', TRUE);
		self::$instance = $this;
		\psm\utils::NoPageCache();
		// get page/action from url
		{
			$page   = \psm\utils\vars::getVar('page',   'str');
			$action = \psm\utils\vars::getVar('action', 'str');
			$this->page   = \psm\utils\san::Filename($page);
			$this->action = \psm\utils\san::Filename($action);
		}
		// forced settings
//		if(defined('psm\\MODULE'))
//		if(defined('psm\\PAGE'))
		// load html engine
//		$this->engine = \psm\engine::get();
		// shutdown hook
		\register_shutdown_function('psm\\portal::shutdown');
		// load configs
		self::ScanConfigs(paths::entry());
		self::ScanConfigs(paths::core());
	}



	// shutdown hook
	public static function shutdown() {
		$portal = self::get();
		$website = $portal->getWebsite();
		$website->loadMain();
		$website->loadPage();
		// hasn't rendered yet, do it now
		if(!$portal->hasRendered())
			$portal->render();
		unset($portal);
	}



	// scan for available website modules
	public static function ScanWebsites($dir) {
		$portal = self::get();
		// get directory contents
		$array = \scandir($dir, SCANDIR_SORT_NONE);
		foreach($array as $entry) {
			if(empty($entry) || substr($entry, 0, 1) === '.') continue;
			// is dir
			if(!\is_dir($dir.DIR_SEP.$entry)) continue;
			// path to website.php file
			$file = $dir.DIR_SEP.$entry.DIR_SEP.'website.php';
			if(!\is_file($file)) continue;
			// website.php file exists
			self::$available[$entry] = $file;
		}
		unset($portal);
	}
	public function getWebsite() {
		if($this->website == NULL) {
			self::ScanWebsites(paths::core());
			self::ScanWebsites(paths::entry());
			if(empty(self::$available))
				fail('No website modules available!');
			// get first available
			$file = \reset(self::$available);
			$name = \key(self::$available);
			$result = include($file);
			if($result === FALSE)
				fail('Failed to load website file: '.$file);
			// new website instance
			$clss = '\\'.$name.'\\'.$name.'_website';
			$this->website = new $clss();
			if($this->website == NULL)
				fail('Failed to create a new website instance: '.$clss);
		}
		return $this->website;
	}
	public function site() {
		return $this->getWebsite()
				->siteName();
	}



	public function page() {
		return $this->page;
	}
	public function action() {
		return $this->action;
	}



	public static function ScanConfigs($dir) {
		$portal = self::get();
		// get directory contents
		$array = \scandir($dir, SCANDIR_SORT_NONE);
		foreach($array as $entry) {
			if(empty($entry) || substr($entry, 0, 1) === '.') continue;
			// is dir
			if(\is_dir($dir.DIR_SEP.$entry)) continue;
			// ends with config.php
			if(!\psm\utils\strings::EndsWith($entry, 'config.php', TRUE)) continue;
			// path to config.php file
			$file = $dir.DIR_SEP.$entry;
			if(!\is_file($file)) continue;
			// include the config file
			include_once($file);
		}
		unset($portal);
	}



	public function version() {
		return self::$VERSION;
	}



	public function hasRendered($rendered=NULL) {
		if($rendered != NULL)
			$this->rendered = \psm\utils\vars::toBoolean($rendered);
		return $this->rendered;
	}
	public function render() {
		$this->getWebsite()
				->render();
	}



}
?>