<?php



function checkToSeeIfTheUserIsSearchingForAWineAndIfYesThenSetThatWineAsTheCurrentWineToEdit() {
  // 2013-12-07 - in use on this page: 
  //
  // http://dev4.krubner.com/admin.php?page=admin_edit_wine&editId=456
  //
  // The first 2 inputs on that page allow the user to search for wines to edit. The inputs look like this:
  //
  //    <select id="new_wine_for_occurrence_1" name="new_wine_for_occurrence_1">
  //
  //    <input type="text" id="new_wine_for_occurrence_2" name="new_wine_for_occurrence_2"> 

  global $controller; 

  $idForThisWine = $controller->getVar("new_wine_for_occurrence_1"); 
  $new_wine_for_occurrence_2 = $controller->getVar("new_wine_for_occurrence_2"); 

  if ($new_wine_for_occurrence_2) {
    $arrayOfWineNamesWhereIndexIsTheDatabaseIdOfTheWine = $controller->command("getAllWinesInAnArray"); 

    $idForThisWine = array_search($new_wine_for_occurrence_2, $arrayOfWineNamesWhereIndexIsTheDatabaseIdOfTheWine);
  }

  if ($idForThisWine) {
    // 2013-12-07 - this is checked in functions such as currentEditingId() and take precedence 
    // over all other rules regarding what the current id should be. 
    $controller->arrayOfAllCarriedInfo['currentEditingIdOverride'] = $idForThisWine;
  }


}




