<?php 
   include("pdsSiteSpecificFiles/initiate.php"); 
?>


<!DOCTYPE html>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <title>Alley Light - Charlottesville, VA</title>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/jquery-1.9.1.js"></script>
    <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

    <script type='text/javascript' src="/pdsJs/private.js"></script>
    <link rel="stylesheet" type="text/css" href="/pdsCss/index.css">

  </head>
  <body>
    <div class="center">

     <div class="inner_logo">
     <a href="/"><img src="/pdsImg/logo.gif"></a>
     </div>

    <nav>

            <a href="/private.php">make a reservation</a>
    	         &nbsp;  &nbsp; ||  &nbsp;  &nbsp; 
            <a href="/index.php?page=parlor_book_an_event">book an event</a>
    	         &nbsp;  &nbsp; ||  &nbsp;  &nbsp; 
            
    <?php
       $loggedInId = $controller->command("getUserIdFromUserLoggedInTable");	
       if (!$loggedInId) {
    ?>
            <a href="/index.php?page=login"  border="0">login</a>		
    <?php } else { ?>	  
	    <?php $arrayOfUserInfo = $controller->command("getUserInfo"); ?>
            <a href="/index.php?choiceMade[]=logout"  border="0">logout</a>
	    <br><br><a href="/private.php?page=private_reservations_for_this_member">Welcome back <?php echo $arrayOfUserInfo["first_name"]; ?> <?php echo $arrayOfUserInfo["last_name"]; ?></a>
    <?php } ?>

    </nav>



    <?php  
       $loggedInId = $controller->command("getUserIdFromUserLoggedInTable");	
       $thisPage = $controller->getVar("page"); 
       if (!$thisPage) $thisPage = "index_reservations";
       if ($loggedInId && $thisPage == 'login') $thisPage = "index_reservations";
       $controller->command("renderPartial", $thisPage); 	
    ?>






    </div>


    <footer>

      <p><em>Est. Feb. 2014</em></p>
      <p>Call or email for reservations <br>
	434-296-5003<br>
	thealleylight@gmail.com</p>

    </footer>


  </body>
</html>

