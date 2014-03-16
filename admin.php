<?php
include("pdsSiteSpecificFiles/initiate.php"); 

$loggedInId = $controller->command("getUserIdFromUserLoggedInTable");	
if (!$loggedInId) {
	$controller->command("renderPartial", "login.htm"); 	
} else { 
  $arrayOfUserInfo = $controller->command("getUserInfo"); 
  if ($arrayOfUserInfo["security_level"] != "admin") { 
    $controller->command("renderPartial", "loginFullPageYouAreNotAnAdmin.htm");
  } else { ?>

<!DOCTYPE html>
<html>
<head>
<title>admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="/pdsCss/admin.css" media="all" rel="stylesheet" type="text/css" />
<script src="/pdsJs/admin.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">


</head>
<body>

<?php if(!isset($hideFrame) || $hideFrame!=true){ ?>
<div class="container">
	<div class="row">
		<div class="span12">
			<h3><a href="/">CMS</a> Admin</h3>
			<p>
				<a href="/admin.php?page=admin_calendar">Calendar</a> ||
			        <a href="/admin.php?page=admin_calendar_consolidated">Reservation calendar </a> ||
				<a href="/admin.php?page=admin_list_proposals_to_book_events">Proposals to book an event</a> ||
				<a href="/admin.php?page=admin_edit_wine">Wines</a> ||
				<a href="/admin.php?page=admin_edit_user">Users (search)</a> ||
				<a href="/admin.php?page=admin_edit_user_not_active">Users in a list</a> ||
				<a href="/admin.php?page=admin_list_email_templates">Email templates</a> ||
				<a href="/admin.php?page=admin_calendar&choiceMade[]=logout">Logout</a> 

			</p>
			<hr style="margin:0 0 10px;">
		</div>
	</div>
</div>
<?php } ?>


    <div class="container">
      <?php echo $controller->command("showUserMessages"); ?>			   
    </div>

    <?php 	
       $thisPage = $controller->getVar("page"); 
       if (!$thisPage) $thisPage = "admin_calendar.htm";
       $controller->command("renderPartial", $thisPage); 	
    ?>



<div class="container">

       <br>
       <br>
       <br>
       <br>Copyright 2013
</div>


</body>
</html>


<?php } } ?>