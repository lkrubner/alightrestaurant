<?php



function sendEmailToThisUser($formInputs=false, $fromTsr=false) {
	// 06-07-08 - this code is being pulled out of sendEmailToWho and set
	// up as its own function so that it can also be called from sendMessageToAllUsers
        //
        // 01-11-09 - we need for this function to use contact@thesecondroad.org as a return address
        // so we are adding in a new parameter. When called from sendMessageToAllUsers, we will
        // use a different return address. 

	global $controller; 


	if (is_array($formInputs)) {
		extract($formInputs);
		
		$query = "SELECT email_address FROM users WHERE id='$send_email_to_who_id' "; 
		$result = $controller->command("makeQuery", $query, "sendEmailToWho"); 
		$row = $controller->command("getRow", $result); 
		$email = $row["email_address"];
		
		//11-13-08 - we are making changes to processEmailToGroupOwner that allow
		// it to send rich HTML. That function creates all of the HTML, so here
		// we simply have to send the correct headers. We are adding a flag
		// to $controller to indicate that we should use rich HTML here.   
		$flagToTriggerRichHtml = $controller->retrieve("flagToTriggerRichHtml"); 
		
		
		if ($flagToTriggerRichHtml) {			
			$to = $email; 
			// To send HTML mail, the Content-type header must be set
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= "To: $email" . "\r\n";
                        if ($fromTsr) {
        			$headers .= 'From: contact@thesecondroad.org' . "\r\n";
                        } else {
        			$headers .= 'From: doNotReply@thesecondroad.org' . "\r\n";
                        }
			
			if (!$subject) $subject = "You've been sent a message from the Second Road website";
			$message = stripslashes($description); 
			$success = mail($to, $subject, $message, $headers);
		} else {
			if ($email) {
				if ($id_users) {
					$sendersInfoArray = $controller->command("getEntry", "users", $id_users); 
					$username = $sendersInfoArray["username"];
					$userSecurityLevel = $sendersInfoArray["security_level"];
	
					// 05-15-08 - let's make the domain name context sensitive
					$config = $controller->getVar("config");
					$domainName = $config["domainName"];
					$urlToVisit = "http://www." . $domainName . "/mypage.php?id=$id_users";
					$urlToBlock = "http://www." . $domainName . "/profile_form.php?blockMessagesFromThisMember=$id_users&formName=block_messages_from_this_member.htm";
					
					// 05-21-08 - laura sends me this in an email: 
					//
					// 		it'd be even better if you were directed to the exact email message in your inbox.
					// 		so taht you could simply reply.
					//
					// so i'm changing the reply email 
					if (!$replyTo) $replyTo = mysql_insert_id(); 
	
	
					// 08-17-07 - a return address should look like this: 
					//
					// http://www.thesecondroad.org/profile.php?id=5&formName=send_me_a_message.htm
					$responseUrl = "http://www." . $domainName . "/my_private_page.php?formName=mp_inbox_send_a_message.htm&id=$id_users&replyTo=$replyTo";
					if ($userSecurityLevel != "admin") { 
						$responseText = "
	You can respond to this message here: $responseUrl 
	
	
	If you'd like to learn more about $username, go here: 
	
	$urlToVisit
	
	
	If you'd like to block further messages from this member, go here: 
	
	$urlToBlock
						"; 
					} else {
						$responseText = "\n You can respond to this message here: $responseUrl \n";
					}
				}
	
	
	
	
				$title = stripslashes($title); 
				$description = stripslashes($description); 
				$to      = $email;
				if (!$subject) $subject = "You've been sent a message from the Second Road website";
				$message = "
	$username sends you this message:
	
	-------------------------
	
	$title
	
	$description
	
	------------------------
	
	$responseText
				";

				if ($fromTsr) {
					$headers .= 'From: contact@thesecondroad.org' . "\r\n";
				} else {
					$headers .= 'From: doNotReply@thesecondroad.org' . "\r\n";
				}

				
				
				
				$success = mail($to, $subject, $message, $headers);


				if ($success) {					
					$userInfoArray = $controller->command("getUserInfo"); 
					$userSecurityLevel = $userInfoArray["security_level"];
					if ($userSecurityLevel == "admin") {
						$controller->addToResults("We just sent an email to $to"); 	
					}
				}
				
			} else {
	// 03-19-08 - Melissa thinks this message is a mistake. 
	//			$controller->addToResults("We're sorry, but we have no email address on record for that member."); 
				$controller->error("We're sorry, but we have no email address on record for that member."); 
			}
		}
	}
	
	return $success;
}



?>