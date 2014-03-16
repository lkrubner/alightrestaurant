<?php



class SpGenField {

  private $title;
  private $key;
  private $multi;
  private $options = null;

  function setAttributes($key, $loadOpts = true){
    global $controller;

    $this->key = $key;

    $sql = "
			SELECT * 
			FROM sp_gen_fields 
			WHERE gen_field_key='".addslashes($key)."'";
    $result = $controller->command("makeQuery", $sql, "SpGenField::setAttributes"); 

    $row = mysql_fetch_object($result);
    if ($row) {
      $this->title = $row->title;
      $this->multi = $row->multi;
    }

    if($loadOpts){
      $this->loadOpts();
    }
  }

  function loadOpts(){
    global $controller;

    if(is_null($this->options)){
      $this->options = array();
      $sql = "
				SELECT * 
				FROM sp_gen_field_opts 
				WHERE gen_field_key='".addslashes($this->key)."'
				AND retired=0
				ORDER BY default_order ASC";
      $result = $controller->command("makeQuery", $sql, "SpGenField::loadOpts");

      while($row = mysql_fetch_object($result)) {
	$this->options[] = $row;
      }

    }
  }

  /*
    2013-11-24 -
    what the hell is this? different methods to update
    the title, the retireOption, and other fields?
    Why the hell don't we have one function for all
    of this? This is crazy. --- lawrence@krubner.com
   */
  function updateTitle($new_title){
    global $controller;
    $sql = "
			UPDATE sp_gen_fields 
			SET title='".addslashes($new_title)."'
			WHERE gen_field_key='".addslashes($this->key)."'";
    $controller->command("makeQuery", $sql, "SpGenField::updateTitle"); 
  }

  function retireOption($opt_key){
    global $controller;
    $sql = "
			UPDATE sp_gen_field_opts 
			SET retired=1, retired_on=NOW()
			WHERE gen_field_key='".addslashes($this->key)."'
			AND opt_key='".addslashes($opt_key)."'";
    $controller->command("makeQuery", $sql, "SpGenField::retireOption"); 
  }

  function updateOption($opt_key, $opt_title, $default_order){
    global $controller; 
    $sql = "
			UPDATE sp_gen_field_opts 
			SET opt_title = '".addslashes($opt_title)."',
			default_order = '".addslashes($default_order)."'
			WHERE gen_field_key='".addslashes($this->key)."'
			AND opt_key='".addslashes($opt_key)."'";
    $controller->command("makeQuery", $sql, "SpGenField::updateOption"); 
  }

  function retire(){
    global $controller; 
    $sql = "
			UPDATE sp_gen_fields 
			SET retired=1, retired_on=NOW()
			WHERE gen_field_key='".addslashes($this->key)."'";
    $controller->command("makeQuery", $sql, "SpGenField::retire"); 
  }

  function key(){
    return $this->key;
  }

  function options(){
    return $this->options;
  }

  function title(){
    return $this->title;
  }

  function multi(){
    return $this->multi;
  }

  function getField($class="", $choose=false, $radio=false){
    $field = '';
    if($this->multi()){
      $field .= '<div data-key="'.$this->key().'" class="'.$class.'">';
      $field .= $this->getOpts();
      $field .= '</div>';
    } else if($radio){
      $field = '';
      foreach($this->options as $opt){
	$field .= '<label class="radio '.$class.'">';
	$field .= '<input type="radio" value="'.$opt->opt_key.'" data-name="'.$this->key().'"> ';
	$field .= $opt->opt_title;
	$field .= '</label>';
      }
    } else {
      $field .= '<select data-key="'.$this->key().'" class="input-block-level '.$class.'">';
      if($choose){
	$field .= '<option value="">Choose...</option>';
      }
      $field .= $this->getOpts();
      $field .= '</select>';
    }
    return $field;
  }

  function getOpts(){
    $opts = "";
    if($this->multi()){
      foreach($this->options as $opt){
	$opts .= '<label class="checkbox"><input type="checkbox" value="'.$opt->opt_key.'"> '.$opt->opt_title.'</label>';
      }
    } else {			
      foreach($this->options as $opt){
	$opts .= "<option value='".$opt->opt_key."'>".$opt->opt_title."</option>";
      }
    }
    return $opts;
  }

  function json_encodable(){
    $new = array(
		 "title"=>$this->title(),
		 "multi"=>$this->multi(),
		 "key"=>$this->key(),
		 "options"=>array()
		 );
    foreach($this->options() as $opt){
      $new['options'][$opt->opt_key] = $opt->opt_title;
    }
    return $new;
  }

  function addOption($opt_key, $opt_title=null){
    global $controller; 

    if($opt_title==null){
      $opt_title = $opt_key;
      $opt_key_sql = "CONCAT('".$this->key()."_', (SELECT COUNT(*)+1 FROM sp_gen_field_opts s WHERE s.gen_field_key='".$this->key()."'))";
    } else {
      $opt_key_sql = "'".addslashes($opt_key)."'";
    }

    $sql = "
			INSERT INTO sp_gen_field_opts (
				gen_field_key,
				opt_key,
				opt_title,
				default_order
			) VALUES (
				'".addslashes($this->key)."',
				".$opt_key_sql.",
				'".addslashes($opt_title)."',
				".(count($this->options())+1)."
			)";
    $controller->command("makeQuery", $sql, "SpGenField::addOption"); 

    $opt = array('default_order'=>count($this->options())+1, 'opt_key'=>$opt_key, 'opt_title'=>$opt_title);
    $this->options[] = json_decode(json_encode($opt));
  }


  /* 
  *   2013-11-24 
  *   What is this? I have never seen a line of code like this: 
  *  
  *   while(mysql_errno() == 1062){ 
  *    
  *   There are better ways to manage an error.  
  *
  *
  *  Now I am getting: 
  *
  *    php -l pdsSiteSpecificFiles/SpGenField.php 
  *    Fatal error: Can't use function return value in write context in pdsSiteSpecificFiles/SpGenField.php on line 224
  *    Errors parsing pdsSiteSpecificFiles/SpGenField.php
  *
  *
  * Removed all the checking of errors -- that is the wrong way to handle errors.
  *
  */
  static function create($key, $title, $multi, $opts=array(), $force = false){
    global $controller; 

    $sql = "
			INSERT INTO sp_gen_fields (
				gen_field_key,
				title,
				multi
			) VALUES (
				'".$key."',
				'".addslashes($title)."',
				".addslashes($multi)."
			)";
    $controller->command("makeQuery", self::createFieldSql($key, $title, $multi), "SpGenField::create");
    $spGenFieldObject = $controller->getObject("SpGenField", "SpGenField::create");

    foreach($opts as $opt_key=>$opt_title) {
      $spGenFieldObject->addOption($opt_key, $opt_title);
    }

    return $spGenFieldObject;
  }

  static function getGenKey($string){
    $string = str_replace("_", " ", trim($string));
    return preg_replace(
			array('/[^a-z0-9 ]/', '/\s+/'), 
			array('', '_'), 
			trim(strtolower($string))
			);
  }



}






