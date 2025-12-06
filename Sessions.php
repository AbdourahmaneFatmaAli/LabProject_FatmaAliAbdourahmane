<?php

session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['Faculty', 'Faculty Intern'])) {
    header('Location: login.html');
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_session'])) {
    $course_id = $_POST['course_id'];
    $topic = $_POST['topic'];
    $location = $_POST['location'];
    $start_time= $_POST['start_time'];
    $end_time= $_POST['end_time'];
    $date= date('Y-m-d', strtotime($start_time));



    $session_code = strtoupper(substr(md5(uniqid()), 0, 6));

    $stmt = $con->prepare('INSERT INTO sessions (course_id, topic, location, start_time, end_time, date, session_code) VALUES (?, ?, ?, ?, ?, ?, ?)');

    $stmt->bind_param('issssss', $course_id, $topic,  $location, $start_time, $end_time, $date, $session_code);
    if ($stmt->execute()) {
        $message = 'Session created';
    } else {
        $message = 'Error creating session ' . $con->error;
    }
}


$faculty_id = $_SESSION['user_id'];
$stmt = $con->prepare('SELECT * FROM courses WHERE faculty_id = ?');
$stmt->bind_param('i', $faculty_id);
$stmt->execute();
$courses = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);


$stmt2 = $con->prepare('SELECT s.*, c.course_name FROM sessions s JOIN courses c ON s.course_id=c.course_id WHERE c.faculty_id = ?');
$stmt2->bind_param('i', $faculty_id);
$stmt2->execute();
$sessions = $stmt2->get_result();
?>




<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Sessions</title>
    <link rel="stylesheet" href="allSectionsstyle.css">
</head>
<body>
    <div class='container'>
        <div class='sidebar'>
        <h2>Faculty</h2>
        
            <a href ='FacultyDash.html'>Dashboard</a>
    </div>
        <div class="main-content">
            <h1>Session Overview</h1>
            <?php if($message): ?>
                <p class='message'><?= $message ?></p>
            <?php endif; ?>

            <h2>Create Session</h2>
           
            <form method='POST'>
                <select name='course_id' required>
                    <option value=''>Select Course</option>
                    <?php foreach($courses as $c): ?>
                        <option value='<?= $c['course_id'] ?>'><?=htmlspecialchars($c['course_name']) ?></option>
                    <?php endforeach; ?>

                </select><br>
                <input type='text' name='topic' placeholder='Session Topic' required><br>
                <input type='text' name='location' placeholder='Location' required><br>
                <input type= 'datetime-local' name='start_time' required><br>
                <input type="datetime-local" name="end_time" required>
                <button type='submit' name='add_session'> Add Session</button>
            </form>

    <h2>Existing Sessions</h2>
    <table>
    <tr>
        <th>Course</th>
        <th>Topic</th>
        <th>Date</th>
        <th>Code</th>
    </tr>

    <?php while($row = $sessions->fetch_assoc()): ?>

        <tr>
            <td><?=htmlspecialchars($row['course_name']) ?></td>
            <td><?=htmlspecialchars($row['topic']) ?></td>
            <td><?= $row['date'] ?></td>
            <td><?= $row['session_code'] ?></td>
        </tr>

        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
