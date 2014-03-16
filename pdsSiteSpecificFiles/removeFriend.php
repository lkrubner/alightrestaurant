<?php



function removeFriend() {
  // 2014-01-19 - in use here: 
  //
  // http://dev4.krubner.com/private.php?page=private_guest_list

  global $controller; 

  $id = $controller->getVar("id"); 

  $query = "delete from lk_friends where user_id_of_friend =$id ";
 
  $controller->command("databaseDeleteSqlForThisUser", $query, "removeFriend");

}






