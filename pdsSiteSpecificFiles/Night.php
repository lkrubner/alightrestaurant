<?php 



class Night {

  public $night;
  public $events;

  public function setAttributes($date, $events=null){
    $this->date = $date;
    if(is_null($events)){
      //Build functionality to load one night at a time

    } else {
      $this->events = $events;
    }
  }

	
}





