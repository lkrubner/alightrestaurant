<?php



function checkToSeeIfMandatoryFieldsAreFilled($formInputs=false, $whichDatabaseTable=false) {
  // 11-14-06 - this is called in rowUpdate() and rowCreate(). The idea here
  // is that we must check to see if all mandatory fields have been filled out. 
  // A list of mandatory fields is stored in the file arrayOfMandatoryFields.php. The
  // array itself is called arrayOfMandatoryFields. When an entry is created or edited
  // all the mandatory fields must be filled. This function returns true or false. If
  // we return true, then rowUpdate and rowCreate proceed with their operations.
  // If we return false, then rowUpdate and rowCreate stop their operations 
  // immediately - they issue no warning, so the warning must be issued here. 
  //
  // 2013-10-21 - returns bool
	
  global $controller; 
	
  if (!isset($controller->arrayOfAllCarriedInfo['arrayOfMandatoryFields'])) {
    // 2014-01-17 - if the owner/programmer for this site has not seen a need to enforce any
    // mandatory fields then we can simply return true from this function.
    return true; 
  }

  $arrayOfMandatoryFields = $controller->arrayOfAllCarriedInfo['arrayOfMandatoryFields'];

  if (!is_array($formInputs)) {
    $controller->error("In checkToSeeIfMandatoryFieldsareFilled we expected the first parameter to be the one dimensional array 'formInputs'. But instead we got: ". print_r($formInputs, true)); 			
    return false; 
  }

  if (!$whichDatabaseTable) {
    $controller->error("In checkToSeeIfMandatoryFieldsareFilled we expected the second parameter to be the name of the database table to be update. But we got nothing for the second parameter."); 	
    return false; 
  }

  if (!is_array($arrayOfMandatoryFields)) {
    // 2013-12-01 - this website may not have any $arrayOfMandatoryFields set, so we can just 
    // say all tests have passed fine, which is the same as returning true.
    return true; 
  }
			
  // 11-14-06 - this is a flag that keeps track if all mandatory fields have been filled out.
  // We start off assuming everything was filled out correctly. If, further down, we hit a 
  // blank field, we will change this to false. This is what we return. 
  $allMandatoryFieldsHaveBeenFilledOut = true; 				

  // 2013-12-01 - arrayOfMandatoryFields is a two dimensional array, the first dimension
  // specifies the database table, the second dimension specifies the field. A typical
  // entry in the array looks like this: 
  //
  // $arrayOfMandatoryFields["neighborhoods"]["city"] = "You must fill in the field for 'city'."; 
  //
  if (isset($arrayOfMandatoryFields[$whichDatabaseTable])) {
    $thisTablesMandatoryFields = $arrayOfMandatoryFields[$whichDatabaseTable];
    if (is_array($thisTablesMandatoryFields)) {
      while (list($nameOfTheMandatoryField, $messageIfFieldIsEmpty) = each($thisTablesMandatoryFields)) {
	$thisFieldShouldHaveBeenFilledOut = $formInputs[$nameOfTheMandatoryField];
	if (!$thisFieldShouldHaveBeenFilledOut) {
	  $allMandatoryFieldsHaveBeenFilledOut = false; 
	  $controller->addToResults($messageIfFieldIsEmpty); 
	}		
      }
    } else {
      // 11-14-06 - this isn't really an error, since we may have tables that don't  have any mandatory fields. 
      //$controller->error("In checkToSeeIfMandatoryFieldsAreFilled we could not get an array of needed fields for the database table '$whichDatabaseTable'."); 
    }
  }
  return $allMandatoryFieldsHaveBeenFilledOut;
}  

