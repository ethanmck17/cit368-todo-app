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


      // this function needs to be preserved and the current login with connection needs to be replaced here
      function test_input($data){
          $data = trim($data);
          $data = stripslashes($data);
          $data = htmlspecialchars($data);

          return $data;
      }
        // User credentials
      $loginusername = test_input($_POST['l_username']);
	  $loginpassword = test_input($_POST['l_password']);
      $loginhashed = hash('sha512', $loginpassword); //128 char hash
        
    
        // Connect to DB
        $conn = mysqli_connect($db_servername, $db_username, $db_password, $db_dbname);
        // Check connection
        if (!$conn) {
      // Use minimalistic error messages
      //echo "Error: " . $sql . "<br>" . mysqli_connect_error($conn);
      echo "Oops! Error occurred.";
        }
		// Prepare statement
        $stmt = $conn->prepare("SELECT * FROM user WHERE username=? AND password=?");
        // Bind parameters
        $stmt->bind_param("ss", $loginusername, $loginhashed);
		// Execute statement
        $stmt->execute();     
        // Get result
        $result = $stmt->get_result();

        // If at least one row matches the credentials, send the user onward.
        if (mysqli_num_rows($result) > 0) {

            // Get the specific record
            $row = $result->fetch_assoc();

            // Set session vars
            $session_vars = array("logged_in"=>true, "username"=>$loginusername, "first_name"=>$row["first_name"], "last_name"=>$row["last_name"], "email"=>$row["email"], "last_activity"=>time());
            $_SESSION["vars"] = $session_vars;
            header("Location: index.php");
        }

        // Otherwise, set variable to inform user that credentials are incorrect.
        else {
            $bad_credentials=true;
        }

        // Close the connection and statement
    	$stmt->close();
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
  	
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.5.0.min.js" integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ=" crossorigin="anonymous"></script>
  <script src= "https://cdn.jsdelivr.net/npm/jquery-validation@1.19.1/dist/jquery.validate.js"> </script>

</head>
<body>
<center>
	<section id="login-form">
    	<h2>Todo</h2>
        <h5>Login</h5>
       <div class ="card w-50 center-aligned p-2">
        	<div id="loginError"></div>
            <?
            	if ($_GET["timeout"]==TRUE) {
                    echo "<h3 style='color:red'>Error: Session expired.</h3>";
                }
            ?>
        <form action="login.php" id="login" name="login" method="post" >
            <?php if ($bad_credentials) { echo "<p>Invalid user credentials.</p>"; } ?>
           <div class=" form-group">
            <input type="text" class="form-control w-50 m-3" id="l_username" name="l_username" placeholder="Username">
            <input type="password" class="form-control w-50 m-3"  id="l_password" name="l_password" placeholder="Password">
            
            </div>
          <input type="submit" class="btn btn-primary"  id="login_btn" name="login_btn" value="Go &rarr;">
    
        </form>
        </div>
        	<br>
        <a href="create.php">Create account &rarr;</a>
 	</section>
</center>

<script>
$(document).ready(function() {
	//alert('you are here');
    $.validator.addMethod("regx", function(value, element, regexpr) {  
        
    return regexpr.test(value);
}, "Invalid Characters Found.");
    
    
    $('#login').validate({
       
        onkeyup: false,
        onfocusout: false,
        rules: {
            l_username: {
                required: true,
                minlength: 5,
                regx: /^[a-zA-Z0-9]+$/ 
            },
            l_password: {
                required: true,
                minlength: 5,
                regx: /^[a-zA-Z0-9]+$/ 
            }
        },
   
		//  return false;
		
			
	
});
});
</script>
</body>

</html>