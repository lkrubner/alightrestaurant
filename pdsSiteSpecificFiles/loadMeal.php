<?php



function loadMeal($id) {
  global $controller; 

  $mealObject = $controller->getObject("Meal", "loadMeal", "makeNewObject"); 

  if ($id) {
    $sql = "
			SELECT m.*, c.*, ch.*
			FROM fb m
			JOIN fb_course c
			ON m.fb_course = c.fb_course_id
			LEFT JOIN fb_chef ch
			ON m.fb_chef = ch.fb_chef_id
			WHERE fb_meal=".addslashes($id)."
			ORDER BY fb_id ASC";
    $results = $controller->command("makeQuery", $sql, "loadMeal"); 

    $meal_courses = array();
    $courses = array();
    $chefs = array();

    while($row = mysql_fetch_object($results)){

      if(!key_exists($row->fb_course, $courses)){
	$courseObject = $controller->getObject("Course", "loadMeal", "makeNewObject");
	$courseObject->setAttributes($row->fb_course, $row->fb_course_name); 
	$courses[$row->fb_course] = $courseObject;
      }

      if(!key_exists($row->fb_chef, $chefs)){
	$chefObject = $controller->getObject("Chef", "loadMeal", "makeNewObject"); 
	$chefObject->setAttributes($row->fb_chef, $row->fb_chef_name); 
	$chefs[$row->fb_chef] = $chefObject;
      }

      $course = $courses[$row->fb_course];
      $chef = $chefs[$row->fb_chef];

      $courseOptionsObject = $controller->getObject("CourseOption", "loadMeal", "makeNewObject");
      $courseOptionsObject->setAttributes($row->fb_id, $id, $course, $chef, $row->fb_name, $row->fb_description); 
      $option = $courseOptionsObject;

      $courses[$row->fb_course]->addOption($option);
    }

    $mealObject->setAttributes($id, array_values($courses)); 
  }
  
  return $mealObject;
}
