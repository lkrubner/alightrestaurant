<?php



function linkTheIdsOfReservationsCoursesFoodsAndWinesForThisOccurrence() {
  // 2013-12-03 - in createRecordsForMultipleDatabaseTables() we might be updating many records for
  // many different database tables. There may be additional processing that needs to happen after
  // these records have been updated, so we must record all of the ids that have been updated. 
  //
  // this is in rowUpdate():
  //
  //      $arrayOfIdsOfRecordsThatWereJustUpdated = $controller->arrayOfAllCarriedInfo["arrayOfIdsOfRecordsThatWereJustUpdated"];
  //      $arrayOfIdsOfRecordsThatWereJustUpdated[$whichDatabaseTable][$id] = $id;
  //      $controller->arrayOfAllCarriedInfo["arrayOfIdsOfRecordsThatWereJustUpdated"] = $arrayOfIdsOfRecordsThatWereJustUpdated;
  //  
  // and this is in rowCreate()
  //  
  //  	  $id = mysql_insert_id(); 
  //  	  $arrayOfIdsOfRecordsThatWereJustCreated = $controller->arrayOfAllCarriedInfo["arrayOfIdsOfRecordsThatWereJustCreated"];
  //  	  $arrayOfIdsOfRecordsThatWereJustCreated[$whichDatabaseTable][$id] = $id;
  //  	  $controller->arrayOfAllCarriedInfo["arrayOfIdsOfRecordsThatWereJustCreated"] = $arrayOfIdsOfRecordsThatWereJustCreated;
  //  
  // This function is called via choiceMade[] from this page: 
  // 
  // http://dev4.krubner.com/admin.php?page=admin_edit_occurrence&occurrence_type=preview_dinner&currentEditingId=41
  //
  // We need to knit together the various records and with the right relationships in the database
  //
  // Mostly you just need to add these wines to lk_occurrences_lk_wines
  //
  //    <select name="new_wine_for_occurrence_1">
  //       <option></option>
  //  	    < ?php echo $controller->command("getAllWinesInASelectBox"); ? >
  //    </select>
  //  	Or search: <input type="text" id="new_wine_for_occurrence_2" name="new_wine_for_occurrence_2">
 
  global $controller; 

  $arrayOfIdsOfRecordsThatWereJustUpdated = $controller->arrayOfAllCarriedInfo["arrayOfIdsOfRecordsThatWereJustUpdated"];
  $arrayOfIdsOfRecordsThatWereJustCreated = $controller->arrayOfAllCarriedInfo["arrayOfIdsOfRecordsThatWereJustCreated"];

  $lkOccurrenceId = current($arrayOfIdsOfRecordsThatWereJustUpdated["lk_occurrences"]);
  if (!$lkOccurrenceId) $lkOccurrenceId = current($arrayOfIdsOfRecordsThatWereJustCreated["lk_occurrences"]); 

  if (!$lkOccurrenceId) {
    $controller->error("In linkTheIdsOfReservationsCoursesFoodsAndWinesForThisOccurrence() we were unable to find an id for an lk_occurrence -- we assume that some lk_occurrence was just updated or created, but we can not find the id in $arrayOfIdsOfRecordsThatWereJustUpdated or $arrayOfIdsOfRecordsThatWereJustCreated."); 
    return false; 
  }

  $query = "UPDATE lk_courses set lk_occurrence_id=$lkOccurrenceId where lk_occurrence_id =''"; 
  $controller->command("makeQuery", $query, "linkTheIdsOfReservationsCoursesFoodsAndWinesForThisOccurrence"); 

  $now = date("Y-m-d H:i:s"); 
  $new_wine_for_occurrence_1 = $controller->getVar("new_wine_for_occurrence_1"); 
  $new_wine_for_occurrence_2 = $controller->getVar("new_wine_for_occurrence_2");   

  if ($new_wine_for_occurrence_1) {
    $query = "INSERT INTO lk_occurrences_lk_wines (lk_wine_id, lk_occurrence_id, created_at, updated_at) VALUES ('$new_wine_for_occurrence_1', '$lkOccurrenceId', '$now', '$now') ";
    $controller->command("makeQuery", $query, "linkTheIdsOfReservationsCoursesFoodsAndWinesForThisOccurrence"); 
  }

  if ($new_wine_for_occurrence_2) {
    $arrayOfWineNamesWhereIndexIsTheDatabaseIdOfTheWine = $controller->command("getAllWinesInAnArray"); 
    $idForThisWine = array_search($new_wine_for_occurrence_2, $arrayOfWineNamesWhereIndexIsTheDatabaseIdOfTheWine);
    if ($idForThisWine) {
      $query = "INSERT INTO lk_occurrences_lk_wines (lk_wine_id, lk_occurrence_id, created_at, updated_at) VALUES ('$idForThisWine', '$lkOccurrenceId', '$now', '$now') ";
      $controller->command("makeQuery", $query, "linkTheIdsOfReservationsCoursesFoodsAndWinesForThisOccurrence"); 
    }
  }

  $specialNameForNewFoodOptionsRows = $controller->getVar("specialNameForNewFoodOptions");
  while (list($key, $formInputs) = each($specialNameForNewFoodOptionsRows)) {
    if ($formInputs['name'] || $formInputs['description']) {
      $controller->command("rowCreate", "lk_foods", $formInputs); 
    }
  }

}


