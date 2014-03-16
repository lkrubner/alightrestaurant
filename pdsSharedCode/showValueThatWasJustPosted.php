<?php



function showValueThatWasJustPosted($nameOfSomeValue=false, $return=false) {
	// 11-06-06 - on forms like oneToManyForm2.htm I just want an easy way to capture values
	// that have just been posted so I can embed them in hidden inputs and thus make sure
	// they'll be available for the final input to some function that will do something 
	// with them. 

	global $controller; 

	$someValue = $controller->getVar($nameOfSomeValue); 

	if ($return) {
		return $someValue;
	} else {
		echo $someValue;
	}
}



?>