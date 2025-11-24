<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'attendancemanagement';

$con = new mysqli($host, $user, $pass, $db);

if ($con->connect_error) {
    die(json_encode(['state' => false, 'message' => 'Connection failed: ' . $con->connect_error]));
}

$con->set_charset('utf8mb4');
?>
