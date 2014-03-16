<?php



function requestFriendship() {
	// 05-27-08 -  this is deprecated and should no longer be used
// replace by processFriendshipRequest

	// 12-06-07 - this is the table we are inputing into: 
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
	//		<input type="hidden" name="choiceMade[]" value="requestFriendship" />
	//		<input type="hidden" name="formInputs[id_of_whose_friendship_is_being_requested]" value="20" />
	//		<input type="hidden" name="formInputs[time]" value="1197511816" />
	//		
	//		</form>

	global $controller; 

	$userId = $controller->command("getIdOfLoggedInUser"); 

	if (!$userId) {
		$controller->addToResults("You must be logged in to request to be friend's with someone."); 
		return false; 
	}


	$formInputs = $controller->getVar("formInputs"); 

	if (is_array($formInputs)) {
		// 12-12-07 - let's make sure the form was correctly filled in when the 
		// request was made. 
		$id_of_whose_friendship_is_being_requested = $formInputs["id_of_whose_friendship_is_being_requested"];
		if (!$id_of_whose_friendship_is_being_requested ) {
			$controller->addToResults("Sorry, but it wasn't clear who you wanted to be friends with. Please navigate back to the person's page and make another request."); 
			return false; 
		}


 		$formInputs["id_of_user_requesting_friendship"] = $userId; 
		$controller->command("createRecord", "pending_friend_requests", $formInputs); 


		// 12-12-07 - let's get the name of the user whose friendship is being requested. 
		$entry = $controller->command("getEntry", "users", $id_of_whose_friendship_is_being_requested); 
		$username = $entry["username"];
		$controller->addToResults("$username will be notified of your request to be a friend."); 
	}
}



?>