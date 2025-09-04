<?php
include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $table = $_POST['table'];

    $allowed_tables = ['phones', 'clothes', 'electronics', 'others'];

    if (in_array($table, $allowed_tables)) {
        $tag = isset($_POST['clear']) ? '' : htmlspecialchars(strip_tags($_POST['tag']));

        $stmt = $conn->prepare("UPDATE `$table` SET tag = ? WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("si", $tag, $id);
            $stmt->execute();
            $stmt->close();
        }
    }
}

header("Location: manage_product.php");
exit;
?>
