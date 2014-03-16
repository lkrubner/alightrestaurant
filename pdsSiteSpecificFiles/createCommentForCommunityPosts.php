<?php



function createCommentForCommunityPosts() {
	// 08-14-07 - this ensures that each comment has the user name and data. 
	// In on this page:
	//
	// 	http://www.thesecondroad.org/posts.php?id=19
        // 
        //  called from this form:  
        // 
        // 		<form method="post" action="posts.php?id=< ?php echo $controller->getVar("id"); ? >">
        // 			<h3>Add your comment</h3>
        // 
        // 			<p><textarea name="totalFormInputs[community_comment][0][main_text]"></textarea></p>
        // 				
        // 			<p><input type="submit" value="Comment" /></p>
        // 			<input type="hidden" name="choiceMade[]" value="createCommentForCommunityPosts" />
        // 			<input type="hidden" name="formName" value="community_comment_edit_Form.htm" />
        // 			<input type="hidden" name="totalFormInputs[community_comment][0][id_community_post]" value="< ?php echo $controller->getVar("id");  ? >" />		
        // 			<input type="hidden" name="id" value="< ?php echo $controller->getVar("id"); ? >" />
	//		</form>


        // 2013-11-23 - I am copying this file over from TSR so I have an example to work with.

					
	global $controller; 

	
	$userId = $controller->command("getIdOfLoggedInUser"); 
	if (!$userId) {
		$controller->addToResults("You must be logged in to post a comment."); 
		return false;
	}	
	
	
	if (!$totalFormInputs) $totalFormInputs = $controller->getVar("totalFormInputs"); 
	
	if (is_array($totalFormInputs)) {
		while (list($whichDatabaseTable, $arrayOfFormInputArrays) = each($totalFormInputs)) {
			while (list($idOfThisEntry, $formInputs) = each($arrayOfFormInputArrays)) {
				// 04-17-07 - I could be wrong, but with all the other changes we've
				// made to this software, don't we want to call updateRecord(), as 
				// oppossed to createRecord? updateRecord will look in the $_POST
				// vars and $_GET to see if there is an id for the current input, 
				// and if it doesn't find such an id, it'll hand the input off 
				// to createRecord to take care of. 
				//
				// $controller->command("createRecord", $whichDatabaseTable, $formInputs);
	
				
				$formInputs["timestamp"] = time();
				$formInputs["id_users"] = $userId;
				$entry = $controller->command("getEntry", "users", $userId); 
				$formInputs["username"] = $entry["username"];
				$formInputs["id"] = $idOfThisEntry;
				$controller->command("updateRecord", $whichDatabaseTable, $formInputs);
			}
		}
	}
	
	
	// 02-15-08 - now lets send an email to the owner of the post, letting them
	// know someone just posted a comment. 
	$idOfCommunityPost = $controller->getVar("id"); 
	$sharingWallEntry = $controller->command("getEntry", "community_post", $idOfCommunityPost); 
	$id_users = $sharingWallEntry["id_users"];	
	$userEntry = $controller->command("getEntry", "users", $id_users); 

	if (is_array($userEntry)) {	
		
		// 05-15-08 - let's make the domain name context sensitive
		$config = $controller->getVar("config");
		$domainName = $config["domainName"];
		$urlToVisit = "http://www." . $domainName . "/posts.php?id=$idOfCommunityPost";	



		$to      =  $userEntry["email_address"]; 
		$subject = 'Someone has posted a comment to your Sharing Wall post on The Second Road website';
		$message = "
Someone has posted a comment to your Sharing Wall post on The Second Road website.

You can read their comment here: 

$urlToVisit
		
		";
		$headers = 'From: contactus@thesecondroad.org' . "\r\n" .
		'Reply-To: contactus@thesecondroad.org' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();
		
		
		$success = mail($to, $subject, $message, $headers);
		if ($success) {
			$controller->addToResults("The person who composed this Sharing Wall post will be notified of your comment."); 	
		} else {
			$controller->error("Unable to send email in createCommentForCommunityPost."); 				
		}
	}
}



?>