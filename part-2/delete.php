<?php
/**
*	Overview: this page processes user requests to delete tasks.
*	Authors: Ethan McKenzie, Brandy Vincent, James Andrews, Stewart Majewsky
*	Course: CIT 368
*	Instructor: C. Suzadail
*	Date: 03/06/2020
*
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

    // If the page is accessed via the wrong POST request, redirect to index
    if(!isset($_POST["delete_btn"]))
    {
        header("Location: index.php");
    }

    ////	Variable declarations	////
    // DB credentials
    $db_servername = "localhost";
    $db_username = "cit368";
    $db_password = "";
    $db_dbname = "my_cit368";
    // User credential
    $username = $_SESSION["vars"]["username"];
    // Get task id from form submission
    $task_id = $_POST["task_id"];

    // Connect to database
    $conn = mysqli_connect($db_servername, $db_username, $db_password, $db_dbname);

    // Check connection
    if (!$conn) {
      // Use minimalistic error messages
      //echo "Error: " . $sql . "<br>" . mysqli_error($conn);
      echo "Oops! Error occurred.";
    }
    else {
      echo "Connected successfully";
    }

    // DB query: delete task using specified ID
    // Prepare statement
    $stmt = $conn->prepare("DELETE FROM task WHERE id=? AND username=?");
    
    // Bind parameters
    $stmt->bind_param("is", $task_id, $username);

    // If the query is successful, redirect to index. Otherwise, explain why not.
    if ($stmt->execute()) {
      echo "Record deleted successfully";
      header("Location: index.php");
    } else {
      // Use minimalistic error messages
      //echo "Error: " . $sql . "<br>" . mysqli_error($conn);
      echo "Oops! Error occurred.";
    }

    // Close the DB connection and statement
    $stmt->close();
    mysqli_close($conn);
?>
