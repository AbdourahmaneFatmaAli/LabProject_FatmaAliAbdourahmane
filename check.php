<?php

session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['email'])) {
    
    session_unset();
    session_destroy();
    header("Location: login.html"); 
    exit(); 
}
?>
