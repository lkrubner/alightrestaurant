<?php



function formatTimeToShowSeatingTimesToTheCurrentLoggedInMemberWhenTheyCreateAReservation ($rowOfOneOccurrence) {
  // 2014-01-13 - occurrences look like this: 
  //
  // id: 97
  // max_total: 48
  // max_hour: 12
  // max_halfhour: 6
  // user_id: 13976
  // type: member_dinner
  // name: Valetine's Delight!
  // description: Take your girl out!
  // start: 19:00:00
  // end: 22:00:00
  // created_at: 2014-01-14 03:04:58
  // updated_at: 2014-01-13 22:09:55
  // start_day: 2014-02-14
  //
  // we need to create the options for a select box that will end up looking like this:
  //
  // <select name="time" class="private-reservations-dropdown">
  // <option value="">SEATING TIME</option>
  // <option value="19:00:00">7:00 PM</option>
  // <option value="19:30:00">7:30 PM</option>
  // <option value="20:00:00">8:00 PM</option>
  // <option value="20:30:00">8:30 PM</option>
  // <option value="21:00:00">9:00 PM</option>
  // <option value="21:30:00">9:30 PM</option>
  // </select>
  //

  global $controller; 

  if (!is_array($rowOfOneOccurrence)) {
    $controller->error("In formatTimeToShowSeatingTimesToTheCurrentLoggedInMemberWhenTheyCreateAReservation we expected one argument, which should have been an array, representing one row from the lk_occurrence database table, but instead all we got was: " . print_r($rowOfOneOccurrence, true)); 
    return false; 
  }

  $timestampInSecondsOfFirstSeating = strtotime($rowOfOneOccurrence['start_day'] . $rowOfOneOccurrence['start']);
  $timestampInSecondsOfLastSeating = strtotime($rowOfOneOccurrence['start_day'] . $rowOfOneOccurrence['end']);
  $thisHalfHourInSeconds = $timestampInSecondsOfFirstSeating;

  $howManySecondsTotal = $timestampInSecondsOfLastSeating - $timestampInSecondsOfFirstSeating;
  $howManyHalfHourSlots = round($howManySecondsTotal / 1800); 

  // 2014-02-03 - I have no idea what the default should be, but if we are going to enforce this, 
  // then we need to have a default. 
  if (!$rowOfOneOccurrence["max_halfhour"]) {
    if (!$rowOfOneOccurrence["max_hour"]) {
      if (!$rowOfOneOccurrence["max_total"]) {
	$allowedTotalOfPeoplePerHalfHour = 10;
      } else {
	$allowedTotalOfPeoplePerHalfHour = round($rowOfOneOccurrence["max_total"] / $howManyHalfHourSlots);
      }
    } else {
      $allowedTotalOfPeoplePerHalfHour = round($rowOfOneOccurrence["max_hour"] / 2);
    }
  } else {
    $allowedTotalOfPeoplePerHalfHour = $rowOfOneOccurrence["max_halfhour"];
  }

  $query = "
    select r.at_what_time_does_the_member_plan_to_arrive, count(g.user_id) as howManyPeopleForThisHalfHour     
    from lk_reservations r left join lk_guests g on r.id=g.lk_reservation_id     
    where r.lk_occurrence_id={$rowOfOneOccurrence["id"]} 
    group by r.at_what_time_does_the_member_plan_to_arrive;
  ";
  $arrayOfReservations = $controller->command("databaseFetchSqlWithTrust", $query, "formatTimeToShowSeatingTimesToTheCurrentLoggedInMemberWhenTheyCreateAReservation"); 

  $arrayOfDateKeyWithPeopleCount = array();
  
  foreach($arrayOfReservations as $r) {
    // 2014-02-03 - we do "+1" here for the person who is making a reservation. If they have 0 guests, we
    // do +1 so there is a number here, and if they have 1 guest then the number should be 2, etc.
    $arrayOfDateKeyWithPeopleCount[$r["at_what_time_does_the_member_plan_to_arrive"]] = $r["howManyPeopleForThisHalfHour"] + 1;
  }

  $stringOfHtml = '';

  while ($thisHalfHourInSeconds < $timestampInSecondsOfLastSeating) {
    $fullDateTime = date('Y-m-d H:i:s', $thisHalfHourInSeconds);
    $howManyPeopleSoFarForThisHour = $arrayOfDateKeyWithPeopleCount[$fullDateTime];

    if ($allowedTotalOfPeoplePerHalfHour > $howManyPeopleSoFarForThisHour) {
      $stringOfHtml .= "<option value='" . $thisHalfHourInSeconds . "'>" . date('g:i A', $thisHalfHourInSeconds)  . "</option>\n"; 
    }

    $thisHalfHourInSeconds = $thisHalfHourInSeconds + 1800; 
  }

  return $stringOfHtml; 
}