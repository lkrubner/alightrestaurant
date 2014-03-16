<?php


function getNeededFunctionsEach($functionNameToBeImported=false) {
  // 09-19-06 - this functions gets called from places like showForms. Any time
  // a file or template is being include that may call PHP functions that have
  // not yet been imported, its best to fetch all possible PHP functions from
  // that file using matchAllPhpFunctionsInString and then use this function
  // to actually get them. It should be noted that this function will always 
  // be called from loopArray, this if the function that you'd give to loopArray
  // once you've got an array of function names to be imported. 
  //
  // The usual item will look like this:
  //
  // <?php currentValueFromLists(
  //
  // Yes, it's just a fragment. We strip off the rest to get the function name.
  //
  // 2013-11-30 - sometimes $functionNameToBeImported looks like this: 
  //
  //  < ?php if (currentValueForTotalFormInputs("occurrences", currentEditingId(), "occurrence_type")): ? >
  // <option value="< ?php echo currentValueForTotalFormInputs("occurrences", currentEditingId(), "occurrence_type") ? >">< ?php echo currentValueForTotalFormInputs("occurrences", currentEditingId(), "occurrence_type") ? ></option>
  //  < ?php else: ? >
  //  <option value="">Choose a type</option>
  //  < ?php endif ? >
  //
  // It's often easier just to use $controller->command("whateverFunctionYouWant")
	
  global $controller;

  $positionOfParen = strpos($functionNameToBeImported, "(");
  $functionNameToBeImported = substr($functionNameToBeImported, 0, $positionOfParen);
	
  $functionNameToBeImported = str_replace("<", "", $functionNameToBeImported);
  $functionNameToBeImported = str_replace("?php ", "", $functionNameToBeImported);

  // 2013-11-30 - typically a call might look like this:
  //
  // <input type="text" id="totalFormInputs[occurrences][< ?php echo currentEditingId(); ? >][name]" name="totalFormInputs[occurrences][< ?php echo currentEditingId(); ? >][name]" value="< ?php echo currentValueForTotalFormInputs("occurrences", currentEditingId(), "name"); ? >" />
  //
  // So we have to remove the "echo".
  $functionNameToBeImported = str_replace("echo", "", $functionNameToBeImported); 
  $functionNameToBeImported = trim($functionNameToBeImported); 

  // 04-20-07 - I'm looking for a way to avoid accidental imports of lines like:
  //
  //  echo basename(currentValueFromLists("id", "", "return"));
  //
  // Maybe we can be very strict about formatting? If the php start tag
  // is followed by more than one open space, we don't both trying to 
  // include this function.
  //
  // $functionNameToBeImported = str_replace(" ", "", $functionNameToBeImported);
  if (!stristr($functionNameToBeImported, " ")) {
    $functionNameToBeImported = str_replace("(", "", $functionNameToBeImported);
    $imported = $controller->import($functionNameToBeImported, "getNeededFunctionsEach"); 
    return $imported; 
  }
}




