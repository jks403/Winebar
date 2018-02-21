 <?php
function wine_sales()
{
	 // I am a little paranoid -- I'm stripping
        //   tags from the username 

        // now both are local variables
	$password= $_SESSION['password'];
	$username= $_SESSION['username'];





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
	else
	{
	$call_string= 'begin :number_of_sales := num_sales; end;';
	$stmt = oci_parse($conn, $call_string);
	oci_bind_by_name($stmt, ":number_of_sales",
                         $number_of_sales, 4);
        oci_execute($stmt, OCI_DEFAULT);

        ?>
	<form method="post" 
                  action="<?= htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES) ?>">
        		
        <p> Joshua's winebar - Number of sales:<?= $number_of_sales ?> </p>
        	<div class="buttons">
		<!-- this submit button takes the user back to the options page--> 
		<input type="submit" name="options" value="back to options" />
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