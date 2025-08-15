<?php 
include 'header.php'; 

// Only run if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database connection details
    $host = 'localhost';
    $user = 'root';
    $password = 'mysql'; 
    $dbname = 'employeeonboarding';

    // Create a database connection
    $conn = new mysqli($host, $user, $password, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    // Get form data safely with fallback
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $position = $_POST['position'] ?? '';
    $department = $_POST['department'] ?? '';
    $start_date = $_POST['start_date'] ?? '';

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO Employees (name, email, position, department, start_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $position, $department, $start_date);

    if ($stmt->execute()) {
        echo "<div style='max-width:500px; margin:30px auto; background:#e0ffe0; padding:20px; border-radius:8px; text-align:center; font-family:Arial, sans-serif;'>
                <p style='color:green; font-size:18px; font-weight:bold;'>Employee registered successfully!</p>
                <p style='font-size:16px;'>You can now view all employees by clicking on the <strong>Employee List</strong> button above.</p>
              </div>";
    } else {
        echo "<p style='color:red; text-align:center;'>Error: " . htmlspecialchars($stmt->error) . "</p>"; 
    }
    

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
      //This redirects to form
      header("Location: registration_employee.php");
      exit;
}

include 'footer.php'; 
?>
