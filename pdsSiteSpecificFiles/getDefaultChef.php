<?php



function getDefaultChef() {
  global $controller; 
  $sql = "SELECT * FROM fb_chef WHERE is_default=1 ORDER BY default_order ASC";
  $results = $controller->command("makeQuery", $sql, "getDefaultChef"); 
  $chefs = array();

  while($row = mysql_fetch_object($results)){
    $chefObject = $controller->getObject("Chef", "getDefaultChef"); 
    $chefObject->setAttributes($row->fb_chef_id, $row->fb_chef_name); 
    $chefs[] = $chefObject;
  }

  return $chefs;
} 
