<?php



class SpPricing {


  private $date;
  private $pricing = array();

  private static $all = array();
  private static $currentDate = null;
  private static $pastDates = array();
  private static $futureDates = array();
  private static $loaded = false;

  function setAttributes($date, $autoLoad=true) {
    global $controller;
    $sql = "
			SELECT * 
			FROM price_breakpoints 
			WHERE start_date='".addslashes($date->format(P_SQL_DATE))."'";
    $result = $controller->command("makeQuery", $sql, "SpPricing::setAttributes"); 

    $this->date = $date;
    if($autoLoad){
      $this->loadPricing();
    }
  }

  function json(){
    return json_encode($this->pricing);
  }
	

  function getPrice($key, $opt_key){
    if(key_exists($key, $this->pricing) && key_exists($opt_key, $this->pricing[$key])){
      return $this->pricing[$key][$opt_key]['price'];
    } else {
      return 0;
    }
  }

  function loadPricing(){
    global $controller;
    $sql = "
			SELECT * FROM pricing
			WHERE start_date='".$this->date()->format(P_SQL_DATE)."'";
    $result = $controller->command("makeQuery", $sql, "SpPricing::loadPricing"); 

    while($row = mysql_fetch_object($result)){
      if(!key_exists($row->type, $this->pricing)){
	$this->pricing[$row->type] = array();
      }
      $this->pricing[$row->type][$row->id] = array(
						   'price'=>$row->price,
						   'scheme'=>$row->scheme,
						   'percent'=>$row->percent
						   );
    }
  }

  function getGenTable($genField){
    $tbl = '<table class="table table-condensed">';
    $tbl .= '<tr><th colspan="2">'.$genField->title().'</th></tr>';
    foreach($genField->options() as $opt){
      $tbl .= '<tr>';
      $tbl .= '<td style="line-height:30px;font-size:85%;">'.$opt->opt_title.'</td>';
      $tbl .= '<td style="text-align:right;"><small>';
      $tbl .= '<div class="input-prepend">';
      $tbl .= '<span class="add-on">$</span>';
      $tbl .= $this->getField('text', $genField->key(), $opt->opt_key, 'price');
      $tbl .= '</div> ';
      $tbl .= $this->getField('select', $genField->key(), $opt->opt_key, 'scheme');
      $tbl .= $this->getField('hidden', $genField->key(), $opt->opt_key, 'percent', 0);
      $tbl .= '</small></td>';
      $tbl .= '</tr>';	
    }
    $tbl .= '</table>';
    return $tbl;
  }

  function getField($fieldType, $type, $id, $attr, $force=null){
    if($fieldType == "select" && $attr == "scheme"){
      return $this->getSchemeDrop($type, $id);
    }
    $default = array('price'=>0, 'scheme'=>SCHEME_FLAT, 'percent'=>0);
    $name = "pricing[".$type."][".$id."][".$attr."]";
    if(!is_null($force)){
      $val = $force;
    } else {
      if(key_exists($type, $this->pricing) && key_exists($id, $this->pricing[$type])){
	$val = $this->pricing[$type][$id][$attr];
      } else {
	$val = $default[$attr];
      }
    }
    if($fieldType=="text"){
      $field = "<input class='price' type='text' name='".$name."' value='".$val."'>";
    } else if($fieldType=="hidden") {
      $field = "<input type='hidden' name='".$name."' value='".$val."'>";
    }
    return $field;
  }

  function getSchemeDrop($type, $id, $force=null){
    $name = "pricing[".$type."][".$id."][scheme]";
    if(!is_null($force)){
      $val = $force;
    } else {
      if(key_exists($type, $this->pricing) && key_exists($id, $this->pricing[$type])){
	$set_val = $this->pricing[$type][$id]['scheme'];
      } else {
	$set_val = SCHEME_FLAT;
      }
    }

    $schemes = array(
		     SCHEME_FLAT=>"Flat", 
		     SCHEME_PERSON=>"/Gst", 
		     SCHEME_HOUR=>"/Hr", 
		     SCHEME_PERSON_HOUR=>"/GstHr"
		     );
    $field = '<select class="auto-width" name="'.$name.'">';
    foreach($schemes as $key=>$val){
      $selected = "";
      if($set_val == $key){
	$selected = ' selected="selected"';
      }
      $field .= '<option'.$selected.' value="'.$key.'">'.$val.'</option>';
    }
    $field .= '</select>';
    return $field;
  }

  function date(){
    return $this->date;
  }

  function save($pricing){
    foreach($pricing as $type=>$price_by_id){
      foreach($price_by_id as $id=>$attrs){
	$this->saveSingle($type, $id, $attrs);
      }
    }
  }

  function saveSingle($type, $id, $attrs){
    global $controller; 
    $sql = "
			UPDATE pricing
			SET price='".addslashes($attrs['price'])."',
			scheme='".addslashes($attrs['scheme'])."',
			percent='".addslashes($attrs['percent'])."',
			updated=NOW()
			WHERE type='".addslashes($type)."'
			AND id='".addslashes($id)."'
			AND start_date='".addslashes($this->date()->format(P_SQL_DATE))."'";
    $result = $controller->command("makeQuery", $sql, "SpPricing::saveSingle"); 

    if(mysql_affected_rows() == 0){
      $sql = "
				INSERT INTO pricing (
					start_date,
					type,
					id,
					price,
					scheme,
					percent,
					updated
				) VALUES (
					'".addslashes($this->date()->format(P_SQL_DATE))."',
					'".addslashes($type)."',
					'".addslashes($id)."',
					'".addslashes($attrs['price'])."',
					'".addslashes($attrs['scheme'])."',
					'".addslashes($attrs['percent'])."',
					NOW()
				)";
    $result = $controller->command("makeQuery", $sql, "SpPricing::saveSingle"); 
    }
  }

  static function create($date){
    global $controller;
    $sql = "
			INSERT INTO price_breakpoints (
				start_date
			) VALUES (
				'".addslashes($date->format(P_SQL_DATE))."'
			)";
    $controller->command("makeQuery", $sql, "SpPricing::create");
  }

  static function all(){
    self::loadDates();
    return self::$all;
  }
  static function pastDates(){
    self::loadDates();
    return self::$pastDates;
  }
  static function futureDates(){
    self::loadDates();
    return self::$futureDates;
  }
  static function currentDate(){
    self::loadDates();
    return self::$currentDate;
  }

  static function loadDates(){
    global $controller;

    if(!self::$loaded){ 
      $sql = "
				SELECT * 
				FROM price_breakpoints
				ORDER BY start_date ASC";
      $result = $controller->command("makeQuery", $sql, "SpPricing::loadDates"); 

      $i = 0;
      $now = new DateTime();
      $past = true;

      while($row = mysql_fetch_object($result)){
	$date = new DateTime($row->start_date);
	self::$all[] = $date;
	if($past){
	  if($date < $now){
	    self::$pastDates[] = $date;
	  } else {
	    self::$currentDate = array_pop(self::$pastDates);
	    self::$futureDates[] = $date;
	    $past = false;
	  }
	} else {
	  self::$futureDates[] = $date;
	}
	$i++;
      }
      if($past){
	self::$currentDate = array_pop(self::$pastDates);
      }

      self::$loaded = true;
    }
  }
}




