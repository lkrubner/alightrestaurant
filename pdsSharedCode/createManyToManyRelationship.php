<?php



function createManyToManyRelationship() {
	// 01-22-07 - this is the action called by the form manyToManyform.htm. The
	// inputs on the form look like this: 
	//
	//		<select name="firstTable">
	//	
	//			<option value="">(No choice made)</option>
	//			<option value="Bug_Tracker">Bug_Tracker</option>
	//			<option value="application_config">application_config</option>
	//			<option value="application_field">application_field</option>
	//			<option value="application_group">application_group</option>
	//			<option value="application_note">application_note</option>
	//			<option value="application_payment_table">application_payment_table</option>
	//			<option value="application_table">application_table</option>
	//			
	//			<option value="application_user">application_user</option>
	//			<option value="application_user_custom_field">application_user_custom_field</option>
	//			<option value="application_user_group">application_user_group</option>
	//			<option value="cacvb_activities">cacvb_activities</option>
	//			<option value="site_identification">site_identification</option>
	//		</select>
	//	
	//		<select name="secondTable">
	//	
	//			<option value="">(No choice made)</option>
	//			<option value="Bug_Tracker">Bug_Tracker</option>
	//			<option value="application_config">application_config</option>
	//			<option value="application_field">application_field</option>
	//			<option value="application_group">application_group</option>
	//			<option value="application_note">application_note</option>
	//			<option value="application_payment_table">application_payment_table</option>
	//			<option value="application_table">application_table</option>
	//			
	//			<option value="application_user">application_user</option>
	//			<option value="application_user_custom_field">application_user_custom_field</option>
	//			<option value="application_user_group">application_user_group</option>
	//			<option value="cacvb_activities">cacvb_activities</option>
	//			<option value="site_identification">site_identification</option>
	//		</select>
	//
	//
	// We need to do 3 things: 
	//
	// 1.) create an index table to hold pointers from each table to the other
	// 2.) create a form for all items from the first table to be assigned to one item in the second table
	// 3.) create a form for all items from the second table to be assigned to one item in the first table

	global $controller; 

	$firstTable = $controller->getVar("firstTable"); 
	$secondTable = $controller->getVar("secondTable"); 

	if ($firstTable && $secondTable) {
		// 01-22-07 - here is an example of what the SQL should look like when creating a table. I just
		// used phpMyAdmin to generate this example. 
		//
		//	CREATE TABLE `webcal_index` (
		//	`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		//	`id_first_table` INT NOT NULL ,
		//	`id_second_table` INT NOT NULL
		//	) TYPE = MYISAM ;
		$databaseTableName = "index_of_".$firstTable."_and_".$secondTable;
		$firstTableField = "id_".$firstTable; 
		$secondTableField = "id_".$secondTable;  
		$query = " 
			CREATE TABLE $databaseTableName (
				id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
				$firstTableField INT NOT NULL,
				$secondTableField INT NOT NULL,
				owning_table varchar(255) NOT NULL default ''
			) TYPE = MYISAM ;	
		";
		$result = $controller->command("makeQuery", $query, "createManyToManyRelationship"); 

		if ($result) {
			$controller->addToResults("We've created the database table '$databaseTableName' to keep track of these many-to-many relationships.");
			$controller->command("createHtmlForManyToManyTableRelationshipForms", $firstTable, $secondTable, $databaseTableName); 
		} else {
			$controller->error("In createManyToManyRelationship we failed to create a new index table in the database. Our query was '$query'."); 
		}
	} else {
		$controller->addToResults("We were expecting the names of two database tables, but we only got '$firstTable' and '$secondTable'. Please pick two tables."); 
	}
}



?>