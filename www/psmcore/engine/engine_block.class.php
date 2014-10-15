<?php namespace psm\engine;
if(!defined('psm\\INDEX_DEFINE') || \psm\INDEX_DEFINE !== TRUE) { echo '<header><meta http-equiv="refresh" content="1;url=../"></header>';
	echo '<font size="+2" style="color: black; background-color: white;">Access Denied!!</font>'; exit(1); }
class engine_block implements \psm\engine {

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



	public function add($html) {
		$data = \trim($html);
		if(empty($data)) return;
		$this->content[] = &$data;
	}



	public function render() {
		foreach($this->content as $chunk)
			echo $chunk.NEWLINE
				.NEWLINE.NEWLINE;
	}



}
?>