<?php



function databaseGetFoodsForCourse($courseId) {
  // 2014-01-17 - in use on pages such as: 
  //
  // http://dev4.krubner.com/private.php?page=private_reservations

  global $controller; 

  $query = "SELECT * FROM lk_foods where lk_course_id = $courseId "; 
  $arrayOfFoods = $controller->command('databaseFetchSqlWithTrust', $query, 'databaseGetCoursesForOccurrence'); 
  return $arrayOfFoods; 
}