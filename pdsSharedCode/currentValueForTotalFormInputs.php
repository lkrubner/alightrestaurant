<?php



function currentValueForTotalFormInputs($datbaseKey, $numericIndexKey, $fieldNameKey, $format=false) {
  // 2013-11-26 - we want our HTML forms to be able to update multiple records in multiple database tables
  // every time we save, therefore we need our HTML form inputs to support names that lead to a 3 dimensional
  // array in PHP. We have the convention of using the name "totalFormInputs" as the name of the array to
  // to be submitted. A typical form input might look like this: 
  //
  // <input type="text" name="totalFormInputs[occurrences][< ?php echo currentValueForFormArrays("id"); ? >][title]" value="< ?php echo currentValueForTotalFormInputs("occurrences", < ?php echo currentValueForFormArrays("id"); ? >, "title"); ? >" />
  //
  // which should look like this as HTML: 
  //
  // <input type="text" name="totalFormInputs[occurrences][16][title]" value="What are the best use cases for using MongoDb?" />
  //
  // The "name" has 4 parts: 
  //
  // 1.) totalFormInputs -- this will be the name of the array we use throughtout our PHP code
  // 
  // 2.) [occurrences] -- this is the name of the database table we are targeting
  // 
  // 3.) [16] -- this is the id of this record in the database. If we are creating a new record, this will be zero
  // 
  // 4.) [title] -- this is the name of the field inside the database table "occurrences" that this value will go into
  //
  // 
  // Any form we use should set choiceMade[] to createRecordsForMultipleDatabaseTables. In that function, totalFormInputs 
  // will look like this: 
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
  // and inside that function, we will loop over the array, creating or updating each record for each
  // database table. 
  //
  // This function assumes there are 2 legimate places for us to look for a value that we should set for
  // whatever field has been specified by the first 3 parameters of this function:
  //
  // 1.) The $_POST info
  //
  // 2.) The $controllers->arrayOfAllCarriedInfo
  //
  // We prefer the $_POST info so we don't lose whatever the user just submitted, assumming they might
  // be making multiple updates to the same record. 

  global $controller;

  $totalFormInputs = $controller->arrayOfAllCarriedInfo["totalFormInputs"]; 

  if (isset($totalFormInputs[$datbaseKey][$numericIndexKey][$fieldNameKey])) {
    $value = $totalFormInputs[$datbaseKey][$numericIndexKey][$fieldNameKey];
  } else {
    // 2013-12-04 - this is for situations where we can assume there is only 1 row, for
    // instance, on this page there will only be 1 lk_occurrences: 
    // http://dev4.krubner.com/admin.php?page=admin_edit_occurrence&type=preview_dinner&editId=
    if (isset($totalFormInputs[$datbaseKey])) {
      if (count($totalFormInputs[$datbaseKey]) == 1) {
	$row = current($totalFormInputs[$datbaseKey]); 
	$value = $row[$fieldNameKey];
      }
    }
  }

  if ($format) $value = $controller->command($format, $value); 
  return $value; 
}



