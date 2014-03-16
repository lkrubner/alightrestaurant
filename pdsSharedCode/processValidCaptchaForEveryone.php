<?php



function processValidCaptchaForEveryone($totalFormInputs=false) {
	//  09-07-08 - obviously, this function is based on processValidCaptcha(). 
	// However, processValidCaptcha doesn't bother to run if the member is logged 
	// in - we assume such people are safe. Yet they aren't. We had someone spam
	// all the members, apparently after logging in. We got this email:
        //                    
        //                             From: Bill O'Luanaigh <skepticbill@mac.com>
        //                               Hey there Melissa,
        //                               
        //                               We never got a chance to reconnect and I'm sorry to send you this as our
        //                               first 'contact' in a while. Looks like someone has figured a way to hijack
        //                               your email list. If not it sure 'feels' that way and folks are going to
        //                               wonder what is up.
        //                               
        //                               I'd call your tech guys and see if they can figure this one out.
        //
        //     I responded: 
        //
        //                               It looks to me like someone went to this page:
        //                              
        //                               http://www.thesecondroad.org/members_list.php?frontpage=frontpage_classic.htm&
        //                              
        //                               And then went down the list, sending a message to each person. Or,
        //                               possibly, then wrote a script to do exactly that.
        //                              
        //                               The person doing this doesn't have people's actual email addresses,
        //                               otherwise they would use their own software to send the messages.
        //                               Instead, these messages are being sent from TSR, as you can see from the
        //                               return address:
        //                              
        //                              > From: <doNotReply@thesecondroad.org>
        //
        //    I then suggested captcha, even if the person is logged in. Melissa agreed to that, so I'm now adding captcha to here:
        //
        //    http://www.cyberbitten.com/my_private_page.php?formName=mp_inbox_send_a_new_message.htm&id=404
	//
	//
	//
	//
	//
	//
	//
	//
	//
	//
	//
	// 06-09-08 - this is mostly for checking to see if comments are valid. In use here:
	//
	// http://www.cyberbitten.com/weblog.php?editId=139
	//
	// You'll only see the captcha input if you not logged in. 
	
	global $controller; 

	// 06-09-08 - this next line is incorrect. I'm in a hurry
	// so I'm doing it wrong. The correct way to do this would be
	// to loop through totalFormInputs looking for a specified database.
	$formInputs = current(current($totalFormInputs)); 
	$description = $formInputs["description"];

	$captcha = $controller->getVar("captcha"); 
	$valid = $controller->command("isThisCaptchaValid", $captcha); 

	if (!$valid) {
		$controller->addToResults("The number you typed in was not a valid number. Please try again."); 
		if ($description) $controller->addToResults("You wrote: '$description'"); 
		unset($totalFormInputs);
		// 06-14-08 - this next line is to carry some information to notifyJournalOwnerOfCommentToOneOfTheirPosts
		$controller->carry("processValidCaptcha", "invalid captcha"); 
	}
	
	return $totalFormInputs;
}



?>