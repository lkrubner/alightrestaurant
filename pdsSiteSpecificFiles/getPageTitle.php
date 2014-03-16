<?php



function getPageTitle($page) {
  global $controller; 

  $userInfo = $controller->command("getUserInfo");
  $userName = '';
  if (is_array($userInfo)) {
    $userName = $userInfo['first_name'] . ' ' . $userInfo['last_name'];
  }
  
  $pageTitle = "New York";
  if ($page == "nominate") $pageTitle = "Nominations"; 
  if ($page == "reservations") $pageTitle = "Reservations"; 
  if ($page == "cellar") $pageTitle = "Wine Cellar"; 
  if ($page == "guest_list") $pageTitle = "Guest List"; 
  if ($page == "directory") $pageTitle = "Directory"; 
  if ($page == "profile") $pageTitle = "Your Profile"; 
  if ($page == "nominate.htm") $pageTitle = "Nominations"; 
  if ($page == "reservations.htm") $pageTitle = "Reservations"; 
  if ($page == "cellar.htm") $pageTitle = "Wine Cellar"; 
  if ($page == "guest_list.htm") $pageTitle = "Guest List"; 
  if ($page == "directory.htm") $pageTitle = "Directory"; 
  if ($page == "profile.htm") $pageTitle = "$userName"; 
  return $pageTitle; 
}







