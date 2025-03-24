<?php
$servername = "localhost";
$username = "root"; // Default for XAMPP
$password = ""; // Default is empty for XAMPP
$database = "car_rental"; // Replace with your actual database name

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("❌ Database Connection Failed! " . $conn->connect_error);
} else {
    // echo "✅ Database Connected Successfully!";
}
?>


