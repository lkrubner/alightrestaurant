<?php



function getMultimediaFilesInDropDownBox($ext1=false, $ext2=false, $ext3=false, $ext4=false, $ext5=false, $ext6=false) {
	// 04-09-07 - being called for the form on this page: 
	//
	// http://www.ihanuman.com/store/?formName=CubeCart_inventory_edit_Form.htm

	global $controller; 

	$arrayOfMultimediaFiles = array(); 
	
	
	// 04-10-07 - I imagine that this next if block must look a little odd. One might wonder why I 
	// hardcoded certain values while leaving other values  to be set as parameters. Well, I wrote
	// this function in a hurry, yesterday, when I was  simply trying to get mp3s and mov files into
	// a drop down box. Today I want to get all image files, gifs and jpegs and pngs, so I'm adding
	// in the function parameters. I'm too lazy to go back and edit my forms so as to use the 
	// function parameters in all cases. 
	if ($ext1) {
		$arrayOfMultimediaFiles = $controller->command("getFilesWhichHaveACertainFileExtension", $ext1); 
		$arrayOfMultimediaFiles = array_merge($controller->command("getFilesWhichHaveACertainFileExtension", $ext2), $arrayOfMultimediaFiles); 
		$arrayOfMultimediaFiles = array_merge($controller->command("getFilesWhichHaveACertainFileExtension", $ext3), $arrayOfMultimediaFiles); 
		$arrayOfMultimediaFiles = array_merge($controller->command("getFilesWhichHaveACertainFileExtension", $ext4), $arrayOfMultimediaFiles); 
		$arrayOfMultimediaFiles = array_merge($controller->command("getFilesWhichHaveACertainFileExtension", $ext5), $arrayOfMultimediaFiles); 
		$arrayOfMultimediaFiles = array_merge($controller->command("getFilesWhichHaveACertainFileExtension", $ext6), $arrayOfMultimediaFiles); 	
	} else {
		$arrayOfMultimediaFiles = $controller->command("getFilesWhichHaveACertainFileExtension", ".mp3"); 
		$arrayOfMultimediaFiles = array_merge($controller->command("getFilesWhichHaveACertainFileExtension", ".mov"), $arrayOfMultimediaFiles); 
		$arrayOfMultimediaFiles = array_merge($controller->command("getFilesWhichHaveACertainFileExtension", ".flv"), $arrayOfMultimediaFiles); 
		$arrayOfMultimediaFiles = array_merge($controller->command("getFilesWhichHaveACertainFileExtension", ".wma"), $arrayOfMultimediaFiles); 
		$arrayOfMultimediaFiles = array_merge($controller->command("getFilesWhichHaveACertainFileExtension", ".wmv"), $arrayOfMultimediaFiles); 
		$arrayOfMultimediaFiles = array_merge($controller->command("getFilesWhichHaveACertainFileExtension", ".asf"), $arrayOfMultimediaFiles); 
		$arrayOfMultimediaFiles = array_merge($controller->command("getFilesWhichHaveACertainFileExtension", ".mp4"), $arrayOfMultimediaFiles); 
		$arrayOfMultimediaFiles = array_merge($controller->command("getFilesWhichHaveACertainFileExtension", ".avi"), $arrayOfMultimediaFiles); 
	}
		
	for ($i=0; $i < count($arrayOfMultimediaFiles); $i++) {
		$file = $arrayOfMultimediaFiles[$i];
		
		// 04-10-07 - generally I don't want to clip file names, but we do
		// need to protect against giant, crazy-long file names which would
		// cause ultra-wide select boxes which would break any design. I think
		// 50 characters is a lax limit. 
		$visibleFileName = substr($file, 0, 50);
		$controller->addToOutput("<option value=\"$file\">$visibleFileName</option>\n"); 
	}
}



?>