<?php



function returnZeroIfThereIsNoValue($value) {
  // 2013-11-26 - this is typically called as the 4th parameter to currentValueForTotalFormInputs()
  // for those situations where we need a zero to fill in non-values. 
  if ($value) {
    return $value; 
  } else {
    return 0; 
  }
}