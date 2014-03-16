<?php



function logout() {
	// 05-17-08 - wow, this function needs to be re-written. First of all, I
	// was not logged out when I went here: 
	// 
	// http://www.cyberbitten.com/my_private_page.php?choiceMade[]=logout
	//
	// Second of all, I do not like how this code references stuff like 
	// the session_id and the $_COOKIE["machineId"]. I want all that isolated
	// to getIdOfLoggedInUser, and then I want to use that function for everything. 
	// 
	// 
	// 01-15-07 - all content management systems need a user system, and therefore
	// a way to log users in and out of the system. Although most CMS have special 
	// requirements for user accounts, this function may server as a good starting 
	// point for others to customize their own functions. We will assume the 
	// existence of a table called users_logged_in. This function is often called by
	// clicking on a link where the url looks like this: 
	//
	// index.php?choiceMade[]=logout

	
	global $controller; 


	// 05-17-08 - okay, apparently sometimes it is not that simple. I deleted
	// a block that uses the machineId to delete people, maybe that
	// is the block that normally works. 
	$machineId = $controller->getVar("machineId"); 
	$query = "DELETE FROM users_logged_in WHERE  machineId='$machineId'";
	$result = $controller->command("makeQuery", $query, "userLoginUpdate"); 

	// 01-16-07 - apparently this next line returns nothing when deleting
	//	$numberOfRowsUpdated = mysql_affected_rows($result); 
	unset($_SESSION["id_users"]);
	unset($_SESSION["username"]);
	unset($_SESSION["first_name"]);
	unset($_SESSION["email"]);

	if ($numberOfRowsUpdated) {
		$controller->addToResults("You are now logged out"); 
	} else {
		// 01-16-07 - apparently mysql_affected_rows doesn't work with deletes, so this was not
		// working. 
		//$controller->error("In the function logout, for some reason we could not log out. "); 
	}
}



