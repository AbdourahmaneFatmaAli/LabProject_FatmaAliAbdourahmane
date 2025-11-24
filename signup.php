<?php
header('Content-Type: application/json');
require 'db.php';
$input = json_decode(file_get_contents('php://input'), true);
$first_name = $input['first_name'] ?? '';
$last_name = $input['last_name'] ?? '';
$email = $input['email'] ?? '';
$password = $input['password'] ?? '';
$role = $input['role'] ?? 'student';
$dob = $input['dob'] ?? null;
if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
    $response = ['state' => false, 'message' => 'All fields are required'];
    echo json_encode($response);
    exit();
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
    $response = ['state' => false, 'message' => 'invalid email format'];
    echo json_encode($response); 
    exit();
}
$check_sql = 'SELECT user_id FROM users WHERE email = ?';
$stmt = $con->prepare($check_sql); 
if (!$stmt) {
    die(json_encode(['state' => false, 'message' => 'Prepare failed: ' . $con->error]));
}
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $response = ['state' => false, 'message' => 'Email already in the database'];
    echo json_encode($response);
    exit();
}
$stmt->close(); 
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$insert_sql = 'INSERT INTO users(first_name, last_name, email, password_hash, role, dob) VALUES(?, ?, ?, ?, ?, ?)';
$stmt = $con->prepare($insert_sql);
$stmt->bind_param('ssssss', $first_name, $last_name, $email, $hashed_password, $role, $dob);
if ($stmt->execute()) {
    $response = [
        'state' => true,
        'message' => 'registration successful',
        'user_id' => $stmt->insert_id
    ];
} else {
    $response = ['state'=> false, 'message' => 'Error: ' . $con->error];
}
echo json_encode($response);
$con->close();
?>
