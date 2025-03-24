<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $rating = htmlspecialchars($_POST["rating"]);
    $comments = htmlspecialchars($_POST["comments"]);

    // Database connection
    $conn = new mysqli("localhost", "root", "", "car_rental");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO feedback (name, email, rating, comments) VALUES ('$name', '$email', '$rating', '$comments')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Feedback submitted successfully!'); window.location='feedback.php';</script>";
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
    <title>Feedback</title>
    <link rel="stylesheet" href="styless.css">
</head>
<body>
    <div class="container">
        <h2>Feedback</h2>
        <form method="POST" action="" onsubmit="return validateFeedbackForm()">

            <input type="text" name="name" placeholder="Your Name" required>
            <input type="email" name="email" placeholder="Your Email" required>
            <select name="rating" required>
                <option value="">Rate Us</option>
                <option value="5">Excellent (5 ⭐)</option>
                <option value="4">Very Good (4 ⭐)</option>
                <option value="3">Good (3 ⭐)</option>
                <option value="2">Fair (2 ⭐)</option>
                <option value="1">Poor (1 ⭐)</option>
            </select>
            <textarea name="comments" placeholder="Your Comments" required></textarea>
            <button type="submit">Submit Feedback</button>
        </form>
    </div>
    <script src="feedback.js"></script>

</body>
</html>
