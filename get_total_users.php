<?php
require 'db_config.php';

$sql = "SELECT COUNT(*) AS total_users FROM users";
$result = mysqli_query($conn, $sql);

if ($result) {
    $data = mysqli_fetch_assoc($result);
    echo json_encode(['total_users' => (int)$data['total_users']]);
} else {
    echo json_encode(['total_users' => 0]);
}

mysqli_close($conn);
?>
