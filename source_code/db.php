<?php
$servername = "localhost";
$username = "root";  // Default XAMPP username
$password = "";      // Default is empty
$database = "genaiprompt"; // Your database name

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function for INSERT, UPDATE, DELETE (IUD) - only one copy!
if (!function_exists('iud_data')) 
{ // Prevent redeclaration
    function iud_data($query) {
        global $conn;  // Use global connection variable
        if (mysqli_query($conn, $query)) {
            return "success";  // Return success message
        } else {
            return "error: " . mysqli_error($conn);  // Return error message
        }
    }
}
?>
