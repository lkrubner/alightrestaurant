<?php



function databaseDeleteAllItemsInDeleteArray() {
  //  
  //    2013-12-06 - We expect to find a totalDeleteArray in $_POST. This should be look like:
  //  
  //      totalDeleteArray['lk_courses'][7] = '0';
  //      totalDeleteArray['lk_courses'][23] = 0;
  //      totalDeleteArray['lk_occurrences'][90] = 'lk_foods';
  //      totalDeleteArray['lk_occurrences'][15] = 'lk_foods';
  //  
  //    We need to loop over this and for every database table, delete the record with that id. 
  //  

  global $controller; 

  $userId = $controller->command("getIdOfLoggedInUser"); 
  if (!$userId) {
    $controller->addToResults("You must log in to delete anything."); 
    return false; 	
  }    
	
  $totalDeleteArray = $controller->getVar("totalDeleteArray");

  if (!$totalDeleteArray) {
    return false; 
  }

  $fieldToMatchAgainst = false; 

  while (list ($databaseTableToDeleteFrom, $row) = each($totalDeleteArray)) {
    while (list ($idOfRowToBeDeleted, $secondDatabaseTableInAManyToManyRelationship) = each($row)) {
      // 2013-12-06 - when 2 tables have a many to many relationship, such as lk_occurrences
      // and lk_wines, there will be a table between them called lk_occurrences_lk_wines. If
      // $relationship is 'many_to_many' then we want to delete from that middle table. 
      // We make the assumption that in this case there will only be 1 of the first database
      // in total form inputs. For instance, on this page:
      //
      //   http://dev4.krubner.com/admin.php?page=admin_edit_occurrence&type=preview_dinner&editId=35
      //
      // we have many wines but only 1 lk_occurrence. 
      if ($secondDatabaseTableInAManyToManyRelationship) {
	$deleteFrom = $databaseTableToDeleteFrom . '_' . $secondDatabaseTableInAManyToManyRelationship;

	if (substr($secondDatabaseTableInAManyToManyRelationship, -1) == "s") {
	  $prefixForFieldName = substr($secondDatabaseTableInAManyToManyRelationship, 0, -1);
	} else {
	  $prefixForFieldName = $secondDatabaseTableInAManyToManyRelationship;
	}
	$fieldToMatchAgainst = $prefixForFieldName . '_id'; 

	if (substr($databaseTableToDeleteFrom, -1) == "s") {
	  $prefixForFieldName = substr($databaseTableToDeleteFrom, 0, -1);
	} else {
	  $prefixForFieldName = $databaseTableToDeleteFrom;
	}
	$fieldToMatchAgainstInTheFirstTable = $prefixForFieldName . '_id'; 

	$totalFormInputs = $controller->getVar("totalFormInputs"); 
	$idToMatchAgainstInFirstTable = key($totalFormInputs[$databaseTableToDeleteFrom]);

	$query = "delete from $deleteFrom where $fieldToMatchAgainst=$idOfRowToBeDeleted and $fieldToMatchAgainstInTheFirstTable= $idToMatchAgainstInFirstTable "; 

	$controller->command("databaseDeleteSql", $query, "databaseDeleteAllItemsInDeleteArray");
      } else {
	$controller->command("rowDelete", $databaseTableToDeleteFrom, $idOfRowToBeDeleted);
      }
    }
  }


}







