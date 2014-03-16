<?php 



function privateReservationsGuestListSaveToDatabase() {

  global $controller; 

  $guests = $controller->getVar("guest");
  $reservationId = $controller->getVar("reservationId");
  $loggedInId = $controller->command("getUserIdFromUserLoggedInTable");


  foreach($guests as $g) {
    $g["active"] = "not active"; 
    $idOfNewUser = $controller->command("rowCreate", "users", $g);
    if (!$idOfNewUser) {
      $controller->error("In privateReservationsGuestListSaveToDatabase() we tried, but failed, to create a new user record. This is the data we tried to save to the 'users' table: " . print_r($g, true)); 
      // this will usually be true reason
      $controller->addToResults("We already have a user in the database with the email address that you gave us."); 
      continue; 
    }
    $newRowForInvitedFriends['user_id'] = $idOfNewUser;
    $newRowForInvitedFriends['lk_reservation_id'] = $reservationId;
    $controller->command("rowCreate", "lk_guests", $newRowForInvitedFriends);
  }

}





 

  













