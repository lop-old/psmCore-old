<?php namespace psm\portal;
if(!defined('psm\\PORTAL_LOADED') || \psm\PORTAL_LOADED !== TRUE) { echo '<header><meta http-equiv="refresh" content="1;url=../"></header>';
	echo '<font size="+2" style="color: black; background-color: white;">Access Denied!!</font>'; exit(1); }
abstract class website {

	protected $page_instance = NULL;



	public abstract function siteName();
	public abstract function siteTitle($title=NULL);
	public abstract function defaultPage();

	public abstract function loadMain();



	public function render() {
		if(\psm\portal::get()->hasRendered()) return;
		\psm\portal::get()->hasRendered(TRUE);
		// stop output buffering
		\psm\engine::get()
			->add(\ob_get_contents());
		\ob_end_clean();
		// start rendering
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



	public function loadPage() {
		$page = $this->page();
		// <entry>/pages/<page>.php
		$path =
			\psm\paths::entry().DIR_SEP.
			$this->siteName().DIR_SEP.
			'pages'.DIR_SEP.
			$page.'.php';
		if(\file_exists($path)) {
			$this->loadPageFile($path, $page);
			return;
		}
		// <core>/pages/<page>.php
		$path =
			\psm\paths::core().DIR_SEP.
			'pages'.DIR_SEP.
			$page.'.php';
		if(\file_exists($path)) {
			$this->loadPageFile($path, $page);
			return;
		}
		// unknown page
		header('HTTP/1.0 404 Not Found');
		fail('Page not found: '.$page);
	}
	protected function loadPageFile($file, $page) {
		if(empty($file))         return;
		if(!\file_exists($file)) return;
		$result = include($file);
		if($result === FALSE)
			fail('Failed to load page: '.$page);
		// include returned string
		if(\is_string($result)) {
			$this->add($result);
			return;
		}
		// new instance
		$clss = '\\'.$this->siteName().'\\pages\\'.$page.'_page';
		$this->page_instance = new $clss();
		// render page content
		$this->add(
			$this->page_instance->render()
		);
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



}
?>