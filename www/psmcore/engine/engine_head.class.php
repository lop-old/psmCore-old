<?php namespace psm\engine;
if(!defined('psm\\INDEX_DEFINE') || \psm\INDEX_DEFINE !== TRUE) { echo '<header><meta http-equiv="refresh" content="1;url=../"></header>';
	echo '<font size="+2" style="color: black; background-color: white;">Access Denied!!</font>'; exit(1); }
class engine_head implements \psm\engine\engine_interface {

	protected static $instance = NULL;

	protected $content = array();

	public $title = NULL;
	public $description = NULL;



	// single instance
	public static function get() {
		if(self::$instance == NULL)
			self::$instance = new engine_head();
		return self::$instance;
	}
	protected function __construct() {
	}



	public function add($html) {
		$data = \trim($html);
		if(empty($data)) return;
		$this->content[] = &$data;
	}



	public function render() {
		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"'.NEWLINE.
				'"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'.NEWLINE;
		echo '<head>'.NEWLINE
				.'<meta charset="utf-8" />'.NEWLINE
				.'<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />'.NEWLINE
				.'<meta name="viewport" content="width=device-width, initial-scale=1" />'.NEWLINE
				.'<meta name="GOOGLEBOT" content="index,follow" />'.NEWLINE
				.'<meta name="language" content="EN-US" />'.NEWLINE;
		if(!empty($this->title))
			echo '<title>'.$this->title.'</title>'.NEWLINE;
		if(!empty($this->description))
			echo '<meta name="Description" content="'.$this->description.'" />'.NEWLINE;
		echo '<meta name="generator" content="ProSiteManager Core '.self::getVersion().'" />'.NEWLINE;
//<link rel="shortcut icon" href="/images/treeicon.ico" type="image/x-icon" />
//<link rel="icon" href="/images/treeicon.ico" type="image/x-icon" />
		foreach($this->content as $chunk)
			echo NEWLINE.NEWLINE
				.$chunk.NEWLINE
				.NEWLINE.NEWLINE;
		echo '</head>'.NEWLINE.NEWLINE;
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