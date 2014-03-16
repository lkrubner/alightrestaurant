<?php



function createCaptcha($justTheNumber=false) {
	// 07-07-06 - we have a problem on Accumulist: we are getting too much in the way of spam comments.
	// The situation is out of control. I don't want to make it difficult for people to post comments, but
	// we need some kind of captcha to help the situation. My feeling is that we can get buy with weak
	// captchas till we are bigger and targeted by more aggressive spammers. So for now, we will just
	// generate a number and ask people to type it in. 
	//
	// 03-01-07 - THis function was further developed by Lawrence and Starrie. First the global value of 
	// $visitorObject is gathered and then assigned to the array "$storeArray". The variable "visitorId"
	// is set to the "visitorId" item in the "$storeArray". Then, a random number and date is generated,
	// and assigned to their respective variables. We assign the string variable "$query" (which includes mySQL
	// terminology which queries --SW
	// 
	//
	// 09-15-07 - Starrie and Lawrence created this function for Accumulist. - LK
	//
	// 
	// 06-08-08 - we have a problem: we've begun to allow journal posts that are open to the whole
	// World Wide Web:
	// 
	// http://www.cyberbitten.com/weblog.php?editId=139
	//
	// The original createCaptcha code was not set up for this purpose - it was instead set up to offer
	// a check when people first signup here: 
	// 
	// http://www.cyberbitten.com/signup.php
	//
	// I'm pulling all the code related to the image out into a separate function. 
        //
	// 2013-10-21 - nowadays I prefer to do this as 2 numbers which must be correctly added together.
	// We store the info here:
	//
	//   CREATE TABLE `captcha` (
        //      `id` int(11) NOT NULL AUTO_INCREMENT,
        //      `created_at` datetime DEFAULT NULL,
        //      `session` varchar(255) DEFAULT '',
        //      `captcha` varchar(255) DEFAULT '',
        //      PRIMARY KEY (`id`)
        //   ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8
        //
	
	
	global $controller; 
	
	$randomNumber1 = rand(1, 10);
	$randomNumber2 = rand(1, 10);
	$correctAnswer = $randomNumber1 + $randomNumber2; 
	$date = date('Y-m-d h:i:s'); 
	$uniqueMachineId =  $_COOKIE["machineId"];
       
	$query = "Insert into captcha (created_at, session, captcha) values ('$date', '$uniqueMachineId', '$correctAnswer')  ";
	$result = $controller->command("makeQuery", $query, "createCaptcha");
	if (!$result) {
		$controller->error("There was a problem in createCaptcha. The query did not go through. The query was '$query'"); 
		return false;	
	}
		
	return array($randomNumber1, $randomNumber2); 
}


