<?php



function getAllWinesInAnArray() {
  // 2013-11-26 - the wine table looks like this: 
  //
  //  `id` int(11) NOT NULL AUTO_INCREMENT,
  //  `name` varchar(255) NOT NULL,
  //  `vintage` year(4) NOT NULL,
  //  `region` varchar(255) NOT NULL,
  //  `color` varchar(255) NOT NULL,
  //  `varietal` varchar(255) NOT NULL,
  //  `price` decimal(10,2) DEFAULT '0.00',
  //  `country` varchar(255) NOT NULL,
  //  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  //  `updated_at` datetime NOT NULL,
  //
  // we need to get all this into a select box. 

  global $controller; 

  $query = "SELECT * FROM lk_wines order by name, vintage";
  $arrayOfWines = $controller->command("databaseFetchSql", $query, "getAllWinesInASelectBox"); 

  $arrayOfWinesNames = array(); 

  foreach ($arrayOfWines as $row) {
    extract($row); 
    $name = str_replace('"', ' ', $name);
    $name = str_replace("'", ' ', $name);
    $nameOfWine = $name . ' / year: ' . $vintage . ' / price: $' .$price . ' / varietal: ' . $varietal . ' / region: ' .   $region . ' / color: ' . $color;
    $arrayOfWinesNames[$id] = $nameOfWine; 
  }

  return $arrayOfWinesNames; 
}