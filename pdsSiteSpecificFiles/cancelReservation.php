<?php



function cancelReservation() {
  // 2014-01-11 - called from here: 
  //
  // http://dev4.krubner.com/private.php?page=reservations

  global $controller; 

  $reservationId = $controller->getVar("id"); 
  
  if ($reservationId) {
    $query = "DELETE from lk_reservations where id = '$reservationId' ";
    $controller->command("databaseDeleteSqlForThisUser", $query, "cancelReservation"); 
  } else {
    $controller->error("In cancelReservation, controller->getVar() should have found a reservationId, but it did not."); 
  }
}





