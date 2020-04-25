<?php
    $db_servername = "localhost";
    $db_username = "cit368";
    $db_password = "";
    $db_dbname = "my_cit368";

    // If the page was accessed after having pressed the login button, do login validation
    if(isset($_POST["login_btn"]))
    {
        // User credentials
      //  $username = $_POST["l_username"];
        //$password = $_POST["l_password"];
        
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
        $loginusername = test_input($_POST['l_username']);
$loginpassword = test_input($_POST['l_password']);


function test_input($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    
    return $data;
}
        // Connect to DB
        $conn = mysqli_connect($db_servername, $db_username, $db_password, $db_dbname);

        // Check connection
        if (!$conn) {
            die("Connection failed " . mysqli_connect_error());
        }
		
		// Prepare statement
        $stmt = $conn->prepare("SELECT * FROM user WHERE username=? AND password=?");
        
        // Bind parameters
        $stmt->bind_param("ss", $username, $password);

		// Execute statement
        $stmt->execute();
        
        // Get result
        $result = $stmt->get_result();

        // If at least one row matches the credentials, send the user onward.
        if (mysqli_num_rows($result) > 0) {

            // Get the specific record
            $row = $result->fetch_assoc();

            // Set session vars
            $session_vars = array("logged_in"=>true, "username"=>$username, "first_name"=>$row["first_name"], "last_name"=>$row["last_name"], "email"=>$row["email"], "last_activity"=>time());
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