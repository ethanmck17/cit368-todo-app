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
      die("Connection failed " . mysqli_connect_error());
    }
    else {
      echo "Connected successfully";
    }

    // DB query: delete task using specified ID
    $query = "DELETE FROM task WHERE id=$task_id AND username='$username'";

    // If the query is successful, redirect to index. Otherwise, explain why not.
    if (mysqli_query($conn, $query)) {
      echo "Record deleted successfully";
      header("Location: index.php");
    } else {
      echo "Error deleting record: " . mysqli_error($conn);
    }

    // Close the DB connection
    mysqli_close($conn);
?>
