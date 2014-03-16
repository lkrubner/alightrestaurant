<?php



function loopArray($array=false, $functionName=false, $param1=false, $param2=false, $param3=false, $param4=false, $param5=false, $param6=false) {
  // 09-18-06 - this is similar to PHP's built-in function array_walk, but here 
  // we have more flexibilty about the parameters we are giving to the function. 
  // Ideally, this function and loop() should be the only places that for() loops 
  // appear in this software.
  //
  // 05-19-08 - this function is used in deleteFromDatabase. 

  global $controller; 
	
  $arrayOfResults = array(); 

  if (is_array($array)) {
    for ($i=0; $i < count($array); $i++) {
      $value = $array[$i];
      if ($param6) {
	$arrayOfResults[] = $controller->command($functionName, $value, $param1, $param2, $param3, $param4, $param5, $param6);
      } elseif($param5) {
	$arrayOfResults[] = $controller->command($functionName, $value, $param1, $param2, $param3, $param4, $param5);
      } elseif($param4){
	$arrayOfResults[] = $controller->command($functionName, $value, $param1, $param2, $param3, $param4);
      } elseif($param3) {
	$arrayOfResults[] = $controller->command($functionName, $value, $param1, $param2, $param3);
      } elseif($param2) {
	$arrayOfResults[] = $controller->command($functionName, $value, $param1, $param2);
      } elseif($param1) {
	$arrayOfResults[] = $controller->command($functionName, $value, $param1);
      } else {
	$arrayOfResults[] = $controller->command($functionName, $value);
      }
    }
    return $arrayOfResults;
  } else {
    $variableAsString = print_r($array, 1); 
    $controller->error("In loopArray, we expected the first parameter to be an array, but instead we were given this: '$variableAsString'. functionName was '$functionName'."); 	
  } 
}



