<?php


session_start();
header('Content-Type: application/json');

session_unset(); 
session_destroy(); 

echo json_encode(["logout" => true, "message" => "You have been logged out."]);
?>
