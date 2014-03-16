<?php

include("config.php"); 

$machineId =  $_COOKIE["machineId"];
if (!$machineId) {
  if (!headers_sent()) {
    $machineId = md5(uniqid(rand()));
    $success = setcookie("machineId", $machineId, time() + 1000000000);	
  } else {
    // not relevent in the initiate file
  }
}

session_start(); 
$sessionId = session_id();

if (!class_exists("Controller")) {
  include($pathToSharedCode."Controller.php"); 
  $controller = new Controller(); 

  // 2013-11-26 - during the era 2002 to 2008 I often output text from functions.
  // This was bad practice and lead to many problems. At the end of 2007 I removed
  // all "echo" and "print" statements from my code, and switched to using the
  // method addToOutput(), which is in the controller. This depends on the Output
  // object. I allowed immediate echo of text to remain the default, though I meant
  // to change it eventually. I did make it possible to change the default in the 
  // Output object, though I did not do so at that time. After 2008 I started using 
  // the Symfony framework and this PDS framework was forgotten. But since I am 
  // using it again for Parlor New York, I will now set the default to return any text
  // sent to addToOutput. 
  //$controller->changeOutputMode(true);


  // 2013-12-01 - arrayOfMandatoryFields is a two dimensional array, the first dimension
  // specifies the database table, the second dimension specifies the field. A typical
  // entry in the array looks like this: 
  //
  // $arrayOfMandatoryFields["neighborhoods"]["city"] = "You must fill in the field for 'city'."; 
  //
  // in checkToSeeIfMandatoryFieldsAreFilled() we fetch the mandatory fields like this:
  //
  // $arrayOfMandatoryFields = $controller->arrayOfAllCarriedInfo['arrayOfMandatoryFields'];
  // 
  // checkToSeeIfMandatoryFieldsAreFilled() is called in rowUpdate() and rowCreate() on every
  // row that gets created or updated in the database. Add any mandatory fields here. 
  $controller->arrayOfAllCarriedInfo['arrayOfMandatoryFields']['lk_rsvps']['lk_occurrence_id'] = "We are sorry but you don't seem to have chosen which event you are going to.";
  $controller->arrayOfAllCarriedInfo['arrayOfMandatoryFields']['lk_rsvps']['email'] = "We are sorry but you don't seem to have added your email.";



  // 05-17-08 - this is called in getTheSafeVersionOfThisCommand()
  //
  //		 05-17-08 - this framework was built in a hurry, back in 2006,
  //		 and every job where it was used, at both Category4.com and 
  //		 at bluewallllc.com, we were always in a rush and security was
  //		 always a secondary concern. The fact that any random visitor
  //		 to the website can execute any function they want, simply by
  //		 typing "choiceMade[]=deleteAll" into the browser url has made this
  //		 framework unsuitable for real world use. We fix this now. 
  //             We will use getTheSafeVersionOfThisCommand to find a safe version of the 
  //		 requested command, if the current user is not an admin. 
  $controller->arrayOfAllCarriedInfo['arrayOfSafeFunctions']["createHtmlForFormInput"] = "youAreNotAllowedMessage";
  $controller->arrayOfAllCarriedInfo['arrayOfSafeFunctions']["deleteThisRecord"] = "deleteThisRecordForThisUser";
  $controller->arrayOfAllCarriedInfo['arrayOfSafeFunctions']["showViewUsers"] = "youAreNotAllowedMessage";
  $controller->arrayOfAllCarriedInfo['arrayOfSafeFunctions']["resetMemberPassword"] = "youAreNotAllowedMessage";





  // 2013-10-27 - the legacy code on parlornewyork.com is full of unfortunate flaws. 
  // Among them is the problem that the database table has primary keys with names such
  // as "user_id" instead of "id". My older software assumed "id", but here I'll have to 
  // look up the actual name of each primary key. 
  $controller->arrayOfAllCarriedInfo['arrayOfPrimaryKeys']["users"] = "user_id";




  // 2013-12-02 - this is called in setTotalFormInputsForPage() so that page will know how to look up all
  // the nested information and put it into totalFormInputs. 
  $controller->arrayOfAllCarriedInfo['arrayOfDatabaseRelationships']['lk_occurrences']['lk_courses'] = 'has_many'; 
  $controller->arrayOfAllCarriedInfo['arrayOfDatabaseRelationships']['lk_occurrences']['lk_reservations'] = 'has_many'; 
  $controller->arrayOfAllCarriedInfo['arrayOfDatabaseRelationships']['lk_occurrences']['lk_wines'] = 'many_to_many'; 
  $controller->arrayOfAllCarriedInfo['arrayOfDatabaseRelationships']['lk_courses']['lk_foods'] = 'has_many'; 
  $controller->arrayOfAllCarriedInfo['arrayOfDatabaseRelationships']['users']['lk_reservations'] = 'has_many'; 
  $controller->arrayOfAllCarriedInfo['arrayOfDatabaseRelationships']['users']['lk_occurrences'] = 'has_many'; 
  $controller->arrayOfAllCarriedInfo['arrayOfDatabaseRelationships']['users']['lk_guests'] = 'has_many'; 
  $controller->arrayOfAllCarriedInfo['arrayOfDatabaseRelationships']['lk_reservations']['lk_guests'] = 'has_many'; 















  $controller->command("updateUserLastAction"); 	
  $controller->command("executeChoiceMade");


}

