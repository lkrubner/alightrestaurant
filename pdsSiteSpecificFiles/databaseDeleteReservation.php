<?php 



function databaseDeleteReservation() {
  // 2014-01-18 - receiving the input from the form on this page:
  //
  //http://dev4.krubner.com/private.php?page=private_reservations_each
  //
  //    <form id="private_reservations_cancel" action="/private.php?page=private_reservations" method="post">
  //      <input type="hidden" name="choiceMade[]" value="databaseDeleteReservation">
  //      <input type="hidden" name="reservationId" value="< ?php echo $reservationId ? >">
  //    </form>


  global $controller; 

  $reservationId = $controller->getVar("reservationId");
  $loggedInId = $controller->command("getUserIdFromUserLoggedInTable");

  $query = "delete from lk_reservations where id=$reservationId ";
  $controller->command("databaseDeleteSqlForThisUser", $query, "databaseDeleteReservation");
  $controller->addToResults("Your reservation has been cancelled."); 

}





 

  













