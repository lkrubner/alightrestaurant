<?php



function addToUserMessages($whichArrangementToUse=false, $arrayOfInfoToBePassedToArrangement=false) {
	// 04-14-08 - For the past 3 or 4 years, I've used the method addToResults to
	// give feedback to users. This was a method of the global controller object.
	// The method had the advantage of being primitive and simple. However, 
	// something better has been needed for awhile. Now that the system 
	// supports internal messaging, we can simply add a message to the queue
	// that the user is suppose to eventually read. 
	//
	// This is the database table we use: 
	//				
	//			CREATE TABLE `send_me_a_message` (
	//				`id` int(11) NOT NULL auto_increment,
	//				`title` varchar(255) NOT NULL default '',
	//				`description` text NOT NULL,
	//				`time` int(11) NOT NULL default '0',
	//				`id_users` int(11) NOT NULL default '0',
	//				`id_send_me_a_message` int(11) NOT NULL default '0',
	//				`send_email_to_who_id` int(11) NOT NULL default '0',
	//				`receipient_has_read_this_how_many_times` int(11) NOT NULL,
	//				`is_this_message_blocked` char(1) NOT NULL,
	//				`status` varchar(255) NOT NULL,
	//				PRIMARY KEY  (`id`)
	//			) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

	global $controller; 

	$message = $controller->command("includeFileAndReturnString", $whichArrangementToUse, $arrayOfInfoToBePassedToArrangement); 

	$title = "A message from The Second Road";
	$description =  addslashes($message);
	$userId = $controller->command("getIdOfLoggedInUser");  
	$time = time(); 


	// 05-11-08 - we are hardcoding the value ""35" as the return value. That is the 
	// user id of "The second road staff". 
	$query = " 
		INSERT INTO send_me_a_message (title, description, time, id_users, send_email_to_who_id, status)
		VALUES ('$title', '$description', '$time', '35', '$userId', 'from TSR')
	";


	$controller->command("makeQuery", $query, "addToUserMessages"); 
}



?>