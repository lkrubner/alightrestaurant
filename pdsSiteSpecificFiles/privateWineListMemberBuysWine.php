<?php



function privateWineListMemberBuysWine() {
  // 2014-01-22 - this is being called from this page: 
  //
  // http://dev4.krubner.com/private.php?page=private_wine_list_for_public

  global $controller; 

  $wine_quantity_to_buy = $controller->getVar("wine_quantity_to_buy"); 

  $totalFormInputs = $controller->getVar("totalFormInputs"); 

  for ($i=0; $i < $wine_quantity_to_buy; $i++) {
    $controller->command("createRecordsForMultipleDatabaseTables",$totalFormInputs);
  }
}








