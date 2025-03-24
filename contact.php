<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $message = htmlspecialchars($_POST["message"]);

    // Database connection
    $conn = new mysqli("localhost", "root", "", "car_rental");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO contact (name, email, message) VALUES ('$name', '$email', '$message')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Message sent successfully!'); window.location='contact.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="styless.css">
</head>
<body>
    <div class="container">
        <h2>Contact Us</h2>
        <form method="POST" action="">
            <input type="text" name="name" placeholder="Your Name" required>
            <input type="email" name="email" placeholder="Your Email" required>
            <textarea name="message" placeholder="Your Message" required></textarea>
            <button type="submit">Send Message</button>
        </form>
    </div>
    <script src="contact.js"></script>

</body>
</html>
