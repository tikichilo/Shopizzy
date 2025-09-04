<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'shopizzy');

// Create MySQL connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// OpenAI Configuration
define('OPENAI_API_KEY', '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');
define('OPENAI_ORG_ID', 'org-spCyxiCTW6j7P1n2rbZaSONo');
?>
