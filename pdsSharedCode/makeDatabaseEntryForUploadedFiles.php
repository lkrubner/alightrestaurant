<?php



function makeDatabaseEntryForUploadedFiles($finalFileName=false,  $whichDatabaseTable=false, $id=false) {
	// 09-21-06 - this is likely the file that is most likely to be overridden on every site. 
	//
	// On www.mjhforwomen.org, we need to add the final image name to the database. We need
	// the name of the database table and an id, which we expect to get the form just submitted.
	// The final file name is given to us as a parameter. If no id is given to us by the form
	// just submitted, then we must assume that the record to which this image belongs was
	// just created, in which case we should be able to find the value in SingletonIdOfEntryJustCreated.

	global $controller; 
	
	if (!$whichDatabaseTable) $whichDatabaseTable = $_POST["whichDatabaseTable"];
	if (!$id) $id = $_POST["id"];
	
	// 09-21-06 - if id isn't present in the POST variable, we assume an entry has just been
	// created in the database. 
	if (!$id) {
		$singletonIdOfEntryJustCreatedObject = & $controller->getObject("SingletonIdOfEntryJustCreated", "makeDatabaseEntryForUploadFiles"); 
		$id = $singletonIdOfEntryJustCreatedObject->get(); 
	}	
	
	if ($id && $whichDatabaseTable && $finalFileName) {
		// 11-14-06 - we will for now hard code  a value for a field name. 
//		if ($whichDatabaseTable === "life_phase") $imageField = "masthead_image";
		//
		// 11-17-06 - I apologize that the variable is called "imageField". I was thinking
		// of image fields when I wrote this, though actually it can hold any file. 
		$imageField = "upload_file";
		if ($imageField) {
			$query = "UPDATE $whichDatabaseTable  SET $imageField = '$finalFileName' WHERE id=$id ";		
			$result = $controller->command("makeQuery", $query, "makeDatabaseEntryForUploadedFiles"); 
			return $result; 
		} else {
			$controller->error("In 	makeDatabaseEntryForUploadedFiles the database table name was $whichDatabaseTable, but from that we could not get a field name, but instead had this: '$imageField'."); 
		}
	} else {
		$controller->error("In makeDatabaseEntryForUploadedFiles, we failed to get an id and a final filename and a name for the database table which we were suppose to update. We got '$id' for the id and '$whichDatabaseTable' for the database table name, and the final file name was '$finalFileName'."); 
	}
}



?>
