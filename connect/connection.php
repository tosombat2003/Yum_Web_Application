<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = '';
$user = '';
$pass = ''; // ใส่รหัสจริง
$db = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตั้งค่าให้ใช้ UTF-8
$conn->set_charset("utf8mb4");
?>
