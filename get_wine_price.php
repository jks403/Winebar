 <?php
function get_wines_price()
{
	 
	
	$user_wine_class= strip_tags(trim($_POST["wine_name"]));
	// may have to plug in $user_wine_class 
        // now both are local variables
	$password= $_SESSION['password'];
	$username= $_SESSION['username'];





	// set up db connection string
	require_once("hsu_conn_sess.php");
	$conn=hsu_conn_sess($username,$password);	
        
	// exiting if can't log in

        if (! $conn)
        {
        ?>
            <p> Could not log into Oracle, sorry. </p>

        <?php
            require_once("328footer.html");
            
	
	}
	else
	{
	//   begin.. exec..start a procedure or function from php ..PL/sQL function call.
		// saving the user_input_variable name I created... calling get_wine_price function
		// price_of _wine is the out_put of the function returning to the client..
		// saving into a local variable $ call_string
	$call_string= 'begin :price_of_wine := get_wine_price(:user_wine_class); end;';
	$stmt = oci_parse($conn, $call_string);
	// Price_of_wine is what the function is returning
	oci_bind_by_name($stmt, ":price_of_wine",
                         $price_of_wine, 4);
	oci_bind_by_name($stmt, ":user_wine_class",$user_wine_class);
        oci_execute($stmt, OCI_DEFAULT);

	
        ?>
	<form method="post" 
                  action="<?= htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES) ?>">
        <p> Your wine selected is <?= $user_wine_class ?> - wine_price: <?= $price_of_wine ?> </p>
        	<div class="buttons">
		<!-- this submit button takes the user back to the options page--> 
		<input type="submit" name="options" value="Options" />
		<input type="submit" name="return" value="Back" />
		<input type="submit" name="done" value="Exit" />

		</div>


	</form> 
        
        <?php
        // FREE your statement, CLOSE your connection!
	// 
        oci_free_statement($stmt);
        oci_close($conn);
    	}

   	 require_once("328footer.html");
  
	
}

?>