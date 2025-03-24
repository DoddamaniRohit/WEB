<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['return_car_id'])) {
    $return_car_id = $_POST['return_car_id'];

    $sql = "UPDATE cars SET status='available', rented_by=NULL WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $return_car_id);

    if ($stmt->execute()) {
        echo "<script>alert('Car returned successfully!'); window.location.href='user.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

?>
