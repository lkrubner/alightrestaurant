<?php



class Output {
	// 04-17-07 - Chris Clarke and I worked out 6 requirements for 
	// any framework we might develop. One of those requirements was
	// to have all output on a single line. To achieve that, I need
	// to store all output as a string somewhere. I'm creating this
	// class, Output, to be a singleton that holds all output. That 
	// way the code can store up the output till the appropriate 
	// moment. 
	//
	// This class has only three methods:
	//
	// addToOutput() adds to the instance variable $stringOfOutput. 
	//
	// getOutput() returns that string
	//
	// setReturnOutput() determines whether the software should echo 
	// text straight to the screen or return it. The default is to 
	// echo text straight to the screen, however, when we are building 
	// software that requires output on a single line, we will want 
	// to set the class variable $returnOutput to true.

	var $returnOutput = false; 
	var $stringOfOutput = ""; 

	function addToOutput($text="") {
		if ($this->returnOutput) {
			$this->stringOfOutput .= $text;
			return $this->stringOfOutput; 
		} else {
			echo $text; 
		}
	}

	function getStringOfOutput() {
		return $this->stringOfOutput;
	}

	function setReturnOutput($mode=false) {
		$this->returnOutput = $mode; 
	}


	function mode($mode=false) {
		$this->setReturnOutput($mode); 
	}


}



