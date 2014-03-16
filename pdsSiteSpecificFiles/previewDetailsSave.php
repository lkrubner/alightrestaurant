<?php



function previewDetailsSave() {
  // 2014-01-28 - in use here: 
  //
  // http://dev4.krubner.com/preview.php
  //
  //    <input type="hidden" name="preview[only_stupid_bots_will_ever_fill_this_input]" value="">
  //    <input type="text" id="first_name" name="preview[first_name]" placeholder="First and Last Name (Required)" required/>
  //    <input type="text" id="guest" name="preview[guest]" placeholder="Name of additional guest (Optional)" >
  //    <input type="text" id="occupation" name="preview[industry]" placeholder="Occupation (Required)" required/ >
  //    <input type="email" id="email" name="preview[email]" placeholder="Email (Required)" required/>
  //    <input type="text" id="phone" name="preview[phone]" placeholder="Phone Number (Required)" required/>
  //    <input type="text" id="city" name="preview[address_city]" placeholder="City of residence (Required)" required/>
  //    <textarea id="allergies" name="preview[allergies]" placeholder="Describe Any Food Allergies (Optional)"></textarea>
  //    <textarea id="notes" name="preview[additional_requests_from_user]" placeholder="Additional Communication(Optional)"></textarea>
  //
  // Please note, there is no place in the database schema for a 'guest' who has no email address. Elsewhere in the system,
  // such as here: 
  //
  // http://dev4.krubner.com/private.php
  //
  // we get the email of the nominee, and can therefore create a record in the users database table, but in this
  // case we are given the name of a guest, and that is all. Creating a new row in lk_reservations or lk_friends would 
  // be a level of denormalization that I am not comfortable with, so instead we will put this information into 
  // additional_requests_from_user.
  //
  // The fields that go to the user table are: 
  //  
  //  first_name
  //  last_name
  //  industry
  //  email
  //  phone
  //  address_city
  //  allergies
  //  
  //    the fields that go the 'lk_reservations' table: 
  //  
  //  guest -> additional_requests_from_user
  //  additional_requests_from_user

  global $controller; 

  $preview = $controller->getVar("preview"); 

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

  if ($preview["industry"] == "") {
    $controller->addToResults("Please tell us your occupation!"); 
    $controller->arrayOfAllCarriedInfo["preview_details_error"] = "true"; 
    return true; 
  }

  if ($preview["phone"] == "") {
    $controller->addToResults("Please tell us your phone number!"); 
    $controller->arrayOfAllCarriedInfo["preview_details_error"] = "true"; 
    return true; 
  }

  if ($preview["address_city"] == "") {
    $controller->addToResults("Please tell us what city you live in!"); 
    $controller->arrayOfAllCarriedInfo["preview_details_error"] = "true"; 
    return true; 
  }

  // 2014-01-24 - let's see if this person is already in our database
  $users = $controller->command("databaseFetchSqlWithTrust", "SELECT * from users where email='{$preview['email']}'", "previewDetailsSave"); 

  if (count($users) > 0) {
    $user = $users[0];
    $userId = $user["user_id"];
  } else {
    $newUser["first_name"] = $preview["first_name"];
    $newUser["last_name"] = $preview["last_name"];
    $newUser["email"] = $preview["email"];
    $newUser["phone"] = $preview["phone"];
    $newUser["allergies"] = $preview["allergies"];
    $newUser["industry"] = $preview["industry"];
    $newUser["address_city"] = $preview["address_city"];
    $newUser["active"] = "not active";
    $controller->addToResults("We have added your personal information to our database.");
    $userId = $controller->command("rowCreate", "users", $newUser);
  }

  if(!$userId) {
    $controller->error("In previewDetailsSave() we were unable to create a new user account. We were given this info: " . print_r($preview, true));
    $controller->addToResults("We are sorry, but for some reason we were unable to create an account for you. Please contact us directly."); 
    $controller->arrayOfAllCarriedInfo["preview_details_error"] = "true"; 
    return true; 
  } 

  $reservation["user_id"] = $userId; 
  $controller->command("rowCreate", "lk_reservations", $reservation); 
  $controller->addToResults("We have made a note of your desire to come in for a Preview Dinner."); 
}












