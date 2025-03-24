<?php
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = "user";  // Default role for new users

    // Check if email already exists
    $checkEmail = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($checkEmail->num_rows > 0) {
        echo "<script>alert('Email already registered!'); window.location.href='register.php';</script>";
    } else {
        // Insert user into database
        $sql = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')";
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Registration Successful! Please Login.'); window.location.href='login.php';</script>";
        } else {
            echo "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <style>
   * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    background-image: url('https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/3.6.95/css/blur-abstract.jpg'), linear-gradient(135deg, #3a1c71 0%, #d76d77 50%, #ffaf7b 100%);
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    margin: 0;
    padding: 20px;
}

/* Overlay for blurry effect */
body::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.4);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    z-index: -1;
}

.container {
    background: rgba(255, 255, 255, 0.9);
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
    width: 100%;
    max-width: 400px;
    text-align: center;
    transition: all 0.3s ease;
    position: relative;
    z-index: 1;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.18);
}

.container:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25);
}

h2 {
    color: #2d3748;
    margin-bottom: 25px;
    font-size: 28px;
    font-weight: 600;
    position: relative;
    padding-bottom: 10px;
}

h2:after {
    content: '';
    position: absolute;
    width: 50px;
    height: 3px;
    background-color: #4299e1;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
}

.form-group {
    position: relative;
    margin-bottom: 20px;
    text-align: left;
}

input {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid rgba(226, 232, 240, 0.8);
    border-radius: 6px;
    font-size: 16px;
    transition: all 0.3s ease;
    background-color: rgba(247, 250, 252, 0.8);
}

input:focus {
    outline: none;
    border-color: #4299e1;
    box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.15);
    background-color: #fff;
}

.error-message {
    color: #e53e3e;
    font-size: 14px;
    margin-top: 5px;
    display: none;
    text-align: left;
    transition: all 0.2s ease;
}

button {
    width: 100%;
    padding: 12px;
    background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 16px;
    font-weight: 600;
    margin-top: 10px;
    transition: all 0.3s ease;
    letter-spacing: 0.5px;
    box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
}

button:hover {
    background: linear-gradient(135deg, #3182ce 0%, #2c5282 100%);
    transform: translateY(-2px);
    box-shadow: 0 7px 14px rgba(50, 50, 93, 0.15), 0 3px 6px rgba(0, 0, 0, 0.1);
}

button:active {
    transform: translateY(0);
}

.login-link {
    margin-top: 20px;
    font-size: 15px;
    color: #4a5568;
}

.login-link a {
    text-decoration: none;
    color: #4299e1;
    font-weight: 600;
    margin-left: 5px;
    transition: color 0.3s ease;
}

.login-link a:hover {
    color: #3182ce;
    text-decoration: underline;
}

/* Password strength indicator */
.password-strength {
    height: 5px;
    margin-top: 5px;
    border-radius: 3px;
    transition: all 0.3s ease;
    background-color: rgba(226, 232, 240, 0.5);
    overflow: hidden;
}

.strength-meter {
    height: 100%;
    width: 0%;
    transition: all 0.3s ease;
}

.weak {
    width: 33%;
    background-color: #f56565;
}

.medium {
    width: 66%;
    background-color: #ed8936;
}

.strong {
    width: 100%;
    background-color: #48bb78;
}

/* Form validation styles */
input.invalid {
    border-color: #f56565;
    background-color: rgba(255, 245, 245, 0.9);
}

input.valid {
    border-color: #48bb78;
    background-color: rgba(240, 255, 244, 0.9);
}

/* Show error messages */
input.invalid + .error-message {
    display: block;
}

/* Form divider */
.form-divider {
    display: flex;
    align-items: center;
    margin: 20px 0;
}

.form-divider::before,
.form-divider::after {
    content: "";
    flex: 1;
    border-bottom: 1px solid rgba(226, 232, 240, 0.8);
}

.form-divider span {
    padding: 0 10px;
    color: #a0aec0;
    font-size: 14px;
}

/* Social login */
.social-login {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin: 15px 0;
}

.social-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(247, 250, 252, 0.8);
    border: 1px solid rgba(226, 232, 240, 0.8);
    cursor: pointer;
    transition: all 0.3s ease;
}

.social-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    background: white;
}

/* Glassmorphism effect */
.glass-effect {
    background: rgba(255, 255, 255, 0.25);
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    border-radius: 10px;
    border: 1px solid rgba(255, 255, 255, 0.18);
}

/* Moving gradient background animation */
@keyframes gradientBG {
    0% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
    100% {
        background-position: 0% 50%;
    }
}

/* Alternative animated background if using a gradient instead of image */
.animated-bg {
    background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
    background-size: 400% 400%;
    animation: gradientBG 15s ease infinite;
}

/* Responsive adjustments */
@media (max-width: 480px) {
    .container {
        padding: 20px 15px;
    }
    
    h2 {
        font-size: 24px;
    }
    
    input, button {
        padding: 10px;
    }
}

/* Animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.container {
    animation: fadeIn 0.5s ease forwards;
}

/* Option to use parallax effect */
.parallax-bg {
    transform: translateZ(-1px) scale(2);
}
    </style>
</head>
<body>

<div class="container">
    <h2>User Registration</h2>
    <form id="registrationForm" action="register.php" method="post">
        <input type="text" name="name" id="name" placeholder="Enter Name" required>
        <p class="error-message" id="nameError">Name must be at least 3 characters long</p>

        <input type="email" name="email" id="email" placeholder="Enter Email" required>
        <p class="error-message" id="emailError">Enter a valid email address</p>

        <input type="password" name="password" id="password" placeholder="Enter Password" required>
        <p class="error-message" id="passwordError">Password must be at least 8 characters, include 1 number & 1 special character (!@#$%^&*).</p>


        <button type="submit">Register</button>
    </form>
    <p class="login-link">Already have an account? <a href="login.php">Login here</a></p>
</div>

<script src="register.js"></script>

</body>
</html>

