<?php

session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['Student'])) {
    header('Location: login.html');
    exit();
}


$message ='';

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['session_code'])) {
    $session_code = strtoupper(trim($_POST['session_code']));
    $student_id = $_SESSION['user_id'];



    $stmt = $con->prepare('
    SELECT s.session_id, s.course_id
    FROM sessions s
    JOIN course_student_list csl ON s.course_id = csl.course_id
    WHERE s.session_code = ? AND csl.student_id = ?');

    $stmt->bind_param('si', $session_code, $student_id);
    $stmt->execute();
    $result = $stmt->get_result();



    if ($result->num_rows === 0){
        $message = 'invalide session code or maybe not enrolled in this course';

    } else {
        $session = $result->fetch_assoc();
        $session_id = $session['session_id'];



        $stmt2 = $con->prepare('SELECT * FROM attendance WHERE session_id = ? AND student_id = ?');
        $stmt2->bind_param('ii', $session_id, $student_id);
        $stmt2->execute();
        $res2 = $stmt2->get_result();


        if ($res2->num_rows > 0){
            $message = 'you already marked you attendace';
        } else {
           $stmt3 = $con->prepare(
                "INSERT INTO attendance (session_id, student_id, status, marked_at) 
                VALUES (?, ?, 'present', NOW())");
                
            $stmt3->bind_param('ii', $session_id, $student_id);
            if ($stmt3->execute()){
                $message = ' Your attendance has beedn marked';
            } else {
                $message = 'error marking attendance' . $con->error;
            }
        }
    }
        

    
}

?>



<!DOCTYPE html>
<html>
<head>
    <title>Mark Attendance</title>
</head>

<body>
<h2>Mark attendance</h2>
<p><?= $message ?></p>
<form method='POST'>
    <input type='text' name='session_code' placeholder= 'Enter Session Code' required>
    <button type ='submit'>Mark attendance</button>
</form>
</body>
</html>

