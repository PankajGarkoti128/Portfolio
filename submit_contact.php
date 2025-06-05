<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Set correct content-type for JSON response
header('Content-Type: application/json');

// Database connection
$servername = "127.0.0.1"; // Change as needed
$username = "root"; // Change as needed
$password = ""; // Change as needed
$dbname = "pankaj"; // Change as needed

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]);
    exit();
}

// Get the data from the POST request safely
$full_name = isset($_POST['fullname']) ? trim($_POST['fullname']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

// Basic validation
if (empty($full_name) || empty($email) || empty($message)) {
    echo json_encode(["success" => false, "message" => "Please fill in all required fields."]);
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["success" => false, "message" => "Invalid email address."]);
    exit();
}

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO ContactMessages (full_name, email, message) VALUES (?, ?, ?)");
if (!$stmt) {
    echo json_encode(["success" => false, "message" => "Prepare failed: " . $conn->error]);
    exit();
}
$stmt->bind_param("sss", $full_name, $email, $message);

// Execute the statement
if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Execution failed: " . $stmt->error]);
}

// Close connections
$stmt->close();
$conn->close();
?>
