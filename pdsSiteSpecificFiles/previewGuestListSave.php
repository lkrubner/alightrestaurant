<?php



function previewGuestListSave() {
  // 2014-01-28 - in use here: 
  //
  // http://dev4.krubner.com/preview.php
  //
  // add this guest to lk_guests: 
  //
  // lk_reservation_id
  // user_id          
  // notes            
  // created_at       
  // updated_at       

  global $controller; 

  $preview = $controller->getVar("guest"); 
  $lk_reservation_id = $controller->getVar("reservationId");

  // this is one way to screen out a certain percentage of bots
  $only_stupid_bots_will_ever_fill_this_input = $preview['only_stupid_bots_will_ever_fill_this_input'];
  if ($only_stupid_bots_will_ever_fill_this_input) {
    return true; 
  }

  if (!stristr($preview["email"], '@')) {
    $controller->addToResults("Please tell us your email address!"); 
    $controller->arrayOfAllCarriedInfo["preview_details_error"] = "true"; 
    return true; 
  }

  if ($preview["first_name"] == "") {
    $controller->addToResults("Please tell us your name!"); 
    $controller->arrayOfAllCarriedInfo["preview_details_error"] = "true"; 
    return true; 
  }

  if ($preview["last_name"] == "") {
    $controller->addToResults("Please tell us your name!"); 
    $controller->arrayOfAllCarriedInfo["preview_details_error"] = "true"; 
    return true; 
  }

  // 2014-01-24 - let's see if this person is already in our database
  $users = $controller->command("databaseFetchSqlWithTrust", "SELECT * from users where email='{$preview['email']}'", "previewGuestListSave"); 

  if (count($users) > 0) {
    $user = $users[0];
    $userId = $user["user_id"];
  } else {
    $newUser["first_name"] = $preview["first_name"];
    $newUser["last_name"] = $preview["last_name"];
    $newUser["email"] = $preview["email"];
    $newUser["active"] = "not active";
    $controller->addToResults("We have added your personal information to our database.");
    $userId = $controller->command("rowCreate", "users", $newUser);
  }

  if(!$userId) {
    $controller->error("In previewGuestListSave() we were unable to create a new user account. We were given this info: " . print_r($preview, true));
    $controller->addToResults("We are sorry, but for some reason we were unable to create an account for you. Please contact us directly."); 
    $controller->arrayOfAllCarriedInfo["preview_details_error"] = "true"; 
    return true; 
  } 

  $newGuest["user_id"] = $userId;
  $newGuest["lk_reservation_id"] = $lk_reservation_id;

  $controller->command("rowCreate", "lk_guests", $newGuest); 
  $controller->addToResults("We have recorded your guest's information."); 
}












