<?php 



function hasThisUserFilledOutAnApplication($userId=false) {
  // 2014-02-02 - in use on this page:
  //
  // http://dev4.krubner.com/preview.php?page=preview_choose_a_date
  //
  // Daniel says that if a user has not yet filled out an application then they should only be shown
  // distant dates, but if a user has filled out an application, then they can be shown more recent dates. 
  
  global $controller; 

  if(!$userId) {
    $controller->error("In hasThisUserFilledOutAnApplication() we expected 1 argument, and for that to be the id of the user we were checking."); 
    return false; 
  }

  $user = $controller->command("rowGetWithTrust", "users", $userId);

  if(!$user) {
    $controller->error("In hasThisUserFilledOutAnApplication() we were given $userId and yet we were unable to find a user with that id."); 
    return false; 
  }

  $thisUserHasFilledOutAnApplication = true; 

  if ($user["organization"] == '') $thisUserHasFilledOutAnApplication = false; 
  if ($user["title"] == '') $thisUserHasFilledOutAnApplication = false; 
  if ($user["university"] == '') $thisUserHasFilledOutAnApplication = false; 
  if ($user["industry"] == '') $thisUserHasFilledOutAnApplication = false; 
  if ($user["what_are_your_greatest_accomplishments"] == '') $thisUserHasFilledOutAnApplication = false; 
  if ($user["what_are_some_of_your_favorite_places_to_travel"] == '') $thisUserHasFilledOutAnApplication = false; 
  if ($user["how_many_weeks_a_year_do_you_travel"] == '') $thisUserHasFilledOutAnApplication = false; 
  if ($user["what_is_your_favorite_piece_of_art"] == '') $thisUserHasFilledOutAnApplication = false; 
  if ($user["have_you_visited_any_of_these_places"] == '') $thisUserHasFilledOutAnApplication = false; 
  if ($user["phone"] == '') $thisUserHasFilledOutAnApplication = false; 

  $arrayOfNominations = $controller->command("databaseFetchSqlWithTrust", "select user_id from users where nomination_which_member_nominated_this_user_id=$userId", "hasThisUserFilledOutAnApplication"); 

  if (count($arrayOfNominations) < 2) $thisUserHasFilledOutAnApplication = false; 

  return $thisUserHasFilledOutAnApplication; 
}





