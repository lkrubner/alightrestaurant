<?php



function getThisSpace($id) {
  global $controller; 
  //This will fail if a space's parent ID is higher than it's own ID

  $sql = "SELECT * FROM spaces WHERE space_id = $id";
  $results = $controller->command("makeQuery", $sql, "loadAllSpaces"); 

  while($row = mysql_fetch_object($results)) {

    if($row->parent_id == 0){
      $parent = null;
    } else {
      $parent = $controller->command("getThisSpace", $row->parent_id);
    }

    $spaceId = $row->space_id; 
    $spaceObject = $controller->getObject("Space", "loadAllSpaces"); 
    $spaceObject->setAttributes($spaceId, $row->title, $parent); 
    
    $allSpaces[$spaceId] = $spaceObject;
  }

  return $spaceObject;
}
