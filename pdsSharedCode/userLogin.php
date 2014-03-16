<?php



function userLogin($formInputs=false) {	
  // 01-15-07 - all content management systems need a user system, and therefore
  // a way to login users. Although most CMS have special requirements for
  // user accounts, this function may serve as a good starting point for others 
  // to customize their own functions. We will assume the existence of a table 
  // called users_logged_in. 

  global $controller;

  $loggedInId = $controller->command("getUserIdFromUserLoggedInTable");	
  if (!$loggedInId) {

    $machineId = $controller->getVar("machineId"); 
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

    if ($machineId) {
      $query = "delete from  users_logged_in WHERE machineId='$machineId'";
      $controller->command("makeQuery", $query, "userLogin"); 
    }

    $originalReferer = $controller->getVar("originalReferer"); 

    if (!$formInputs) $formInputs = $controller->getVar("formInputs"); 
		
    if (!is_array($formInputs)) {
      $controller->addToResults("Sorry, an error occurred. We received no input from the login. We could not log you in."); 
      $controller->error("In userLogin we expected to find an array called formInputs which should have contained a email and password, probably just input from the login form. However, all we got was this: '$formInputs'."); 
      return false; 
    }

    $inputUsername = trim($formInputs["email"]);
    $inputPassword = trim($formInputs["password"]); 


    // 01-24-07 - I'm trying to fix a problem on this page: http://soveryvirginia.cat4dev.com/partnerappform.php
    // People are not getting logged in when they create their account, because their username/email is not in the logged_in
    // database table.
    //if (!$inputPassword) $inputPassword = $formInputs["password1"];

    $tryUsername = addslashes($inputUsername);

    $query = "SELECT * FROM users WHERE email='$tryUsername'"; 
    $result = $controller->command("makeQuery", $query, "userLogin"); 
    // 2013-10-26 - what if the user incorrectly adds uppercase letters? 
    if (mysql_num_rows($result) < 1) {
      $tryUsername2 = strtolower($tryUsername); 
      if ($tryUsername != $tryUsername2) {
	$controller->addToResults("Please try using lowercase letters when typing your username."); 
	$query = "SELECT * FROM users WHERE email='$tryUsername'"; 
	$result = $controller->command("makeQuery", $query, "userLogin"); 
      }
    }

    $whatWasTried = stripslashes($tryUsername); 

    // 2013-10-26 - let's see if this account has been activated. 
    if (mysqli_num_rows($result) > 0) {
      $controller->addToResults("We found a user account with an email of '$whatWasTried'."); 
      $row = $controller->command("row", $result, "userLogin"); 
    } else {
      $controller->addToResults("We could not find a user account with an email of '$whatWasTried'."); 
      return false;
    }

    $realUsername = $row["email"];
    $realPassword = $row["password"];

    // 08-14-07 - this next if() clause is not working. That is, when a user correctly
    // inputs their username and password, they still fail this test. My best guess
    // is that the type is not matching. Why this should be is hard to guess. I can 
    // remove the triple equal signs (===) but that would make the software less
    // secure. I thinks best to simple ensure that all inputs have been cast to 
    // to type "string". 
    $inputUsername = "$inputUsername";
    $realUsername = "$realUsername";
    $inputPassword = "$inputPassword";			
    $realPassword = "$realPassword";
	
	
    // 11-01-07 - we need to encrypt passwords in the database. We've started doing this
    // when we create user accounts, so now we need to change this code as well. For crypt(), 
    // this is the example that they give on www.php.net: 
    //	
    //		$password = crypt('mypassword'); // let the salt be automatically generated
    //		
    //		/* You should pass the entire results of crypt() as the salt for comparing a
    //		   password, to avoid problems when different hashing algorithms are used. (As
    //		   it says above, standard DES-based password hashing uses a 2-character salt,
    //		   but MD5-based hashing uses 12.) */
    //		if (crypt($user_input, $password) == $password) {
    //		   echo "Password verified!";
    //		}
    //
    // Since we initially had some unencrypted passwords, I'm going to test both encrypted
    // and unencrypted versions for now. A month from now we should delete the non-encrypted
    // version of this checking. 
    $encryptedPassword = crypt($inputPassword, $realPassword); 
       	
    // 04-07-08 - let's set a flag to see if things are safe and login should proceed
    $userShouldBeAllowedToLogin = true; 

    if ("$encryptedPassword" != "$realPassword" && "$inputPassword" != "$realPassword") {
      $userShouldBeAllowedToLogin = false; 
    }

    // 04-07-08 - what if the user thinks their username contains uppercase letters, but it doesn't? 
    // Upper case are especially unlikely since I just made all usernames lowercase. 
    $lowercasePassword = strtolower($inputPassword); 
    if ($lowercasePassword != $inputPassword) {
      // 2013-10-26 - we give them one more chance
      $userShouldBeAllowedToLogin = true;
    }

    if (!$userShouldBeAllowedToLogin) {
      $controller->addToResults("Sorry, but your password doesn't seem quite right."); 
      return false;
    }

    if (is_array($row)) {
      extract($row); 
      $_SESSION["users_id"] = $id;
      $_SESSION["username"] = $email;
      $last_action = time(); 
      $sessionId = session_id(); 
      $machineId =  $_COOKIE["machineId"];

      if (!$email) {
	$controller->error("There was no email found for the user id '$id'", "userLogin");
      }

      $query = "INSERT INTO users_logged_in (id, username, security_level, last_action, session_id, machineId) VALUES ('$id', '$email', '$security_level', '$last_action', '$sessionId', '$machineId') "; 
      $result = $controller->command("makeQuery", $query, "userLogin"); 


      // 2013-10-30 - in an effort to get my PDS framework to integrate with the legacy code that
      // Joe created on parlornewyork.com, I'm adding this line, which is drawn from Joe's code. 
      // Joe for some reason felt that setting the first_name in the session info was a reasonable
      // way of determining whether a user was logged in. 
      setcookie('first_name',$first_name,time()+300000);

      // 01-05-08 - we are going to need a history of all logins, so we can prove that
      // users are really using the site. This is for thesecondroad.org. Since the information
      // in users_logged_in is temporary, I've created a new table to store permanent
      // login history info: 
      //		CREATE TABLE `users_login_history` (
      //		`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
      //		`users_id` INT NOT NULL ,
      //		`time` INT NOT NULL
      //		) ENGINE = MYISAM ;
      //
      // 02-05-08 - it is really important that when we record user logins in the table
      // users_login_history we also record the machineId that is stored as a cookie on 
      // their computer. 
      $machineId = $controller->getVar("machineId"); 
      $query = "INSERT INTO users_login_history (id_users, time, machineId) VALUES ('$id', '$last_action', '$machineId') "; 
      $controller->command("makeQuery", $query, "userLogin"); 
    }
    return $result;
  }
  return $loggedInId; 
}



