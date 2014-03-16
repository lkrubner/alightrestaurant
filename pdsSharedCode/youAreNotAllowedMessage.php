<?php



function youAreNotAllowedMessage() {
  global $controller; 
  $controller->addToResults("You are not allowed to do what you are trying to do. Only admins are allowed to do that, and you are NOT an admin.");
  $controller->addToResults("If you are an admin, try logging in below.");
  $controller->command("renderPartial", "loginFullPage.htm"); 
}


