<?php



function setTotalFormInputsForPage($nameOfDatabaseTable=false, $idOfCurrentRecord=0, $parentTable=false, $typeOfRelationshipThisTableHasToParent=false) {
  // 2013-11-26 - please see the very long note at the start of currentValueForTotalFormInputs() to
  // better understand what this does. 

  global $controller; 
	
  if (!$nameOfDatabaseTable) {
    $controller->error("In setTotalFormInputsForPage() we expected the first parameter to be the name of the database table, but we got nothing.");
    return false; 
  }

  if (!is_numeric($idOfCurrentRecord)) {
    $controller->error("In setTotalFormInputsForPage() we expected the second parameter to be the id of the row in the database that we wanted, or to be zero if we are creating a new record, but instead we got: '$idOfCurrentRecord'.");
    return false; 
  }

  // 2013-12-07 - this is set in functions such as checkToSeeIfTheUserIsSearchingForAWineAndIfYesThenSetThatWineAsTheCurrentWineToEdit()
  if (isset($controller->arrayOfAllCarriedInfo['currentEditingIdOverride'])) {
    $idOfCurrentRecord = $controller->arrayOfAllCarriedInfo['currentEditingIdOverride'];
  }

  // 2013-12-04 - we here assume that when you are calling setTotalFormInputsForPage(), if there is 
  // only 1 row in totalFormInputs for this particular database table, then it is save to use the id
  // of that row, for getting the information that we need. 
  if (!$idOfCurrentRecord) {
    $arrayOfCreated = $controller->arrayOfAllCarriedInfo["arrayOfIdsOfRecordsThatWereJustCreated"];
    // print_r($arrayOfCreated); 
    // [lk_occurrences] => Array ( [80] => 80 )
    $arrayOfNewIdsForThisDatabase = $arrayOfCreated[$nameOfDatabaseTable];
    if (count($arrayOfNewIdsForThisDatabase) == 1) {
      $idOfCurrentRecord = key($arrayOfNewIdsForThisDatabase); 
    }
  }

  if ($parentTable && !$typeOfRelationshipThisTableHasToParent) {
    $controller->error("In setTotalFormInputsForPage() we were given the name of a parentTable, '$parentTable', but we were not told what relationship the current table '$nameOfDatabaseTable' has with the parent table. setTotalFormInputsForPage() expects to be given both parentTable && typeOfRelationshipThisTableHasToParent or neither, but not one without the other.");
    return false;      
  }

  if (!$parentTable && $typeOfRelationshipThisTableHasToParent) {
    $controller->error("In setTotalFormInputsForPage() we were NOT given the name of a parentTable,  but we were told what relationship '$typeOfRelationshipThisTableHasToParent' the current table '$nameOfDatabaseTable' has with the parent table. setTotalFormInputsForPage() expects to be given both parentTable && typeOfRelationshipThisTableHasToParent or neither, but not one without the other.");
    return false;      
  }

  if ($parentTable && $typeOfRelationshipThisTableHasToParent) {
    if (substr($parentTable, -1) == "s") {
      $prefixForFieldName = substr($parentTable, 0, -1);
    } else {
      $prefixForFieldName = $parentTable;
    }
    $fieldToMatchAgainst = $prefixForFieldName . '_id'; 

    if ($typeOfRelationshipThisTableHasToParent == 'has_many') {
      // 2013-12-02 - if the parent table is called "lk_occurrences" then the field that 
      // points to it should be called lk_occurrence_id, so we need to strip off the "s".
      $query = "select * from $nameOfDatabaseTable where $fieldToMatchAgainst = $idOfCurrentRecord";
    }
    
    if ($typeOfRelationshipThisTableHasToParent == 'many_to_many') {
      $databaseLookupTable = $parentTable . '_' . $nameOfDatabaseTable;
      $query = "select * from $databaseLookupTable where $fieldToMatchAgainst = $idOfCurrentRecord";
      $result = $controller->command("makeQuery", $query, "setTotalFormInputsForPage"); 
      if ($result) {
	$stringOfIds = '';
	while ($rowFromManyToManyLookupTable = $controller->command("row", $result, "setTotalFormInputsForPage")) {
	  if (substr($nameOfDatabaseTable, -1) == "s") {
	    $idPrefixFieldName = substr($nameOfDatabaseTable, 0, -1);
	  } else {
	    $idPrefixFieldName = $nameOfDatabaseTable;
	  }
	  $idField = $idPrefixFieldName . '_id'; 
	  $idOfRowInFinalTable = $rowFromManyToManyLookupTable[$idField];
	  $stringOfIds .= $idOfRowInFinalTable . ', ';
	}
	$stringOfIds = substr($stringOfIds, 0, -2); 
	if ($idOfRowInFinalTable) $query = "select * from $nameOfDatabaseTable where id in ($stringOfIds) ";
      }
    }

    /* 2013-12-05 - this leads to infinite recursion */
    /*     if($typeOfRelationshipThisTableHasToParent == 'has_one') { */
    /*       $fieldToMatchAgainst =  $controller->arrayOfAllCarriedInfo['arrayOfPrimaryKeys'][$nameOfDatabaseTable];  */
    /*       if (!$fieldToMatchAgainst) $fieldToMatchAgainst =  'id';  */
    /*       if (substr($nameOfDatabaseTable, -1) == "s") { */
    /* 	$prefixForFieldName = substr($nameOfDatabaseTable, 0, -1); */
    /*       } else { */
    /* 	$prefixForFieldName = $nameOfDatabaseTable; */
    /*       } */
    /*       $matchingFieldId = $prefixForFieldName . '_id';  */
    /*       $parentRow = current($controller->arrayOfAllCarriedInfo['totalFormInputs'][$parentTable]); */
    /*       $idInParentThatMatchesPrimaryKeyOfCurrentDatabaseTable = $parentRow[$matchingFieldId]; */
    /*       $query = "SELECT * FROM $nameOfDatabaseTable WHERE $fieldToMatchAgainst = $idInParentThatMatchesPrimaryKeyOfCurrentDatabaseTable "; 	 */
    /*     } */
    
  } else {
    if (!$fieldToMatchAgainst) $fieldToMatchAgainst =  $controller->arrayOfAllCarriedInfo['arrayOfPrimaryKeys'][$nameOfDatabaseTable]; 
    if (!$fieldToMatchAgainst) $fieldToMatchAgainst =  'id'; 
    $query = "SELECT * FROM $nameOfDatabaseTable WHERE $fieldToMatchAgainst = $idOfCurrentRecord "; 	
  }

  if ($query) $arrayOfResults = $controller->command("databaseFetchSql", $query, "setTotalFormInputsForPage"); 

  foreach ($arrayOfResults as $row) { 
    if (!$thisPrimarykey) $thisPrimarykey =  $controller->arrayOfAllCarriedInfo['arrayOfPrimaryKeys'][$nameOfDatabaseTable];      
    if (!$thisPrimarykey) $thisPrimarykey =  'id'; 
    $rowId = $row[$thisPrimarykey];

    // 2013-12-05 - because setTotalFormInputsForPage() calls itself recursively it is very easy for us to end
    // up with an infinite loop. For instance this:
    //
    //  $controller->arrayOfAllCarriedInfo['arrayOfDatabaseRelationships']['users']['lk_reservations'] = 'has_many'; 
    //  $controller->arrayOfAllCarriedInfo['arrayOfDatabaseRelationships']['lk_reservations']['users'] = 'has_one'; 
    //
    // means that when we get the users table we need to also get the lk_reservations table, and when we get the
    // lk_reservations table, we need to also get the users table, and so on to infinity. To break out of this 
    // cycle, we check to see if a particular combination of database and row has already been called, and we
    // return if we find something. 
    if (isset($controller->arrayOfAllCarriedInfo["totalFormInputs"][$nameOfDatabaseTable][$rowId])) {
      continue; 
    } else {
      $controller->arrayOfAllCarriedInfo["totalFormInputs"][$nameOfDatabaseTable][$rowId] = $row;
    }

    // 2013-12-02 - $arrayOfDatabaseRelationships is set in initiate.php. We used the has_many
    // and many_to_many relationships to recursively look up every record associated with the 
    // current record. On a page like this:
    //
    // http://dev4.krubner.com/admin.php?page=admin_edit_dinner&id=41
    //
    // we need to start with lk_occurrences and then look up the related lk_courses, lk_foods,
    // lk_reservations, lk_wines, etc. A typical entry in initiate.php looks like this: 
    //
    //      $controller->arrayOfAllCarriedInfo['arrayOfDatabaseRelationships']['lk_occurrences']['lk_courses'] = 'has_many'; 
    //      $controller->arrayOfAllCarriedInfo['arrayOfDatabaseRelationships']['lk_occurrences']['lk_reservations'] = 'has_many'; 
    //      $controller->arrayOfAllCarriedInfo['arrayOfDatabaseRelationships']['lk_occurrences']['lk_wines'] = 'many_to_many'; 
    //      $controller->arrayOfAllCarriedInfo['arrayOfDatabaseRelationships']['lk_courses']['lk_foods'] = 'has_many'; 
    //      $controller->arrayOfAllCarriedInfo['arrayOfDatabaseRelationships']['users']['lk_reservations'] = 'has_many'; 
    //      $controller->arrayOfAllCarriedInfo['arrayOfDatabaseRelationships']['users']['lk_occurrences'] = 'has_many'; 
    //      $controller->arrayOfAllCarriedInfo['arrayOfDatabaseRelationships']['users']['lk_guests'] = 'has_many'; 
    //      $controller->arrayOfAllCarriedInfo['arrayOfDatabaseRelationships']['lk_reservations']['lk_guests'] = 'has_many'; 
    //    
    $arrayOfDatabaseRelationships = $controller->arrayOfAllCarriedInfo['arrayOfDatabaseRelationships'];
    $arrayOfDependentTablesForThisTable = $arrayOfDatabaseRelationships[$nameOfDatabaseTable];
    while (list($nestedDatabaseTable, $typeOfRelationship) = each($arrayOfDependentTablesForThisTable)) {
      setTotalFormInputsForPage($nestedDatabaseTable, $rowId, $nameOfDatabaseTable, $typeOfRelationship);    
    }
  }

}





 
