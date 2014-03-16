<?php



function processTrimInput($totalFormInputs=false) {
	// 06-16-08 - a function for running trim() on everything that is input.
	// This is used inside of processInput. 
	
	global $controller; 
	
	$newArray = array(); 
	$newTotalArray = array(); 

	if (is_array($totalFormInputs)) {
		while (list($whichDatabaseIsTargeted, $arrayOfInfoFromInputDataForThisParticularDatabaseTable) = each($totalFormInputs)) {		
			while (list($idOfTargetedEntry, $formInputs) = each($arrayOfInfoFromInputDataForThisParticularDatabaseTable)) {
				while(list($key, $val) = each($formInputs)) {
					$formInputs[$key] = trim($val); 
				}
				$newArray[$idOfTargetedEntry] = $formInputs; 
			}
			$newTotalArray[$whichDatabaseIsTargeted] = $newArray;
		}
		$totalFormInputs = $newTotalArray;
	}
	
	return $totalFormInputs;
}



