<?php



class Event {

  public $id;
  public $type;
  public $night;
  public $start;
  public $end;

  public function __construct($id, $type, $night, $start, $end){
    global $controller;
    $this->id = $id;
    $this->type = $type;
    $this->night = $night;
    $this->start = $controller->command("getPrintTime", $start);
    $this->end = $controller->command("getPrintTime", $end);
  }

  public function setAttributes($id, $type, $night, $start, $end){
    global $controller;
    $this->id = $id;
    $this->type = $type;
    $this->night = $night;
    $this->start = $controller->command("getPrintTime", $start);
    $this->end = $controller->command("getPrintTime", $end);
  }

  function delete(){
    global $controller;
    $sql = "
			DELETE FROM night_events 
			WHERE event_id=".addslashes($this->id)." 
			AND event_type_id=".addslashes($this->type);
    $controller->command("makeQuery", $sql, "Event->delete"); 
  }

  function update($night, $start, $end){
    global $controller;
    $sql = "
			UPDATE night_events 
			SET start='".addslashes($start)."', 
			end='".addslashes($end)."', 
			night='".addslashes($night->format(P_SQL_DATE))."'
			WHERE event_id=".addslashes($this->id)."
			AND event_type_id=".addslashes($this->type);
    $controller->command("makeQuery", $sql, "Event->update"); 
  }


}


