<?php



function getIdOfNewlyCreatedRecord() {
	// 01-09-08 - this is being used in findIdForForms. I simply wanted a simple, one 
	// line way of getting the id of a record that has just been created in the database. 
	// So now, in findIdForForms, we get to do this: 
	//
	// $id = $controller->command("getIdOfNewlyCreatedRecord"); 

	global $controller; 
	$singletonNewIdObject = & $controller->getObject("SingletonIdOfEntryJustCreated", "getIdOfNewlyCreatedRecord"); 
	$idOfNewlyCreatedRecordInTheDatabase = $singletonNewIdObject->get(); 
	return $idOfNewlyCreatedRecordInTheDatabase;
}



?>