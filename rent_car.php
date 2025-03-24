<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['car_id'])) {
    $car_id = $_POST['car_id'];

    // Start a transaction to ensure consistency
    $conn->begin_transaction();

    try {
        // Step 1: Insert into rentals table
        $stmt = $conn->prepare("INSERT INTO rentals (user_id, car_id, rent_date, returned) VALUES (?, ?, NOW(), 0)");
        $stmt->bind_param("ii", $user_id, $car_id);
        $stmt->execute();
        $stmt->close();

        // Step 2: Update car status
        $stmt = $conn->prepare("UPDATE cars SET status='rented', rented_by=? WHERE id=?");
        $stmt->bind_param("ii", $user_id, $car_id);
        $stmt->execute();
        $stmt->close();

        // Commit transaction
        $conn->commit();

        echo "<script>alert('Car rented successfully!'); window.location.href='user.php';</script>";
    } catch (Exception $e) {
        $conn->rollback(); // Rollback in case of failure
        echo "Error: " . $e->getMessage();
    }
}
?>
