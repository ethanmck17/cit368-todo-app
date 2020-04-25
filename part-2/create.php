<?php
/**
*	Overview: this page both creates and processes requests for new user accounts.
*			the HTML form at the bottom is used to create the request which is then forwarded to 
*			this same page. When the page detects that a POST request has been submitted, it validates
*			and processes it.
*	Authors: Ethan McKenzie, Brandy Vincent, James Andrews, Stewart Majewsky
*	Course: CIT 368
*	Instructor: C. Suzadail
*	Date: 03/06/2020
*
**/
// Create or continue existing session
session_start();

// If the user is already logged in, redirect to the landing page.
if ($_SESSION["vars"]["logged_in"]){
	header("Location: login.php");
}



// If the page was arrived at via POST request, the form on the page has been submitted.
// → continue with account creation process.
	if(isset($_POST["create_btn"])){
      ////	Variable declarations	////
      // DB credentials
      $db_servername = "localhost";
      $db_username = "cit368";
      $db_password = "";
      $db_name = "my_cit368";

	  // Clean up input data
      function test_input($data){
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);

      return $data;
    }
  	// User credentials
  	$first_name = test_input($_POST["first_name"]);
  	$last_name = test_input($_POST["last_name"]);
  	$email = test_input($_POST["email"]);
  	$username = test_input($_POST["username"]);
  	$password = test_input($_POST["password"]);
	$pwhashed = hash('sha512', $password);
   // $options = array('cost' =>11);
	// Connect to database
	$conn = mysqli_connect($db_servername, $db_username, $db_password, $db_name);

	// Check connection
	if (!$conn) {
		die("Connection failed " . mysqli_connect_error());
	}
    
    //passes $password and hashes it with bcrypt, the array is machine performance cost, 10 is the default and is low expense
	//$hash = password_hash($password, PASSWORD_BCRYPT, array('cost'=>11));
    
	//echo $hash;
	
    //This set verifies the password
	//if (password_verify($password, $hash)) {
	//	if(password_needs_rehash($hash, PASSWORD_DEFAULT, $options)) {
	//	$newHash = password_hash($password, PASSWORD_DEFAULT, $options);
     //   }
    	
//	}
    // Prepare statement
    $stmt = $conn->prepare("INSERT INTO user(first_name,last_name,email,username,password) values(?,?,?,?,?)");
    
    // Bind parameters
    $stmt->bind_param("sssss",$first_name,$last_name,$email,$username,$pwhashed);

	// If the query is successful, redirect to login
    if ($stmt->execute()) {
      echo "New record created successfully";
      header("Location: login.php");
    // Otherwise, inform the debugger
    } else {
      // Use minimalistic error messages
      //echo "Error: " . $sql . "<br>" . mysqli_error($conn);
      echo "Oops! Error occurred.";
    }

	// Close the connection and statment
    $stmt->close();
	mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Todo | CREATE</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

</head>
<body>
<center>
	<section id="create-form">
    	<h1>Create Account</h1>
        <form action="create.php" name="create" method="post">
            <?php if ($bad_credentials) { echo "<p>Invalid credentials.</p>"; } ?>
            <input type="text" name="first_name" placeholder="First Name">
            <input type="text" name="last_name" placeholder="Last Name">
            <input type="email" name="email" placeholder="Email">
            	<br>
            <input type="text" name="username" placeholder="Username">
            <input type="password" name="password" placeholder="Password">
            	<br><br>
            <input type="submit" name="create_btn" value="Go &rarr;">
        </form>
        	<br>
        <a href="login.php">Login to existing account &rarr;</a>
 	</section>
</center>
</body>
</html>