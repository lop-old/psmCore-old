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



	public function css($code, $name=NULL) {
		$data = \trim($code);
		if(empty($data)) return;
		if(empty($name))
			$this->css[] = $data;
		else
			$this->css[$name] = $data;
	}
	public function js($code, $name=NULL) {
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
		$this->usingjQuery();
		$this->cssFile(self::BOOTSTRAP_CSS);
		$this->jsFile( self::BOOTSTRAP_JS);
	}
	public function usingjQuery() {
		$this->jsFile(self::JQUERY_JS);
	}



	// head content
	public function addHead($html, $name=NULL) {
		if(is_array($html)) {
			foreach($html as $v)
				$this->add($v);
			return;
		}
		$data = \trim($html);
		if(empty($data)) return;
		if(empty($name))
			$this->head[] = &$data;
		else
			$this->head[$name] = &$data;
	}



	// body content
	public function add($html, $name=NULL) {
		if(is_array($html)) {
			foreach($html as $v)
				$this->add($v);
			return;
		}
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
		echo '<!DOCTYPE html>'.CRLF;
		echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">'.CRLF;
		echo '<head>'.CRLF.
				'<meta charset="UTF-8" />'.CRLF.
				'<base href="//'.(empty($_SERVER['HTTP_HOST']) ? @$_SERVER['SERVER_NAME'] : @$_SERVER['HTTP_HOST']).'" />'.CRLF.
				'<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />'.CRLF.
				'<meta name="viewport" content="width=device-width, initial-scale=1" />'.CRLF.
				'<meta name="GOOGLEBOT" content="index,follow" />'.CRLF;
		echo '<title>'.(empty($this->title) ? ' ' : $this->title).'</title>'.CRLF;
		if(!empty($this->description))
			echo '<meta name="Description" content="'.$this->description.'" />'.CRLF;
		echo '<meta name="generator" content="psmCore '.self::getVersion().'" />'.CRLF;
		// include css files
		if(!empty($this->cssfiles)) {
			foreach($this->cssfiles as $file) {
				if(empty($file)) continue;
				echo '<link rel="stylesheet" href="'.$file.'" />'.CRLF;
			}
		}
		// include js files
		if(!empty($this->jsfiles)) {
			foreach($this->jsfiles as $file) {
				if(empty($file)) continue;
				echo '<script type="text/javascript" src="'.$file.'"></script>'.CRLF;
			}
		}
		// debug mode
		if(!\psm\debug())
			$this->css('.debug {'.CRLF.TAB.'display: none;'.CRLF.'}');
		// css content
		foreach($this->css as $name => $chunk) {
			echo CRLF.CRLF;
			if(\is_string($name))
				echo '<!-- '.$name.' -->'.CRLF;
			echo '<style type="text/css">'.CRLF.
					$chunk.CRLF.
					'</style>'.CRLF.
					CRLF.CRLF;
		}
		// js content
		foreach($this->js as $name => $chunk) {
			echo CRLF.CRLF;
			if(\is_string($name))
				echo '<!-- '.$name.' -->'.CRLF;
			echo '<script type="text/javascript"><!--//'.CRLF.
					$chunk.CRLF.
					'//--></script>'.CRLF.
					CRLF.CRLF;
		}
		// header content
		foreach($this->head as $name => $chunk) {
			echo CRLF.CRLF;
			if(\is_string($name))
				echo '<!-- '.$name.' -->'.CRLF;
			echo $chunk.CRLF.
					CRLF.CRLF;
		}
		echo '</head>'.CRLF;
	}
	public function render_body() {
		echo CRLF.
				'<body>'.CRLF.
				'<!-- ~~~~~~~~~~~~~~~~~~~~ -->'.CRLF.
				CRLF.CRLF;
		if(!empty($this->mainpre))
			echo $this->mainpre.CRLF.
					CRLF.CRLF;
		foreach($this->body as $name => $chunk) {
			echo CRLF.CRLF;
			if(\is_string($name))
				echo '<!-- '.$name.' -->'.CRLF;
			$this->parseTags($chunk);
			echo $chunk.CRLF.
					CRLF.CRLF;
		}
		if(!empty($this->mainpost))
			echo CRLF.CRLF.
					$this->mainpost.CRLF;
		echo CRLF.CRLF.
				'</body>'.CRLF.
				'</html>'.CRLF;
	}



	public static function parseTags(&$data, $tags=NULL) {
//		if($tags == NULL)
//			$tags = array(
//				'' => '',
//			);
		// {css} {/css}
		while(TRUE) {
			$part = \psm\utils\strings::getBetween($data, '{css}', '{/css}', TRUE);
			if($part === NULL) break;
			self::get()->css($part);
		}
		// {js} {/js}
		while(TRUE) {
			$part = \psm\utils\strings::getBetween($data, '{js}', '{/js}', TRUE);
			if($part === NULL) break;
			self::get()->js($part);
		}
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