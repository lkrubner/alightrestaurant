<?php



function getAllNominees() {
  global $controller;

  $sql = "
			SELECT
				u.first_name as user_first, 
				u.last_name as user_last,
				u.email as user_email,
				n.first_name as nominee_first,
				n.last_name as nominee_last,
				n.email as nominee_email,
				un.*
			FROM user_nominees un
			JOIN users u
			ON u.user_id=un.user_id
			JOIN users n
			ON n.user_id=un.nominee_id
			ORDER BY time_nominated DESC";
  $result = $controller->command("makeQuery", $sql, "getAllNominees"); 

  $nominees = array();

  while($row = mysql_fetch_object($result)){
    $nominees[] = $row;
  }

  return $nominees;
}