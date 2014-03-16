<?php



function createCoursesFromJsonForMeals($meal_id, $json_data){
  global $controller; 

  $courses = array();
  $courses_data = json_decode($json_data);
  foreach($courses_data as $course_data){
    $course = $controller->command("findOrCreateByTitleForCourses", $course_data->title);
    foreach($course_data->options as $option_data){
      $chefObject = $controller->getObject("Chef", "Meal::createCoursesFromJSON", "makeNewObject");
      $chefObject->setAttributes($option_data->chef); 
      $course->addNewOption($meal_id, $chefObject, $option_data->name, $option_data->description);
    }
    $courses[] = $course;
  }
  return $courses;
}

