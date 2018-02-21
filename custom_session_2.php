<?php
    session_start();
?>
<!DOCTYPE html>
<html  xmlns="http://www.w3.org/1999/xhtml">

<!-- 
    using session attributes to control a multi-state
    application

    you can run this via the URL:
    http://nrs-projects.humboldt.edu/~jks403/hw11/custom_session_2.php

    by: Joshua Stewart
    last modified: 2017-may -02
-->

<head>  
    <title> Joshua'S WineBAr </title>
	<meta charset="utf-8" />
	<img src="winery.jpg" alt="Joshua's winery"/> 

    <?php
        /* these are bringing in PHP functions I am calling below */

        require_once("login.php");
        require_once("hsu_conn_sess.php");
	require_once("choose_next.php");
	require_once("wine_sales.php");
	require_once("droponme.php");
	require_once("completeme.php");
	require_once("get_wine_price.php");


	
    ?>

    <link href="http://users.humboldt.edu/smtuttle/styles/normalize.css" 
          type="text/css" rel="stylesheet" />
<link href="custom.css" 
          type="text/css" rel="stylesheet" />

</head> 

<body>
    <h1> Joshua's WineBar </h1>
	<video width="350" height="350" controls>
  	<source src="pouring-red-wine.mp4" type="video/mp4">
  
  	Your browser does not support the video tag.
	</video>



    <?php
    
	// this session is for the creation of pages
		// array key checks for a value
	// save this value "function_call" 
	// into the variable called 'next_page'
	// inside the superglobal array called '$_SESSION'
/////////////////////////////////////////////////////////////////////
// if ive never made any next pages yet....
if (! array_key_exists('next_page', $_SESSION))
    	{	// first page calling the login function
        logintome();
        $_SESSION['next_page'] = "pick_options";
    	}
    	// go to llogin page.. second screen options screen 
	elseif ($_SESSION['next_page'] == "pick_options")
   	 {
        choose_next_screen();//load options page.. with exit, get sales, or select wine
        $_SESSION['next_page'] = "function_calls";
   	 }
	// this is the first case.... get wine sales from the function 
   	// Num Sales is the button name.... num_sales from the last screen 
	// did the previous one that got me here, return the numSales submission? was the numsales button clicked? 
	elseif(($_SESSION['next_page'] == "function_calls")&&(array_key_exists('numSales', $_POST))) 
	{	// wine sales for the month of january
		wine_sales();
		// another screen letting us know where we are going
		$_SESSION['next_page'] = "options";
	 }
	
	// if the user/ client clicked on exit.... 
	elseif(($_SESSION['next_page'] == "options")&&(array_key_exists('done', $_POST))) 
	{
	// if they click done.... return to login and destroy session.... must login again 
	session_destroy();
        session_regenerate_id(TRUE);
        session_start();
	logintome();
        $_SESSION['next_page'] = "pick_options";
	
		
	}

	

	
	 
	// if the user clicked on select-wine.. we will call drop_on me... of the wine-classes 
	elseif(($_SESSION['next_page'] == "function_calls")&&(array_key_exists('select_wine', $_POST)))
	{
	droponme();
	$_SESSION['next_page'] = "options";

	}
	
	elseif(($_SESSION['next_page'] == "options")&&(array_key_exists('wine_price', $_POST)))
	{	
		
		
		get_wines_price();
       		 $_SESSION['next_page'] = "options";

	}	
	elseif(($_SESSION['next_page'] == "options")&&(array_key_exists('return', $_POST)))
	{
		
	droponme();
	$_SESSION['next_page'] = "options";

	}
	// if the user clicked back.... this defaults to the options page
	elseif($_SESSION['next_page'] == "options")
	{
	// go back to options... 
	choose_next_screen();//load options page.. with exit, get sales, or select wine
        $_SESSION['next_page'] = "function_calls";
	
	}	

	// if they clicked exit, destroy session back to login screen .....
	elseif ($_SESSION['next_page'] == "function_calls")
   	 {
	session_destroy();
        session_regenerate_id(TRUE);
        session_start();
	// take them back to login screen 
	logintome();
        $_SESSION['next_page'] = "pick_options";
	// name = 'next_page' value = 'pick_options' 


   	}
    
	
    // I hope I can't reach this...!

    else
    {
        ?>
	<img src="images.jpg" alt="Hi JOshua" > 
            here! </strong> </p>



        
        <?php

        session_destroy();
        session_regenerate_id(TRUE);
        session_start();

       
    }

    require_once("328footer.html");
?>
</body>
</html>