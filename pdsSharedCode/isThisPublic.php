<?php



function isThisPublic() {
	// 09-18-07 - used to create different css classes on view_my_private_journal.htm
	// 01-16-08 - also in use on user_private_journal_list_each_form.htm
	
	global $controller; 
	
	$private = $controller->command("currentValueFromLists", "private", "", "return"); 
	
	if ($private === "t") $answer = "private"; 
	if ($private === "f")  $answer =  "public"; 
	if ($private === "")  $answer =  "private"; 
	if ($private === "c")  $answer =  "confidantes"; 
	if ($private === "i")  $answer =  "confidantes"; 
	if ($private === " ")  $answer =  "private"; 
	if ($private === "A")  $answer =  "private"; 
	if ($private === "d")  $answer =  "forum"; 
	
	// 01-16-08 - adding in a catch-all answer. This should be accurate, so long
	// as we ensure that these entries default to private on pages like this:
	//
	// http://www.cyberbitten.com/profile_public.php?id=59
	if (!$answer) $answer = "private"; 
	
	return $answer;
}



?>