<?php



function formatCalendarDates($formatString, $timeAsSeconds) {
  // 2014-01-22 - I'm throwing this in just to get rid of the errors:
  // In import(), in the class Controller, we attempted to find a function or file called ' date.php ' 
  // but we failed. The request came from 'getNeededFunctionsEach'. If you're certain this class or
  //  file exists, check for PHP parse errors, for they will cause this software to fail,
  //  without giving any indication of problems.
  return date($formatString, $timeAsSeconds);
}









