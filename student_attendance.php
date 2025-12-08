<?php

session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['Student'])) {
    header('Location: login.html');
    exit();
}

if (!isset($_GET['course_id'])) {
    die('Course not specified');
}


$student_id = $_SESSION['user_id'];
$course_id = intval($_GET['course_id']);


$check = $con->prepare("SELECT * FROM course_student_list WHERE student_id = ? AND course_id = ?");
$check->bind_param("ii", $student_id, $course_id);
$check->execute();
$enrolled = $check->get_result();




if ($enrolled->num_rows === 0) {
    die('you are not enrolled please enroll first')
}


$stmt = $con->prepare("
SELECT s.session_id, s.topic, s.date,
    COALESCE(a.status, 'Absent') AS status,
    a.marked_at
FROM sessions s 
LEFT JOIN attendance a 
    ON s.session_id = a.session_id AND  a.student_id = ?
WHERE s.course_id = ?
ORDER BY s.date ASC ");

$stmt-> bind_param('ii', $student_id, $course_id);
$stmt->execute();
$result = $stmt->get_result();


$sessions = [];
$attended = 0;


while ($row = $result->fetch_assoc()) {
    $sessions[] = $row;
    if (strtolower($row['status'])=== 'present') {
        $attended++;
    }
}

$total_sessions = count($sessions);
$percentage = $total_sessions > 0 ? round(($attended / $total_sessions) * 100, 1) : 0;
?>



<!DOCTYPE html>
<html lang= 'en'>
<head>
    <title>Attendance Report</title>
</head>
<body>
<style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f4f6f8; }
        h2 { color: #354e00; }
        table { border-collapse: collapse; width: 100%; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #354e00; color: white; }
</style>
</head>
</body>



<h2> Attendance Report </h2>


<p><strong>Total Sessions:</strong> <?= $total_sessions ?></p>
<p><strong>Attended:</strong> <?= $attended ?></p>
<p><strong>Attendance Percentage:</strong> <?= $percentage ?>%</p>


<table>
    <tr>
        <th>Date</th>
        <th>Topic</th>
        <th>Status</th>
        <th>Marked At</th>
    </tr>

    <?php foreach ($sessions as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['date']) ?></td>
            <td><?= htmlspecialchars($row['topic']) ?></td>
            <td><?= ucfirst($row['status']) ?></td>
            <td><?= $row['marked_at'] ? $row['marked_at'] : '-' ?></td>
        </tr>
    <?php endforeach; ?>

</table>

</body>
</html>