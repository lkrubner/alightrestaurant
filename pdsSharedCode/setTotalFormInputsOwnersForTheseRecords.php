<?php



function setTotalFormInputsOwnersForTheseRecords() {
  // 2013-12-05 - This should be called after setTotalFormInputsForPage is called -- this will
  // look up all the users who own the various records already stored in totalFormInputs. 

  global $controller; 

  while (list($databaseName,$row) = each($controller->arrayOfAllCarriedInfo["totalFormInputs"])) {
    $formInputs = current($row); 
    if (isset($formInputs['user_id'])) {
      $userId = $formInputs['user_id']; 
      if ($userId) {
	$query = "SELECT * FROM users where user_id = $userId ";
	$result = $controller->command("makeQuery", $query, "setTotalFormInputsOwnersForTheseRecords"); 
	$row = $controller->command("row", $result, "setTotalFormInputsOwnersForTheseRecords"); 
	$controller->arrayOfAllCarriedInfo["totalFormInputs"][$databaseName][$userId] = $row; 
      }
    }
  }

}







