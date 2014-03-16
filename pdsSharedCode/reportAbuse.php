<?php



function reportAbuse() {
	// 08-14-07 - receives the  form input on this page: 
	//
	// http://www.thesecondroad.org/report_abuse.php?id=14

	global $controller; 
	
	$formInputs = $controller->getVar("formInputs"); 
	if (is_array($formInputs)) {
		while(list($key,$val) = each($formInputs)) {
			$formInputs[$key] = addslashes($val); 	
		}
		
		extract($formInputs); 
		
		$userInfoArray = $controller->command("getEntry", "users", $id_users); 
		$username = $userInfoArray["username"];
		$email = $userInfoArray["email_address"];
		
		$to      = 'contactus@thesecondroad.org';
		$subject = 'A Report Of Abuse From The Second Road Website';
		$message = "
		An abuse report was written about this post: http://www.thesecondroad.org/posts.php?id=$id_community_post
		
		The user with the name '$username' (with the email address of '$email') wrote the following complaint about the above post: 
		
		$description
		
		";
		$headers = 'From: contactus@thesecondroad.org' . "\r\n" .
		    'Reply-To: contactus@thesecondroad.org' . "\r\n" .
		    'X-Mailer: PHP/' . phpversion();
		
		$success = mail($to, $subject, $message, $headers);
		
		if ($success) {
			$controller->addToResults("Your abuse report has been sent to the administrators. Thank you for your help."); 	
		} else {
			$controller->addToResults("We're sorry, there has been error. We were unable to send your abuse report to the administrators."); 
		}
		
		$time = time(); 
		$query = "INSERT INTO abuse_reports (description, id_users, id_community_post, time) VALUES ('$description', '$id_users', '$id_community_post', '$time') "; 
		$result = $controller->command("makeQuery", $query, "reportAbuse");
		
		
	}
}



?>