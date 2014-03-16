<?php



function listEntriesInDatabase($databaseTableName=false, $arrangement=false, $pagination=false, $nameOfFieldThatRecordsOwningPage=false, $orderByThisField="id", $reverseOrder=false, $getKeyForStorageInSingleton=false, $setKeyForStorageInSingleton=false) {
	// 11-18-06 -
	//
	//				Darren,
	//				
	//				This email started as an email to Lisa and Chris Konizer, written on 09-28-06. I was trying to explain how the template system worked on the mjhforwomen.org site. I'm now rewriting this so it is a letter to you and mostly about the Craig Builder's site. I will reference mjhforwomen.org many times, because that site is up and working, and so if you want to see an example of what I'm talking about, you can go look at that site. 
	//				
	//				This is pretty much the same template system that we used on the CMS, just a little looser. I imagine you'll have an easy time with it. 
	//				
	//				---------------------------------------------
	//				
	//				The code looks to see what is in the URL and does different things, depending on what it sees in the URL. 
	//				
	//				I'm going to assume that for craigbuilders.com you'll set up 2 files, index.php and admin.php. You can call these files anything, but these are good enough names. On mjhforwomen.org we used these two file names. At the center of admin.php should be a command that makes the different forms appear. If you go to mjhforwomen.org and login to admin.php and click on one of the links, you'll see that there is a "formName" in the URL. For instance, if you want to add a new homes to the database, and you click that link, then in the URL you'll see this:
	//				
	//				admin.php?formName=formCreatehomes
	//				
	//				The code looks to see what "formName" equals. In this case, it equals createhomes. The code then adds ".htm" to the end, so it's got this name:
	//				
	//				formCreatehomes.htm
	//				
	//				And if you look in authorized/site_specific_files on mjhforwomen.org you'll see that form. If you need to change or style that form in any way, you can open it up and edit it in Dreamweaver. You might, for instance, want to offer some helpful hints that show up onMouseOver. Whatever you want to do, feel free. 
	//				
	//				On craigbuilders.com we've got a form called showNeigborhoods.htm. This is the file that shows info about neighborhoods to the public. It's likely that on the file index.php, you'll put this PHP command somewhere: 
	//				
	//				< ?php showForms("showNeigborhoods.htm."); ? >
	//					//				This makes sure that the form showNeigborhoods.htm shows up at that point in the page. It would look at the url to figure out which particular neigborhood it should show. The url might look like this, for instance: 
	//				
	//				index.php?id=4
	//				
	//				That means it will show the neighborhood that in the database has the id of 4. On the front page perhaps we will list all of the neighborhoods so that people can click through to examine each one. Below, I'll explain the command that you can use to list all the neighborhoods. 
	//				
	//				On the backend, on admin.php, you can put the command that makes the forms appear, which is this:
	//				
	//				< ?php showForms(); ? >
	//				
	//				Wherever you want the forms to appear, place that PHP command. If there is a form that you want to appear by default, before the user has clicked on any links, you just put the default form in quotes in that command: 
	//				
	//				< ?php showForms("create_neighborhoods_Form.htm"); ? >
	//				
	//				You may at some point need to create forms that I've not yet thought of. It's fairly easy, so long as you have a rough understanding of how the info is stored in the database. All you have to know is the names of the tables and the names of their columns. If you look in authorized/scaffolding/index.php you'll see a link that says "Show all tables". Click that and you'll see a list of all the tables and all the fields in each table. 
	//				
	//				I don't have access to the dev server today, because I'm writing this from home, but as I recall, Chris Mckarsky created 2 database tables: 
	//				
	//				neighborhoods
	//				homes  (or maybe this was called model_homes)
	//				
	//				Suppose you want to list every neighborhood. You only need to pieces of info, the name of table in the database (neighborhoods) and the form you'd like to use to show each row from the database. Let's suppose you have a form called list_neighborhoods_each.htm. Just do this: 
	//				
	//				< ?php listEntriesInDatabase("neighborhoods", "list_neighborhoods_each.htm"); ? >
	//				
	//				Wherever you put this PHP command, all the homes entries in the database will be retrieved, and then each row will be formatted depending on how you format the HTML in list_neighborhoods_each.htm. If you were already using list_neighborhoods_each.htm for some other list and wanted this particular list to look different, you could just invent a new file and call it whatever you wanted. You could make up a file called reallyCoolRegions.htm and call the command like this: 
	//				
	//				< ?php listEntriesInDatabase("neighborhoods", "reallyCoolRegions.htm"); ? >
	//				
	//				reallyCoolRegions.htm could have HTML that styles it differently from list_neighborhoods_each.htm. In both cases, we are getting info out of the database table neighborhoods, so in both cases we are dealing with the same raw info, but the two files could show different fields from the database, and style it differently. You can see an example of where this was useful on mjhforwomen.org - on the front page we list all the life phases in a big, bold list in the middle of the page, but on the interior pages we list as a small list of headlines that is in the left column. Its the same info, but on the front page we wanted it big, bold and centered, whereas on the interior pages we wanted that list small and tucked into a side column. 
	//				
	//				Another example of where we'd want to list the same info in different ways is admin.php. On index.php we want the information to look pretty for the public to enjoy, but on admin.php we'll want to present the info in a utilitarian manner, so the user can have an easy time creating, updating, and deleting records.  We'll use listEntriesInDatabase on both index.php and admin.php, but we will format the output differently. Again, just to be clear, you alter the formatting of the output by handing the command a different file to wrap the output in: 
	//				
	//				< ?php listEntriesInDatabase("neighborhoods", "list_entries_for_editing.htm"); ? >
	//				
	//				Almost all of this work has already been done for you, I'm only telling you this so you'll understand the system. You'll need to understand it if you want to change it. However, all the pages and lists should be there for you, in raw form. As soon as Chris Mckarsky set the site up, all the files were created for you. 
	//				
	//				I hope "listEntriesInDatabase" is a reasonably intuitive name for this PHP command. Please let me know what would strike you as intuitive. I believe strongly that all PHP functions should be named by graphic designers, so that they make sense to the designers and, hopefully, become easier to remember. Any time you think a PHP command is poorly named, let me know and I'll create an alias for it that is easier to remember. 
	//				
	//				Of course, you don't necessarily want to list every neighborhood in the database. If you want to limit the number, just add a number into the command, like this: 
	//				
	//				< ?php listEntriesInDatabase("neighborhoods", "reallyCoolRegions.htm", "10"); ? >
	//				
	//				This will only show 10 neighborhoods. 
	//				
	//				The use of listEntriesInDatabase becomes more complicated when we consider the information that is going to be stored in the database table called "homes". There is no page where we will want to do simply this:
	//				
	//				< ?php listEntriesInDatabase("homes", "list_homes.htm", 10); ? >
	//				
	//				This will list 10 homes, but we don't want that. The homes belong to neighborhoods. We want to show which homes belong to each neighborhood. We need to say, in effect, "Show 10 of the homes that belong to the neighborhood whose id is 4". We would do that by listing which field in the database table "homes" should be matched against the id of a neighborhood. If we had a field in the database table "homes" that was called id_of_neighborhood then our PHP command would look like this: 
	//				
	//				< ?php listEntriesInDatabase("homes", "list_homes.htm", 10, "id_of_neighborhood"); ? >
	//				
	//				If the user went to a page where the URL looked like this: 
	//				
	//				index.php?id=6
	//				
	//				and you had this PHP command on that page
	//				
	//				< ?php listEntriesInDatabase("homes", "list_homes.htm", 10, "id_of_neighborhood"); ? >
	//				
	//				then the software would, in effect, say "We will get 10 homes whose id_of_neighborhood equals 6 and we will show that info on screen, formatting the output according to the file list_homes.htm". 
	//				
	//				Imagine, for a moment, that the database table neighborhoods has these fields: 
	//				
	//				id
	//				name
	//				description
	//				city
	//				state
	//				
	//				And imagine, for a moment, that the database table homes has these fields: 
	//				
	//				id
	//				model
	//				size
	//				color
	//				number_of_stories
	//				id_of_neighborhood
	//				
	//				Every record in neighborhood has an id which is a number that lets us identify which neighborhood we are trying to use. Each home belongs to a neighborhood. In the field id_of_neighborhood it records the id of the neighborhood to which it belongs. That's the field name you use to specify the relationship between homes and neighborhoods. I'm repeating myself at this point, because I'm worried that this is tough to understand, and I'm hoping to be clear: 
	//				
	//				< ?php listEntriesInDatabase("homes", "list_homes.htm", 10, "id_of_neighborhood"); ? >
	//				
	//				If the user went to this url: 
	//				
	//				index.php?id=6
	//				
	//				and you've the above PHP command on the page, and there are no homes that belong to the neighborhood with the id of 6, then nothing would appear. If there were 12 homes, only 10 would appear, because you're limiting the page to list just 10. You'd then have to use the command paginate in your design somewhere: 
	//				
	//				< ?php paginate("homes", 10); ? >
	//				
	//				That would let the user know there is another page of homes to be looked at. Or you could increase the number of homes you're willing to show on one page. For instance, this would show 20 homes on one page (if there were 20 homes that belonged to one neighborhood): 
	//				
	//				< ?php listEntriesInDatabase("homes", "list_homes.htm", 20, "id_of_neighborhood"); ? >
	//				
	//				I realize that all this might sound complicated, but most of the pages you need are auto-generated for you, so all you have to do is know how to modify what's already there. It'll be rare that you need to create new pages from scratch. 
	//				
	//				Once you understand this system, I think it becomes clear that all you need to know is the names off the database tables and fields, and then you can build any page or form you want, and make any information appear whevever you want. This same system would work on any website, so long as you knew the database tables and fields of that other site. You could build an unlimited range of database-driven websites using this approach. And you'd never need any help from a computer programmer, save for setting up the database in the first place. 
	//				
	//				By default, when you use listEntriesInDatabase, the items are listed in the order they were added to the database. Sometimes you'll want to list information in a particular order. For instance, sometimes you'll want to list the neighborhoods alphabetically. To list them alphabetically by their name, just add the field for the name to the command we've used above:
	//				
	//				< ?php listEntriesInDatabase("homes", "list_homes.htm", 20, "id_of_neighborhood", "name"); ? >
	//				
	//				If, instead, you want to list them by what city they are in, put in the name of the field that stores the city:
	//				
	//				< ?php listEntriesInDatabase("homes", "list_homes.htm", 20, "id_of_neighborhood", "city"); ? >
	//				
	//				Sometimes, of course, you'll want to show things in the reverse order. For instance, what if you want to show the 10 newest homes that were added to the database? Just use the word "reverse": 
	//				
	//				< ?php listEntriesInDatabase("homes", "list_homes.htm", "10", "id_of_neighborhood", "id", "reverse"); ? >
	//				
	//				If you forget this word, just try to guess. You can actually use the words "reverse", "reversed", "backward" or "backwards", the software will get the gist of what you mean. 
	//				
	//				What if you want to list all homes, not just 10? Just leave the number blank: 
	//				
	//				< ?php listEntriesInDatabase("homes", "list_homes.htm", "", "id_of_neighborhood", "id", "reverse"); ? >
	//				
	//				What if you want to list all the homes all of the time, not just when there is the word "id_of_neighborhood" in the URL? Then just leave that word blank, too: 
	//				
	//				< ?php listEntriesInDatabase("homes", "list_homes.htm", "", "", "name"); ? >
	//				
	//				If you want to list all homes in reverse alphabetical order do this: 
	//				
	//				< ?php listEntriesInDatabase("homes", "list_homes.htm", "", "", "name", "reverse"); ? >
	//				
	//				If you're playing around with this command and suddenly you get a web page that goes pure white, or it says on screen that there is a "Parse error" in the PHP, then 99% of the time it is because of those quote marks. Either you've a quote mark inside the quote marks, or you forgot a closing quote mark. Always check the quote marks carefully. They can be a major source of errors. 
	//				
	//				This is command is hard to understand but pretty flexibile. Please let me know if you have any questions. 
	
	global $controller; 


	
	// 11-29-06 - if $nameOfFieldThatRecordsOwningPage is set then we want for this function to do 
	// nothing and to stay invisible if there is no id. 
	//
	// 03-31-07 - I need to add in a look for "id" in currentValueFromLists, otherwise I 
	// cannot use listEntriesInDatabase inside of a loop being called by listEntriesInDatabase.
	// Right now I'm trying to use a loop inside of a loop to show the songs that belong to the 
	// albums on the store on monkeyclaus: 
	//
	// http://www.monkeyclaus.org/media/audio/store2.php
	//
	// For this to work, each arrangement showing the albums needs to find an id to get
	// the songs that belong to that album. 
	if ($nameOfFieldThatRecordsOwningPage) {
		$id = $controller->getVar("id"); 
		if (!$id) $id = $controller->command("currentValueFromLists", "id", "", "return", "", $getKeyForStorageInSingleton); 
	}
	if ($nameOfFieldThatRecordsOwningPage && !$id) {
		return false;
	} else {
		if ($databaseTableName) {		
			if ($arrangement) {					
				$query = "SELECT * FROM $databaseTableName ";
								
				if ($pagination) {
					$limitClause = " LIMIT $pagination ";
				}

				if ($nameOfFieldThatRecordsOwningPage) {
					$whereClause = " WHERE $nameOfFieldThatRecordsOwningPage=$id "; 
				}
				
				if ($orderByThisField) {
					$orderByClause = " ORDER BY $orderByThisField ";
				} else {
					$orderByClause = " ORDER BY id ";
				}
				
				if ($reverseOrder) {
					if ($orderByClause) {
						$orderByClause .= " DESC "; 
					}
				}
				$query .= "$whereClause $orderByClause $limitClause"; 		
				
				$result = $controller->command("makeQuery", $query, "listEntriesInDatabase"); 
	
				if ($result) {
					$controller->command("loop", $result, "importForm", $arrangement, $setKeyForStorageInSingleton);
				}
			} else {
				$controller->error("In listEntriesInDatabase we needed to be told what sub-template (sometimes called an arrangement) to use to format the info we get from the database. However, we got nothing. This should be the second parameter to the function.");
			}
		} else {
			$controller->error("In listEntriesInDatabase we needed to be told what database table we were suppose to get info from, but we were not told. It should be the first parameter given to the function."); 
		}
	}
}



?>