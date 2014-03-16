<?php 



function privateReservationsDetailsSaveToDatabase() {
  // 2014-01-13 - receiving the input from the form on this page:
  //
  // http://dev4.krubner.com/private.php?page=private_reservations_complete_details

  global $controller; 

  $seatingTime = $controller->getVar("seating_time");
  $reservationId = $controller->getVar("reservationId");
  $guests = $controller->getVar("guests"); 

  $seatingDateTime = date('Y-m-d H:i:s', $seatingTime); 

  $query = "update lk_reservations set number_of_guests=$guests , at_what_time_does_the_member_plan_to_arrive='$seatingDateTime' where id='$reservationId' ";
  $controller->command("databaseUpdateSqlForThisUser", $query, "privateReservationsDetailsSaveToDatabase");
}



