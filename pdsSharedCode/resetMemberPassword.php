<?php



function resetMemberPassword() {
  // 2013-10-27 - assume a staff person (with admin priviledges) goes here:
  //
  // http://dev4.krubner.com/parlor.php?page=reset_member_password
  //
  // that brings them back to a page where the form looks like this: 
  //
  // <form class="login_signup member_form_interaction" method="post" action="< ?php echo $actionUrl; ? >">
  //   <p>What is the member's email? <br><input type="text" name="formInputs[email]">
  //   <p>New password: <br><input type="text" name="formInputs[password]" /></p>
  //   <p>Confirm member's new password: <br><input type="text" name="password2" /></p>
  //   <p><input type="submit" value="Reset your password" /></p>
  //   <input type="hidden" name="choiceMade[]" value="resetMemberPassword" />
  // </form>
  // 
  // Since the staff person is an admin, we simply reset the password. 
  
  global $controller; 

  $formInputs = $controller->getVar("formInputs");
  $email = $formInputs['email'];

  $query = "select * from users where email='$email'";
  $result = $controller->command("makeQuery", $query, "resetYourPassword"); 
  $row = $controller->command("getRowWithTrust", $result); 
  
  if (is_array($row)) {
    $controller->addToResults("We found the user account for '{$row['email']}'."); 
    if ($formInputs['password'] == $controller->getVar("password2")) {
      $encryptedPassword = crypt($formInputs['password']); 
      $query = "update users set pass='$encryptedPassword', password='$encryptedPassword', reset_the_password_key='' where user_id='{$row['user_id']}' ";
      $controller->command("makeQuery", $query, "resetYourPassword"); 
      $controller->addToResults("We have updated the member '$email' password.");
    }  else {
      $controller->addToResults("Sorry, but the 2 passwords did not match. Try again."); 
    }
  } else {
    $controller->addToResults("We were not able to find a user account that had an email address of '$email'.");
  }
}



