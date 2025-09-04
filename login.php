<?php
session_start();
header('Content-Type: application/json');

// Log session at start
error_log("Session start - user_id: " . ($_SESSION["user_id"] ?? 'NOT SET'));

// CORS headers
header("Access-Control-Allow-Origin: http://localhost");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "shopizzy";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed."]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = json_decode(file_get_contents("php://input"), true);
    
    $email = trim($input['email'] ?? $_POST['email'] ?? '');
    $password = trim($input['password'] ?? $_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "All fields are required."]);
        exit();
    }

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        // Log session after login
        error_log("Login successful - user_id: " . $_SESSION['user_id']);

        echo json_encode([
            "status" => "success",
            "message" => "Login successful!",
            "user_id" => $user['id'],
            "redirect" => "home.html"
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid login credentials."]);
    }

    $stmt->close();
}

$conn->close();
?>
