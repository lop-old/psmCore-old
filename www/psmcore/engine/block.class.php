<?php namespace psm\engine;
if(!defined('psm\\INDEX_DEFINE') || \psm\INDEX_DEFINE !== TRUE) { echo '<header><meta http-equiv="refresh" content="1;url=../"></header>';
	echo '<font size="+2" style="color: black; background-color: white;">Access Denied!!</font>'; exit(1); }
class block {

	protected $content = NULL;
	protected $isphp   = NULL;



	public static function load($file, $clss=NULL) {
		if(empty($file)) return NULL;
		if(!\file_exists($file)) {
			fail('File not found: '.$file);
			return NULL;
		}
		// load .tpl.php file
		if(\psm\utils\strings::EndsWith($file, '.tpl.php')) {
			if(empty($clss)) {
				$clss = $file;
				if(\psm\utils\strings::EndsWith($clss, '.tpl.php'))
					$clss = substr($clss, 0, 0-strlen('.tpl.php'));
				$pos = max(
					strrpos($clss, '/'),
					strrpos($clss, '\\')
				);
				if($pos !== FALSE)
					$clss = substr($clss, $pos+1);
				$clss = trim($clss);
				if(empty($clss))
					fail('clss argument not set');
				$clss = \psm\utils\san::Filename($clss).'_tpl';
				return NULL;
			}
			include($file);
			$result = new $clss();
			if(method_exists($result, 'css'))
				\psm\engine::get()->css($result->css());
			if(method_exists($result, 'js'))
				\psm\engine::get()->css($result->js());
			return new self($result);
		}
		// load .tpl file
		if(\psm\utils\strings::EndsWith($file, '.tpl')) {
			$result = \file_get_contents($file);
			if($result != NULL)
				return new self($result);
		}
		// not found
		fail('File not found: '.$file);
		return NULL;
	}



	public function __construct($content=NULL) {
		$this->content = $content;
		$this->isphp = !\is_string($content);
		if(!$this->isphp) {
			while(TRUE) {
				$css = $this->getBlock('css', TRUE);
				if(empty($css)) break;
				\psm\engine::get()->css($css);
			}
		}
	}



	public function getBlockString($name, $remove=TRUE) {
		if(empty($this->content))
			fail('block content is empty');
		if(empty($name))
			fail('name argument is empty');
//TODO:
		if(is_string($this->content))
			return $this->content;
		return $this->content->getBlockString($name);
	}






}
?>