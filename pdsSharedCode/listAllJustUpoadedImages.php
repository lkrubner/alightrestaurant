<?php



function listAllJustUpoadedImages() {
	// 06-08-07 - I just wrote a very long comment in showFileInFormIfItExists about this
	// function, please read that comment for more info.
	
	global $controller;
	
	$singletonJustUploadedImagesObject = & $controller->getObject("SingletonAllJustUploadedImages", "uploadedImagesEach");

	$arrayOfAllJustUploadedImages = $singletonJustUploadedImagesObject->get();
	
	if (is_array($arrayOfAllJustUploadedImages)) {
		if (count($arrayOfAllJustUploadedImages) > 0) {
			for ($i=0; $i < count($arrayOfAllJustUploadedImages); $i++) {
				$imageName = $arrayOfAllJustUploadedImages[$i];
				$controller->command("showFileInFormIfItExists", $imageName);	
			}				
		} else {
			$controller->command("showFileInFormIfItExists");
		}
	} else {
		$controller->error("In listAllJustUploadedImages() we have failed to get an array back from SingletonAllJustUploadedImages"); 	
	}
}



?>