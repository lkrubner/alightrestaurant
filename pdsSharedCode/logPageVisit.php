<?php



function logPageVisit() {
	// 06-29-07 - for now this function will be called at the top of 
	// the config file. It inserts into the database some information
	// about each page visit. Because it does an INSERT, it could slow 
	// our software down. Because of that, we might have to move this to
	// the bottom of our pages, eventually. I'm actually only putting it
	// in config because so many other people on iHanuman are constantly
	// changing other files, I fear overwriting their work. config.php
	// is the one file that no one touches but me. 
	//
	// In the database, we've this table: 
	//
	//		The table is 'visitors'
	//			id    int(11)       PRI       auto_increment
	//			date    int(11)          0   
	//			ipAddress    varchar(15)            
	//			hostname    varchar(255)            
	//			machineId    varchar(255)            
	//			referrals    varchar(255)            
	//			domain    varchar(255)            
	//			fileName    varchar(255)            
	//			queryString    varchar(255)            
	//			userId    int(11)          0   
	//			documentRoot    varchar(255)            				
	//			host    varchar(255)            
	//			userAgent    varchar(255)  
	//
  	// We wish to gather this info and insert it. userId should be in $_SESSIONs.
  	// machineId is a random string we generate and then send as a cookie, if it 
  	// isn't already set. date is a Unix timestamp. 'domain' had meaning for 
  	// Accumulist, but probably has no meaning here. hostname is the host of the 
  	// visitors server, if the value can be captured. 
  	
  	global $controller;

  	// 10-23-07 - we really don't want Ajax calls to api.php getting logged. Among
  	// other things, quotes in the query string cause errors. 
  	$pageName = basename($_SERVER["PHP_SELF"]);
  	if ($pageName == "api.php") {
	 	return false;  	
  	}
  	
  	
  	  	
  	$date = time(); 
  	$ipAddress = $_SERVER["REMOTE_ADDR"];
  	$hostname = $_SERVER["REMOTE_HOST"];
  	
  	$machineId =  $_COOKIE["machineId"];
	if (!$machineId) {
		if (!headers_sent()) {
			$machineId = md5(uniqid(rand()));
			$success = setcookie("machineId", $machineId, time() + 10000000);	
		} else {
			$controller->error("In logPageVisit() we wanted to set a machine id but the headers were already sent."); 
		}
	}

	$session_id	= session_id();
	$referrals = $_SERVER["HTTP_REFERER"];
	$domain = $_SERVER["SERVER_NAME"];
	$fileName = $_SERVER["SCRIPT_FILENAME"];  
	$queryString = $_SERVER["QUERY_STRING"];
	$userId = $controller->command("getIdOfLoggedInUser");
	$documentRoot = $_SERVER["DOCUMENT_ROOT"];
	$host = $_SERVER["HTTP_HOST"];
  	$userAgent = $_SERVER["HTTP_USER_AGENT"];

	// 06-10-08 - let's track front page templates page visits
	$storedFrontPageFileName = $_COOKIE["storedFrontPageFileName"]; 
  	
  	$stringOfColumnNames = " (date, ipAddress, hostname, machineId, referrals, domain, fileName, queryString, userId, documentRoot, host, userAgent, session_id, front_page_template) ";
	$stringOfValues = " ('$date', '$ipAddress', '$hostname', '$machineId', '$referrals', '$domain', '$fileName', '$queryString', '$userId', '$documentRoot', '$host', '$userAgent', '$session_id', '$storedFrontPageFileName') ";
  	
	$query = "INSERT INTO visitors $stringOfColumnNames VALUES $stringOfValues ";	
  	
	$result = $controller->command("makeQuery", $query, "logPageVisit"); 
}



?>