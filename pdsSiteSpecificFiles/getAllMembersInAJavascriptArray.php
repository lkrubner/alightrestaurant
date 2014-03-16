<?php



function getAllMembersInAJavascriptArray() {
  // 2013-12-07 - 
  //
  // we need to get all this into a Javascript array. If you look here:
  //
  // http://jqueryui.com/autocomplete/
  //
  // You will see that we need this:
  //
  //   <script>
  //   $(function() {
  //     var availableTags = [
  //       "ActionScript",
  //       "AppleScript",
  //       "Asp",
  //       "BASIC",
  //       "C",
  //       "C++",
  //       "Clojure",
  //       "COBOL",
  //       "ColdFusion",
  //       "Erlang",
  //       "Fortran",
  //       "Groovy",
  //       "Haskell",
  //       "Java",
  //       "JavaScript",
  //       "Lisp",
  //       "Perl",
  //       "PHP",
  //       "Python",
  //       "Ruby",
  //       "Scala",
  //       "Scheme"
  //     ];
  //     $( "#tags" ).autocomplete({
  //       source: availableTags
  //     });
  //   });
  //   </script>

  global $controller; 

  $query = "SELECT user_id, first_name, last_name, email FROM users order by last_name, first_name";
  $arrayOfMembers = $controller->command("databaseFetchSql", $query, "getAllMembersInASelectBox"); 

  $stringOfHtml = '';

  $i=0;
  foreach ($arrayOfMembers as $row) {
    if ($i) $stringOfHtml .= ",\n\t\t\t";
    extract($row);  
    $nameOfMember = "$first_name $last_name -- $email";
    $stringOfHtml .= " \"$nameOfMember\" ";
    $i++;
  }

  return $stringOfHtml; 
}