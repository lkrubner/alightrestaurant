<?php



function processTagsForCommunityPosts($totalFormInputs=false) {
	// 06-17-08 - I'm trying to get rid of createCommunityPost(), which was a one-off
	// hack created for the sake of speed. The correct way to handle special cases, such
	// as tags, is with a "process" function such as this, which can be assigned to the
	// array processInputWithTheseFunctions and then called inside of processInput. This
	// function should make createCommunityPost() unnecessary. This is called on these pages:
	//
	// http://www.cyberbitten.com/my_private_page.php?formName=mp_sharing_wall.htm
	//
	// http://www.cyberbitten.com/my_private_page.php?formName=mp_sharing_wall_other_peoples_posts.htm


	global $controller; 

	if (is_array($totalFormInputs)) {
		while (list($whichDatabaseTable, $arrayOfFormInputArrays) = each($totalFormInputs)) {
			while (list($idOfThisEntry, $formInputs) = each($arrayOfFormInputArrays)) {
				if (isset($formInputs["tags"])) {
					$stringOfTags = $formInputs["tags"];
					unset($formInputs["tags"]);
					
					// 06-17-08 - we can not create tags without first knowing which
					// entry they will belong to. If $idOfThisEntry is set, then
					// we know which entry to modify, but if it is not set, then
					// we must create it. 
					if (!$idOfThisEntry) {
						// 06-17-08 - I am surprised that I need to add in this
						// next line, yet without it, we end up with both the zero
						// index, and the new index whose id we are about to invent.
						// And the zero index retains the "tags" key, and so throws
						// an error when we try to send it to the database. 
						unset($arrayOfFormInputArrays[0]);
						$arrayForNewCommunityPost = array(); 
						$arrayForNewCommunityPost["time"] = time(); 
						$arrayForNewCommunityPost["id_users"] = $controller->command("getIdOfLoggedInUser"); 
						$idOfThisEntry = $controller->command("createRecord", "community_post", $arrayForNewCommunityPost); 
					}
	
					$tagsArray = explode(",", $stringOfTags); 
					if(is_array($tagsArray)) {
						$query = "DELETE FROM tags WHERE belongsToRow='$idOfThisEntry' AND belongsToTable='community_post' ";
						$controller->command("makeQuery", $query, "createCommunityPost"); 

						for ($i=0; $i < count($tagsArray); $i++) {
							$thisTag = $tagsArray[$i]; 
							$thisTag = trim($thisTag); 
							if ($thisTag) {
								$thisTag = addslashes($thisTag); 
								$query = "INSERT INTO tags (tag, belongsToRow, belongsToTable) VALUES ('$thisTag', '$idOfThisEntry', 'community_post') ";    
								$controller->command("makeQuery", $query, "processTagsForCommunityPosts"); 
							}
						}
					}
				}
				$arrayOfFormInputArrays[$idOfThisEntry] = $formInputs;
			}
			$totalFormInputs[$whichDatabaseTable] = $arrayOfFormInputArrays;
		}
		reset($totalFormInputs); 
	}

	return $totalFormInputs;
}



