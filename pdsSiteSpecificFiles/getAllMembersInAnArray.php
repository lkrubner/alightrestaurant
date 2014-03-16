<?php



function getAllMembersInAnArray() {

  global $controller; 

  $query = "SELECT user_id, first_name, last_name, email FROM users order by last_name, first_name";
  $arrayOfMembers = $controller->command("databaseFetchSql", $query, "getAllMembersInASelectBox"); 

  $arrayOfMembersNames = array(); 

  foreach ($arrayOfMembers as $row) {
    extract($row); 
    $nameOfMember = "$first_name $last_name -- $email";
    $arrayOfMembersNames[$user_id] = $nameOfMember; 
  }

  return $arrayOfMembersNames; 
}