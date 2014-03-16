<?php



function getDatabaseTableValuesInOptionTags($databaseTableName=false, $fieldToGetForValues=false, $fieldToGetForVisibleText=false) {
  // 11-06-07 - there are many, many times when we'd like to get some info from a database table
  // and wrap it in some option tags, to be used in a select box. We need to know 3 things, the
  // parameters we are given: 
  // 
  // $databaseTableName - what database table are we going to get this info from? 
  //
  // $fieldToGetForValues - what database table field should be used for the values in the option tags? 
  //
  // $fieldToGetForVisibleText - what database table field should be used for the visible text in the option tags? 
  //
  // This function is commonly used on pages where you are creating or editing an entry 
  // that must belong to another page. For instance, if you had two database tables, "weblogs" and 
  // "weblog entries" then on the create and edit forms for weblog entries, you'd probably have a
  // select box that allowed the user to specify which weblog this weblog entry would belong to. You
  // would use this command like this, for that situation: 
  //
  // <select name="formInputs[idOfWhichWeblog]">
  //	<option value="">(No choice made)</option>
  //	< ?php getDatabaseTableValuesInOptionTags("weblogs", "id", "name");  ? >
  // </select>
  //
  // (There is an artificial space between the "<" and "?" in the above example, to avoid triggering
  // PHP errors. 	
	
  global $controller; 

  if ($databaseTableName && $fieldToGetForValues && $fieldToGetForVisibleText) {
    $query = "SELECT $fieldToGetForValues, $fieldToGetForVisibleText  FROM $databaseTableName";
    $result = $controller->command("makeQuery", $query, "getDatabaseTableValuesInOptionTags"); 
    if ($result) {
      $howManyResults = mysql_num_rows($result); 
      $stringOfHtmlForOptionTags = "";
      for ($i=0; $i < $howManyResults; $i++) {
	$row = $controller->command("getRow", $result); 
	$someValue = $row[$fieldToGetForValues];
	$someVisibleText = $row[$fieldToGetForVisibleText];
	// 01-22-07 - desperate times call for desperate measures
	if (!$someVisibleText) $someVisibleText = $someValue; 
	$stringOfHtmlForOptionTags .= "
					<option value=\"$someValue\">$someVisibleText</option>
				";
      }
    }

    return $stringOfHtmlForOptionTags;
  } else {
    $controller->error("In getDatabaseTableValuesInOptionTags we did not get one of the parameters that we need: databaseTableName $databaseTableName  fieldToGetForValues $fieldToGetForValues  fieldToGetForVisibleText $fieldToGetForVisibleText"); 
  }
}



