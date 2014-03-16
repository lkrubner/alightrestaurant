<?php



function createHtmlForFormInput($name=false, $type=false, $callingCode=false, $nameOfTable=false) {
	// 01-08-07 - this is called from createTheCreateAndUpdateForm(). We are being given the
	// name and type of a field in the database, and we need to create the appropriate HTML
	// for a form input for that field. 
	//
	// @param - name - string - not optional - the name of field in the database. Used 
	// 	in part to create the name of the input. 
	//	
	// @param - type - string - not optional - the type of the field in the database. 
	//	Used to determine what kind of HTML input should represent the field. 
	//
	// @param - callingCode - string - optional - this is the name of the function that is calling
	//	this function. It is used for debugging only. 
	//
	// 04-17-07 - I'm going to try to switch to using a 2 dimensional array when inputing 
	// forms, so that I can use createRecordsForMultipleDatabaseTables() to process form
	// input. This should have the advantage that forms can update multiple database
	// tables at once. If I can build into the scaffolding the auto-generation of forms
	// that update multiple database tables at once, then I am offering a rather large
	// convenience to users of the scaffolding. 
	//
	// @param - nameOfTable - string - not optional - name of database table that this input
	// 	should go to. Needed because now the database table that the input goes to will
	//	be specified in every single input, as part of its name.
	//
	// return string
	//
	//
	// 05-03-07 - today we are changing the standard array used when auto-generating forms. 
	// We used to use a simple one dimensional associative array, which throughout the code
	// we referred to as 'formInputs'. We will now be using "totalFormInputs". This is a 3
	// dimensional array, which specifies the database table to update, the id of the item 
	// to update (no id means create a new record) and then, at the 3rd level, the same 
	// information as what used to be in formInputs. This 3 dimensional structure allows for
	// very flexible forms, that can update multiple database tables. 
	//
	// A typical input looks like this: 
	//
	// 			<p>Name <input type="text" name="totalFormInputs[albums][0][name]" value="" /></p>
	//
	// When we hit totalFormInputs with print_r(), the output looks like this:
	//
	//		Array 
	//		(
	//		    [albums] => Array
	//		        (
	//		            [0] => Array
	//		                (
	//		                    [id_users] => 7
	//		                    [name] => yet another test
	//		                    [description] => test
	//		                    [price] => 45
	//		                )
	//		        )		
	//		)
	//
	// We are also creating a new function, createHtmlForFormInputForLists(), which is based
	// on this function, but when looking for the value of the id (the second dimension of our
	// array) it looks in currentValueForFormArraysFromLists(), instead of currentValueForFormArrays
	

	global $controller; 

	if ($name != "" && $type != "") {
		// 11-05-06 - we have to embed the PHP tags without causing a great many errors, so
		// we carefully create variables for these tags.
		$phpStart = "<";
		$phpStart .= "?"; 
		$phpStart .= "php"; 
		$phpStop = "?";
		$phpStop .= ">";

		if ($name != "id" && !stristr($name,"upload_file")) {
			$publicName = str_replace("_", " ", $name); 
			$publicName = ucfirst($publicName); 

			// 01-10-07 - I can't be sure of the case of the type (unlike the name, which I hit with 
			// strtolower when I create it), so we should make it lower case so
			// we can more easily test below. 
			$type = strtolower($type); 

			// 01-08-07 - I might regret this later, but for now I'm going to make all fields of type "char" to 
			// be checkboxes by default. 
			if (substr($type, 0, 4) == "char") {
				$stringForInputsWhenEditing .= "
					<p>$publicName <input type=\"checkbox\" name=\"totalFormInputs[$nameOfTable][$phpStart currentValueForFormArrays(\"id\"); $phpStop][$name]\" value=\"true\" /></p>
				";
			} elseif (substr($type, 0, 4) ==  "date") {
				$stringForInputsWhenEditing .= "
					<p>$publicName 
					<select name=\"totalFormInputs[$nameOfTable][$phpStart currentValueForFormArrays(\"id\"); $phpStop][$name][year]\">
						<option value=\"\">(No choice made)</option> 
						<option value=\"2006\">2006</option> 
						<option value=\"2007\">2007</option> 
						<option value=\"2008\">2008</option> 
						<option value=\"2009\">2009</option> 
						<option value=\"2010\">2010</option> 
						<option value=\"2011\">2011</option> 
						<option value=\"2012\">2012</option> 
						<option value=\"2013\">2013</option> 
						<option value=\"2014\">2014</option> 
					</select>	
					<select name=\"totalFormInputs[$nameOfTable][$phpStart currentValueForFormArrays(\"id\"); $phpStop][$name][month]\">
						<option value=\"\">(No choice made)</option> 
						<option value=\"01\">January</option> 
						<option value=\"02\">February</option> 
						<option value=\"03\">March</option> 
						<option value=\"04\">April</option> 
						<option value=\"05\">May</option> 
						<option value=\"06\">June</option> 
						<option value=\"07\">July</option> 
						<option value=\"08\">August</option> 
						<option value=\"09\">September</option> 
						<option value=\"10\">October</option> 
						<option value=\"11\">November</option> 
						<option value=\"12\">December</option> 
					</select>
					<select name=\"totalFormInputs[$nameOfTable][$phpStart currentValueForFormArrays(\"id\"); $phpStop][$name][day]\">
						<option value=\"\">(No choice made)</option> 
						<option value=\"01\">01</option> 
						<option value=\"02\">02</option> 
						<option value=\"03\">03</option> 
						<option value=\"04\">04</option> 
						<option value=\"05\">05</option> 
						<option value=\"06\">06</option> 
						<option value=\"07\">07</option> 
						<option value=\"08\">08</option> 
						<option value=\"09\">09</option> 
						<option value=\"10\">10</option> 
						<option value=\"11\">11</option> 
						<option value=\"12\">12</option> 
						<option value=\"13\">13</option> 
						<option value=\"14\">14</option> 
						<option value=\"15\">15</option> 
						<option value=\"16\">16</option> 
						<option value=\"17\">17</option> 
						<option value=\"18\">18</option> 
						<option value=\"19\">19</option> 
						<option value=\"20\">20</option> 
						<option value=\"21\">21</option> 
						<option value=\"22\">22</option> 
						<option value=\"23\">23</option> 
						<option value=\"24\">24</option> 
						<option value=\"25\">25</option> 
						<option value=\"26\">26</option> 
						<option value=\"27\">27</option> 
						<option value=\"28\">28</option> 
						<option value=\"29\">29</option> 
						<option value=\"30\">30</option> 
						<option value=\"31\">31</option> 					
					</select>
				";
			} else if ($type == "text" || $type == "mediumtext") {
				$stringForInputsWhenEditing .= "
					<p>$publicName <textarea name=\"totalFormInputs[$nameOfTable][$phpStart currentValueForFormArrays(\"id\"); $phpStop][$name]\">$phpStart currentValue(\"$name\"); $phpStop</textarea></p>
				";
			} else {
				if ($name == "password") {
					$stringForInputsWhenEditing .= "
						<p>$publicName <input type=\"password\" name=\"totalFormInputs[$nameOfTable][$phpStart currentValueForFormArrays(\"id\"); $phpStop][$name]\" value=\"$phpStart currentValue(\"$name\"); $phpStop\" /></p>
					";
				} else {
					$stringForInputsWhenEditing .= "
						<p>$publicName <input type=\"text\" name=\"totalFormInputs[$nameOfTable][$phpStart currentValueForFormArrays(\"id\"); $phpStop][$name]\" value=\"$phpStart currentValue(\"$name\"); $phpStop\" /></p>
					";
				}
			}
		}
		if (stristr($name,"upload_file")) {	
			// 11-14-06 - if the project manager adds a field called 'upload_file' then
			// we need to add a field to the forms that looks like this: 
			//
			// <p>Headshot: <input type="file" name="upload_file[]" /></p>
			//
			// We will create an empty string. If the project manager wants image uploads, we will fill 
			// that string with the appropriate input. Otherwise we will leave as an empty string, so we 
			//. add basically nothing to the forms below. 
			//
			// 05-03-07 - it seems to me that any time we offer a file upload to people, we'll also 
			// want to give them the chance to pick a file from the server. Since this is mere
			// scaffolding and the designer is free to change it, we might as well go ahead and
			// add in the choice. 
			$phpCodeForController = "\$";
			$phpCodeForController .= "controller->command";
			
			$stringForInputsWhenEditing .=  "
				<p>Upload file: <input type=\"file\" name=\"totalFormInputs[$nameOfTable][$phpStart currentValueForFormArrays(\"id\"); $phpStop][$name]\" />
				
					or choose from server: 
					
					<select name=\"totalFormInputs[$nameOfTable][$phpStart currentValueForFormArrays(\"id\"); $phpStop][fileFromServer]\">
					<option value=\"\">(No choice made)</option>
					$phpStart $phpCodeForController(\"getMultimediaFilesInDropDownBox\");   $phpStop
					</select>				
				</p>
			";
		} 

		return $stringForInputsWhenEditing;
	} else {
		$controller->error("In createHtmlForFormInput we expected to be given a name and type for this form input, but we were only given '$name' and '$field'. This function was called from the function '$callingCode'."); 
	}
}



?>