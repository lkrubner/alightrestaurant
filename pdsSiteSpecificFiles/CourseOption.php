<?php



class CourseOption {

  public $id;
  public $meal_id;
  public $course;
  public $chef;
  public $name;
  public $description;


  function setAttributes($id, $meal_id, $course, $chef, $name, $description){
    $this->id = $id;
    $this->meal_id = $meal_id;
    $this->course = $course;
    $this->chef = $chef;
    $this->name = $name;
    $this->description = $description;
  }


}



