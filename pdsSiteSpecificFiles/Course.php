<?php



class Course {

  public $id;
  public $title;
  public $options = array();


  function setAttributes($id, $title){
    $this->id = $id;
    $this->title = $title;
  }

  function addOption($option){
    $this->options[] = $option;
  }

  function addNewOption($meal_id, $chef, $name, $description){
    global $controller;
    $courseOptionObject = $controller->getObject("CourseOption", "Course::addNewOption", "makeNewObject"); 
    $courseOptionObject->create($meal_id, $this, $chef, $name, $description);
    $this->options[] = $courseOptionObject;
  }


}



