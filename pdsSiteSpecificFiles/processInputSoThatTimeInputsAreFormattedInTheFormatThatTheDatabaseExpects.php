<?php



function processInputSoThatTimeInputsAreFormattedInTheFormatThatTheDatabaseExpects($totalFormInputs=false) {
  // 2013-12-02 - this is in use on this form:
  //
  // http://dev4.krubner.com/admin.php?page=admin_edit_occurrence&occurrence_type=preview_dinner&currentEditingId=41
  //
  // We set this function in a hidden input like this:
  //
  //     <input type="hidden" name="processInputWithTheseFunctions[]" value="processInputSoThatTimeInputsAreFormattedInTheFormatThatTheDatabaseExpects" />
  //
  // this is then called in  createRecordsForMultipleDatabaseTables() like this: 
  //
  //  	$processInputWithTheseFunctionsArray = $controller->getVar("processInputWithTheseFunctions");
  //	if ($processInputWithTheseFunctionsArray) {
  //		if (is_array($processInputWithTheseFunctionsArray)) {
  //			reset($processInputWithTheseFunctionsArray);	
  //			while(list($key, $nameOfFunction) = each($processInputWithTheseFunctionsArray)) {
  //				$totalFormInputs = $controller->command($nameOfFunction, $totalFormInputs); 
  //			}
  //		} 
  //	}
  //
  // 
  // The form inputs for the time look like this:
  //
  //	  <select id="occurrence_start" name="occurrence_start" class="auto-width">
  //	    <option value='12:00'>12:00</option>
  //	    <option value='12:30'>12:30</option>
  //	    <option value='1:00'>1:00</option>
  //	    <option value='1:30'>1:30</option>
  //	    <option value='2:00'>2:00</option>
  //	    <option value='2:30'>2:30</option>
  //	    <option value='3:00'>3:00</option>
  //	    <option value='3:30'>3:30</option>
  //	    <option value='4:00'>4:00</option>
  //	    <option value='4:30'>4:30</option>
  //	    <option value='5:00'>5:00</option>
  //	    <option value='5:30'>5:30</option>
  //	    <option selected='selected' value='6:00'>6:00</option>
  //	    <option value='6:30'>6:30</option>
  //	    <option value='7:00'>7:00</option>
  //	    <option value='7:30'>7:30</option>
  //	    <option value='8:00'>8:00</option>
  //	    <option value='8:30'>8:30</option>
  //	    <option value='9:00'>9:00</option>
  //	    <option value='9:30'>9:30</option>
  //	    <option value='10:00'>10:00</option>
  //	    <option value='10:30'>10:30</option>
  //	    <option value='11:00'>11:00</option>
  //	    <option value='11:30'>11:30</option>
  //	  </select>
  //	  <select name="start_merediem" class="auto-width">
  //	    <option value='AM'>AM</option>
  //	    <option selected='selected' value='PM'>PM</option>
  //	  </select>
  //
  // The other input has the name occurrence_end and end_merediem
  //
  // We need to format this for the database, such that "6 PM" becomes "18:00:00" and we need to store the data here:
  //
  // totalFormInputs[lk_occurrences][< ?php echo currentEditingId(); ? >][start]
  //
  // and here: 
  //
  // totalFormInputs[lk_occurrences][< ?php echo currentEditingId(); ? >][end]
  
  global $controller; 

  if (!$totalFormInputs) {
    $controller->error("In processInputSoThatTimeInputsAreFormattedInTheFormatThatTheDatabaseExpects() the first parameter has to be totalFormInputs, but the function was given nothing."); 
    return false; 
  }

  $occurrenceStart = $controller->getVar("occurrence_start");
  $startMerediem =  $controller->getVar("start_merediem");
  $occurrenceEnd = $controller->getVar("occurrence_end");
  $endMerediem = $controller->getVar("end_merediem");

  if ($startMerediem == "AM") {
    if ($occurrenceStart == '12:00') $formattedHour = '00:00:00';
    if ($occurrenceStart == '12:30') $formattedHour = '00:30:00';
    if ($occurrenceStart == '1:00') $formattedHour = '01:00:00';
    if ($occurrenceStart == '1:30') $formattedHour = '01:30:00';
    if ($occurrenceStart == '2:00') $formattedHour = '02:00:00';
    if ($occurrenceStart == '2:30') $formattedHour = '02:30:00';
    if ($occurrenceStart == '3:00') $formattedHour = '03:00:00';
    if ($occurrenceStart == '3:30') $formattedHour = '03:30:00';
    if ($occurrenceStart == '4:00') $formattedHour = '04:00:00';
    if ($occurrenceStart == '4:30') $formattedHour = '04:30:00';
    if ($occurrenceStart == '5:00') $formattedHour = '05:00:00';
    if ($occurrenceStart == '5:30') $formattedHour = '05:30:00';
    if ($occurrenceStart == '6:00') $formattedHour = '06:00:00';
    if ($occurrenceStart == '6:30') $formattedHour = '06:30:00';
    if ($occurrenceStart == '7:00') $formattedHour = '07:00:00';
    if ($occurrenceStart == '7:30') $formattedHour = '07:30:00';
    if ($occurrenceStart == '8:00') $formattedHour = '08:00:00';
    if ($occurrenceStart == '8:30') $formattedHour = '08:30:00';
    if ($occurrenceStart == '9:00') $formattedHour = '09:00:00';
    if ($occurrenceStart == '9:30') $formattedHour = '09:30:00';
    if ($occurrenceStart == '10:00') $formattedHour = '10:00:00';
    if ($occurrenceStart == '10:30') $formattedHour = '10:30:00';
    if ($occurrenceStart == '11:00') $formattedHour = '11:00:00';
    if ($occurrenceStart == '11:30') $formattedHour = '11:30:00';
  } else {
    if ($occurrenceStart == '12:00') $formattedHour = '12:00:00';
    if ($occurrenceStart == '12:30') $formattedHour = '12:30:00';
    if ($occurrenceStart == '1:00') $formattedHour = '13:00:00';
    if ($occurrenceStart == '1:30') $formattedHour = '13:30:00';
    if ($occurrenceStart == '2:00') $formattedHour = '14:00:00';
    if ($occurrenceStart == '2:30') $formattedHour = '14:30:00';
    if ($occurrenceStart == '3:00') $formattedHour = '15:00:00';
    if ($occurrenceStart == '3:30') $formattedHour = '15:30:00';
    if ($occurrenceStart == '4:00') $formattedHour = '16:00:00';
    if ($occurrenceStart == '4:30') $formattedHour = '16:30:00';
    if ($occurrenceStart == '5:00') $formattedHour = '17:00:00';
    if ($occurrenceStart == '5:30') $formattedHour = '17:30:00';
    if ($occurrenceStart == '6:00') $formattedHour = '18:00:00';
    if ($occurrenceStart == '6:30') $formattedHour = '18:30:00';
    if ($occurrenceStart == '7:00') $formattedHour = '19:00:00';
    if ($occurrenceStart == '7:30') $formattedHour = '19:30:00';
    if ($occurrenceStart == '8:00') $formattedHour = '20:00:00';
    if ($occurrenceStart == '8:30') $formattedHour = '20:30:00';
    if ($occurrenceStart == '9:00') $formattedHour = '21:00:00';
    if ($occurrenceStart == '9:30') $formattedHour = '21:30:00';
    if ($occurrenceStart == '10:00') $formattedHour = '22:00:00';
    if ($occurrenceStart == '10:30') $formattedHour = '22:30:00';
    if ($occurrenceStart == '11:00') $formattedHour = '23:00:00';
    if ($occurrenceStart == '11:30') $formattedHour = '23:30:00';
  }


  if ($endMerediem == "AM") {
    if ($occurrenceEnd == '12:00') $formattedHour2 = '00:00:00';
    if ($occurrenceEnd == '12:30') $formattedHour2 = '00:30:00';
    if ($occurrenceEnd == '1:00') $formattedHour2 = '01:00:00';
    if ($occurrenceEnd == '1:30') $formattedHour2 = '01:30:00';
    if ($occurrenceEnd == '2:00') $formattedHour2 = '02:00:00';
    if ($occurrenceEnd == '2:30') $formattedHour2 = '02:30:00';
    if ($occurrenceEnd == '3:00') $formattedHour2 = '03:00:00';
    if ($occurrenceEnd == '3:30') $formattedHour2 = '03:30:00';
    if ($occurrenceEnd == '4:00') $formattedHour2 = '04:00:00';
    if ($occurrenceEnd == '4:30') $formattedHour2 = '04:30:00';
    if ($occurrenceEnd == '5:00') $formattedHour2 = '05:00:00';
    if ($occurrenceEnd == '5:30') $formattedHour2 = '05:30:00';
    if ($occurrenceEnd == '6:00') $formattedHour2 = '06:00:00';
    if ($occurrenceEnd == '6:30') $formattedHour2 = '06:30:00';
    if ($occurrenceEnd == '7:00') $formattedHour2 = '07:00:00';
    if ($occurrenceEnd == '7:30') $formattedHour2 = '07:30:00';
    if ($occurrenceEnd == '8:00') $formattedHour2 = '08:00:00';
    if ($occurrenceEnd == '8:30') $formattedHour2 = '08:30:00';
    if ($occurrenceEnd == '9:00') $formattedHour2 = '09:00:00';
    if ($occurrenceEnd == '9:30') $formattedHour2 = '09:30:00';
    if ($occurrenceEnd == '10:00') $formattedHour2 = '10:00:00';
    if ($occurrenceEnd == '10:30') $formattedHour2 = '10:30:00';
    if ($occurrenceEnd == '11:00') $formattedHour2 = '11:00:00';
    if ($occurrenceEnd == '11:30') $formattedHour2 = '11:30:00';
  } else {
    if ($occurrenceEnd == '12:00') $formattedHour2 = '12:00:00';
    if ($occurrenceEnd == '12:30') $formattedHour2 = '12:30:00';
    if ($occurrenceEnd == '1:00') $formattedHour2 = '13:00:00';
    if ($occurrenceEnd == '1:30') $formattedHour2 = '13:30:00';
    if ($occurrenceEnd == '2:00') $formattedHour2 = '14:00:00';
    if ($occurrenceEnd == '2:30') $formattedHour2 = '14:30:00';
    if ($occurrenceEnd == '3:00') $formattedHour2 = '15:00:00';
    if ($occurrenceEnd == '3:30') $formattedHour2 = '15:30:00';
    if ($occurrenceEnd == '4:00') $formattedHour2 = '16:00:00';
    if ($occurrenceEnd == '4:30') $formattedHour2 = '16:30:00';
    if ($occurrenceEnd == '5:00') $formattedHour2 = '17:00:00';
    if ($occurrenceEnd == '5:30') $formattedHour2 = '17:30:00';
    if ($occurrenceEnd == '6:00') $formattedHour2 = '18:00:00';
    if ($occurrenceEnd == '6:30') $formattedHour2 = '18:30:00';
    if ($occurrenceEnd == '7:00') $formattedHour2 = '19:00:00';
    if ($occurrenceEnd == '7:30') $formattedHour2 = '19:30:00';
    if ($occurrenceEnd == '8:00') $formattedHour2 = '20:00:00';
    if ($occurrenceEnd == '8:30') $formattedHour2 = '20:30:00';
    if ($occurrenceEnd == '9:00') $formattedHour2 = '21:00:00';
    if ($occurrenceEnd == '9:30') $formattedHour2 = '21:30:00';
    if ($occurrenceEnd == '10:00') $formattedHour2 = '22:00:00';
    if ($occurrenceEnd == '10:30') $formattedHour2 = '22:30:00';
    if ($occurrenceEnd == '11:00') $formattedHour2 = '23:00:00';
    if ($occurrenceEnd == '11:30') $formattedHour2 = '23:30:00';
  }

  // We only expect one row of lk_occurrences here
  reset($totalFormInputs);
  $k = key($totalFormInputs['lk_occurrences']); 
  $formInputs = current($totalFormInputs['lk_occurrences']);

  if ($occurrenceStart) {
    $formInputs['start'] = $formattedHour;
  }
  if ($occurrenceEnd) {
    $formInputs['end'] = $formattedHour2;
  }
  $id = $k;
  if (!$id) $id = 0;

  $totalFormInputs["lk_occurrences"][$id] = $formInputs; 

  return $totalFormInputs; 
}




