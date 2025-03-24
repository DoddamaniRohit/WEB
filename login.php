<?php
session_start();
include("db.php");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch user from database
    $result = $conn->query("SELECT * FROM users WHERE email='$email'");
    
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['role'] = $row['role'];

            if ($row['role'] == 'admin') {
                header("Location: admin.php");  // Redirect Admin to Admin Dashboard
            } else {
                header("Location: user.php");   // Redirect User to User Dashboard
            }
            exit();
        } else {
            echo "<script>alert('Invalid password!'); window.location.href='login.php';</script>";
        }
    } else {
        echo "<script>alert('User not found!'); window.location.href='login.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link rel="stylesheet" href="style.css">
    <!-- <style>
  body {
    background-color: #f4f4f4;
    font-family: Arial, sans-serif;
  }
  input, button {
    padding: 10px;
    margin: 5px;
  }
  button {
    background-color: #28a745;
    color: white;
    border: none;
    cursor: pointer;
  }
</style> -->

    <style>
    /* General Reset */
 /* Reset and Base Styles */
 * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

/* Body Styling with Blurry Background */
body {
    background-image: url('hero.jpg'); /* Replace with your image URL */
    background-size: cover;
    background-position: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh;
    margin: 0;
    position: relative;
}

/* Blurry Overlay */
body::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: inherit;
    filter: blur(10px); /* Adjust blur intensity */
    z-index: -1; /* Place it behind the content */
}

/* Navbar Styling */
nav {
    background-color: rgba(51, 51, 51, 0.8); /* Semi-transparent background */
    width: 100%;
    padding: 12px 0;
    position: absolute;
    top: 0;
    left: 0;
    text-align: center;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    z-index: 1000; /* Ensure navbar is above the blurry background */
}

nav ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

nav ul li {
    display: inline;
    margin: 0 20px;
}

nav ul li a {
    text-decoration: none;
    color: white;
    font-size: 16px;
    padding: 8px 16px;
    transition: all 0.3s ease;
    border-radius: 5px;
}

nav ul li a:hover {
    background: #f4a261;
    color: #333;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

/* Login Form Container */
.container {
    background: rgba(255, 255, 255, 0.9); /* Semi-transparent white background */
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    width: 350px;
    text-align: center;
    margin-top: 80px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    z-index: 1; /* Ensure form is above the blurry background */
}

.container:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

/* Heading */
h2 {
    color: #333;
    margin-bottom: 20px;
    font-size: 24px;
    font-weight: 700;
    position: relative;
}

h2::after {
    content: '';
    display: block;
    width: 60px;
    height: 4px;
    background: #f4a261;
    margin: 10px auto 0;
    border-radius: 2px;
}

/* Input Fields */
input {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

input:focus {
    border-color: #f4a261;
    box-shadow: 0 0 8px rgba(244, 162, 97, 0.3);
    outline: none;
}

/* Login Button */
button {
    width: 100%;
    padding: 12px;
    background: linear-gradient(135deg, #28a745, #218838);
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 16px;
    margin-top: 15px;
    transition: all 0.3s ease;
}

button:hover {
    background: linear-gradient(135deg, #218838, #28a745);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

/* Register Link */
a {
    text-decoration: none;
    color: #007bff;
    display: block;
    margin-top: 15px;
    font-weight: 600;
    transition: color 0.3s ease;
}

a:hover {
    color: #0056b3;
    text-decoration: underline;
}

/* For JavaScript functionality (add to your JS file):
document.addEventListener('DOMContentLoaded', function() {
    const navToggle = document.querySelector('.nav-toggle');
    const nav = document.querySelector('nav');
    
    navToggle.addEventListener('click', function() {
        nav.classList.toggle('active');
    });
});
*/
</style>

</head>
<body>
    <h2>User Login</h2>
    <form action="login.php" method="post">
        <input type="email" name="email" placeholder="Enter Email" required><br>
        <input type="password" name="password" placeholder="Enter Password" required><br>
        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a></p>
</body>
<script src="validation.js"></script>

</html>
