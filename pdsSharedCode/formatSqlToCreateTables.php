<?php



function formatSqlToCreateTables() {
	// 11-03-06 - we want to imitate the scaffolding that Ruby On Rails offers. 
	// This funcion is called from the form on this page: 
	//
	// http://craigbuilders.cat4dev.com/authorized/development/index.php?formName=createTableForm
	//
	// The idea is to take the input of the form and turn it into the kind of SQL that
	// we could create a table with. We show the user the SQL on this form:
	//
	// index.php?formName=showSqlForTableCreationForm.htm
	//
	// so they can  make final adjustments. 

	global $controller; 

	$singletonFormValuesObject = & $controller->getObject("SingletonResultsFormValues", "formatSqlToCreateTables");
	$nameOfTable = $controller->getVar("nameOfTable"); 
	$fieldsToCreate = $controller->getVar("fieldsToCreate"); 

	// 12-11-06 - by far, this is the most common error I've seen so far, 
	// various people and project managers leaving in open spaces. 
	$nameOfTable = str_replace(" ", "_", $nameOfTable);

	$stringOfSql = "";

	/*
	11-03-06 - this is an example of how the SQL to create a table should look: 	
	(depending on the server, the backticks will maybe thrown an error)
	
			CREATE TABLE `tourism_sites` (
			`id` int(11) NOT NULL auto_increment,
			`site_name` varchar(50) NOT NULL default '',
			`site_address_1` varchar(50) NOT NULL default '',
			`site_address_2` varchar(50) NOT NULL default '',
			`site_city` varchar(50) NOT NULL default '',
			`site_state` char(2) NOT NULL default '',
			`site_zip` varchar(12) NOT NULL default '',
			`site_phone` varchar(10) NOT NULL default '',
			`site_url` varchar(255) NOT NULL default '',
			`site_contact` varchar(50) NOT NULL default '',
			`site_email` varchar(50) NOT NULL default '',
			`site_hours` varchar(50) NOT NULL default '',
			`site_description` text NOT NULL,
			`site_image` varchar(255) NOT NULL default '',
			`site_logo` varchar(255) NOT NULL default '',
			`site_category_id` int(11) default '0',
			`site_sort_order` int(11) default '0',
			`create_date` datetime NOT NULL default '0000-00-00 00:00:00',
			`last_update` timestamp(14) NOT NULL,
			`inactive` char(1) NOT NULL default '',
			PRIMARY KEY  (`id`)
			) TYPE=MyISAM AUTO_INCREMENT=1 ;
	*/

	if ($nameOfTable != "" && is_array($fieldsToCreate)) {
		$nameOfTable = $controller->command("formatDatabaseTableAndFieldNamesForSafety", $nameOfTable); 
		$stringOfSql .= "CREATE TABLE `$nameOfTable` ( \n";
		$stringOfSql .= "`id` int(11) NOT NULL auto_increment,\n";
	
		// 11-14-06 - if the project manager wants to add an image field here, then we need 
		// to add a field called uploaded_image
		$areImageUploadsWanted = $controller->getVar("addAnImageField");
		if ($areImageUploadsWanted) $stringOfSql .= "`uploaded_image` varchar(255) NOT NULL default '',\n";

		for ($i=0; $i < count($fieldsToCreate); $i++) {
			// 11-03-06 - each row of fieldsToCreate has 3 rows: 
			// 
			// fieldsToCreate[0][type]
			// fieldsToCreate[0][defaultValues]
			// fieldsToCreate[0][name]
			$row = $fieldsToCreate[$i];

			// 01-11-07 - when Lark created some forms, she seems to have discovered a bug whereby the variable
			// $type is set once and then never changed, so all the inputs end up being whatever type the first
			// one is.  I can't imagine why. I am, however, going to reset 
			// both name and type with each loop, in the hope that the type will read correctly from 
			// each field. I'm commenting out the use of extract. 
			//
			//			extract($row); 
			$name = ""; 
			$type = ""; 
			$name = $row["name"];
			$type = $row["type"]; 
			
			// 12-11-06 - by far, this is the most common error I've seen so far, 
			// various people and project managers leaving in open spaces. 
			//
			// 01-09-07 - we've now a function to do error checking on the names.
			$name = $controller->command("formatDatabaseTableAndFieldNamesForSafety", $name); 

			if ($name != "") {
/* 
 01-11-07 - I can't imagine why I did it this way originally, except maybe I felt I was under great time pressure? 

				if (stristr($type, "varchar") || stristr($type, "int") || stristr($type, "char") || stristr($type, "char") || stristr($type, "timestamp")) {
					if (!$defaultValues) {
						if (stristr($type, "varchar")) $defaultValues = 255; 
						if (!$defaultValues && stristr($type, "char")) $defaultValues = 1; 
						if (!$defaultValues && stristr($type, "int")) $defaultValues = 11; 
						if (!$defaultValues && stristr($type, "timestamp")) $defaultValues = 14; 
					}
					$stringOfSql .= "`$name` $type($defaultValues) NOT NULL default '',\n";
				} else {
					if (!$defaultValues) {
							if (stristr($type, "text")) $defaultValues = ""; 
							if (stristr($type, "datetime")) $defaultValues = "0000-00-00 00:00:00"; 
					}
					$stringOfSql .= "`$name` $type NOT NULL default '$defaultValues',\n";
				}
*/



				/*
				01-11-07 - here is a table, created in phpMyAdmin, that shows useful defaults for most 
				the database table field types. The backticks don't really belong in this code, 
				phpMyAdmin puts them there for laughs. 
			
								
							CREATE TABLE `kittens` (
							`id` int(11) NOT NULL auto_increment,
							`color` varchar(255) NOT NULL default '',
							`number_of_toes` int(11) NOT NULL default '0',
							`date_of_birth` date NOT NULL default '0000-00-00',
							`date_of_photo` datetime NOT NULL default '0000-00-00 00:00:00',
							`description` mediumtext NOT NULL,
							`short_description` text NOT NULL,
							`last_update` timestamp NOT NULL default CURRENT_TIMESTAMP,
							`gender` char(1) NOT NULL default '',
							`purchased_price` float NOT NULL default '0',
							`sale_price` decimal(10,0) NOT NULL default '0',
							`comments` varchar(255) NOT NULL default '',
							`children` char(1) NOT NULL default '',
							PRIMARY KEY  (`id`)
							) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
				*/

				$type = strtolower($type); 
				if ($type === "varchar") {
					$stringOfSql .= "$name varchar(255) NOT NULL default '',\n";
				} else if ($type === "int") {
					$stringOfSql .= "$name int(11) NOT NULL default '0',\n";
				} else if ($type === "char") {
					$stringOfSql .= "$name char(1) NOT NULL default '',\n";
				} else if ($type === "timestamp") {
					$stringOfSql .= "$name timestamp NOT NULL default CURRENT_TIMESTAMP,\n";
				} else if ($type === "date") {
					$stringOfSql .= "$name date NOT NULL default '0000-00-00',\n";
				} else if ($type === "datetime") {
					$stringOfSql .= "$name datetime NOT NULL default '0000-00-00 00:00:00',\n";
				} else if ($type === "text") {
					$stringOfSql .= "$name text NOT NULL,\n";
				} else if ($type === "mediumtext") {
					$stringOfSql .= "$name mediumtext NOT NULL,\n";
				} else if ($type === "float") {
					$stringOfSql .= "$name float NOT NULL default '0',\n";
				} else if ($type === "decimal") {
					$stringOfSql .= "$name decimal(10,0) NOT NULL default '0',\n";
				} else {
					// 01-19-07 we need a default, especially in case a user tries to run
					// the "Regenerate Forms" option on database tables that were invented
					// for other, unrelated software. We will default to the varchar option.
					$stringOfSql .= "$name varchar(255) NOT NULL default '',\n";
				}
			} else {
				// 11-03-06 - this is no error. Because the form inputs 30 rows and since many of them will be blank, 
				// it is not an error if $name is blank. 
			}
		}
			
		$stringOfSql .= "PRIMARY KEY  (`id`)\n";
		$stringOfSql .= ") TYPE=MyISAM AUTO_INCREMENT=1 ;\n";

		$stringOfSql = str_replace("`", "", $stringOfSql); 

		$newRow = array(); 
		$newRow["sql"] = $stringOfSql;
		$singletonFormValuesObject->set($newRow); 
	} else {
		$controller->error("We need a name for the database table, and an array of info for the fields, but we only got '$nameOfTable' and '$fieldsToCreate'."); 
	}
}



?>