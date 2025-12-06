<?php

session_start();

require 'db.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Student') {
    header('Location: login.html');
    exit();    
}

$message = '';
$student_id = $_SESSION['user_id'];


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['session_code'])) {
    $session_code = strtoupper(trim($_POST['session_code']));
    

    $stmt = $con->prepare('
        SELECT s.session_id, s.course_id
        FROM sessions s
        JOIN course_student_list csl ON s.course_id = csl.course_id
        WHERE s.session_code = ? AND csl.student_id = ?');
    $stmt->bind_param('si', $session_code, $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $message = 'Invalid session code or not enrolled.';
    } else {
        $session = $result->fetch_assoc();
        $session_id = $session['session_id'];

        $stmt2 = $con->prepare('SELECT * FROM attendance WHERE session_id=? AND student_id=?');

        if (!$stmt2) {
            die("SQL ERROR in stmt2 PREPARE: " . $con->error);
        }


        $stmt2->bind_param('ii', $session_id, $student_id);
        $stmt2->execute();
        $res2 = $stmt2->get_result();

        if ($res2->num_rows > 0) {
            $message = 'You already marked attendance for this session.';
        } else {
            $stmt3 = $con->prepare('INSERT INTO attendance (session_id, student_id, status, check_in_time) VALUES (?, ?, "present", NOW())');
            $stmt3->bind_param('ii', $session_id, $student_id);
            if ($stmt3->execute()) {
                $message = 'Attendance marked successfully!';
            } else {
                $message = 'Error marking attendance: '.$con->error;
            }
        }
    }
}


$attendance_stmt = $con->prepare('
    SELECT c.course_name, s.date, a.status, a.check_in_time
    FROM attendance a
    JOIN sessions s ON a.session_id = s.session_id
    JOIN courses c ON s.course_id = c.course_id
    WHERE a.student_id = ?
    ORDER BY s.date DESC
');
$attendance_stmt->bind_param('i', $student_id);
$attendance_stmt->execute();
$attendance_result = $attendance_stmt->get_result();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Sessions</title>
    <link rel="stylesheet" href="allSectionsstyle.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h2>Student</h2>
            <a href ='StudentDash.html'>Dashboard</a>
            <a href="StudentSessions.php">Sessions</a>
            <a href="StudentReport.html">Reports</a>
            <a href="login.html">Logout</a>
            

        </div>
        <div class="main-content">
            <h1>Sessions</h1>

            <h2>Mark Attendance</h2>
            <?php if (!empty($message)): ?>
                <p style="background: lightgreen; padding:10px;">
                     <?= htmlspecialchars($message) ?>
                </p>
            <?php endif; ?>

            <form method='POST'>
                <input type='text' name='session_code' placeholder='Enter Session Code' required>
                <button type='submit'>Mark Attendance</button>
            </form>


            <h2>Attendance Records</h2>
            <table border='1' cellpadding='5'>
                <tr>
                    <th>Course</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>

                <?php while($row = $attendance_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['course_name']) ?></td>
                        <td><?= $row['date'] ?></td>
                        <td><?= $row['status'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>          
</body>
</html>