<?php



class Meal {
	
  public $id;
  public $courses;
  public $isShared = null;

  function __construct($id, $courses = null){
    $this->id = $id;
    $this->courses = $courses;
  }

  function setAttributes($id, $courses = null){
    $this->id = $id;
    $this->courses = $courses;
  }

  function isShared(){
    global $controller; 

    if(!is_null($this->isShared)){
      return $this->isShared;
    }

    $sql = "SELECT * FROM mbr_dinners WHERE meal_id=".addslashes($this->id);
    $results = $controller->command("makeQuery", $sql, "Meal::isShared");
    return (mysql_num_rows($results) != 1);
  }

  function delete(){
    global $controller; 

    $sql = "DELETE FROM fb WHERE fb_meal=".addslashes($this->id);
    $controller->command("makeQuery", $sql, "Meal::delete");
		
    $sql = "DELETE FROM fb_meal WHERE fb_meal_id=".addslashes($this->id);
    $controller->command("makeQuery", $sql, "Meal::delete");
  }

  function getJSON(){
    $courses = array();
    foreach($this->courses as $course){
      $course_obj = array('title'=>$course->title, 'options'=>array());
      foreach($course->options as $option){
	$course_obj['options'][] = array(
					 'name'=>$option->name,
					 'description'=>$option->description,
					 'chef'=>$option->chef->id
					 );
      }
      $courses[] = $course_obj;
    }
    return json_encode($courses);
  }

  function update($json_data){
    global $controller;

    $sql = "DELETE FROM fb WHERE fb_meal=".addslashes($this->id);
    $controller->command("makeQuery", $sql, "Meal::update"); 

    $this->courses = $controller->command("createCoursesFromJsonForMeals", $this->id, $json_data);
  }



}





