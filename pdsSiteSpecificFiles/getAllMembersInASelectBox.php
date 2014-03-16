<?php



function getAllMembersInASelectBox() {

  global $controller; 

  $query = "SELECT user_id, first_name, last_name, email from users order by last_name, first_name";
  $arrayOfMembers = $controller->command("databaseFetchSql", $query, "getAllMembersInASelectBox"); 

  $stringOfHtml = '';

  foreach ($arrayOfMembers as $row) {
    extract($row); 
    $nameOfMember = "$last_name, $first_name -- $email";
    $stringOfHtml .= "<option value='" . $user_id . "'>$nameOfMember</option> \n ";
  }

  return $stringOfHtml; 
}