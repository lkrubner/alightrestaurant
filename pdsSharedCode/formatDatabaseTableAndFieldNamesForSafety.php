<?php



function formatDatabaseTableAndFieldNamesForSafety($name=false) {
	// 01-09-07 - as I watch people use the scaffolding, I see that the most common mistake
	// they make is that they put spaces into their field names. We need some basic error
	// chhecking on database table names and field names. This function will check for open
	// spaces and quotes and other possible mistakes. 

	// 04-10-07 - this next line, strtolower, was meant to ensure that users
	// never did something stupid, like use uppercase letters for a database
	// table name. However, I'm now trying to use the framework to build a 
	// digital download store around the database tables used by CubeCart, and
	// CubeCart uses uppercase letters in its database tables names, so this
	// next line is simply causing bugs, in particular mismatches between the
	// actual database table name and the way the HTML forms reference it.
	// So I'm commenting this next line out. 
	//
	// $name = strtolower($name);

	$name = str_replace(" ", "_", $name);
	$name = str_replace("'", "_", $name);
	$name = str_replace("\"", "_", $name);
	$name = str_replace(":", "_", $name);
	$name = str_replace("<", "_", $name);
	$name = str_replace(">", "_", $name);
	$name = str_replace("?", "_", $name);
	$name = str_replace("%", "_", $name);
	$name = str_replace("!", "_", $name);
	$name = str_replace("@", "_", $name);
	$name = str_replace("#", "_", $name);
	$name = str_replace("$", "_", $name);
	$name = str_replace("^", "_", $name);
	$name = str_replace("&", "_", $name);
	$name = str_replace("*", "_", $name);
	$name = str_replace("(", "_", $name);
	$name = str_replace(")", "_", $name);


	$name = str_replace("|", "_", $name);
	$name = str_replace("/", "_", $name);
	$name = str_replace("{", "_", $name);
	$name = str_replace("}", "_", $name);
	$name = str_replace("=", "_", $name);
	$name = str_replace("-", "_", $name);
	$name = str_replace(";", "_", $name);
	$name = str_replace("`", "_", $name);

	$name = str_replace("__", "_", $name);
	$name = str_replace("__", "_", $name);
	$name = str_replace("__", "_", $name);

	
	// 05-13-07 - I'm adding in the next 5 lines of code so we can avoid the errors
	// that arise when people accidentally use reserved MySql keywords as field 
	// names. Read the comments in checkDatabaseFieldNamesToBeSureTheyAreAllowed for
	// more info.
	global $controller;	
	$wasSafe = $controller->command("checkDatabaseFieldNamesToBeSureTheyAreAllowed", $name); 
	
	if (!$wasSafe) {
		// 05-13-07 - if the name was a reserved keyword then we change the name
		// by adding "rk_" to the beginning. This should make the name safe. 
		$name = "rk_".$name; 	
	}
	
	
	return $name; 
}



?>