<?php namespace psm\engine;
if(!defined('psm\\INDEX_DEFINE') || \psm\INDEX_DEFINE !== TRUE) { echo '<header><meta http-equiv="refresh" content="1;url=../"></header>';
	echo '<font size="+2" style="color: black; background-color: white;">Access Denied!!</font>'; exit(1); }
class manager implements \psm\engine {

	private static $instance = NULL;

	private $head;
	private $body;

	private $rendered = FALSE;



	// single instance
	public static function get() {
		if(self::$instance == NULL)
			self::$instance = new self();
		return self::$instance;
	}
	protected function __construct() {
		$this->head = engine_head::get();
		$this->body = engine_block::get('body');
	}



	public function addHeader($html) {
		$this->head->add($html);
	}
	public function add($html) {
		$this->body->add($html);
	}



	public function render() {
		if($this->hasRendered()) return;
		$this->hasRendered(TRUE);
		// ensure website has loaded
		\psm\portal::get()->getWebsite();
		// header
		$this->head->render();
		// body
		echo '<body>'.NEWLINE
			.NEWLINE.NEWLINE;
		$this->body->render();
		echo NEWLINE.NEWLINE
			.'</body>'.NEWLINE
			.'</html>'.NEWLINE;
	}



	public function hasRendered($rendered=NULL) {
		if($rendered != NULL)
			$this->rendered = \psm\utils\vars::toBoolean($rendered);
		return $this->rendered;
	}



}
?>