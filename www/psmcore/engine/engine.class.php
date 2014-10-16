<?php namespace psm;
if(!defined('psm\\INDEX_DEFINE') || \psm\INDEX_DEFINE !== TRUE) { echo '<header><meta http-equiv="refresh" content="1;url=../"></header>';
	echo '<font size="+2" style="color: black; background-color: white;">Access Denied!!</font>'; exit(1); }
class engine implements \psm\engine\engine_interface {

	private static $instance = NULL;

	public $render = NULL;
	public $head = NULL;
	public $body = NULL;

	private $rendered = FALSE;



	// single instance
	public static function get() {
		if(self::$instance == NULL)
			self::$instance = new self();
		return self::$instance;
	}
	protected function __construct() {
		$this->head = \psm\engine\engine_head::get();
		$this->body = \psm\engine\engine_block::get('body');
	}



	public function addHeader($html) {
		$this->head->add($html);
	}
	public function add($html, $name=NULL) {
		$this->body->add($html, $name);
	}



	public function render() {
		if($this->hasRendered()) return;
		$this->hasRendered(TRUE);
		if($this->render == NULL)
			$this->render = new \psm\engine\render();
		$this->render->render();
	}



	public function hasRendered($rendered=NULL) {
		if($rendered != NULL)
			$this->rendered = \psm\utils\vars::toBoolean($rendered);
		return $this->rendered;
	}



}
?>