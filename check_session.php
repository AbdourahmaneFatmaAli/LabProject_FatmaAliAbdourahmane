<?php
session_start();


if (isset($_SESSION['user_id'])) {
    $response = [
        'authenticated' => true,
        'user' => [
            'user_id' => $_SESSION['user_id'],
            'first_name' => $_SESSION['first_name'],
            'last_name' => $_SESSION['last_name'],
            'email' => $_SESSION['email'],
            'role' => $_SESSION['role'],

        ]
    ];
} else {
    $response = ['authenticated' => false];
}

header('content-type: application/json');

echo json_encode($response);

?>