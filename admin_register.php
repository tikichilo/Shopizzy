<?php
session_start();
$host = "localhost";
$user = "root"; 
$pass = "";
$dbname = "shopizzy";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Registration</title>
    <link rel="stylesheet" href="admin.css"> <!-- Link your CSS here -->
</head>
<body>
<?php
// Process registration form
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash password for security

    $query = "INSERT INTO admins (fullname, username, email, password) VALUES ('$fullname', '$username', '$email', '$password')";

    if ($conn->query($query)) {
        echo "<div class='success-message'>Registration successful! <a href='admin_login.html'>Login here</a></div>";
    } else {
        echo "<div class='error-message'>Error: " . $conn->error . "</div>";
    }
}
?>
</body>
</html>
