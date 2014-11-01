<?php namespace psm\portal;
if(!defined('psm\\PORTAL_LOADED') || \psm\PORTAL_LOADED !== TRUE) { echo '<header><meta http-equiv="refresh" content="1;url=../"></header>';
	echo '<font size="+2" style="color: black; background-color: white;">Access Denied!!</font>'; exit(1); }
abstract class website {



	public abstract function siteName();
	public abstract function siteTitle($title=NULL);

	public abstract function loadPage();



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
	public function add($html, $name=NULL) {
		\psm\engine::get()
			->add($html, $name);
	}



	public function page() {
		$page = \psm\portal::get()->page();
		if(!empty($page))
			return $page;
		return $this->defaultPage();
	}
	public function action() {
		return \psm\portal::get()
			->action();
	}
//	public function page_content() {
//		echo '&lt; PAGE &gt;';
//	}



}
?>