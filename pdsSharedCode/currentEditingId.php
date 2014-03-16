<?php



function currentEditingId($nameOfDatabaseTable, $currentEditingId=false) {
  // 2013-11-30 - on a page like this:
  //
  // http://dev4.krubner.com/admin.php?page=admin_edit_occurrence&occurrence_type=member_interview
  //
  // currentEditingId will either find a "editId" var in the $_GET or $_POST
  // or it will return zero, but on other pages, where we list many items for 
  // possible deletion, then the code that calls this function should hand it
  // the variable as an argument.

  global $controller; 

  // 2013-12-07 - this is set in functions such as checkToSeeIfTheUserIsSearchingForAWineAndIfYesThenSetThatWineAsTheCurrentWineToEdit()
  if (isset($controller->arrayOfAllCarriedInfo['currentEditingIdOverride'])) {
    $currentEditingId = $controller->arrayOfAllCarriedInfo['currentEditingIdOverride'];
  }

  if (!$currentEditingId) {
    $currentEditingId = $controller->getVar("editId");
  }

  // 2013-12-04 - we here assume that when you are calling setTotalFormInputsForPage(), if there is 
  // only 1 row in totalFormInputs for this particular database table, then it is safe to use the id
  // of that row, for getting the information that we need. 
  if (!$currentEditingId) {
    if (isset($controller->arrayOfAllCarriedInfo["arrayOfIdsOfRecordsThatWereJustCreated"])) {
      $arrayOfCreated = $controller->arrayOfAllCarriedInfo["arrayOfIdsOfRecordsThatWereJustCreated"];
      // print_r($arrayOfCreated); 
      // [lk_occurrences] => Array ( [80] => 80 )
      $arrayOfNewIdsForThisDatabase = $arrayOfCreated[$nameOfDatabaseTable];
      if (count($arrayOfNewIdsForThisDatabase) == 1) {
	$currentEditingId = key($arrayOfNewIdsForThisDatabase); 
      }
    }
  }

  if (!$currentEditingId) {
    $currentEditingId = 0;
  }

  return $currentEditingId; 
}