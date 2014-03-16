<?php



function newPara($text="") {
	// 09-27-06 - Lisa McCade named this function, what she and I regard as an intuitive name.
	// It's just a wrapper for nl2br.
	$text = nl2br($text);
	return $text; 
}



?>