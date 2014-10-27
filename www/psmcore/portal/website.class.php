<?php namespace psm\portal;
if(!defined('psm\\PORTAL_LOADED') || \psm\PORTAL_LOADED !== TRUE) { echo '<header><meta http-equiv="refresh" content="1;url=../"></header>';
	echo '<font size="+2" style="color: black; background-color: white;">Access Denied!!</font>'; exit(1); }
abstract class website {



	public abstract function siteName();
	public abstract function siteTitle($title=NULL);



	public function render() {
		if(\psm\portal::get()->hasRendered()) return;
		$this->render_head();
		$this->render_body();
	}
	public function render_head() {
		\psm\engine::get()
				->render_head();
	}
	public function render_body() {
		\psm\engine::get()
				->render_body();
	}
//	public function page_content() {
//		echo '&lt; PAGE &gt;';
//	}



}
?>