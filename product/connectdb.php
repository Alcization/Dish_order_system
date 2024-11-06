<?php
    function open_connect() 
    {
        // default XAMPP credentials 
        $servername = "localhost";
        $username = "root";
        $pass = "";
        $db = "dish_ordering"; // Name of database

        // connect to db
        $conn = new mysqli($servername, $username, $pass, $db);

        /* check connection */
        if (!$conn) 
            die("Connection failed: " . mysqli_connect_error());

        return $conn;
    }

    function close_connect($conn) 
    {
        $conn->close();
    }
