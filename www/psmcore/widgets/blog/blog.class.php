<?php namespace psm\widgets;
if(!defined('psm\\PORTAL_LOADED') || \psm\PORTAL_LOADED !== TRUE) { echo '<header><meta http-equiv="refresh" content="1;url=../"></header>';
	echo '<font size="+2" style="color: black; background-color: white;">Access Denied!!</font>'; exit(1); }
class blog implements widget_interface {

	protected $output_html = NULL;

	protected $pool  = NULL;
	protected $table = NULL;



	public function __construct(\psm\pxdb\dbPool $pool=NULL, $table=NULL) {
		$this->pool  = $pool;
		$this->table = $table;
	}
	public function output() {
		return $this->output_html;
	}



	public function render() {
		$dbx = $this->pool->getConnection();
		// query articles
		$sql = 'SELECT * FROM `_table_'.$this->table.'` LIMIT 3';
		if(!$dbx->exec($sql)) {
			fail('Failed to query articles!');
			return;
		}
		// article html

$this->output_html .= 'news page';

		while($dbx->next()) {

			$this->output_html .=
				'<pre>'.
				print_r($dbx->getString('title'), TRUE).
				'</pre>'."\n";

		}
		$dbx->free();
	}



}
?>