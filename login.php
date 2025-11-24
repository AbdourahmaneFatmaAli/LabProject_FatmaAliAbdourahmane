<?php

session_start();
header('Content-Type: application/json'); 
require 'db.php'; 

$response = ['state' => false, 'message' => 'An unknown error occurred.']; 

$input = json_decode(file_get_contents('php://input'), true);

$email = $input['email'] ?? '';
$password = $input['password'] ?? '';
$role = $input['role'] ?? ''; 

if (empty($email) || empty($password) || empty($role)) {
    $response = ['state' => false, 'message' => 'Email, password, and role are all required.'];
    echo json_encode($response);
    $con->close();
    exit();
}

$sql = 'SELECT user_id, first_name, last_name, email, password_hash, role
        FROM users
        WHERE email = ? AND role = ?'; 
$stmt = $con->prepare($sql);

if (!$stmt) {
    $response['message'] = 'Database query preparation failed: ' . $con->error;
    echo json_encode($response);
    $con->close();
    exit();
}

$stmt->bind_param('ss', $email, $role); 
$stmt->execute();
$result = $stmt->get_result();


if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password_hash'])) {
       
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];
        $_SESSION['role'] = $user['role'];

        $response = [
            'state' => true,
            'message' => 'Login successful',
            'user' => [
                'user_id' => $user['user_id'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'email' => $user['email'],
                'role' => $user['role'],
            ]
        ];

    } else {
     
        $response['message'] = 'Invalid email or password';
    }

} else {

    $response = [
        'state' => false,
        'message' => 'User not found for that role. Would you like to sign up?',
        'showSignupLink' => true
    ];
}


echo json_encode($response);
$con->close();
exit(); 
?>








