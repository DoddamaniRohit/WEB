<?php
session_start();
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch admin from database
    $result = $conn->query("SELECT * FROM users WHERE email='$email' AND role='admin'"); // Only fetch admin role

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_name'] = $row['name'];
            $_SESSION['role'] = $row['role'];

            header("Location: admin.php"); // Redirect to Admin Dashboard
            exit();
        } else {
            echo "<script>alert('Invalid password!'); window.location.href='admin_login.php';</script>";
        }
    } else {
        echo "<script>alert('Admin not found!'); window.location.href='admin_login.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        /* Import Google Font */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

/* General Page Styling */
body {
    font-family: 'Poppins', sans-serif;
    background: url('hero.jpg') no-repeat center center/cover; /* Replace 'background.jpg' with your image */
    backdrop-filter: blur(10px); /* Blurry effect for modern look */
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Blurry Overlay */
body::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    backdrop-filter: blur(5px); /* Slight blur over the background */
    background: rgba(0, 0, 0, 0.5); /* Dark overlay for contrast */
    z-index: -1;
}

/* Login Container */
.login-container {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(20px); /* Glassmorphism effect */
    border-radius: 12px;
    padding: 30px;
    width: 350px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    text-align: center;
}

/* Heading */
h2 {
    color: white;
    font-size: 50px;
    font-weight: 600;
    margin-bottom: 20px;
}

/* Input Fields */
input {
    width: 100%;
    padding: 12px;
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.2);
    color: white;
    font-size: 16px;
    margin-bottom: 15px;
    outline: none;
}

/* Placeholder Text */
input::placeholder {
    color: rgba(255, 255, 255, 0.7);
}

/* Login Button */
button {
    width: 110%;
    padding: 12px;
    border: none;
    border-radius: 8px;
    background: #007bff;
    color: white;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
}

button:hover {
    background: #0056b3;
}

/* Forgot Password */
.forgot-password {
    margin-top: 10px;
}

.forgot-password a {
    color: white;
    text-decoration: none;
    font-size: 14px;
}

.forgot-password a:hover {
    text-decoration: underline;
}

    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Login</h2>
        <form action="admin_login.php" method="post">
            <input type="email" name="email" placeholder="Enter Email" required><br>
            <input type="password" name="password" placeholder="Enter Password" required><br>
            <button type="submit">Login</button>
        </form>
        <p><a href="login.php">User Login</a></p> <!-- Link back to user login -->
    </div>
    <script src="validation.js"></script>

</body>
</html>
