<?php



function getPageCssClass($page) {
  global $controller; 

  $userInfo = $controller->command("getUserInfo");
  $userName = '';
  if (is_array($userInfo)) {
    $userName = $userInfo['first_name'] . ' ' . $userInfo['last_name'];
  }

  if (substr($page, -4) == '.htm') $page = substr($page, 0, -4); 
  
  $pageCssClass = "New York";
  if ($page == "nominate") $pageCssClass = "std"; 
  if ($page == "reservations") $pageCssClass = "res"; 
  if ($page == "cellar") $pageCssClass = "cellar"; 
  if ($page == "my_cellar") $pageCssClass = "cellar"; 
  if ($page == "my_empties") $pageCssClass = "cellar"; 
  if ($page == "guest_list") $pageCssClass = "res"; 
  if ($page == "directory") $pageCssClass = "std"; 
  if ($page == "profile") $pageCssClass = "profile"; 
  return $pageCssClass; 
}







