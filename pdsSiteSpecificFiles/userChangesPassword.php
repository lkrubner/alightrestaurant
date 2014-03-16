<?php



function userChangesPassword() {
  // 2014-01-23 - in use here:
  //
  // http://dev4.krubner.com/private.php?page=private_edit_profile
 
  global $controller;

  $password1 = $controller->getVar("password1"); 
  $password2 = $controller->getVar("password2"); 

  if(!$password1) return true; 
  if(!$password2) return true; 

  if ($password1 != $password2) {
    $controller->addToResults("We are sorry, but the 2 passwords that you typed in do not match each other."); 
    return true; 
  }

  $query = "Update users set pass='$password1', password='$password1' ";
  $controller->command("databaseUpdateSqlForThisUser", $query, "userChangesPassword"); 



}




