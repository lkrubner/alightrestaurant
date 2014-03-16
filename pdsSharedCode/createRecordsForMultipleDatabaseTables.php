<?php



function createRecordsForMultipleDatabaseTables($totalFormInputs=false) {
  // 12-13-06 - there are many times where, from one form, we wish to input
  // to several different database tables. Most of the time, when we do that
  // we should use createRecordsForMultipleDatabaseTables as the action of 
  // form being input. The form should have a 3 dimensional array called
  // totalFormInputs. The first dimension should indicate which database
  // table we should input to. The second dimension should be the array
  // that is being added to that database table. The second dimension should
  // be an associative array where the keys match the name of the fields in
  // the database. For each database table, we will simply call rowCreate,
  // like this: 
  //
  // rowCreate($whichDatabaseTable, $formInputs) 
  //
  //
  //
  // 05-03-07- if we hit totalFormInputs with print_r(), the output looks
  // like this: 
  //
  //			Array
  //			(
  //			    [albums] => Array
  //			        (
  //			            [0] => Array
  //			                (
  //			                    [id_users] => 7
  //			                    [name] => yet another test
  //			                    [description] => test
  //			                    [price] => 45
  //			                )
  //			        )			
  //			)
  //
  //
  //
  //
  // 2013-12-05 - an example of what the kind of form input names we expect: 
  //
  // 		<input class="course-title" type="text" id="totalFormInputs[lk_courses][< ?php echo $c['id']; ? >][name]" name="totalFormInputs[lk_courses][< ?php echo $c['id']; ? >][name]" value="< ?php echo $c['name']; ? >">
  //

  global $controller; 

  if (!$totalFormInputs) $totalFormInputs = $controller->getVar("totalFormInputs"); 

  $userId = $controller->command("getIdOfLoggedInUser");
  if (!$userId) {
    $textOfInput = print_r($totalFormInputs, "true");
    $controller->addToResults("We're sorry, but you are not logged in, so you can not save that information.");
    if ($textOfInput) $controller->addToResults("Though you were not logged in, we think you attempted to write something: '$textOfInput'"); 
    return false; 
  }

  // 2013-11-26 - to be clear, if we need to format a date, or format someone's name,
  // or format time, or any other special input, it should be done in a function that
  // is here specified. However, if we simply want to do some extra work, once
  // all of this information has already been stored in the database, (perhaps we
  // want to send the user an email, or perhaps we want to send out a mass email
  // based on whatever was just input, or perhaps we want update some HTML templates,
  // or we want to also delete something) then in our HTML form we could
  // simply specify another function for the choiceMade[] array, and it will be
  // processed after createRecordsForMultipleDatabaseTables has already stored
  // all this data in the datbase. 
  //
  // This is for when you need the input in totalFormInputs to change before it goes to rowUpdate().
  $processInputWithTheseFunctionsArray = $controller->getVar("processInputWithTheseFunctions");
  if ($processInputWithTheseFunctionsArray) {
    if (is_array($processInputWithTheseFunctionsArray)) {
      reset($processInputWithTheseFunctionsArray);	
      while(list($key, $nameOfFunction) = each($processInputWithTheseFunctionsArray)) {
	$totalFormInputs = $controller->command($nameOfFunction, $totalFormInputs); 
      }
    } else {
      $controller->error("In createRecordsForMultipleDatabaseTables, we had an input called 'processInputWithTheseFunctionsArray'. This should be an array, but it was not. Instead we got this: '$processInputWithTheseFunctionsArray'"); 
    }
  }

  if (is_array($totalFormInputs)) {
    reset($totalFormInputs); 
    while (list($whichDatabaseTable, $arrayOfFormInputArrays) = each($totalFormInputs)) {		  
      while (list($idOfThisEntry, $formInputs) = each($arrayOfFormInputArrays)) {
	// 2014-02-03 - the rowUpdate() function expects to see an id in $formInputs
	// if this is a record that was previously created, and we are simply editing it.
	// If the id is not there, then rowUpdate() will call rowCreate(). This
	// is a potential source of bugs. 	
	if (!$thisPrimarykey) $thisPrimarykey =  $controller->arrayOfAllCarriedInfo['arrayOfPrimaryKeys'][$whichDatabaseTable];      
	if (!$thisPrimarykey) $thisPrimarykey =  'id'; 
	if (!isset($formInputs[$thisPrimarykey]) || $formInputs[$thisPrimarykey] == "") { 
	  $formInputs[$thisPrimarykey] = $idOfThisEntry;
	}

	$controller->command("rowUpdate", $whichDatabaseTable, $formInputs);
      }
    }
  }
}



