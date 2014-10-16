<?php namespace psm\engine;
if(!defined('psm\\PORTAL_LOADED') || \psm\PORTAL_LOADED !== TRUE) { echo '<header><meta http-equiv="refresh" content="1;url=../"></header>';
	echo '<font size="+2" style="color: black; background-color: white;">Access Denied!!</font>'; exit(1); }
class render {



	public function render() {
		$engine = \psm\engine::get();
		$portal = \psm\portal::get();
		// ensure website has loaded
		$portal->getWebsite();
		// header
		$engine->head->render();
		// body
		echo '<body>'.NEWLINE
				.NEWLINE.NEWLINE;
		$engine->body->render();
		echo NEWLINE.NEWLINE
				.'</body>'.NEWLINE
				.'</html>'.NEWLINE;
	}



}
?>