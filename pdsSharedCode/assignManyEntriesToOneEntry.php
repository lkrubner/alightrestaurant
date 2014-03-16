<?php



function assignManyEntriesToOneEntry() {
	// 01-23-07 - we are getting a form that looks like this:
	//
	//		<form method="post" action="/framework_01-22-07/scaffolding/index.php">
	//			<p>Owning page:
	//				<select name="owningPage">
	//					<option value="">(No choice made)</option>
	//					<option value="1">Susan</option>
	//					<option value="2">Billy</option>
	//				</select></p>
	//
	//			<p>Assign these items to the page above: </p>
	//
	//			<p>Martha Jefferson<input type="checkbox" name="totalFormInputs[nurses][]" value="1" /></p>
	//
	//			<p>UVA<input type="checkbox" name="totalFormInputs[hospitals][]" value="2" /></p>
	//
	//			<input type="submit" value="Assign these entries" />
	//			<input type="hidden" name="formName" value="manyToManyForm.htm" />
	//			<input type="hidden" name="choiceMade[]" value="assignManyEntriesToOneEntry" />
	//			<input type="hidden" name="owningDatabaseTable" value="nurses" />
	//
	//			<input type="hidden" name="whichDatabaseTable" value="index_of_nurses_and_hospitals" />
	//		</form>


	global $controller;

	$totalFormInputs = $controller->getVar("totalFormInputs");
	$whichDatabaseTable = $controller->getvar("whichDatabaseTable");
	$owningPage = $controller->getVar("owningPage");
	$owningDatabaseTable = $controller->getVar("owningDatabaseTable");

	if (is_array($totalFormInputs)) {
		if ($whichDatabaseTable) {
			// 01-23-07 99% of the time there will only be one row
			$key = key($totalFormInputs);
			$row = current($totalFormInputs);
			for ($i=0; $i < count($row); $i++) {
				$val = $row[$i];
				$firstFieldName = "id_".$key;
				$secondFieldName = "id_".$owningDatabaseTable;
					$query = "INSERT INTO $whichDatabaseTable ($firstFieldName, $secondFieldName, owning_table) VALUES ('$val', '$owningPage', '$owningDatabaseTable')";
				$result = $controller->command("makeQuery", $query, "assignManyEntriesToOneEntry");
				if ($result) {
//			$controller->addToResults("We've assigned");
				} else {

				}
			}

			if ($result) $controller->addToResults("Entries assigned");
		} else {
			$controller->error("In assignManyEntriesToOneEntry we expected a hidden input called whichDatabaseTable to tell us which database we were to update, but we got nothing. ");
		}
	} else {
		$controller->error("In assignManyEntriesToOneEntry we expected a form to submit an array called 'totalFormInputs' but we got nothing.");
	}
}



?>