<?php
$host = 'localhost';
$user = 'root';
$pass = 'passroot';
$db = 'sae23';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $conn = mysqli_connect($host, $user, $pass, $db);
    mysqli_set_charset($conn, "utf8mb4");
} catch(Exception $e) {
    error_log($e->getMessage());
    exit('Error connecting to database');
}
?>
