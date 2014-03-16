<?php



function databaseGetAllGuestsForThisReservation($reservationId) {
  // 2014-01-18 - in use on this page:
  //
  // http://dev4.krubner.com/private.php?page=private_reservations_each

  global $controller; 

  $query = "SELECT user_id from lk_guests where lk_reservation_id = $reservationId";
  $arrayOfRowsOfUserIds = $controller->command("databaseFetchSqlWithTrust", $query, "databaseGetAllGuestsForThisReservation");
  
  $stringOfUserIds = '';

  foreach ($arrayOfRowsOfUserIds as $row) {
    $stringOfUserIds .= $row['user_id'];
    $stringOfUserIds .= ',';
  }

  $stringOfUserIds = substr($stringOfUserIds, 0, -1); 
  $arrayOfusers = array(); 

  if ($stringOfUserIds) {
    $query = "select * from users where user_id in ($stringOfUserIds) "; 
    $arrayOfusers = $controller->command("databaseFetchSqlWithTrust", $query, "databaseGetAllGuestsForThisReservation");
  }
  return $arrayOfusers; 
}





