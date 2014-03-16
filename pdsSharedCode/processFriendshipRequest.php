<?php



function processFriendshipRequest($totalFormInputs=false) {
	// 05-27-08 - this is the table we are inputing into: 
	//
	//		CREATE TABLE `pending_friend_requests` (
	//		`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	//		`time` INT NOT NULL ,
	//		`id_of_whose_friendship_is_being_requested` INT NOT NULL ,
	//		`id_of_user_requesting_friendship` INT NOT NULL
	//		) ENGINE = MYISAM ;
	//
	// This function is being called from the input from pages such as this: 
	//
	// http://www.cyberbitten.com/profile_form.php?id=5&formName=request_friendship.htm
	//
	// The user requesting friendship, and the request must be stored till
	// the user whose friendship has been requested either approves or denies it. 
	//
	// This is the form that triggers this function: 
	//
	//		<form method="post" action="profile_public.php?id=20">
	//		<p>Would you like to ask laura to be your friend?</p>
	//		
	//		<p><input type="submit" value="Ask" /></p>
	//		
	//		<input type="hidden" name="totalFormInputs[pending_friend_requests][0][id_of_whose_friendship_is_being_requested]" value="158" />
	//		<input type="hidden" name="totalFormInputs[pending_friend_requests][0][id_of_user_requesting_friendship]" value="5" />
	//		<input type="hidden" name="totalFormInputs[pending_friend_requests][0][time]" value="1211954841" />
	//		<input type="hidden" name="processInputWithTheseFunctions[]" value="processFriendshipRequest" />
	//		<input type="hidden" name="choiceMade[]" value="processInput" />
	//		
	//		</form>
	//
	//
	// The only need for this function is to check and see if two people are
	// already friends - they don't need to become friends again. 

	global $controller; 

	if (is_array($totalFormInputs)) {
		reset($totalFormInputs); 
		while (list($whichDatabaseTable, $arrayOfFormInputArrays) = each($totalFormInputs)) {
			if ($whichDatabaseTable == "pending_friend_requests") {
				while (list($idOfThisEntry, $formInputs) = each($arrayOfFormInputArrays)) {
					$alreadyFriends = false; 
					$id_of_whose_friendship_is_being_requested  = $formInputs["id_of_whose_friendship_is_being_requested"];
					$id_of_user_requesting_friendship  = $formInputs["id_of_user_requesting_friendship"];
					$query = "SELECT id FROM friends WHERE (user_who_requested_friendship_id='$id_of_user_requesting_friendship' && user_who_accepted_friendship_id='$id_of_whose_friendship_is_being_requested') OR (user_who_requested_friendship_id='$id_of_whose_friendship_is_being_requested' && user_who_accepted_friendship_id='$id_of_user_requesting_friendship') ";
					$result = $controller->command("makeQuery", $query, "processFriendshipRequest"); 
					if (!$result) return $totalFormInputs;
	
					// 12-12-07 - let's get the name of the user whose friendship is being requested. 
					$entry = $controller->command("getEntry", "users", $id_of_whose_friendship_is_being_requested); 
					$username = $entry["username"];
					if (mysql_num_rows($result) > 0) {
						$controller->addToResults("$username is already a friends of yours!"); 
						$alreadyFriends = true; 
					} else {
						$controller->addToResults("$username will be notified of your request to be a friend."); 
					}
				}				
			}
			if ($alreadyFriends) unset($totalFormInputs[$whichDatabaseTable]);
		}
		reset($totalFormInputs); 
	}

	return $totalFormInputs;
}



