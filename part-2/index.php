<?php
/**
*	Overview: This is the landing page for the site. In CRUD fashion, it allows users to:
				* Create new tasks
				* Read existing tasks
                * Update existing tasks
                * Delete existing tasks
*	Authors: Ethan McKenzie, Brandy Vincent, James Andrews, Stewart Majewsky
*	Course: CIT 368
*	Instructor: C. Suzadail
*	Date: 03/06/2020
**/
  // Continues session opened at login.
  session_start();
  
  // If the user isn't logged in, redirect to login.
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
  
  //// Variable declarations
  // Database variables
  $db_servername = "localhost";
  $db_username = "cit368";
  $db_password = "";
  $db_dbname = "my_cit368";
  // Session variables
  $username = $_SESSION["vars"]["username"];
  // In the DB, statuses are stored as integers: 0, 1, 2, and 3, each corresponding to the following classifications
  // $statuses is the key for this correspondence.
  $statuses = array("Not started", "On track", "At risk", "Complete");
  // DB query: check to see if any tasks have been created by the user
  $query = "SELECT * FROM task WHERE username='$username'";

  // Connect to db
  $conn = mysqli_connect($db_servername, $db_username, $db_password, $db_dbname);

  // Check connection
  if (!$conn) {
      // Use minimalistic error messages
      //echo "Error: " . $sql . "<br>" . mysqli_connect_error($conn);
      echo "Oops! Error occurred.";
  }

  // Send query
  $result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Todo | HOME</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

  	<link rel="stylesheet" type="text/css" href="./style/style.css?<?php echo date('l jS \of F Y h:i:s A'); ?>">
</head>
<body>
<center>
	<h1>Welcome, <?php echo $_SESSION["vars"]["first_name"]; ?></h1>
    <a href="logout.php">Log out &rarr;</a>
    	<br><br>
    <section>
      <h2>Tasks:</h2>
      <?php
          if (mysqli_num_rows($result) > 0) {
            // For each task, create a form for editing and deleting
            while($row = mysqli_fetch_assoc($result)) {
                echo "<form action='edit.php' class='inline-block' method='post'>";
                echo "<input type='text' class='invisible-input' name='task_name' value='" . $row["name"]. "'>";
                echo "<input type='text' class='invisible-input' name='task_location' value='" . $row["location"]. "'>";
                echo "<input type='date' class='invisible-input' name='task_due' value='" . $row["due"]. "'>";
                echo "<select name='task_status' class='invisible-input' value='" . $statuses[$row["status"]] . "'>
                          <option value='" . $row["status"] . "'>" . $statuses[$row["status"]] . "</option>
                          <option value=0>Not started</option>
                          <option value=1>On track</option>
                          <option value=2>At risk</option>
                          <option value=3>Complete</option>
                      </select>";
                echo "<input type='hidden' name='task_id' value='" . $row["id"] . "'>";
                echo "<input type='submit' name='edit_btn' value='Update &rarr;'>";
                echo "</form>";
                echo "<form action='delete.php' class='inline-block' method='post'>";
                echo "<input type='hidden' name='task_id' value='" . $row["id"] . "'>";
                echo "<input type='submit' name='delete_btn' value='Delete &rarr;'>";
                echo "</form><br>";
            }
          } else {
            echo "No tasks found.";
          }
      ?>
    </section>
    	<br>
    <section>
    	<h3>Add Task</h3>
      <form action="insert.php" name="task" method="post">
          <input type="text" name="task_name" placeholder="Add task...">
          <input type="text" name="task_location" placeholder="Task Location">
          <input type="date" name="task_due">
          <input type="submit" name="insert_btn" value="Add &rarr;">
      </form>
    </section>
</center>

<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
<!--<script src= "js/validate_index.js"></script>-->
<script src= "js/validate_login.js"></script>


</body>
</html>