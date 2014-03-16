<?php



class SpEvent  {



  public $id;
  public $type;
  public $night;
  public $start;
  public $end;



  public $class = "sp-event";
  public $data;

  function setAttributes($id, $night, $start, $end, $sp_data=null){
    global $controller; 

    if($sp_data == null){
      $sql = "
				SELECT *
				FROM sp_events
				WHERE sp_event_id=".addslashes($id);
      $result = $controller->command("makeQuery", $sql, "SpEvent::setAttributes"); 
      $row = mysql_fetch_object($result); 
      $this->data = json_decode($row->sp_data);
    } else {
      $this->data = json_decode($sp_data);
    }
		
    $this->title = ($this->data->name == '' ? "Special Event" : $this->data->name);
    $this->setAttributesForEvent($id, EVENT_SPECIAL, $night, $start, $end);
  }

  public function getAdminLink(){
    return 'events/main.php?id='.$this->id;
  }

  function update($night, $start, $end, $data){
    global $controller;
		
    $sql = "
			UPDATE sp_events 
			SET sp_data='".addslashes($data)."' 
			WHERE sp_event_id=".addslashes($this->id);
    $controller->command("makeQuery", $sql, "SpEvent::update"); 

    $this->updateForEvent($night, $start, $end, $data);		
  }

  function delete(){
    global $controller; 

    if(!$this->meal->isShared()){
      $this->meal->delete();
    }

    $sql = "DELETE FROM mbr_dinners WHERE mbr_dinner_id=".addslashes($this->id);
    $controller->command("makeQuery", $sql, "SpEvent::delete"); 

    $this->deleteForEvent();
  }




  public function setAttributesForEvent($id, $type, $night, $start, $end){
    global $controller;
    $this->id = $id;
    $this->type = $type;
    $this->night = $night;
    $this->start = $controller->command("getPrintTime", $start);
    $this->end = $controller->command("getPrintTime", $end);
  }


  function deleteForEvent(){
    global $controller;
    $sql = "
			DELETE FROM night_events 
			WHERE event_id=".addslashes($this->id)." 
			AND event_type_id=".addslashes($this->type);
    $controller->command("makeQuery", $sql, "Event->delete"); 
  }


  function updateForEvent($night, $start, $end){
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



