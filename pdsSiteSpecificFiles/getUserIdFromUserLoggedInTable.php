<?php



function getUserIdFromUserLoggedInTable() {
  // 01-16-07 - most of the time, when we want to see if a user is logged in or not,
  // we would look in the table users_logged_in, to see if there is a username there
  // that matches the current session id. This function grabs the username that 
  // matches the id. 
  //  
  // 2013-11-26 - This function is often used inside of forms to give a value to a  
  //  hidden input that sets the value for the user_id field of some database table.

  global $controller; 

  static $id; 

  $machineId =  $_COOKIE["machineId"];	
	
  if (!$machineId) {
    if (!headers_sent()) {
      $machineId = md5(uniqid(rand()));
      $success = setcookie("machineId", $machineId, time() + 10000000);	
    } else {			
      // 11-16-08 - is this really an error? we use this function all over the place, not 
      // just the top of the page.
      $controller->error("In getUserIdFromUserLoggedInTable() we wanted to set a machine id but the headers were already sent."); 
    }
  }
	
  if (!$machineId) {
    // 01-16-08 - this next warning fires often, and it is almost never true. 
    // Apparently there is some headers being sent at some point, but I have
    // not been able to track down where. So I'm adding an if() check for 
    // protection. 
    if (!headers_sent()) {
      $controller->addToResults("It would seem that your browser is not accepting what is known as 'cookies'. Without these we can not log you in. Please adjust the security settings on your browser, so that it allows cookies."); 
      return false; 	
    }
  }
	

  if (!$id) {
    $query = "SELECT username FROM users_logged_in WHERE machineId='$machineId'";
    $result = $controller->command("makeQuery", $query, "getUserIdFromUserLoggedInTable"); 
    $row = $controller->command("row", $result, "getUserIdFromUserLoggedInTable"); 
    $username = $row["username"];
	
    if ($username) {
      $query = "SELECT user_id FROM users WHERE email='$username' ";
      $result = $controller->command("makeQuery", $query, "getUserIdFromUserLoggedInTable"); 
      $row = $controller->command("row", $result, "getUserIdFromUserLoggedInTable"); 
      $id = $row["user_id"];
    }
  }

  // 05-17-08 - I just realized, since we've made the $id variable static,
  // the logout() function can no longer have immediate effect. So we need
  // to look at the URL and see if choiceMade is being set to logout anywhere. 
  $choiceMade = $controller->getVar("choiceMade"); 
  if (is_array($choiceMade)) {
    if (in_array("logout", $choiceMade)) {
      return false; 
    }
  }

  return $id;
}



