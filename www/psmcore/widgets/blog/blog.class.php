<?php namespace psm\widgets;
if(!defined('psm\\PORTAL_LOADED') || \psm\PORTAL_LOADED !== TRUE) { echo '<header><meta http-equiv="refresh" content="1;url=../"></header>';
	echo '<font size="+2" style="color: black; background-color: white;">Access Denied!!</font>'; exit(1); }
class blog implements widget_interface {

	protected $tpl    = NULL;
	protected $output = NULL;

	protected $pool  = NULL;
	protected $table = NULL;

	protected $dbx = NULL;


	public function __construct(\psm\pxdb\dbPool $pool=NULL, $table=NULL) {
		$this->pool  = $pool;
		$this->table = $table;
	}
	public function output() {
		return $this->output;
	}



	public function setTemplate($tpl) {
		if(\is_string($tpl))
			$tpl = \psm\engine\block::load($tpl);
		$this->tpl = $tpl;
	}



	public function query() {
		$this->dbx = $this->pool->getConnection();
		$sql = 'SELECT `news_id`, UNIX_TIMESTAMP(`posted`) AS `posted`, `author`, `title`, `text` FROM `_table_'.$this->table.'` ORDER BY `posted` DESC LIMIT 300';
		if(!$this->dbx->exec($sql)) {
			fail('Failed to query articles!');
			return;
		}
	}



	public function render() {
		if($this->dbx == NULL)
			$this->query();
		$this->output .= $this->tpl->getBlockString('open');
		$count = 0;
		while($this->dbx->next()) {
			$title   = $this->dbx->getString('title');
			$author  = $this->dbx->getString('author');
			$date    = $this->dbx->getDate  ('posted', 'F j, Y, g:i a');
			$permUrl = 'http://google.com';
			$content = $this->dbx->getString('text');
			$numComments = 0;
			// build html
			$block = $this->tpl->getBlockString('entry');
			$block = str_replace('{title}',           $title,       $block);
			$block = str_replace('{author}',          $author,      $block);
			$block = str_replace('{posted date}',     $date,        $block);
			$block = str_replace('{perm url}',        $permUrl,     $block);
			$block = str_replace('{number comments}', $numComments, $block);
			$block = str_replace('{content}',         $content,     $block);
			if($count++ > 0)
				$this->output .= $this->tpl->getBlockString('separator');
			$this->output .= $block;
			unset($block);
		}
		$this->output .= $this->tpl->getBlockString('close');
		$this->dbx->free();
	}



}
?>