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
      die("Connection failed " . mysqli_connect_error());
    }
    else {
      echo "Connected successfully";
    }

    // DB query: insert new task using provided data
    $query = "INSERT INTO task(name,location,due,status,username) VALUES('$task', '$location', '$due', 0, '$username')";

	// If the query is successful, redirect to login
    if (mysqli_query($conn, $query)) {
      echo "New record created successfully";
      header("Location: index.php");
    // Otherwise, inform the debugger
    } else {
      echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

	// Close the connection
    mysqli_close($conn);
?>
