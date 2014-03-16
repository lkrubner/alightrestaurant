<?php



function loop($resourcePointerToDatabaseResult=false, $nameOfActionToBeUsedToProcessEachRow=false, $param1=false, $keyForStorageInSingleton=false) {
	// 03-31-07 - I wasn't able to get loops to work inside of loops, especially on this page:
	//
	// http://www.monkeyclaus.org/media/audio/store2.php
	//
	// So I'm removing the contents of this function and moving it to an object
	// called loopDatabase. I'm hoping that multiple loops will allow less 
	// conflict between what each loop is trying to do, while it runs inside
	// another loop.
		
	global $controller; 
	$loopObject = $controller->getObject("loopDatabase", "loop", "makeNewObject");
	$howManyItemsWereThere = $loopObject->run($resourcePointerToDatabaseResult, $nameOfActionToBeUsedToProcessEachRow, $param1, $keyForStorageInSingleton);
	return $howManyItemsWereThere;
}



?>