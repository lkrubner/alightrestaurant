<?php



function databaseGetCoursesForOccurrence($occurrenceId) {
  // 2014-01-17 - in use on pages such as: 
  //
  // http://dev4.krubner.com/private.php?page=private_reservations

  global $controller; 

  $query = "SELECT * FROM lk_courses where lk_occurrence_id = $occurrenceId order by order_of_appearance "; 
  $arrayOfCourses = $controller->command('databaseFetchSqlWithTrust', $query, 'databaseGetCoursesForOccurrence'); 
  return $arrayOfCourses; 
}