<?php



function rowCreate($whichDatabaseTable, $formInputs) {
  // 11-05-06 - we need a general way to create a new record for any database table. This
  // function is a general one that allows the input into any one table. This function
  // won't work when info needs to be put into several database tables from one form input.
  // However, when only one table is being updated for a form input, this function will work.
  // We need two things from the form that is being input: we need for all input to be part
  // of the array formInputs, and we need a hidden input called whichDatabaseTable to tell 
  // us which database table to input to. A typical input on the form might look like this: 
  //
  // <input type="text" name="formInputs[description]" />
  //
  //
  // 12-13-06 - today I created the function createRecordsForMultipleDatabaseTables. It processes
  // form input that is meant for many different database tables. createRecord is meant to only
  // handle one database table at a time. createRecordsForMultipleDatabaseTables processes 
  // totalFormInputs, which is a 2 dimensional array in which the first dimension indicates
  // the name of the database table. createRecordsForMultipleDatabaseTables then calls on
  // createRecord. Thus, today, I'm adding two optional parameters to createRecord, for when
  // it is called from createRecordsForMultipleDatabaseTables.
	
  global $controller; 

  $allMandatoryFieldsHaveBeenFilled = $controller->command("checkToSeeIfMandatoryFieldsAreFilled", $formInputs, $whichDatabaseTable);

  if ($allMandatoryFieldsHaveBeenFilled) {	
    if (is_array($formInputs)) {
      if ($whichDatabaseTable) {
	$stringOfColumns = ""; 
	$stringOfValues = ""; 
	while (list($key, $val) = each($formInputs)) {	
	  $val = addslashes($val); 
	  $stringOfColumns .= "$key, ";
	  $stringOfValues .= "'$val', ";
	}
				
	// 11-05-06 - we need to remove the last comma from both strings
	$stringOfColumns = substr($stringOfColumns, 0, -2);
	$stringOfValues = substr($stringOfValues, 0, -2); 
					
	$query = "INSERT INTO $whichDatabaseTable ($stringOfColumns) VALUES ($stringOfValues) "; 
	$result  = $controller->command("makeQuery", $query, "rowCreate"); 

	$controller->error("In rowCreate, we run this query: $query"); 
			
	if ($result) {
	  // 2013-11-26 - in createRecordsForMultipleDatabaseTables() we might be creating many records for
	  // many different database tables. And createRecordsForMultipleDatabaseTables() calls updateRecord
	  // which then calls this function, createRecord(), if we are creating something instead of updating
	  // something. Some of the records in createRecordsForMultipleDatabaseTables need to be linked. For
	  // instance, we might be creating a new "dinner" record, and we also need to create a new "meal"
	  // record. The id of the new dinner record somehow needs to be stored with the meal record, assuming
	  // the "meal" database table has a dinner_id field that points back to the "dinner" database table. 
	  // We need a place to store all of the ids that are being created, organized by what database table
	  // they belong to. We would then depend on some extra function being assigned to choiceMade[] and
	  // running after createRecordsForMultipleDatabaseTables() has completed. This extra function would 
	  // then have to look in the$arrayOfIdsOfRecordsThatWereJustCreated that we store in the controller,
	  // and it would have to use that information to figure out what ids go to what fields in what tables. 
	  //
	  // 2014-02-11 - to deal with the switch to the mysqli module (which seems to be the only mysql module
	  // that works on the HostGator server that Parlor New York currently rents) I had to move the 
	  // next 2 lines of code to the Database class. 
	  //
	  $id =  $controller->arrayOfAllCarriedInfo["new_database_id"];
	  $controller->arrayOfAllCarriedInfo["arrayOfIdsOfRecordsThatWereJustCreated"][$whichDatabaseTable][$id] = $id;
	}
      } else {
	$controller->error("In createRecord() we expected to find a variable called 'whichDatabaseTable' in the info from the form that was just submitted, but we did not. Be sure that form has a hidden input called 'whichDatabaseTable' which will tell us which table in the database we should add this information too.");
      }
    } else {
      $controller->error("In createRecord we did not recieve an array called formInputs"); 
    }
  }

  return $id; 
}



