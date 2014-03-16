<?php



function resetYourPassword() {
  // 2013-10-27 - assume the user first went here: 
  //
  // http://dev4.krubner.com/parlor.php?page=forgot_password
  //
  // That causes us to set a special key in the users database table
  // for their account, which we then also append to a link to send them.
  // They were then sent an email, and the email contained a link such as:
  //
  // http://dev4.krubner.com/parlor.php?page=reset_your_password&key=lkj940293ls22llkhbgg23
  //
  // that brings them back to a page where the form looks like this: 
  //
  // <form class="login_signup member_form_interaction" method="post" action="< ?php echo $actionUrl; ? >">
  //   <p>New password: <br><input type="password" name="formInputs[password]" /></p>
  //   <p>Confirm your new password: <br><input type="password" name="password2" /></p>
  //   <p><input type="submit" value="Reset your password" /></p>
  //   <input type="hidden" name="choiceMade[]" value="resetYourPassword" />
  // </form>
  //
  // Now we must find the user account with that special key, and then make 
  // sure their passwords match, and if so, we can crypt() their passwords
  // and store them in the database in their user record. 

  global $controller; 

  $formInputs = $controller->getVar("formInputs");
  $key = $controller->getVar("key");

  $query = "select * from users where reset_the_password_key='$key'";
  $result = $controller->command("makeQuery", $query, "resetYourPassword"); 
  $row = $controller->command("getRowWithTrust", $result); 
  
  if (is_array($row)) {
    $controller->addToResults("We found the user account for '{$row['email']}' for the  'reset your password' key of '$key'."); 
    if ($formInputs['password'] == $controller->getVar("password2")) {
      $encryptedPassword = crypt($formInputs['password']); 
      $query = "update users set pass='$encryptedPassword', password='$encryptedPassword', reset_the_password_key='' where user_id='{$row['user_id']}' ";
      $controller->command("makeQuery", $query, "resetYourPassword"); 
      $controller->addToResults("We have updated your password. <a href='/parlor.php?page=login'>Please login</a>.");
    }  else {
      $controller->addToResults("Sorry, but the 2 passwords did not match. Try again."); 
    }
  } else {
    $controller->addToResults("We were not able to find a user account that had a secret 'reset your password' key of '$key'.");
  }
}



