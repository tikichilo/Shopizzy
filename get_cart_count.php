<?php
session_start();
header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "shopizzy";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    echo json_encode(["count" => 0]);
    exit();
}

if (!isset($_SESSION["user_id"])) {
    echo json_encode(["count" => 0]);
    exit();
}

$user_id = $_SESSION["user_id"];
$sql = "SELECT SUM(quantity) as total FROM cart WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

echo json_encode(["count" => $row["total"] ?? 0]);

$stmt->close();
$conn->close();
?>
