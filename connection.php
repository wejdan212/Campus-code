<?php
$host = 'mysql.railway.internal';
$port = '3306';
$db   = 'railway';
$user = 'root';
$pass = 'GNusEwmWjJWMKUlPxbqrFAVVuNHMNzmn';

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
