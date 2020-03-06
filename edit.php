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
		die("Failure: " . mysqli_connect_error());
	}
    else {
		echo "Success.";
    }

	// DB query: update specified task using specified info.
	$query = "UPDATE task SET
    name='$task', 
    location='$location', 
    due='$due', 
    status=" . $status . "
    WHERE username='$username' AND id=" . $task_id;

	// Debugging tools
    if (mysqli_query($conn, $query)) {
    	echo "Record updated successfully";
        header("Location: index.php");
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }

	// Close the connection
    mysqli_close($conn);
?>
