<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;

    switch ($action) {
        case 'register':
            handleRegister($conn);
            break;

        case 'search':
            handleSearch($conn);
            break;

        case 'update':
            handleUpdate($conn);
            break;

        case 'delete':
            handleDelete($conn);
            break;

        default:
            echo json_encode(['status' => 'error', 'message' => 'Invalid action specified.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}

$conn->close();

// Function to handle registration
function handleRegister($conn) {
    $name = sanitizeInput($_POST['name'] ?? '');
    $age = sanitizeInput($_POST['age'] ?? '');
    $mobile = sanitizeInput($_POST['mobile'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $gender = sanitizeInput($_POST['gender'] ?? '');
    $city = sanitizeInput($_POST['city'] ?? '');

    if (empty($name) || empty($age) || empty($mobile) || empty($email) || empty($gender) || empty($city)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        return;
    }

    $stmt = $conn->prepare("INSERT INTO students (name, age, mobile, email, gender, city) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sissss", $name, $age, $mobile, $email, $gender, $city);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Registration successful for: ' . htmlspecialchars($name)]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $stmt->error]);
    }

    $stmt->close();
}

// Function to handle search
function handleSearch($conn) {
    $name = sanitizeInput($_POST['name'] ?? '');

    if (empty($name)) {
        echo json_encode(['status' => 'error', 'message' => 'Please enter a name to search.']);
        return;
    }

    $stmt = $conn->prepare("SELECT * FROM students WHERE name LIKE ?");
    $searchTerm = "%$name%";
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        echo json_encode(['status' => 'success', 'data' => $rows]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No records found.']);
    }

    $stmt->close();
}

// Function to handle updates
function handleUpdate($conn) {
    $id = sanitizeInput($_POST['id'] ?? '');
    $name = sanitizeInput($_POST['name'] ?? '');
    $age = sanitizeInput($_POST['age'] ?? '');
    $mobile = sanitizeInput($_POST['mobile'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $gender = sanitizeInput($_POST['gender'] ?? '');
    $city = sanitizeInput($_POST['city'] ?? '');

    if (empty($id)) {
        echo json_encode(['status' => 'error', 'message' => 'ID is required for updating records.']);
        return;
    }

    $stmt = $conn->prepare("UPDATE students SET name = ?, age = ?, mobile = ?, email = ?, gender = ?, city = ? WHERE id = ?");
    $stmt->bind_param("sissssi", $name, $age, $mobile, $email, $gender, $city, $id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Record updated successfully for ID: ' . htmlspecialchars($id)]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $stmt->error]);
    }

    $stmt->close();
}

// Function to handle deletion
function handleDelete($conn) {
    $id = sanitizeInput($_POST['id'] ?? '');

    if (empty($id)) {
        echo json_encode(['status' => 'error', 'message' => 'ID is required for deleting records.']);
        return;
    }

    $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Record deleted successfully for ID: ' . htmlspecialchars($id)]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $stmt->error]);
    }

    $stmt->close();
}

// Function to sanitize user input
function sanitizeInput($input) {
    return htmlspecialchars(trim($input ?? ''), ENT_QUOTES, 'UTF-8');
}
?>
