<?php

	function wine_sales()

	$passsword= $_SESSION['password']; 
	$username= $_SESSION ['username']; 
	require_once("hsu_conn.php");
	$conn=hsu_conn_sess($username, $password);

	if(!$conn)
	{
	?>
		<p> Could not connect ot Oracle </p>

		<?php
			require_once("328footer.html");
	}

	else
	{
		$call_string= 'begin :number_of_sales := num_sales; end;';
		$stmt = oci_parse($conn, $call_string);
		oci_bind_by_name($stmt, ":number_of_sales", $number_of_sales, 4);
		oci_execute($stmt, OCI_DEFAULT);
		?> 

	<form method="post"
		action="<?=htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES) ?>">

	<p> JOSHUA'S wINEBAR- Number of Sales:<