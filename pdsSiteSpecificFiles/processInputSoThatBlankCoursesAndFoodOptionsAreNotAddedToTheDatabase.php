<?php



function processInputSoThatBlankCoursesAndFoodOptionsAreNotAddedToTheDatabase($totalFormInputs) {
  // 2013-12-04 - this is being called from a hidden input on this page: 
  //
  // http://dev4.krubner.com/admin.php?page=admin_edit_occurrence&occurrence_type=preview_dinner&currentEditingId=41
  //
  // this is then caught and processed in createRecordsForMultipleDatabaseTables() like this: 
  //
  //  	$processInputWithTheseFunctionsArray = $controller->getVar("processInputWithTheseFunctions");
  //  	if ($processInputWithTheseFunctionsArray) {
  //  		if (is_array($processInputWithTheseFunctionsArray)) {
  //  			reset($processInputWithTheseFunctionsArray);	
  //  			while(list($key, $nameOfFunction) = each($processInputWithTheseFunctionsArray)) {
  //  				$totalFormInputs = $controller->command($nameOfFunction, $totalFormInputs); 
  //  			}
  //  		} else {
  //  			$controller->error("In createRecordsForMultipleDatabaseTables, we had an input called 'processInputWithTheseFunctionsArray'. This should be an array, but it was not. Instead we got this: '$processInputWithTheseFunctionsArray'"); 
  //  		}
  //  	}
  //  
  //  We want to be sure that 
  //
  // 1.) if a lk_courses has no name then it is not added to the database
  //
  // 2.) if a lk_foods has no name and no description then it is not added to the database

  global $controller; 

  $query = "delete from lk_courses where name = '' ";
  $controller->command("databaseDeleteSql",$query, "processInputSoThatBlankCoursesAndFoodOptionsAreNotAddedToTheDatabase");

  $query = "delete from lk_foods where name = '' and chef='' and description=''";
  $controller->command("databaseDeleteSql", $query, "processInputSoThatBlankCoursesAndFoodOptionsAreNotAddedToTheDatabase");

  $query = "delete from lk_occurrences where max_total = '' and max_hour='' and max_halfhour='' and name='' and description='' and start = '' and end ='' and type=''";
  $controller->command("databaseDeleteSql", $query, "processInputSoThatBlankCoursesAndFoodOptionsAreNotAddedToTheDatabase");

}



