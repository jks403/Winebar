
<?php
	function droponme()
	{
		//first time we run this, do this stuff here:
		if(array_key_exists('username', $_POST))
		{
			//grab the values 'username' and 'password'
			//from the POST superglobal array, named $_POST
			//store them locally so we can use them
			$username = strip_tags($_POST['username']);
			$password = $_POST['password'];
			
			//storing these values into $_SESSION for later use
			$_SESSION['username'] = $username;
			$_SESSION['password'] = $password;
		}
		else
		{
			//if we run it again, do this stuff:
			$username = $_SESSION['username'];
			$password = $_SESSION['password'];
		}

		//this is the way we connect to NRS projects, only.
		$db_conn_str = "(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)
                                       (HOST = cedar.humboldt.edu)
                                       (PORT = 1521))
                                       (CONNECT_DATA = (SID = STUDENT)))";
	//this is the string we need to make connection
        $conn = oci_connect($username, $password, $db_conn_str);

	//if we can't connect, display a warning.
        if (! $conn)
        {
        	?>
            <p> Could not log into Oracle, sorry. </p>

            <?php
           		require_once("328footer.html");
           		session_destroy();
            		
			//exit is a call that doesn't process anything below,
			//"exits" anything beyond this point.
			exit;        
        }

		//if we can connect, display all this stuff below.
		?>
		<p class="title"> Dropdown </p>
		<?php
			//make a new query
		// php calling my sql query... 			
		$query_wine = 'select wine_num, wine_class '.
                      	  'from wine';
			//get it ready
			$stmt = oci_parse($conn, $query_wine);
        //send it to the server
	oci_execute($stmt, OCI_DEFAULT);
        ?>

	
        <form method="post"  
              action="<?= htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES) ?>" 
			  name="drop_down">
        	<fieldset>
            	<legend class="legend"> Select a Wine_class  </legend>
				
				<label for="wine" > wine_name: </label>
				<div>
					<select id="wine" name="wine_name" size="3" class="select">
              	<?php
			
			while(oci_fetch($stmt))
              		{
                		$curr_wine = oci_result($stmt, "WINE_CLASS");
                		$curr_number = oci_result($stmt, "WINE_NUM");
                ?>
                		<option value= <?= $curr_wine ?>> <?= $curr_wine ?> </option>
                <?php
              		}
			//basic maintenance, close what you open.
                	oci_free_statement($stmt);
                	oci_close($conn);
                ?>
					</select>
				</div>
				
				<div class="buttons">
					<input type="submit" name="wine_price" value="wine price" />
					<input type="submit" name="back" value="options" />
				</div>
			</fieldset>
		</form>
        <?php 
	}
?>