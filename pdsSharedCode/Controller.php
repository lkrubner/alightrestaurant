<?php



class Controller {
	// 07-16-06 - the controller is the main object of this software. It is the 
	// only global variable. It is initialized on the page initiate.php or config.php, 
	// which must be included on every page that this software is to be used. 
	// The controller is responsible for importing all the other code. The other 
	// code is normally imported on an as-needed basis, when a function name 
	// is handed to the command()  method, which then imports the function and 
	// executes it, giving to it whatever parameters were given to command(). The 
	// controller also has an error method which is used to record programming errors. 
	// 
	// Controller has an array that stores a reference to every object that is used on
	// a page, and the method getObject returns the object by reference, so every
	// object in use is a singleton by default. The 3rd parameter on the getObject()
	// method should be used when you want a truly new object. 
	//
	// 04-17-07 - importing functions only when they are needed is known as the 
	// Lazy Loading design pattern. With this Controller, we take that pattern to
	// a sort of extreme. 
	// http://today.java.net/pub/a/today/2006/07/13/lazy-loading-is-easy.html

	var $arrayOfErrorMessages = array(); 
	var $arrayOfResultMessagesForUser = array(); 
	var $arrayOfAllTheObjectsSoFarLoaded = array();

	// 07-31-07 - explained below in addToMessageArray()
	var $arrayOfArrangements = array(); 	
	
	// 11-30-07 - see the comment for carry().
	// I'm probably creating this for the same reason I created arrayOfStatusCodes, but
	// I like the name better because it is more general and I can use it for anything. 
	// Anyone who wants strict use of variables should probably use Java, or some other
	// language besides PHP. You're not getting the full advantage of PHP unless you 
	// accept loose typing in all things. 
	var $arrayOfAllCarriedInfo = array(); 	
	

	// 2013-10-26 - I just now tried to delete this, and then I realized it was in widespread
	// use in the code. At some point in 2007 I made an effort to remove "echo" from the code
	// and replace it with this, for all the places in the templates where I want to show a 
	// value. I suspect I don't need any of these external objects but could simply use arrays
	// here in the Controller object, but I don't have time today to rewrite so much of the code.
	var $outputObject = null; 
	

	/**
	* 07-16-06 - we want to save memory by having just one of each object, where possible. It's
	* rare in PHP scripts to need several objects of the same class (an exception is where one
	* is using an object to mask all calls to the database, which we are not doing in the code
	* for WorldStrides). Therefore this object keeps an array of all objects so far called, 
	* and returns referencesto the code that needs them. This method also enforces security in that, before
	* creating an object it checks the name of the class against the list in arrayOfAllAllowedFunctions.php
	* If the class isn't listed, this method won't create the object. This method and import(), below,
	* manage the including and initialization of all the classes and functions that this code uses.
	*
	* @param - $nameOfClassToBeUsed - this is used when import()  is called - it is the name of the file
	* 	we are trying to get.
	*
	* @param - nameOfFunctionOrClassCalling - this is used when import() is called - it's only role is in
	* debugging, when there is an error it is important to know where the call came from.
	* 
	* @param - makeNewObject - normally this method returns a reference to an existing object, if the 
	* object has already been instantiate. The third parameter is by defaut 'false'. If it is set to 
	* true, then this method will return a new instance of an object, even if an instance of the 
	* object already exists. 
	* 
	* public
	* returns object
	*/
	function & getObject($nameOfClassToBeUsed=false, $nameOfFunctionOrClassCalling=false, $makeNewObject=false) {
	  $showStackTrace = $this->getVar("stackTrace"); 
	  if ($showStackTrace) echo "<p>$nameOfClassToBeUsed</p>\n";

		if (!$nameOfClassToBeUsed) {
			$this->error("The method getObject(), in the class Controler, is being called but we are not being told what object to get. This code is being called from '$nameOfFunctionOrClassCalling'.");
			return false;
		}
		if (!$nameOfFunctionOrClassCalling) {
			// 07-16-06 - even though nameOfFunctionOrClassCalling is only used for debugging purposes, we make
			// it mandatory because we expect there to be bugs, eventually, and so we want this used universally.
			$this->error("The method getObject(), in the class Controller, is being called. We are told to look for the object '$nameOfClassToBeUsed' but we are not being told what code is calling the method. The second parameter to getObject, the name of the calling code, is mandatory."); 
			return false; 		
		}
				
		// 07-16-06 - if we've already loaded this object then it is stored in the array arrayOfAllTheObjectsSoFarLoaded
		// and so we can simply return it, rather than waste time creating a new instance of it. 
		if (isset($this->arrayOfAllTheObjectsSoFarLoaded[$nameOfClassToBeUsed]) && $makeNewObject == false) {
			$object = & $this->arrayOfAllTheObjectsSoFarLoaded[$nameOfClassToBeUsed];
			if (is_object($object)) {
				return $object;
			} else {
				$this->error("In getObject(), the array of loaded objects seemed to already have a copy of the object '$nameOfClassToBeUsed', yet we couldn't retrieve it.", "Controller"); 	
			}
		} elseif (class_exists($nameOfClassToBeUsed) && $makeNewObject == true)  {
			// 07-16-06 - this method, by default, returns a reference to an existing object, if a class has already
			// been instantiated. However, if the third parameter, makeNewObject, is set to true, then this method
			// will return a new instance. 
			
			$object = new $nameOfClassToBeUsed();
			if (is_object($object)) {
				$this->arrayOfAllTheObjectsSoFarLoaded[$nameOfClassToBeUsed] = & $object;
				return $object;
			} else {
				$this->error("In getObject(), the code new of a class named '$nameOfClassToBeUsed' but was unable to instantiate it.");
			}
		} else {
			// 07-16-06 - we now import the file that contains the code that defines the class we want. Having imported
			// the file, we will check to see if the class exists, and then we will attempt to instantiate it. If the
			// import fails, that is most likely due to parse errors in the PHP code. If the import succeeds yet we
			// can not instaniate the object we want, that's usually due to logic errors in the code, errors that 
			// normally trigger fatal errors in PHP. 
			$imported = $this->import($nameOfClassToBeUsed, $nameOfFunctionOrClassCalling);
			if ($imported) {
				if (class_exists($nameOfClassToBeUsed)) {
					$object = new $nameOfClassToBeUsed();						
					if (is_object($object)) {
						$this->arrayOfAllTheObjectsSoFarLoaded[$nameOfClassToBeUsed] = & $object;
						return $object;
					} else {
						$this->error("In getObject(), we were suppose to get the class '$nameOfFunctionOrClassCalling' but even though the class existed we were not able to get an object.");  	
					}
				} else {
					$this->error("In getObject, in the class Controller, the '$nameOfClassToBeUsed' class was not found. The code that wants this class is '$nameOfFunctionOrClassCalling '");
				}			
			} else {
				$this->error("In getObject(), in the class Controller, we failed to import the class '$nameOfClassToBeUsed'. The most common cause of this is parse errors in the PHP code that we were trying to import."); 
			}	
		}
	}
	
	
	/**
	* 07-16-06 - we keep each function and class in its own file and then include it when it is 
	* needed. This method should be the only method ever used by the code to include software. 
	*
	* @param - $name - string - this is the name of the file and class or function that is being sought.
	* 
	* @param - $callingClass - string - this should be the name of the class or function that is calling 
	* the Controller. Normally this method is being invoked from inside the getObject() method. 
	* 
	* private
	* returns bool
	*/
	function import($name=false, $callingClass="") {	
		if (is_string($name)) {
			if ($callingClass != "") {
				// 07-16-06 - how do we know if the programmer included the ".php" ending that makes up
				// part of the file name? If they included ".php" we will fail to get the right file after we
				// add our own ".php". So its best for us to remove it and then add it back. Thus the 
				// programmer doesn't have to remember whether they are suppose to give the full file 
				// name or just the name of the function or class that is wanted. 
			        $name = trim($name); 
				$name = str_replace(".php", "", $name);
				$name = $name.".php";
					
				// 04-20-04 - this next line protects against including the same file twice 
				// and thus getting a "Can't redeclare function" error. 
				if (!function_exists($name) && !class_exists($name)) {
					$theFileIsLoaded = false;
					$pathToSiteSpecificFiles = $this->getVar("pathToSiteSpecificFiles"); 
					$pathToSharedCode = $this->getVar("pathToSharedCode"); 					
					$pathToTry = $pathToSiteSpecificFiles.$name;
					$pathToTry2 = $pathToSharedCode.$name; 

					if (file_exists($pathToTry)) {
					  $theFileIsLoaded = include($pathToTry); 
					}
					if (!$theFileIsLoaded) {
					  if (file_exists($pathToTry2)) {
					    $theFileIsLoaded = include($pathToTry2); 
					  }
					}

					if ($theFileIsLoaded) {
						return true;
					} else {
						$this->error("In import(), in the class Controller, we attempted to find a function or file called ' $name ' but we failed. The request came from '$callingClass'. If you're certain this class or file exists, check for PHP parse errors, for they will cause this software to fail, without giving any indication of problems.");
						return false; 
					}	 
				} else {
					return true; 
				}
			} else {
				$this->error("In the Controller, we were not told what code was calling import(). The second parameter to import() is mandatory, we need to know who is calling this code."); 
			}
		} else {
			$this->error("In import(), in the class Controller, we expected to be handed the name of the wanted function or file, but instead we got this: $name. The request came from $callingClass");			
			return false; 
		}
	}

	
	
	/**
	* 07-16-06 - it suddenly strikes me as odd that we have no method that masks import(). Obviously
	* import, and all the error checking needed around it, should not be called in the client code. So
	* I'm adding this method today, to replicate the 4 lines of code I use whenever I use import(). 
	* I'm giving this method 6 parameters, even though I never write functions that take 6 parameters. 
	* 
	* 
	* 
	*/	
	function command($functionName=false, $param1=false, $param2=false, $param3=false, $param4=false, $param5=false, $param6=false) {
	  $showStackTrace = $this->getVar("stackTrace"); 
	  if ($showStackTrace) echo "<p>$functionName</p>\n";
		if ($functionName) {
			if (function_exists($functionName)) {
				$imported = true; 
			} else {
				$imported = $this->import($functionName, "Controller"); 				
			}
			if ($imported) {
				if (function_exists($functionName)) {
					if ($param6) {
						$result = $functionName($param1, $param2, $param3, $param4, $param5, $param6);
					} elseif($param5) {
						$result = $functionName($param1, $param2, $param3, $param4, $param5);
					} elseif($param4){
						$result = $functionName($param1, $param2, $param3, $param4);
					} elseif($param3) {
						$result = $functionName($param1, $param2, $param3);
					} elseif($param2) {
						$result = $functionName($param1, $param2);
					} elseif($param1) {
						$result = $functionName($param1);
					} else {
						$result = $functionName();
					}
					return $result; 
				} else {
					$this->error("In Controller, we were able to import '$functionName' yet no function by that name could be found."); 
				}
			} else {
				$this->error("In Controller, in the method command(), we were told to import the function '$functionName' but we were unable to.", "Controller");
			}
		} else {
			$this->error("In Controller, the method command() was called but was given no function name to call.", "Controller");
		}
	}


	function safeCommand($functionName=false, $param1=false, $param2=false, $param3=false, $param4=false, $param5=false, $param6=false) {
	  $safeChoice = $this->command("getTheSafeVersionOfThisCommand", $functionName);
	  $this->command($safeChoice, $param1, $param2, $param3, $param4, $param5, $param6);
	}


	function error($error=false) {
	  // 07-16-06 - an alias of addToErrors
	  $this->addToErrors($error);
	}
	
	
	function addToErrors($error=false) {
	  // 07-16-06 - I'm not yet sure what I'll do with these errors - LK
	  $this->arrayOfErrorMessages[] = $error;
		
	  $filename = 'log.txt';

	  // Let's make sure the file exists and is writable first.
	  if (is_writable($filename)) {
	    // In our example we're opening $filename in append mode.
	    // The file pointer is at the bottom of the file hence
	    // that's where $somecontent will go when we fwrite() it.
	    if (!$handle = fopen($filename, 'a')) {
	      echo "Cannot open file ($filename)";
	      exit;
	    }
	    // Write $somecontent to our opened file.
	    $somecontent = date('Y-m-d H:i:s') . " ------- $error \n\n";
	    if (fwrite($handle, $somecontent) === FALSE) {
	      echo "Cannot write to file ($filename)";
	      exit;
	    }
	    fclose($handle);
	  } else {
	    echo "the software is unable to write to log.txt"; 
	  }

	  global $config; 		
	  $debug = $this->getVar("debug"); 
	  $errorConfig = $config["error_messages"]; 

	  if ($debug || $errorConfig) {
	    $this->addToOutput("<p>$error</p>");
	  } 		
	}
	
	
	function getArrayOfErrorMessages() {
		return $this->arrayOfErrorMessages;
	}
	
	
	function result($result=false) {
		// 0716-06 - an alias for addToResults
		$this->addToResults($result);
	}
	
	
	function addToResults($message=false) {
		// 07-16-06 - this is the method that the software uses to send a message
		// to the user. For instance, if the user has just uploaded images, the 
		// function uploadImages will use this method to say "You've successfully
		// uploaded images." The function showUserMessages gets this array and
		// prints the messages to the screen, assuming the designer has actually
		// used the function showUserMessages somewhere in the template. 
		$this->arrayOfResultMessagesForUser[] = $message;
	}


	function getArrayOfResultMessagesForUser() {
		// 07-16-06 - called from showUserMessages to get the messages that should be
		// shown to the user. 
		return $this->arrayOfResultMessagesForUser;
	}
	
	
	function addToMessageArray($nameOfArrangement, $arrayOfInfoForArrangement=false) {
	  $row['nameOfArrangement'] = $nameOfArrangement;
	  $row['arrayOfInfoForArrangement'] = $arrayOfInfoForArrangement;
	  $this->arrayOfArrangements[] = $row;
	}


	function getVar($var=false) {
		// 07-25-06 - this function gets variables. It is handy for getting variables that might 
		// be in $_GET or might be in $_POST. Potentially, this function also offers some possible
		// future security - getting all variables through one function means a layer of security
		// could be added to this function - though I'm not sure that security might entail. 
		//
		// Note that this function is it reverses PHP's built-in load order for variables. 
		//
		// 05-16-07 - I'm adding in another line so that this function now looks to the 
		// built-in variable $config, as well as looking all the other places. 
		
		if ($var) {
			global $HTTP_POST_VARS;
			global $HTTP_SERVER_VARS;
			global $HTTP_COOKIE_VARS;
			global $HTTP_GET_VARS;
			global $HTTP_ENV_VARS;
			global $HTTP_FILE_VARS;
			global $config; 
			
			
			if (isset($HTTP_POST_VARS[$var])) {
				$value = $HTTP_POST_VARS[$var];
			} elseif (isset($HTTP_GET_VARS[$var])) {
				$value = $HTTP_GET_VARS[$var];
			} elseif (isset($HTTP_ENV_VARS[$var])) {
				$value = $HTTP_ENV_VARS[$var];
			} elseif (isset($HTTP_SERVER_VARS[$var])) {
				$value = $HTTP_SERVER_VARS[$var];
			} elseif (isset($HTTP_COOKIE_VARS[$var])) {
				$value = $HTTP_COOKIE_VARS[$var];
			} elseif (isset($HTTP_FILE_VARS[$var])) {
				$value = $HTTP_FILE_VARS[$var];
			} elseif (isset($_POST[$var])) {
				$value = $_POST[$var];
			} elseif (isset($_GET[$var])) {
				$value = $_GET[$var];
			} elseif (isset($_ENV[$var])) {
				$value = $ENV[$var];
			} elseif (isset($_SERVER[$var])) {
				$value = $_SERVER[$var];
			} elseif (isset($_COOKIE[$var])) {
				$value = $_COOKIE[$var];
			} elseif (isset($_FILES[$var])) {
				$value = $_FILES[$var];
			} elseif (isset($_SESSION[$var])) {
				$value = $_SESSION[$var];
			} elseif (isset($GLOBALS[$var])) {
				$value = $GLOBALS[$var];
			} elseif (isset($config[$var])) {
				$value = $config[$var]; 
			}

			if (isset($value)) {
				return $value;
			} else {
				return false; 	
			}
		} else {
			$this->error("In getVar(), in Controller, we expected the first parameter would tell us which variable we should look for, but we were given a blank."); 
		}
	}
	
	
	
	/**
	* 12-04-06 - we need to find the config file, include it, then return the 
	* captured array. 
	* 
	* 01-08-07 - I'm changing this so that the config info is out of the normal
	* web directory path, and that it is included automatically as a global variable.
	*/
	function getConfig() {
		global $config;
		if (is_array($config)) {
			return $config; 
		} else {
			$this->error("In getConfig(), in the Controller, we tried to find the configuration file at the path '$pathToConfigFile', but we failed to find it."); 
		}
	} 	
	


      /**
        * 04-08-07 - chris clarke and I agreed on 6 main requirements for our framework. One
        * was outputing on a single line. This means I need to mask all the calls that I've made 
        * to echo. 
        * 
        * 
        * 
        * 
        * 
        */
        function addToOutput($text=false) {
                if (!is_object($this->outputObject)) {
                        $this->outputObject = & $this->getObject("Output", "Controller"); 
                }
                $this->outputObject->addToOutput($text);
        }
        

        function changeOutputMode($newMode=false) {
                if (!is_object($this->outputObject)) {
                        $this->outputObject = & $this->getObject("Output", "Controller"); 
                }
                $this->outputObject->mode($newMode); 
        }

	
	
	// 11-30-07 - the methods addToResults() and error() are both rigid and out of date. 
	// With the error() method, I'm frustrated that there is only one level of 
	// error. I'd rather have "error" and "warning" and "notice". The code, till
	// now, has been very rigid about handling arrays of info. With addToResults(),
	// the same problem, all results show up in the same place and have the same
	// formatting.  I'd rather be able to put messages in different places. 
	// What I'm doing today is creating the class array arrayOfAllCarriedInfo,
	// an associative array that can carry any information. The array key can
	// be used to specify what area it is being targeted for. The keys "error"
	// and "warning" and "notice" can be used to carry different levels of
	// error messages, and keys such as "login" and "personal page" can be
	// used to specify user messages that appear in different places. Mind you,
	// I have not yet written any of the supporting code that would actually
	// do something with these to methods, but they have to exist before I
	// can do anything useful with them. 
	//
	// @param - mixedVariable - this is given a nonsense default value, because
	// false values would be legitimate for it. We'd like to test, below, to see
	// if the programmer correctly passed two parameters to this method. The only
	// way the nonsense default value would survive would be if the programmer
	// forgot to pass in a second parameter. 
	function carry($arrayKey=false, $mixedVariable="rewoplPOIUYTREWQASDFGHJKLmnbvcxzasdfghjkl12345678900987654321") {
		if (!$arrayKey) {
			$this->error("In the method carry(), in the class Controller, we expected an array key, but we got nothing."); 
			return false; 	
		}
		
		if (!is_string($arrayKey)) {
			$this->error("In the method carry(), in the class Controller, we expected the array key to be a string, but instead we got this: '$arrayKey'."); 
			return false; 	
		}
		
		if ($mixedVariable === "rewoplPOIUYTREWQASDFGHJKLmnbvcxzasdfghjkl12345678900987654321") {
			$this->error("In the method carry(), in the class Controller, we expected the second parameter to be passed some value, but it was passed nothing."); 
			return false; 	
		}
		
		$this->arrayOfAllCarriedInfo[$arrayKey] = $mixedVariable; 
	}
	
	// 11-30-07 - see the comment for carry().
	function retrieve($arrayKey=false) {
		if (!$arrayKey) {
			$this->error("In the method retrieve(), in the class Controller, we expected an array key, but we got nothing."); 
			return false; 	
		}
		
		if (!is_string($arrayKey)) {
			$this->error("In the method retrieve(), in the class Controller, we expected the array key to be a string, but instead we got this: '$arrayKey'."); 
			return false; 	
		}
		
		$mixedVariable = $this->arrayOfAllCarriedInfo[$arrayKey]; 
		return $mixedVariable; 
	}
	
	
}


