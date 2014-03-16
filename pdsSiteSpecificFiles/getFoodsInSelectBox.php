<?php 



function getFoodsInSelectBox() {
  // 2014-02-23 - in use here:
  //
  // http://dev4.krubner.com/admin.php?page=admin_edit_occurrence&type=member_dinner
  
  global $controller; 

  $query = "select id , name from lk_foods group by name order by name";
  $arrayOfFoodIdsAndNames = $controller->command("databaseFetchSqlWithTrust", $query, "getFoodsInSelectBox"); 

  $htmlString = ''; 

  foreach ($arrayOfFoodIdsAndNames as $f) {
    $htmlString .= "<option value='{$f['id']}'>{$f['name']}</option>"; 
  }

  return $htmlString;
}





