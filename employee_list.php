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

// Fetch employee records using prepared statement
$sql = "SELECT * FROM Employees";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

// Store results in an array
$employees = [];
while ($row = $result->fetch_assoc()) {
    $employees[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h2 {
            text-align: center;
        }
        table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
        }
        a {
            margin: 0 4px;
            text-decoration: none;
            color: #0066cc;
        }
    </style>
</head>
<body>

<h2>Employee List</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Position</th>
        <th>Department</th>
        <th>Start Date</th>
        <th>Actions</th>
    </tr>
    <?php if (count($employees) > 0): ?>
        <?php foreach ($employees as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['position']) ?></td>
                <td><?= htmlspecialchars($row['department']) ?></td>
                <td><?= htmlspecialchars($row['start_date']) ?></td>
                <td>
                    <a href="employee_profile.php?id=<?= $row['id'] ?>">View</a> |
                    <a href="update_employee.php?id=<?= $row['id'] ?>">Edit</a> |
                    <a href="delete_employee.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this employee?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="7">No employees found.</td></tr>
    <?php endif; ?>
</table>

</body>
</html>
<?php include 'footer.php'; ?>