<?php


// 2013-11-24 - older comment by Colin:
//
//Super simple keyval...
//it's a small table so we just grab it all at once and put it in memory

// 2013-11-24 - my comment:
//
// Why oh why does this class exist? It would simpler to to have a global array in globals 
// and shove the information there. Or, better, hard code the information in globals.php
// As of right now there is only one entry in this database table. 

class SpConfig {

	private static $all = null;

	static function load(){
		if(!is_null(self::$all)){
			return;
		}
		global $controller; 
		$sql = "SELECT * FROM sp_config";
		$result = $controller->command("makeQuery", $sql, "SpConfig::load"); 
		while($row = mysql_fetch_object($result)) {
			self::$all[$row->id] = $row->val;
		}
	}

	static function get($id){
		self::load();
		return self::$all[$id];
	}

	static function set($id, $val){
	  global $controller;
	  $sql = "UPDATE sp_config SET val='".addslashes($val)."' WHERE id='".addslashes($id)."'";
	  $controller->command("makeQuery", $sql, "SpConfig::set"); 
	}
}


