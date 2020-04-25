<?php
/**
*	Overview: This page processes user requests to delete existing tasks owned by them.
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
// If this page isn't being accessed via the right POST request, redirect to index.
if(!isset($_POST["edit_btn"])) {
	header("Location: index.php");
}
////	Variable declarations	//
// Information from POST submission
$task = $_POST["task_name"];
$due = $_POST["task_due"];
$location = $_POST["task_location"];
$status = $_POST["task_status"];
$task_id = $_POST["task_id"];
// DB credentials
$db_servername = "localhost";
$db_username = "cit368";
$db_password = "";
$db_dbname = "my_cit368";
// Session variables
$username = $_SESSION["vars"]["username"];

	// Connect to DB
	$conn = mysqli_connect($db_servername, $db_username, $db_password, $db_dbname);

	// Check connection
	if (!$conn) {
      // Use minimalistic error messages
      //echo "Error: " . $sql . "<br>" . mysqli_error($conn);
      echo "Oops! Error occurred.";
	}
    else {
		echo "Success.";
    }

	// DB query: update specified task using specified info.
    // Prepare statment
    $stmt = $conn->prepare("UPDATE task SET name=?, location=?, due=?, status=? WHERE username=? AND id=?");
    
    // Bind parameters
    $stmt->bind_param("sssisi",$task,$location,$due,$status,$username,$task_id);

	// Execute, verify success
    if ($stmt->execute()) {
    	echo "Record updated successfully";
        header("Location: index.php");
    } else {
      // Use minimalistic error messages
      //echo "Error: " . $sql . "<br>" . mysqli_error($conn);
      echo "Oops! Error occurred.";
    }

	// Close the connection
    $stmt->close();
    mysqli_close($conn);
?>
