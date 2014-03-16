<?php



function deleteDinner() { 
  global $controller; 
  $dinner = $controller->command("loadMemberDinner", $_GET['id']);
  $dinner->delete();
}