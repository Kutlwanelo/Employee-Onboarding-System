<?php 
include 'header.php'; 

// error reporting 
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$host = 'localhost';
$user = 'root';
$password = 'mysql';
$dbname = 'employeeonboarding';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
  die('Connection failed: ' . $conn->connect_error);
}

// Check if ID is passed
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  die('Invalid or missing employee ID.');
}

$id = intval($_GET['id']);

// Prepare and execute SQL
$stmt = $conn->prepare("SELECT * FROM Employees WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
  echo "Employee not found.";
  exit;
}

$employee = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Employee Profile</title>
  <style>
    .profile-container {
      width: 50%;
      margin:auto;
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-family: Arial, sans-serif;
      background-color: #f9f9f9;
    }
    h2 {
      text-align: center;
    }
    .profile-field {
      margin: 10px 0;
    }
    .label {
      font-weight: bold;
    }
  </style>
</head>
<body>
<div class="profile-container">
    <h2>Employee Profile</h2>
    <div class="profile-field"><span class="label">ID:</span> <?= htmlspecialchars($employee['id']) ?></div>
    <div class="profile-field"><span class="label">Name:</span> <?= htmlspecialchars($employee['name']) ?></div>
    <div class="profile-field"><span class="label">Email:</span> <?= htmlspecialchars($employee['email']) ?></div>
    <div class="profile-field"><span class="label">Position:</span> <?= htmlspecialchars($employee['position']) ?></div>
    <div class="profile-field"><span class="label">Department:</span> <?= htmlspecialchars($employee['department']) ?></div>
    <div class="profile-field"><span class="label">Start Date:</span> <?= htmlspecialchars($employee['start_date']) ?></div>
    <br>
    <a href="employee_list.php">← Back to Employee List</a>
</div>
</body>
</html>
<? include 'footer.php'; ?>