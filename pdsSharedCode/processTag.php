<?php



function processTag($tag=false) {
	// 11-13-07 - this is being called from addTagsForThisUser
	
	global $controller; 
	
	if ($tag) {
		$tag = trim($tag);
		$tag = str_replace(" ", " ", $tag);
		$tag = str_replace(",", " ", $tag);
		$tag = str_replace(".", " ", $tag); 
		$tag = str_replace("'", " ", $tag);
		$tag = str_replace("\"", " ", $tag);
		$tag = str_replace("=", " ", $tag);
		$tag = str_replace("\\", " ", $tag);
		$tag = str_replace("|", " ", $tag);
		$tag = str_replace("+", " ", $tag);
		$tag = str_replace("/", " ", $tag);
		$tag = str_replace("`", " ", $tag);
		$tag = str_replace("@", " ", $tag);
		$tag = str_replace("#", " ", $tag);
		$tag = str_replace("$", " ", $tag);
		$tag = str_replace("%", " ", $tag);
		$tag = str_replace("^", " ", $tag);
		$tag = str_replace("&", " ", $tag);
		$tag = str_replace("*", " ", $tag);
		$tag = str_replace("(", " ", $tag);
		$tag = str_replace(")", " ", $tag);
		$tag = str_replace("{", " ", $tag);
		$tag = str_replace("}", " ", $tag);
		$tag = str_replace("[", " ", $tag);
		$tag = str_replace("]", " ", $tag);
		$tag = str_replace("<", " ", $tag);
		$tag = str_replace(">", " ", $tag);
		$tag = str_replace("\n", "", $tag);
		$tag = str_replace("\r", "", $tag);
		$tag = str_replace("?", " ", $tag);
		$tag = str_replace(";", " ", $tag);
		// $tag = str_replace("-", " ", $tag);
		$tag = strtolower($tag); 
		$tag = htmlspecialchars($tag); 

		// 11-10-08 - let's take out the obvious spam and mistakes. Any URL is spam. Anything 
		// with the default text of "music lover or African american" is a mistake. 
		
		if (stristr($tag, "http")) return false; 
		
		
				
		return $tag;
	} else {
		$controller->error("In processFileName we expected to be given a tag, but we were not.", "processTag"); 		
	}
}



?>