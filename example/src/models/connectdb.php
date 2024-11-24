<?php 
  $servername = "localhost";
  $username = "root";
  $password = "";
  $db = "ananas";

	// Create connection
	$DBConnect = mysqli_connect($servername, $username, $password);

	// Check connection
	if (!$DBConnect) 
	{
  		die("Connection failed: " . mysqli_connect_error());
	}

  // Select database
  $ananas_db = mysqli_select_db ($DBConnect, $db);
  if (!$ananas_db)
	{
		die("Cannot use database" . mysqli_error($DBConnect));
  }
