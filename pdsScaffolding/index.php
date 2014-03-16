<?php 
include("../pdsSiteSpecificFiles/initiate.php");
$userInfo = $controller->command("getUserInfo"); 
if ($userInfo["security_level"] != "admin") { ?>

Sorry, you must be an administrator on this site, and you must be logged in. 


<?php } else { ?>





<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>PHP On Rails Scaffolding</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="scaffolding.css" rel="stylesheet" type="text/css">
</head>
<body>

<div id="wrapper">


<div id="options">
<p><strong>Create, alter, delete</strong></p>
<ul>
<li><a href="index.php?formName=createTableForm">Create a table</a></a></li>
<li><a href="index.php?formName=addFieldForm.htm">Add a field</a></li>
<li><a href="index.php?formName=deleteFieldForm.htm">Delete a field</a></li>
<li><a href="index.php?formName=deleteTableForm.htm">Delete a table</a></li>
<li><a href="index.php?formName=showAllTables.htm">Show all tables</a></li>
<li><a href="index.php?formName=oneToManyForm.htm">Many-to-One</a></li>
<li><a href="index.php?formName=manyToManyForm.htm">Many-to-Many</a></li>
<li><a href="index.php?formName=regenerateForm.htm">Regenerate forms</a></li>
</ul>

<p><strong>Add constraints?</strong></p>
<ul>
<li><a href="index.php?formName=mandatory.htm">Mandatory fields</a></li>
<li><a href="index.php?formName=unique.htm">Unique fields</a></li>
<li><a href="index.php?formName=required.htm">Required values</a></li>
<li><a href="index.php?formName=forbidden.htm">Forbidden values</a></li>
</ul>


<p><strong>Setup</strong></p>
<ul>
<li><a href="index.php?formName=createConfiguration.htm">Create configuration</a></li>
<li><a href="index.php?formName=emailAnAdministrator.htm">Email an administrator</a></li>
</ul>


<p><strong>Unstyled but working forms:</strong></p>

<?php $controller->command("showAllFormsInDirectoryAsLinksForScaffolding"); ?>

</div>


<div id="adminpanel">


<?php $controller->command("showUserMessages"); ?>
<hr>
<?php $controller->command("showForms", "createTableForm"); ?> 



</div>




</div><!--wrapper-->
</body>
</html>




<?php } ?>