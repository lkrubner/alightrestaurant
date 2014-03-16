<?php



function checkToSeeIfTheUserIsSearchingForAMemberAndIfYesThenSetThatMemberAsTheCurrentMemberToEdit() {
  // 2013-12-07 - in use on this page: 
  //
  // http://dev4.krubner.com/admin.php?page=admin_edit_member&editId=456
  //
  // The first 2 inputs on that page allow the user to search for members to edit. The inputs look like this:
  //
  //    <select id="new_member_for_occurrence_1" name="new_member_for_occurrence_1">
  //
  //    <input type="text" id="new_member_for_occurrence_2" name="new_member_for_occurrence_2"> 

  global $controller; 

  $idForThisMember = $controller->getVar("new_member_for_occurrence_1"); 
  $new_member_for_occurrence_2 = $controller->getVar("new_member_for_occurrence_2"); 

  if ($new_member_for_occurrence_2) {
    $arrayOfMemberNamesWhereIndexIsTheDatabaseIdOfTheMember = $controller->command("getAllMembersInAnArray"); 
    $idForThisMember = array_search($new_member_for_occurrence_2, $arrayOfMemberNamesWhereIndexIsTheDatabaseIdOfTheMember);
  }

  if ($idForThisMember) {
    // 2013-12-07 - this is checked in functions such as currentEditingId() and take precedence 
    // over all other rules regarding what the current id should be. 
    $controller->arrayOfAllCarriedInfo['currentEditingIdOverride'] = $idForThisMember;
  }


}




