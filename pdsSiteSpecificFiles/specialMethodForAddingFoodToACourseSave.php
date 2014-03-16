<?php



function specialMethodForAddingFoodToACourseSave() {
  // 2014-02-23 - in use here:
  //
  // http://dev4.krubner.com/admin.php?page=admin_edit_occurrence&type=member_dinner
  
  global $controller; 

  $arrayOfFoodsForCourses = $controller->getVar("specialMethodForAddingFoodToACourse"); 

  while(list($courseId, $foodId) = each($arrayOfFoodsForCourses)) {
    if ($courseId && $foodId) {
      $query = "select * from lk_foods where id = '$foodId'"; 
      $arrayOfFood = $controller->command("databaseFetchSqlWithTrust", $query, "specialMethodForAddingFoodToACourseSave"); 
      $food = $arrayOfFood[0];

      $food["id"] = '';
      $food["lk_course_id"] = $courseId; 

      $controller->command("rowCreate", "lk_foods", $food); 
    }
  }
}










