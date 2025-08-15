<?php include 'header.php';
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

// Get ID from URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Invalid or missing employee ID.');
}

$id = intval($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    $name = $_POST['name'];
    $email = $_POST['email'];
    $position = $_POST['position'];
    $department = $_POST['department'];
    $start_date = $_POST['start_date'];

    $stmt = $conn->prepare("UPDATE Employees SET name = ?, email = ?, position = ?, department = ?, start_date = ? WHERE id = ?");
    $stmt->bind_param("sssssi", $name, $email, $position, $department, $start_date, $id);

    if ($stmt->execute()) {
        echo "<div style='max-width:500px; margin:30px auto; background:#e0ffe0; padding:20px; border-radius:8px; text-align:center; font-family:Arial, sans-serif;'>
                <p style='color:green; font-size:18px; font-weight:bold;'>Employee updated successfully!</p>
                <p style='font-size:16px;'>You can now view all employees by clicking on the <strong>Employee List</strong> button above.</p>
              </div>";
    } else {
        echo "<p style='color:red; text-align:center;'>Error: " . htmlspecialchars($stmt->error) . "</p>"; 
    }

    $stmt->close();
    $conn->close();
    exit;
} else {
    // Fetch current employee data
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
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Employee</title>
    <style>
        form {
            width: 50%;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            background: #f9f9f9;
            border-radius: 8px;
        }
        label {
            display: block;
            margin-top: 10px;
        }
        input[type="text"], input[type="email"], input[type="date"] {
            width: 100%;
            padding: 8px;
            margin-top: 4px;
        }
        input[type="submit"] {
            margin-top: 20px;
            padding: 10px 15px;
        }
    </style>
</head>
<body>

<h2 style="text-align:center;">Update Employee</h2>

<form method="POST" action="">
    <label for="name">Name:</label>
    <input type="text" name="name" required value="<?= htmlspecialchars($employee['name']) ?>">

    <label for="email">Email:</label>
    <input type="email" name="email" required value="<?= htmlspecialchars($employee['email']) ?>">

    <label for="position">Position:</label>
    <input type="text" name="position" required value="<?= htmlspecialchars($employee['position']) ?>">

    <label for="department">Department:</label>
    <input type="text" name="department" required value="<?= htmlspecialchars($employee['department']) ?>">

    <label for="start_date">Start Date:</label>
    <input type="date" name="start_date" required value="<?= htmlspecialchars($employee['start_date']) ?>">

    <input type="submit" value="Update Employee">
</form>

</body>
</html>
<?php include 'footer.php'; ?>