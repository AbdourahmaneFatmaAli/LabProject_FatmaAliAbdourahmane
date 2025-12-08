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
$stmt->store_result();


if ($stmt->num_rows === 1) {
    $stmt->bind_result($user_id, $first_name, $last_name, $email_db, $password_hash, $role_db);
    $stmt->fetch();

    $user = [
        'user_id'=> $user_id,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'email' => $email_db,
        'role' => $role_db
    ];

    if (password_verify($password, $password_hash)) {
       
        $_SESSION['user_id'] = $user_id;
        $_SESSION['email'] = $email_db;
        $_SESSION['first_name'] = $first_name;
        $_SESSION['last_name'] = $last_name;
        $_SESSION['role'] = $role_db;

        $response = [
            'state' => true,
            'message' => 'Login successful',
            'user' => $user,
            
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








