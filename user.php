<?php
session_start();
include("db.php");

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Define $user_id

// Get user details
$user_query = $conn->prepare("SELECT name FROM users WHERE id=?");
$user_query->bind_param("i", $user_id);
$user_query->execute();
$user_result = $user_query->get_result();
$user = $user_result->fetch_assoc();

// Prevent error if user data is missing
$user_name = $user ? htmlspecialchars($user['name']) : "Guest";

// Get available cars
$available_cars = $conn->query("SELECT id, name, model FROM cars WHERE status='available'");

// Get rented cars by the user
$rented_cars_query = $conn->prepare("SELECT id, name, model FROM cars WHERE status='rented' AND rented_by=?");
$rented_cars_query->bind_param("i", $user_id);
$rented_cars_query->execute();
$rented_cars = $rented_cars_query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Car Rental</title>
    <style>
        /* Navbar Styling */
      /* Modern responsive navigation */
/* Navbar Styling */
nav {
    background-color: #333;
    padding: 12px 0;
    text-align: center;
    width: 100%;
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1000;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2); /* Added shadow for depth */
}

nav ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    justify-content: center;
}

nav ul li {
    margin: 0 20px;
}

nav ul li a {
    text-decoration: none;
    color: white;
    font-size: 18px;
    padding: 8px 16px; /* Slightly larger padding */
    display: inline-block;
    transition: all 0.3s ease;
    border-radius: 5px;
}

nav ul li a:hover {
    background-color: #f4a261;
    color: #333; /* Change text color on hover for better contrast */
    transform: translateY(-2px); /* Slight lift effect */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Shadow on hover */
}

/* General Styling */
body {
    font-family: 'Poppins', sans-serif; /* Changed to a more modern font */
    background-color: #f4f4f4;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 80px 20px 20px; /* Adjusted for fixed navbar */
    margin: 0;
}

.container {
    background: white;
    padding: 25px; /* Increased padding for better spacing */
    border-radius: 12px; /* More rounded corners */
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1); /* Softer shadow */
    width: 90%; /* Slightly wider for better readability */
    max-width: 1000px; /* Increased max-width */
    margin-top: 30px; /* More space above */
    transition: transform 0.3s ease, box-shadow 0.3s ease; /* Smooth hover effect */
}

.container:hover {
    transform: translateY(-5px); /* Lift effect on hover */
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15); /* Enhanced shadow on hover */
}

h2 {
    text-align: center;
    color: #333;
    font-size: 28px; /* Slightly larger font size */
    font-weight: 700; /* Bold font weight */
    margin-bottom: 20px;
    position: relative;
}

h2::after {
    content: '';
    display: block;
    width: 60px;
    height: 4px;
    background: #f4a261; /* Accent color */
    margin: 10px auto 0; /* Centered underline */
    border-radius: 2px;
}

/* Table Styling */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background: white;
    border-radius: 8px; /* Rounded corners for the table */
    overflow: hidden; /* Ensures rounded corners are visible */
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* Subtle shadow */
}

th, td {
    padding: 12px; /* Increased padding for better spacing */
    border: 1px solid #ddd; /* Lighter border color */
    text-align: center;
    transition: background 0.3s ease; /* Smooth hover effect */
}

th {
    background: linear-gradient(135deg, #333, #444); /* Gradient background */
    color: white;
    font-weight: 600; /* Bold font weight */
    text-transform: uppercase; /* Uppercase headers */
}

tr:hover td {
    background: #f9f9f9; /* Light hover effect for rows */
}

/* Button Styling */
button {
    padding: 10px 18px; /* Slightly larger padding */
    background: linear-gradient(135deg, #28a745, #218838); /* Gradient background */
    color: white;
    border: none;
    border-radius: 8px; /* More rounded corners */
    cursor: pointer;
    font-size: 14px;
    font-weight: 600; /* Bold font weight */
    transition: all 0.3s ease; /* Smooth hover effect */
}

button:hover {
    background: linear-gradient(135deg, #218838, #28a745); /* Gradient on hover */
    transform: translateY(-2px); /* Lift effect on hover */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Shadow on hover */
}

/* Link Styling */
a {
    text-decoration: none;
    color: #007bff;
    display: block;
    text-align: center;
    margin-top: 20px; /* More space above */
    font-weight: 600; /* Bold font weight */
    transition: color 0.3s ease; /* Smooth hover effect */
}

a:hover {
    color: #0056b3; /* Darker color on hover */
}
/* Add this script to toggle mobile menu */
/* 
<script>
document.addEventListener('DOMContentLoaded', function() {
    const navToggle = document.querySelector('.nav-toggle');
    const nav = document.querySelector('nav');
    
    navToggle.addEventListener('click', function() {
        nav.classList.toggle('active');
    });
});
</script>
*/
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav>
        <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="feedback.php">Feedback</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <h2>Welcome, <?= $user_name ?>!</h2>

    <div class="container">
        <!-- Available Cars Section -->
        <h2>Available Cars for Rent</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Model</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $available_cars->fetch_assoc()) { ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= isset($row['name']) ? htmlspecialchars($row['name']) : 'N/A' ?></td>
                <td><?= isset($row['model']) ? htmlspecialchars($row['model']) : 'N/A' ?></td>
                <td>
                    <form action="rent_car.php" method="post">
                        <input type="hidden" name="car_id" value="<?= htmlspecialchars($row['id']) ?>">
                        <button type="submit" name="rent">Rent</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </table>

        <!-- Rented Cars Section -->
        <h2>Your Rented Cars</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Model</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $rented_cars->fetch_assoc()) { ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= isset($row['name']) ? htmlspecialchars($row['name']) : 'N/A' ?></td>
                <td><?= isset($row['model']) ? htmlspecialchars($row['model']) : 'N/A' ?></td>
                <td>
                    <form action="return_car.php" method="post">
                        <input type="hidden" name="return_car_id" value="<?= htmlspecialchars($row['id']) ?>">
                        <button type="submit" name="return">Return</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>

    <script src="user.js"></script>

</body>
</html>
