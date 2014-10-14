<?php namespace psm\engine;
if(!defined('psm\\INDEX_DEFINE') || \psm\INDEX_DEFINE !== TRUE) { echo '<header><meta http-equiv="refresh" content="1;url=../"></header>';
	echo '<font size="+2" style="color: black; background-color: white;">Access Denied!!</font>'; exit(1); }
class manager {

	private static $instance = NULL;

	private $rendered = FALSE;



	public static function get() {
		if(self::$instance == NULL)
			self::$instance = new self();
		return self::$instance;
	}
	protected function __construct() {
	}



	public function render() {
		if($this->hasRendered()) return;
		$this->hasRendered(TRUE);
		echo '<p>RENDER</p>';
	}



	public function hasRendered($rendered=NULL) {
		if($rendered != NULL)
			$this->rendered = \psm\utils\vars::toBoolean($rendered);
		return $this->rendered;
	}



}
?>