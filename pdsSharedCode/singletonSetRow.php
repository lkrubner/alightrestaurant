<?php



function singletonSetRow($row=false) {
	// 03-12-07 - this software uses a singleton object, called SingletonFormValues,
	// to hold values that need to be passed to partial templates that will be
	// rendered as PHP, and filled in with the correct values. The PHP commands
	// in those partial templates, when they look for values, look to 
	// SingletonFormValues. There are many places in the code where SingletonFormValues
	// needs to be set, so are creating this function to provide a simple, 
	// universally available way of doing so.

	global $controller;

	if (is_array($row)) {
		$singletonVaulesObject = & $controller->getObject("SingletonFormValues", "singletonSetRow"); 
		$singletonVaulesObject->set($row); 
	} else {
		$controller->error("In singletonSetRow, we expected to be given an array as the function argument, but we were not."); 
	}
}



?>