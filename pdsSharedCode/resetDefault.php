<?php



function resetDefault($whichDatabaseTable=false, $formInputs=false) {
	// 04-10-07 - there are many situations where you want to associate one set of items
	// with some other item, and you want one of those items to have priority of presentation.
	// For instance, look at this page:
	// 
	// http://www.ihanuman.com/store/?formName=CubeCart_inventory_list_Form.htm
	// 
	// The page lists the inventory that is in the store. Each item can have an unlimited
	// number of images associated with it. However, when we show that list, we want to
	// show only one image. Which image should we show? My suggestion is that we allow one
	// of those images marked as the default image. 
	//
	// There are many other situations where we want one item out of a set items marked
	// as the default item. Therefore, to make my life easier, I'm going to start treating
	// the field name "is_default" as a magic name. The programmer for a site can give
	// any database table a field called "is_default". When so offered, the scaffolding
	// system will automatically add true and false checkboxes to any form that allows for
	// input or updates to that table. The values of those checkboxes will be "t" and "f". 
	//
	// If you are adding or updating an item, and you want it to become the default for 
	// whatever set of items it is part of, it is important that all the other items be
	// marked as "f". After all, if you already have some other item marked with "t", and
	// you add a new item with a value of "t", you'll then have two items marked as default. 
	// The simplest way to make these forms is to call resetDefault() before you call 
	// updateRecord. This function will set all values to "f". updateRecord can then set
	// the new item to "t". 
	//
	// On all the forms that  the scaffolding creates, there is an hidden input (sometimes
	// several) that have the name "choiceMade[]". This creates an array of actions which
	// happen after we submit the form. I suggest that, when you need to resetDefaults, 
	// make sure this function's name is stored in choiceMade first, and then another 
	// function like updateRecord can be called second. 
	
	global $controller; 
	
	if (!$whichDatabaseTable) $whichDatabaseTable = $controller->getVar("whichDatabaseTable"); 	
	if (!$formInputs) $formInputs = $controller->getVar("formInputs"); 

	if ($whichDatabaseTable != "" && is_array($formInputs)) {
		$thisItemShouldBeTheDefault = $formInputs["is_default"];
		
		// 04-10-07 - if the user isn't trying to make the current item the default
		// then there is no point reseting the default. Actually, it would be a bug
		// to reset the default, because we'd be erasing whatever default value the
		// user has previously choosen. 
		if ($thisItemShouldBeTheDefault == "t" || $thisItemShouldBeTheDefault == "true") {
			// 04-10-07 - what item does the current item belong to? I can't imagine 
			// a situation where an item that you can mark is_default doesn't belong
			// to some other item. That is, I expect items in this database table
			// to have a many-to-one relationship with some other  table. If this 
			// database does have a many-to-one relationship with some other table, 
			// then it should have a field that begins with "id_". Let's hope there
			// is only one field that begins with "id_", or else this becomes a 
			// hopeless muddle.
			while(list($key, $val) = each($formInputs)) {
				$firstThreeCharacters = substr($key, 0, 3); 
				if ($firstThreeCharacters == "id_") $owningKeyIs = $key; 
			}	

			if ($owningKeyIs) {
				$idOfOwningPage = $formInputs[$owningKeyIs];
				if (is_numeric($idOfOwningPage) && $idOfOwningPage > 0) {
					$query = "UPDATE $whichDatabaseTable SET is_default='f' WHERE $owningKeyIs = $idOfOwningPage  ";
					$controller->command("makeQuery", $query, "resetDefault"); 
				}
			}
		}
	}
}



?>