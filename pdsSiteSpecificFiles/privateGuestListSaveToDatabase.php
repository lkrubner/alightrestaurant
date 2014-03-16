<?php 



function privateGuestListSaveToDatabase() {
  // 2014-01-19 - iin use on this page:
  //
  // http://dev4.krubner.com/private.php?page=private_guest_list

  global $controller; 

  $g = $controller->getVar("guest");
  $g["active"] = "not active"; 
  $loggedInId = $controller->command("getUserIdFromUserLoggedInTable");
  $idOfNewUser = $controller->command("rowCreate", "users", $g);
  if (!$idOfNewUser) {
    $controller->error("In privateReservationsGuestListSaveToDatabase() we tried, but failed, to create a new user record. This is the data we tried to save to the 'users' table: " . print_r($g, true)); 
    // this will usually be true reason
    $controller->addToResults("We already have a user in the database with the email address that you gave us."); 
  } else {
    $controller->addToResults("We have added your friend to our database."); 
  }
  $newRowForInvitedFriends['user_id_of_friend '] = $idOfNewUser;
  $newRowForInvitedFriends['user_id'] = $loggedInId;
  $controller->command("rowCreate", "lk_friends", $newRowForInvitedFriends);

}





 

  













