<?php namespace psm;
if(!defined('psm\\INDEX_DEFINE') || \psm\INDEX_DEFINE !== TRUE) { echo '<header><meta http-equiv="refresh" content="1;url=../"></header>';
	echo '<font size="+2" style="color: black; background-color: white;">Access Denied!!</font>'; exit(1); }
class engine {

	const BOOTSTRAP_CSS = '//netdna.bootstrapcdn.com/bootswatch/latest/flatly/bootstrap.min.css';
	const BOOTSTRAP_JS  = '//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js';
	const JQUERY_JS = '//code.jquery.com/jquery-2.1.1.min.js';

	private static $instance = NULL;

//	private $rendered = FALSE;

	// header content
	public $head     = array();
	public $cssfiles = array();
	public $jsfiles  = array();
	public $css      = array();
	public $js       = array();
	public $title    = NULL;
	public $desc     = NULL;

	// body content
	public $body = array();
	public $mainpre  = NULL;
	public $mainpost = NULL;



	// single instance
	public static function get() {
		if(self::$instance == NULL)
			new self();
		return self::$instance;
	}
	protected function __construct() {
		self::$instance = $this;
	}



	public static function load($file) {
		if(!\file_exists($file)) {
			Fail('File not found: '.$file);
			return NULL;
		}
		$data = \file_get_contents($file);
		if($data == NULL) {
			Fail('Failed to load file: '.$file);
			return NULL;
		}
//		return new \psm\engine\engine_block(
//			$data
//		);
	}



	public function addHead($html, $name=NULL) {
		$data = \trim($html);
		if(empty($data)) return;
		if(empty($name))
			$this->head[] = &$data;
		else
			$this->head[$name] = &$data;
	}



	public function css($code, $name) {
		$data = \trim($code);
		if(empty($data)) return;
		if(empty($name))
			$this->css[] = $data;
		else
			$this->css[$name] = $data;
	}
	public function js($code, $name) {
		$data = \trim($code);
		if(empty($data)) return;
		if(empty($name))
			$this->js[] = $data;
		else
			$this->js[$name] = $data;
	}
	public function cssFile($file) {
		// file already included
		if(in_array($file, $this->cssfiles)) return;
		// includes list
		$this->cssfiles[] = $file;
	}
	public function jsFile($file) {
		// file already included
		if(in_array($file, $this->jsfiles)) return;
		// includes list
		$this->jsfiles[] = $file;
	}
	// 3rd party libraries
	public function usingBootstrap() {
		$this->cssFile(self::BOOTSTRAP_CSS);
		$this->jsFile( self::BOOTSTRAP_JS);
	}
	public function usingjQuery() {
		$this->jsFile(self::JQUERY_JS);
	}



	// body content
	public function add($html, $name=NULL) {
		$data = \trim($html);
		if(empty($data)) return;
		if(empty($name))
			$this->body[] = &$data;
		else
			$this->body[$name] = &$data;
	}



	public function setMain($html) {
		if(\strpos($html, '{content}') === FALSE)
			$this->mainpre = $html;
		else
			list($this->mainpre, $this->mainpost) = \explode('{content}', $html, 2);
	}



	public function render_head() {
		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" '.
				'"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'.NEWLINE;
		echo '<head>'.NEWLINE.
				'<meta charset="utf-8" />'.NEWLINE.
				'<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />'.NEWLINE.
				'<meta name="viewport" content="width=device-width, initial-scale=1" />'.NEWLINE.
				'<meta name="GOOGLEBOT" content="index,follow" />'.NEWLINE.
				'<meta name="language" content="EN-US" />'.NEWLINE;
		if(!empty($this->title))
			echo '<title>'.$this->title.'</title>'.NEWLINE;
		if(!empty($this->description))
			echo '<meta name="Description" content="'.$this->description.'" />'.NEWLINE;
		echo '<meta name="generator" content="ProSiteManager Core '.self::getVersion().'" />'.NEWLINE;
		// include css files
		if(!empty($this->cssfiles)) {
			foreach($this->cssfiles as $file) {
				if(empty($file)) continue;
				echo '<link rel="stylesheet" href="'.$file.'" />'.NEWLINE;
			}
		}
		// include js files
		if(!empty($this->jsfiles)) {
			foreach($this->jsfiles as $file) {
				if(empty($file)) continue;
				echo '<script src="'.$file.'"></script>'.NEWLINE;
			}
		}
		// css content
		foreach($this->css as $name => $chunk) {
			echo NEWLINE.NEWLINE;
			if(\is_string($name))
				echo '<!-- '.$name.' -->'.NEWLINE;
			echo $chunk.NEWLINE.
					NEWLINE.NEWLINE;
		}
		// js content
		foreach($this->js as $name => $chunk) {
			echo NEWLINE.NEWLINE;
			if(\is_string($name))
				echo '<!-- '.$name.' -->'.NEWLINE;
			echo '<style type="text/css"><!--//'.NEWLINE.
					$chunk.NEWLINE.
					'//--></style>'.NEWLINE.
					NEWLINE.NEWLINE;
		}
		// header content
		foreach($this->head as $name => $chunk) {
			echo NEWLINE.NEWLINE;
			if(\is_string($name))
				echo '<!-- '.$name.' -->'.NEWLINE;
			echo '<script type="text/javascript"><!--//'.NEWLINE.
					$chunk.NEWLINE.
					'//--></script>'.NEWLINE.
					NEWLINE.NEWLINE;
		}
		echo '</head>'.NEWLINE.NEWLINE;
	}
	public function render_body() {
		echo NEWLINE.NEWLINE.
				'<body>'.NEWLINE.
				NEWLINE.NEWLINE;
		if(!empty($this->mainpre))
			echo $this->mainpre.NEWLINE.
					NEWLINE.NEWLINE;
		foreach($this->body as $name => $chunk) {
			echo NEWLINE.NEWLINE;
			if(\is_string($name))
				echo '<!-- '.$name.' -->'.NEWLINE;
			echo $chunk.NEWLINE.
					NEWLINE.NEWLINE;
		}
		if(!empty($this->mainpost))
			echo NEWLINE.NEWLINE.
					$this->mainpost.NEWLINE;
		echo NEWLINE.NEWLINE.
				'</body>'.NEWLINE.
				'</html>'.NEWLINE;
	}



	public static function getVersion() {
		$vers = \psm\portal::get()->version();
		$pos = strrpos($vers, '.');
		if($pos !== FALSE)
			return \substr($vers, 0, $pos);
		return $vers;
	}



}
?>