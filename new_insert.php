<!DOCTYPE html>
<html  xmlns="http://www.w3.org/1999/xhtml">

<!--
    demo inserting a new department,
    committing that change, and
    setting Oracle bind variables from PHP

    by: Peter Johnson
    adapted by: Sharon Tuttle
    last modified: 2017-03-08
-->

<head>  
    <title> New_Wine_data </title>
    <meta charset="utf-8" />

    <link href="http://users.humboldt.edu/smtuttle/styles/normalize.css" 
          type="text/css" rel="stylesheet" />
    <link href="lect08-2.css" type="text/css"
          rel="stylesheet" />
</head> 

<body> 

    <h1> new_sales_insert</h1>

    <?php
    // when first called, show an enter-dept-info form

    if (! array_key_exists("username", $_POST))
    {
        // if there is no username in the $_POST array,
        //     they need a login form
        ?>

        <form method="post"
              action="<?= htmlentities($_SERVER['PHP_SELF'],
                                       ENT_QUOTES) ?>">
        <fieldset>
            <fieldset>
                <legend> Enter Oracle username/password: 
                    </legend>

                <label for="user_name"> Username: </label>
                <input type="text" name="username" id="user_name" /> 

                <label for="pass_word"> Password: </label>
                <input type="password" name="password" 
                       id="pass_word" />
            </fieldset>
            <fieldset>
                <legend> Enter new department's info </legend>

                <label for="dept_num"> Dept Number: </label>
                <input type="text" name="deptnum" id="dept_num" 
                       required="required" />

                <label for="dept_name"> Dept Name: </label>
                <input type="text" name="deptname" id="dept_name" 
                       required="required" />

                <label for="dept_loc"> Dept Location: </label>
                <input type="text" name="deptloc" id="dept_loc" 
                       required="required" />
            </fieldset>

            <div class="submit">
                <input type="submit" value="Add to Dept" />
                </div>
        </fieldset>
        </form>
        
        <?php
    }
        
    // otherwise, try to log in and insert a new department
 
    else     
    {
        // I will be maybe slightly over-careful
        //    and strip any tags from username

        $username = strip_tags($_POST['username']);

        // not doing ANYTHING w/password EXCEPT trying to
        //    log in with it -- so HOPE OK to use as-is

        $password = $_POST['password']; 

        $db_conn_str = "(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)
                                       (HOST = cedar.humboldt.edu)
                                       (PORT = 1521))
                            (CONNECT_DATA = (SID = STUDENT)))";

        // try to connect
        
        $conn = oci_connect($username, $password, $db_conn_str);

        if (! $conn)
        {
            ?>
            <p> Could not log into Oracle, sorry. </p>
            
            <?php
            require_once("328footer.html");
            ?>
</body>
</html>
            <?php
            exit;
        }
   
        // if get here -- I connected!

        // grab the entered new dept info, stripping tags
        //    if there are any (because there shouldn't be...)

        $new_dept_num = strip_tags($_POST['deptnum']);
        $new_dept_name = strip_tags($_POST['deptname']);
        $new_dept_loc = strip_tags($_POST['deptloc']);        

        // instead of CONCATENATING these to a string to build
        //    my SQL insert statement, (which COULD be vulnerable
        //    to SQL injection), I will instead use BIND VARIABLES
        //    within that, and bind values to those bind variables

        $insert_string = 'insert into dept 
                         values
                          (:new_dept_num, :new_dept_name, 
                         :new_dept_loc)';

        $insert_stmt = oci_parse($conn, $insert_string);

        // then bind values to the bind variables

        oci_bind_by_name($insert_stmt, ":new_dept_num", $new_dept_num);
        oci_bind_by_name($insert_stmt, ":new_dept_name", $new_dept_name);
        oci_bind_by_name($insert_stmt, ":new_dept_loc", $new_dept_loc);

        // now, can execute the now-completed insert -- so happens
        //    that the insert here will return the number of
        //    rows inserted

        $num_inserted = oci_execute($insert_stmt, OCI_DEFAULT);

        if ($num_inserted != 1)
        {
            ?>
            <p> SORRY, no row inserted! </p>
            <?php
        }
        else
        {
            ?>
            <p> 1 row inserted! </p>
            <?php
            
            // so now I am happy to commit!

             oci_commit($conn);
        }

        // free statement, close connection

        oci_free_statement($insert_stmt);
        oci_close($conn);
    }

    require_once("328footer.html");             
?>

</body> 
</html> 