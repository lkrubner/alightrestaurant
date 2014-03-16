<?php



function processEmailToGroupOwner($totalFormInputs=false) {
	// 05-16-08 - Ginger wrote to me and complained that she was getting
	// invitations that didn't tell her which group a person was trying
	// to join. I'm adding in this function, which will be triggered by
	// the invitation form, as a variable that gets sent to 
	// createRecordForMultipleDatabaseTables, which will use this 
	// function to add the group name to the message going to the 
	// group owner.


	global $controller; 

	// 05-27-08 - who the hell is making this request? We should find out.
	$userId = $controller->command("getIdOfLoggedInUser"); 
	if (!$userId) {
		return false;
	}

	// 05-27-08 - it would be cool to get the name of the user who is making the request
	// and to include a link to their personal page. 
	$arrayOfInfoAboutTheRequestingUser = $controller->command("getEntryWithTrust", "users", $userId); 
	$username = $arrayOfInfoAboutTheRequestingUser["username"];
	$config = $controller->getVar("config"); 
	$domainName = $config["domainName"];
	$urlOfTheRequestingUser = "http://www." . $domainName . "/mypage.php?id=" . $userId;

	$groupId = $controller->getVar("groupId"); 
	$groupInfoArray = $controller->command("getEntryWithTrust", "chat_rooms", $groupId); 
	$groupName = $groupInfoArray["name"];

    $urlToAddMember = "http://www." . $domainName . "/my_private_page.php?formName=mp_groups.htm&editId=" . $groupId . "&choiceMade[]=addMemberToGroup&memberName=$username&id_chat_rooms=$groupId&nameOfGroup=$groupName";
	$urlToGroup = "http://www.$domainName/groups_chat.php?groupId=$groupId";
    $pathToRequestingUsersImage = $controller->command("showUserImage","", $userId);
    
	if (is_array($totalFormInputs)) {
		while (list($whichDatabaseTable, $arrayOfFormInputArrays) = each($totalFormInputs)) {
			while (list($idOfThisEntry, $formInputs) = each($arrayOfFormInputArrays)) {
				$description = $formInputs["description"];
				$description = "
				
				
				
				

<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'> 
<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en'> 
<head> 
<title>Message from The Second Road</title>  
</head> 
<body> 
<table WIDTH=660 BORDER=0 CELLSPACING=0 CELLPADDING=0 style='font-family:Arial, Sans-serif; font-size:12px;'> 
	<tr VALIGN=Middle>
		<td colspan=1 WIDTH=100 style='margin:0px 10px 10px 0px; '>
			<a style='color:#0410fa; font-size:10px; font-weight:bold; text-decoration:none;' href='$urlOfTheRequestingUser'><img style='border:none;' id='user_img' src='$pathToRequestingUsersImage' alt='user image' /></a> 
		</td>
		
		<td>
			&nbsp;
		</td>	
			  
		<td colspan=2 WIDTH=400 style='padding:10px;'> 
			<p>Hi, this is <a style='color:#0410fa; font-size:10px; font-weight:bold; text-decoration:none;' href='$urlOfTheRequestingUser'>$username</a> from The Second Road community. 
			I would like to join your group <a style='color:#0410fa; font-size:10px; font-weight:bold; text-decoration:none;' href='$urlToGroup'>$groupName</a>.</p> 
			
			<p>Click my pic to learn more about me</p> 
		</td>
		
		<td>
			&nbsp;
		</td>	
			  
		<td colspan=1  VALIGN=Top Align=Right style='margin:0px 0px 10px 10px;'> 
			 <a style='color:#0410fa; font-size:10px; font-weight:bold; text-decoration:none;' href='www.$domainName'><img style='border:none;' src='http://www.$domainName/graphics/images/tsr_logo.jpg' alt='The Second Road' /></a> 
	 	</td>
	</tr>
	 
	<tr BGCOLOR='#f8f0db' VALIGN=Middle style='margin:15px 0px 0px 0px'> 
		<td colspan=6 style='padding:10px;'>
			$username sends this message: 
			  
			<p>$description</p>
			  
			<p><a style='color:#0410fa; font-size:10px; font-weight:bold; text-decoration:none;' href='$urlToAddMember'>ADD THIS MEMBER</a> <a style='margin:0px 0px 0px 20px; color:#0410fa; font-size:10px; font-weight:bold; text-decoration:none;' href='http://www.$domainName/my_private_page.php?formName=mp_inbox_send_a_new_message.htm&id=$userId'>REPLY TO MESSAGE</a></p> 
			<p>This is a request to join the $groupName group.</p> 
			
			<p><a style='color:#0410fa; font-size:10px; font-weight:bold; text-decoration:none;' href='http://www.$domainName/my_private_page.php?formName=block_messages_from_this_member.htm&blockMessagesFromThisMember=$userId'>BLOCK MESSAGES FROM THIS MEMBER</a></p> 
		</td>
	</tr>
					
	<tr VALIGN=Middle>
		<td COLSPAN=6>
			<a style='color:#0410fa; font-size:10px; font-weight:bold; text-decoration:none;' href='http://www.$domainName/groups.php'><img style='border:none;'  src='http://www.cyberbitten.com/graphics/EMAIL_chat.gif' alt='Chat' /></a>
		
			<a style='color:#0410fa; font-size:10px; font-weight:bold; text-decoration:none;' href='http://www.$domainName/my_private_page.php'><img style='border:none;'  src='http://www.cyberbitten.com/graphics/EMAIL_profile.gif' alt='Profile' /></a>
		
			<a style='color:#0410fa; font-size:10px; font-weight:bold; text-decoration:none;' href='http://www.$domainName/videos.php'><img style='border:none;'  src='http://www.cyberbitten.com/graphics/EMAIL_videos.gif' alt='Videos' /></a>
		
			<a style='color:#0410fa; font-size:10px; font-weight:bold; text-decoration:none;' href='http://www.$domainName/sharing_wall.php'><img style='border:none;'  src='http://www.cyberbitten.com/graphics/EMAIL_sharingwall.gif' alt='Sharing Wall' /></a>
		
			<a style='color:#0410fa; font-size:10px; font-weight:bold; text-decoration:none;' href='http://www.$domainName/members_list.php'><img style='border:none;'  src='http://www.cyberbitten.com/graphics/EMAIL_members.gif' alt='Members' /></a>
		
			<a style='color:#0410fa; font-size:10px; font-weight:bold; text-decoration:none;' href='http://www.$domainName/forum.php'><img style='border:none;'  src='http://www.cyberbitten.com/graphics/EMAIL_forums.gif' alt='Forums' /></a>
		</td>
	</tr>	
</table>
</body> 
 </html> 

				";
				$formInputs["description"] = $description;
				
				// 11-13-08 - adding in this next flag, which is meant to trigger rich HTML email  
				// in sendEmailToWho(). 
				$controller->carry("flagToTriggerRichHtml", true); 

				$arrayOfFormInputArrays[$idOfThisEntry] = $formInputs;
			}
			$totalFormInputs[$whichDatabaseTable] = $arrayOfFormInputArrays;
		}
		reset($totalFormInputs); 
	}
	return $totalFormInputs;
}



?>