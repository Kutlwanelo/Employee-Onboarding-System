<?php 
include 'header.php';

// Enable error reporting
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

// Check if ID is set and numeric
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Invalid or missing employee ID.');
}

$id = intval($_GET['id']);

// Confirm employee exists 
$stmt = $conn->prepare("SELECT name FROM Employees WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Employee not found.";
    exit;
}

$employee = $result->fetch_assoc();
$stmt->close();

// Delete record 
$stmt = $conn->prepare("DELETE FROM Employees WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "<div style='max-width:500px; margin:30px auto; background:#ffe0e0; padding:20px; border-radius:8px; text-align:center; font-family:Arial, sans-serif;'>
            <p style='color:red; font-size:18px; font-weight:bold;'>Employee deleted successfully.</p>
            <p style='font-size:16px;'>Return to the <strong>Employee List</strong> using the button above.</p>
          </div>";
} else {
    echo "<p style='color:red; text-align:center;'>Error: " . htmlspecialchars($stmt->error) . "</p>";
}

$stmt->close();
$conn->close();

?>
<?php include 'footer.php'; ?>