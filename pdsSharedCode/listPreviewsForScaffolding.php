<?php



function listPreviewsForScaffolding() {
	// 01-23-07 - this is used on the scaffolding page. What we want is for designers to be
	// able to put folders inside the scaffolding folders, and for these folders to be compelete
	// previews of what some site could potentially look like. 
	//
	// 04-17-07 - this function is not really doing anything yet and I'm not 
	// sure it ever will. It is, however, currently sitting on the index.php
	// page in the scaffolding/ directory. 

	global $controller; 

	if ($handle = opendir('.')) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != "..") {
				if (is_dir($file)) {
					$visibleName = str_replace("_", " ", $file);
					$controller->addToOutput("<p><a href=\"$file/\">$visibleName</a></p> \n");
				}
			}
		}
		closedir($handle);
	}
}



?>