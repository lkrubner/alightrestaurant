<?php



function showPostsComments($idOfPost=false, $arrangement="community_comment_list_public.htm") {
	// 08-12-07 -used on this url: 
	//
	// http://www.thesecondroad.org/posts.php?id=15
	
	
	global $controller;
	
	$query = "SELECT * FROM community_comment WHERE id_community_post='$idOfPost' ORDER BY id desc"; 	
	$result = $controller->command("makeQuery", $query, "showPostsComments"); 
	
	if ($result) {
			$controller->command("loop", $result, "importForm", $arrangement); 
	}
}



?>