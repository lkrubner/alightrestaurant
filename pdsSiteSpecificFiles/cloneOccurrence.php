<?php



function cloneOccurrence() {
  // 2014-01-28 - in use here:
  //
  // http://dev4.krubner.com/admin.php?page=admin_edit_occurrence.htm&type=preview_dinner&editId=99

  global $controller;

  $totalFormInputs = $controller->getVar("totalFormInputs"); 

  $occurrence = current($totalFormInputs["lk_occurrences"]);
  $oldOccurrenceId = key($totalFormInputs["lk_occurrences"]);
  unset($occurrence["id"]);

  $controller->command("rowCreate", "lk_occurrences", $occurrence); 
  $newOccurrenceId = $controller->arrayOfAllCarriedInfo["new_database_id"];

  $query = "select * from lk_occurrences_lk_wines where lk_occurrence_id=$oldOccurrenceId";
  $wines = $controller->command("databaseFetchSqlWithTrust", $query, "cloneOccurrence"); 

  foreach($wines as $w) {
    unset($w["id"]); 
    $w["lk_occurrence_id"] = $newOccurrenceId;
    $controller->command("rowCreate", "lk_occurrences_lk_wines", $w);     
  }

  $query = "select * from lk_courses where lk_occurrence_id=$oldOccurrenceId";
  $courses = $controller->command("databaseFetchSqlWithTrust", $query, "cloneOccurrence"); 

  foreach($courses as $c) {
    $oldCourseId = $c["id"];
    unset($c["id"]);
    $c["lk_occurrence_id"] = $newOccurrenceId;

    $controller->command("rowCreate", "lk_courses", $c);     
    $newCourseId = $controller->arrayOfAllCarriedInfo["new_database_id"];

    $query = "select * from lk_foods where lk_course_id=$oldCourseId";
    $foods = $controller->command("databaseFetchSqlWithTrust", $query, "cloneOccurrence"); 

    foreach($foods as $f) {
      unset($f["id"]);
      $f["lk_course_id"] = $newCourseId;
      $controller->command("rowCreate", "lk_foods", $f); 
    }
  }

  $controller->arrayOfAllCarriedInfo['currentEditingIdOverride'] = $newOccurrenceId; 
  $controller->addToResults("We have made a clone of the event you were working on."); 

}






