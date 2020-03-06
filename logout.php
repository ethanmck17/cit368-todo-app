<?php
    /**
    *	Overview:  Processes logout requests.
    *	Authors: Ethan McKenzie, Brandy Vincent, James Andrews, Stewart Majewsky
    *	Course: CIT 368
    *	Instructor: C. Suzadail
    *	Date: 03/06/2020
    **/
    // Open the existing session.
    session_start();
    
    // Unset session variables
    session_unset(); 
    
    // Destroy the session
    session_destroy(); 
    
    // Redirect to login
    header("Location: login.php");
?>