<?php namespace psm\engine\template;
if(!defined('psm\\INDEX_DEFINE') || \psm\INDEX_DEFINE !== TRUE) { echo '<header><meta http-equiv="refresh" content="1;url=../"></header>';
	echo '<font size="+2" style="color: black; background-color: white;">Access Denied!!</font>'; exit(1); }
abstract class phpclss implements template_interface {



	public function getBlockString($name=NULL) {
		if(empty($name))
			fail('name argument is empty');
		if(!method_exists($this, $name))
			fail('Unknown block: '.$name);
		return $this->$name();
	}



}
?>