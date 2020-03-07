<?php
    /**
    *	Overview:  this page both creates and processes requests for user login.
	*			The HTML form at the bottom is used to create the login request which is then forwarded to 
	*			this same page. When the page detects that a POST request has been submitted, it validates
	*			and processes it.
    *	Authors: Ethan McKenzie, Brandy Vincent, James Andrews, Stewart Majewsky
    *	Course: CIT 368
    *	Instructor: C. Suzadail
    *	Date: 03/06/2020
    **/
	// Begin or continue existing session
    session_start();

    // If the user is already logged in, redirect to index.
    if ($_SESSION["vars"]["logged_in"]) {
        header("Location: index.php");
    }

    ////	Variable declarations	////
    $db_servername = "localhost";
    $db_username = "cit368";
    $db_password = "";
    $db_dbname = "my_cit368";

    // If the page was accessed after having pressed the login button, do login validation
    if(isset($_POST["login_btn"]))
    {
        // User credentials
        $username = $_POST["username"];
        $password = $_POST["password"];
        
        //passes $password and hashes it with bcrypt, the array is machine performance cost, 10 is the default and is low expense
        // Hash the entered password
       // $options = ['cost' => 11];
        
       //hash = password_hash($password, PASSWORD_BCRYPT, $options);
        //This set verifies the password
        
        	//echo $hash;
     //   if (password_verify($password, $hash)) {
          //  if(password_needs_rehash($hash, PASSWORD_DEFAULT, $options)) {
         //   $newHash = password_hash($password, PASSWORD_DEFAULT, $options);
         //   }

      //  }
        
        // Connect to DB
        $conn = mysqli_connect($db_servername, $db_username, $db_password, $db_dbname);

        // Check connection
        if (!$conn) {
            die("Connection failed " . mysqli_connect_error());
        }
		
        // DB query
        $query = "SELECT * FROM user WHERE username='$username' AND password='$password'";

        // Send query
        $result = mysqli_query($conn, $query);

        // If at least one row matches the credentials, send the user onward.
        if (mysqli_num_rows($result) > 0) {

            // Get the specific record
            $row = $result->fetch_assoc();

            // Set session vars
            $session_vars = array("logged_in"=>true, "username"=>$username, "first_name"=>$row["first_name"], "last_name"=>$row["last_name"], "email"=>$row["email"]);
            $_SESSION["vars"] = $session_vars;
            header("Location: index.php");
        }

        // Otherwise, set variable to inform user that credentials are incorrect.
        else {
            $bad_credentials=true;
        }

        // Close the connection
        mysqli_close($conn);

    }
    else {
        $bad_credentials = false;
    }
?>
<!DOCTYPE html>
<html>
<head>
	<title>Todo | LOGIN</title>
  	<link rel="stylesheet" type="text/css" href="./style/style.css?<?php echo date('l jS \of F Y h:i:s A'); ?>">
</head>
<body>
<center>
	<section id="login-form">
    	<h2>Todo</h2>
        <h5>Login</h5>
        <form action="login.php" method="post">
            <?php if ($bad_credentials) { echo "<p>Invalid user credentials.</p>"; } ?>
            <input type="text" name="username" placeholder="Username">
            <input type="password" name="password" placeholder="Password">
            <input type="submit" name="login_btn" value="Go &rarr;">
        </form>
        	<br>
        <a href="create.php">Create account &rarr;</a>
 	</section>
</center>
</body>
</html>