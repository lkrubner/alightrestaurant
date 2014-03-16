<?php



function checkCaptcha($answerGivenByUser) {
  // 2013-10-21 - I just made some changes to isThisCaptchaValid and now i'm changing this
  // to match those changes. This is the table: 
  //
  //  CREATE TABLE `captcha` (
  //			  `id` int(11) NOT NULL AUTO_INCREMENT,
  //			  `created_at` datetime DEFAULT NULL,
  //			  `session` varchar(255) DEFAULT '',
  //			  `captcha` varchar(255) DEFAULT '',
  //			  PRIMARY KEY (`id`)
  //			  ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8
  //

	global $controller; 

	$machineId = $controller->getVar("machineId"); 
	$query = "select * from captcha where session='$machineId' order by id desc limit 1";  
	$result = $controller->command("makeQuery", $query, "checkCaptcha"); 	

	if ($result) { 
	  $howManyRows = mysqli_num_rows($result); 
	  if ($howManyRows > 1) $controller->error("In checkCaptcha, we got back $howManyRows rows when we ran this query: $query", "checkCaptcha");

	  $row = $controller->command("row", $result, "checkCaptcha"); 
	  if ($answerGivenByUser == $row['captcha']) {
	    return array('correct', $row['captcha']);
	  } else {
	    return array('incorrect', $row['captcha']);
	  }
	}
}



