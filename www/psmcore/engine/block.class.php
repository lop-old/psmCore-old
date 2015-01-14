<?php namespace psm\engine;
if(!defined('psm\\INDEX_DEFINE') || \psm\INDEX_DEFINE !== TRUE) { echo '<header><meta http-equiv="refresh" content="1;url=../"></header>';
	echo '<font size="+2" style="color: black; background-color: white;">Access Denied!!</font>'; exit(1); }
class block {

	protected $content = NULL;
	protected $isphp   = NULL;






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