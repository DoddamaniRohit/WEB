




<?php

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");


session_start();
include("db.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle Add Car Request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_car'])) {
    $id = $_POST['car_id'];
    $name = $_POST['name'];
    $model = $_POST['model'];
    $price_per_day = $_POST['price_per_day'];

    $query = "INSERT INTO cars (id, name, model, price_per_day, status) VALUES ('$id', '$name', '$model', '$price_per_day', 'available')";
    if ($conn->query($query) === TRUE) {
        echo "<script>alert('Car added successfully!'); window.location.href='admin.php';</script>";
    } else {
        echo "<script>alert('Error adding car!'); window.location.href='admin.php';</script>";
    }
}

// Handle Update Car Request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $old_car_id = $_POST['old_car_id']; // Previous ID
    $new_car_id = $_POST['car_id']; // New ID entered by admin
    $name = $_POST['name'];
    $model = $_POST['model'];
    $price_per_day = $_POST['price_per_day'];

    if ($old_car_id != $new_car_id) {
        $conn->begin_transaction();
        try {
            $conn->query("UPDATE rentals SET car_id='$new_car_id' WHERE car_id='$old_car_id'");
            $conn->query("UPDATE cars SET id='$new_car_id', name='$name', model='$model', price_per_day='$price_per_day' WHERE id='$old_car_id'");
            $conn->commit();
            echo "<script>alert('Car updated successfully!'); window.location.href='admin.php';</script>";
        } catch (Exception $e) {
            $conn->rollback();
            echo "<script>alert('Error updating car!'); window.location.href='admin.php';</script>";
        }
    } else {
        $query = "UPDATE cars SET name='$name', model='$model', price_per_day='$price_per_day' WHERE id='$old_car_id'";
        if ($conn->query($query) === TRUE) {
            echo "<script>alert('Car updated successfully!'); window.location.href='admin.php';</script>";
        } else {
            echo "<script>alert('Error updating car!'); window.location.href='admin.php';</script>";
        }
    }
}

// Handle Delete Car Request
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM cars WHERE id='$id'");
    echo "<script>alert('Car deleted successfully!'); window.location.href='admin.php';</script>";
}

// Fetch Cars with Rental Info
// $result = $conn->query("SELECT cars.*, IFNULL(users.name, 'Not Rented') AS rented_by 
//                         FROM cars 
//                         LEFT JOIN rentals ON cars.id = rentals.car_id 
//                         LEFT JOIN users ON rentals.user_id = users.id");


$result = $conn->query("
    SELECT cars.*, 
           CASE 
               WHEN cars.rented_by IS NULL THEN 'Not Rented'
               ELSE users.name 
           END AS rented_by
    FROM cars 
    LEFT JOIN users ON cars.rented_by = users.id
");


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Car Rental</title>
    <style>
        /* General Page Styling */
        body {
    font-family: 'Roboto', 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    color: #333;
    line-height: 1.5;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
}

.page-container {
    width: 100%;
    max-width: 1400px;
    margin: 0 auto;
    padding: 30px 20px;
    box-sizing: border-box;
}

/* Header */
h2 {
    text-align: center;
    color: #2c3e50;
    font-size: 32px;
    font-weight: 600;
    margin: 20px 0 40px;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
    position: relative;
}

h2:after {
    content: '';
    display: block;
    width: 80px;
    height: 4px;
    background: #4a6fa5;
    margin: 15px auto 0;
    border-radius: 2px;
}

/* Add Car Form Styling */
.add-car-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 15px;
    background: white;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    padding: 30px;
    width: 80%;
    max-width: 900px;
    margin: 0 auto 50px;
}

.add-car-container input {
    width: 90%;
    padding: 12px 15px;
    border-radius: 6px;
    border: 1px solid #d1d1d1;
    font-size: 15px;
    transition: all 0.2s;
}

.add-car-container input:focus {
    border-color: #4a6fa5;
    outline: none;
    box-shadow: 0 0 0 2px rgba(74, 111, 165, 0.2);
}

/* Add Car Button */
.add-car-btn {
    background-color: #4a6fa5;
    color: white;
    font-size: 16px;
    padding: 12px 24px;
    border-radius: 6px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
    text-align: center;
    display: block;
    width: fit-content;
    margin: 15px auto 0;
    box-shadow: 0 3px 6px rgba(0,0,0,0.1);
}

.add-car-btn:hover {
    background-color: #3a5a87;
    transform: translateY(-2px);
    box-shadow: 0 5px 10px rgba(0,0,0,0.15);
}

/* Divider */
.divider {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto 50px;
    text-align: center;
    position: relative;
    height: 20px;
}

.divider:before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 2px;
    background: rgba(0,0,0,0.1);
}

.divider span {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    padding: 0 20px;
    position: relative;
    display: inline-block;
    color: #4a6fa5;
    font-weight: 500;
}

/* Table Container */
.table-container {
    width: 90%;
    max-width: 1200px;
    margin: 0 auto;
}

/* Table Styling */
table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    background: white;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 40px;
}

th {
    background-color: #4a6fa5;
    color: white;
    padding: 16px 12px;
    font-size: 15px;
    font-weight: 600;
    text-align: center;
    border-bottom: 1px solid #3a5a87;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

td {
    padding: 14px 12px;
    text-align: center;
    border-bottom: 1px solid #e0e0e0;
    font-size: 14px;
}

tr:hover td {
    background-color: #f5f7fa;
}

tr:last-child td {
    border-bottom: none;
}

/* Input Fields in Table */
table input {
    width: 90%;
    padding: 10px 12px;
    border: 1px solid #d1d1d1;
    border-radius: 6px;
    text-align: center;
    font-size: 14px;
    transition: all 0.2s;
}

table input:focus {
    border-color: #4a6fa5;
    outline: none;
    box-shadow: 0 0 0 2px rgba(74, 111, 165, 0.2);
}

/* Buttons */
.btn {
    padding: 9px 16px;
    border: none;
    cursor: pointer;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.2s;
    margin: 0 5px;
}

.update-btn {
    background-color: #5b8c51;
    color: white;
    box-shadow: 0 2px 4px rgba(91, 140, 81, 0.2);
}

.update-btn:hover {
    background-color: #4a7342;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(91, 140, 81, 0.3);
}

.delete-btn {
    background-color: #a94442;
    color: white;
    box-shadow: 0 2px 4px rgba(169, 68, 66, 0.2);
}

.delete-btn:hover {
    background-color: #923a38;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(169, 68, 66, 0.3);
}

/* Logout Link */
.logout-container {
    text-align: center;
    margin-top: 30px;
    padding: 20px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    width: 90%;
    max-width: 1200px;
    margin-left: auto;
    margin-right: auto;
}

.logout-container a {
    text-decoration: none;
    font-weight: 600;
    color: #a94442;
    font-size: 16px;
    transition: all 0.2s;
    display: inline-block;
    padding: 8px 16px;
    border-radius: 6px;
}

.logout-container a:hover {
    color: #923a38;
    background: #f5f5f5;
    transform: translateY(-2px);
}

/* Wow Effect - Background Animation */
@keyframes gradientAnimation {
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

body {
    background: linear-gradient(-45deg, #EEF2F7, #C3CFE2, #D6E4F0, #E9F1FB);
    background-size: 400% 400%;
    animation: gradientAnimation 15s ease infinite;
}

/* Responsive Design */
@media (max-width: 992px) {
    .add-car-container, .table-container, .logout-container {
        width: 95%;
    }
}

@media (max-width: 768px) {
    .add-car-container {
        padding: 20px;
    }
    
    h2 {
        font-size: 26px;
    }
    
    .btn {
        padding: 8px 12px;
        font-size: 13px;
    }
    
    table {
        display: block;
        overflow-x: auto;
    }
}

/* Smooth Page Appearance */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

body {
    animation: fadeIn 0.8s ease;
}
    </style>
</head>
<body>

    <h2>Admin Panel - Manage Cars</h2>

    <!-- Add Car Form -->
    <form id="addCarForm" method="post" class="add-car" onsubmit="return validateForm('addCarForm')">
        <input type="number" name="car_id" placeholder="ID" required>
        <input type="text" name="name" placeholder="Car Name" required>
        <input type="text" name="model" placeholder="Model" required>
        <input type="number" name="price_per_day" placeholder="Price/Day" required>
        <button type="submit" name="add_car" class="btn update-btn">Add Car</button>
    </form>

    <!-- Display Existing Cars -->
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Model</th>
            <th>Price</th>
            <th>Status</th>
            <th>Rented By</th>
            <th>Actions</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <form id="updateCarForm<?= $row['id'] ?>" method="post" onsubmit="return validateForm('updateCarForm<?= $row['id'] ?>')">
                    <td><input type="text" name="car_id" value="<?= $row['id'] ?>" required></td>
                    <input type="hidden" name="old_car_id" value="<?= $row['id'] ?>">
                    <td><input type="text" name="name" value="<?= $row['name'] ?>" required></td>
                    <td><input type="text" name="model" value="<?= $row['model'] ?>" required></td>
                    <td><input type="number" name="price_per_day" value="<?= $row['price_per_day'] ?>" required></td>
                    <td><?= $row['status'] ?></td>
                    <td><?= empty($row['rented_by']) || $row['rented_by'] == 'NULL' ? 'Not Rented' : $row['rented_by']; ?></td>

                    <td>
                        <button type="submit" name="update" class="btn update-btn">Update</button>
                        <a href="admin.php?delete=<?= $row['id'] ?>" class="btn delete-btn">Delete</a>
                    </td>
                </form>
            </tr>
        <?php } ?>
    </table>

    <p><a href="logout.php">Logout</a></p>

    <script src="admin.js"></script>

</body>
</html>

