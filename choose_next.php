<?php
	function choose_next_screen()
	
	{
	if(!array_key_exists('username', $_SESSION))
	{
	$username = strip_tags($_POST['username']);
	$password = $_POST['password'];
	$_SESSION['password']= $password;
	$_SESSION['username'] = $username;

	}
	else
	{
	$username = $_SESSION['username'];
	$password = $_SESSION['password'];
	
	}
        // set up db connection string
	require_once("hsu_conn.php");
	$conn=hsu_conn_sess($username,$password);	
        
	// exiting if can't log in

        if (! $conn)
        {
        ?>
            <p> Could not log into Oracle, sorry. </p>

        <?php
            require_once("328footer.html");
            
	
	}

		?>
 
<form method="post" 
                  action="<?= htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES) ?>">    
<fieldset> 
	<legend> What would you like to do? </legend> 
	<div class="buttons">
		<input type="submit" name="numSales" value="Get Number of sales" />
		<input type="submit" name="select_wine" value="Select Wine" />
		<input type="submit" name="Exit" value="Exit" />
		
		</div>
</fieldset> 
		

</form> 

<?php

	//require_once("328footer.html");
	}
?> 

