<?php



function loadAllChefs(){
  global $controller; 
  $sql = "SELECT * FROM fb_chef";
  $results = $controller->command("makeQuery", $sql, "loadAllChefs");
  $chefs = array();

  while($row = mysql_fetch_object($results)){
    $chefObject = $controller->getObject("Chef", "getAllChefs", "makeNewObject");
    $chefObject->setAttributes($row->fb_chef_id, $row->fb_chef_name); 
    $fbChefId = $row->fb_chef_id;
    $chefs[$fbChefId] = $chefObject;
  }

  return $chefs;
}



