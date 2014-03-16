<?php



function getUserInfo() {
  // 05-17-08 - this is meant to be a simple, clean, safe
  // way to get all the needed info about the current user. 

  global $controller;

  static $entry = false; 

  if (!is_array($entry)) {
    $userId = $controller->command("getIdOfLoggedInUser"); 
    if ($userId) {
      $entry = $controller->command("rowGetWithTrust", "users", $userId); 
    }
  }
	
  return $entry; 
}



