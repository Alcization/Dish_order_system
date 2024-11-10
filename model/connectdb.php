<?php
    // default XAMPP credentials 
    $servername = "localhost";
    $username = "root";
    $pass = "";
    $db = "pizza"; // name of database

    // connect to db
    $conn = new mysqli($servername, $username, $pass);

    // check connection
    if (!$conn) die("Connection failed: " . mysqli_connect_error());

    // select database
    $pizza_db = mysqli_select_db($conn, $db);
    if (!$pizza_db) die("Database selection failed: " . mysqli_error($conn));

    
