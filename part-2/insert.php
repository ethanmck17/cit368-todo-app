<?php
    /**
    *	Overview: This page processes user requests to create new tasks.
    *	Authors: Ethan McKenzie, Brandy Vincent, James Andrews, Stewart Majewsky
    *	Course: CIT 368
    *	Instructor: C. Suzadail
    *	Date: 03/06/2020
    **/
    // Continue existing session
    session_start();

    // If the user isn't logged in, redirect to login
    if (!$_SESSION["vars"]["logged_in"]){
        header("Location: login.php");
    }
    
    // Add session timeout functionality. Sessions expire after 30 minutes.
    if (isset($_SESSION['vars']['last_activity']) && (time() - $_SESSION['vars']['last_activity'] > 1800)) {
        // Last request was over 30 minutes ago
        session_unset();     // Unset $_SESSION variable for the run-time 
        session_destroy();   // Destroy session data in storage
        header("Location: login.php?timeout=TRUE");
    }
    $_SESSION['vars']['last_activity'] = time(); // Update last activity time stamp

    // If the correct POST request wasn't received, redirect to index
    if(!isset($_POST["insert_btn"]))
    {
        header("Location: index.php");
    }
    ////	Variable declarations	////
    // Information from POST submission
    $task = $_POST["task_name"];
    $due = $_POST["task_due"];
    $location = $_POST["task_location"];
    $status = 0;
    // DB credentials
    $db_servername = "localhost";
    $db_username = "cit368";
    $db_password = "";
    $db_dbname = "my_cit368";
    // User credential
    $username = $_SESSION["vars"]["username"];

    // Connect to database
    $conn = mysqli_connect($db_servername, $db_username, $db_password, $db_dbname);

    // Check connection
    if (!$conn) {
      // Use minimalistic error messages
      //echo "Error: " . $sql . "<br>" . mysqli_connect_error($conn);
      echo "Oops! Error occurred.";
    }
    else {
      echo "Connected successfully";
    }

    // Prepare statement
    $stmt = $conn->prepare("INSERT INTO task(name,location,due,status,username) values(?,?,?,?,?)");
    
    // Bind parameters
    $stmt->bind_param("sssis",$task,$location,$due,$status,$username);

	// If the query is successful, redirect to login
    if ($stmt->execute()) {
      echo "New record created successfully";
      header("Location: index.php");
    // Otherwise, inform the debugger
    } else {
      // Use minimalistic error messages
      //echo "Error: " . $sql . "<br>" . mysqli_error($conn);
      echo "Oops! Error occurred.";
    }

	// Close the connection and statment
    $stmt->close();
    mysqli_close($conn);
?>
