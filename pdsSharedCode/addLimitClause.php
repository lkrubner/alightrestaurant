<?php



function addLimitClause($query=false, $noLimit=false) {
	// 03-03-06 - this next block of code is responsible for pagination. It rewrites the query
	// and adds a LIMIT clause. Some functions, such as showRelatedSearches(), have to override this
	// so they call "noLimit". If you go here you'll be on the 3rd page of recent searches and you'll
	// see the pagination at work: 
	//
	// http://www.accumulist.com/index.php?whatPage=searchHistory&dbPage=2
	//
	// 04-12-06 - we need for the limit and offset portions of the query to have an easy override from
	// parameters in the URL, so we will look for those variables and use them to rewrite the LIMIT 
	// clause of the query. 
	//
	// 11-18-06 - stolen from the code I did for Accumulist.com

	global $controller; 
	
	if ($noLimit != "noLimit") {	
		if (stristr($query, "select")) {
			// 11-18-06 - the first of these if() statements looks for a comma specifying a start
			// page and offset, like "6, 10". The second if() statement checks to see if maybe there
			// is just a number following after the word LIMIT. In either case, we end up with
			// a number that is telling us how many records are allowed per database "page" (or slice,
			// using the language of this software). 
			if (preg_match('/(.*)limit (.*),(.*)/i', $query)) {
				// 11-18-06 - I could be really wrong about this, but if they idea here is that we 
				// want to capture the offset, I think if there is a commma then we want to capture the
				// 3rd block, after the commma, rather than the second.
				// $replacement = '$2';
				$replacement = '$3';
				$quantityToShow = preg_replace ('/(.*)limit (.*),(.*)/i', $replacement, $query);
			}			if (preg_match('/(.*)limit (.*)/i', $query)) {
				$replacement = '$2';
				$quantityToShow = preg_replace ('/(.*)limit (.*)/i', $replacement, $query);
			}
		
			// 11-18-06 - now we get the start record, which is the dbPage multiplied
			// by the quantityToShow. quantityToShow can be specified by the "limit" variable
			// in the url, or we will use the number in the query itself, or we will default
			// to 20. 
			if (!$quantityToShow) {
				$quantityToShow = $controller->getVar("limit"); 
			}
			if (!$quantityToShow) $quantityToShow = 20;
			if ($quantityToShow) {
				$dbPage = getVar("slice"); 
				// 11-18-06 - If dbPage is zero then of course the startRecord should also be zero. 
				$startRecord = $dbPage * $quantityToShow; 
			} 
			
			if ($quantityToShow) { 
				$position = strpos($query, "LIMIT"); 
				if ($position) {
					// 04-12-06 - a reminder about how to regex in PHP: 
					//
					//			$string = 'April 15, 2003';
					//			$pattern = '/(\w+) (\d+), (\d+)/i';
					//			$replacement = '${1}1,$3';
					//			echo preg_replace($pattern, $replacement, $string);
			//		$pattern = "(.*)limit (.*),(.*)";
					$replacement = '$1';
					$replacement .= "LIMIT $quantityToShow,";
					$replacement .= '$3';
					if (preg_match('/(.*)limit (.*),(.*)/i', $query)) {
						$query = preg_replace ('/(.*)limit (.*),(.*)/i', $replacement, $query);
					} else {
		//				$pattern = "(.*)limit (.*)";
						$replacement = '$1';
						$replacement .= "LIMIT $quantityToShow,";
						$replacement .= '$2';								
						$query = preg_replace ('/(.*)limit (.*)/i', $replacement, $query);
					}
				}
			}
		}
	}
	return $query; 
}







?>