<?php



function createTemporaryFileForCustomerToConsume() {

	$randomNumber = rand(0, 100000);
	$fileName = $formInputs[x_description];
	$fileName = trim($fileName); 
	$tempFileName = $randomNumber."_".$fileName; 
	$pathToFile = "protected_files/$fileName"; 



	$pathToTempFile = "./temporary_files/$tempFileName"; 

	if (!copy($pathToFile, $pathToTempFile)) {
		$controller->addToResults("failed to copy $file...\n");
	} else {
		$controller->addToResults("<p><a href=\"download.php?fileToBuy=$tempFileName\">You can download $fileName here</a></p>");
	}

}



?>