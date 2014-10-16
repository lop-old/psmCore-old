<?php namespace psm\engine;
if(!defined('psm\\INDEX_DEFINE') || \psm\INDEX_DEFINE !== TRUE) { echo '<header><meta http-equiv="refresh" content="1;url=../"></header>';
	echo '<font size="+2" style="color: black; background-color: white;">Access Denied!!</font>'; exit(1); }
class engine_block implements \psm\engine\engine_interface {

	protected static $instances = array();

	protected $name = NULL;

	protected $content = array();



	public static function get($name=NULL) {
		// unnamed block
		if(empty($name))
			return new self();
		// existing named block
		if(isset(self::$instances[$name]))
			return self::$instances[$name];
		// new named block
		$block = new self($name);
		self::$instances[$name] = &$block;
		return $block;
	}
	public function __construct($name=NULL) {
		$this->name = $name;
	}



	public function add($html, $name=NULL) {
		$data = \trim($html);
		if(empty($data)) return;
		if(empty($name))
			$this->content[] = &$data;
		else
			$this->content[$name] = &$data;
	}



	public function render() {
		foreach($this->content as $name => $chunk) {
			if(\is_string($name))
				echo '<!-- '.$name.' -->'.NEWLINE;
			echo $chunk.NEWLINE.
				NEWLINE.NEWLINE;
		}
	}



}
?>