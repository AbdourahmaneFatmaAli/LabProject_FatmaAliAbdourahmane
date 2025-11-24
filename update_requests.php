<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'Faculty' && $_SESSION['role'] !== 'Faculty Intern')) {
    http_response_code(403);
    die('Unauthorized');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die('Method not allowed');
}

if (!isset($_POST['course_id'], $_POST['student_id'], $_POST['status']) || 
    !in_array($_POST['status'], ['approved', 'rejected'])) {
    http_response_code(400);
    die('Invalid request');
}

$course_id = (int)$_POST['course_id'];
$student_id = (int)$_POST['student_id'];
$status = $_POST['status'];
$faculty_id = $_SESSION['user_id'];

$check = $con->prepare("
    SELECT 1 FROM courses 
    WHERE course_id = ? AND faculty_id = ?
");
$check->bind_param('ii', $course_id, $faculty_id);
$check->execute();

if ($check->get_result()->num_rows === 0) {
    http_response_code(403);
    die('Not authorized to update this course');
}

$update = $con->prepare("
    UPDATE course_student_list 
    SET status = ? 
    WHERE course_id = ? AND student_id = ?
");
$update->bind_param('sii', $status, $course_id, $student_id);

if ($update->execute()) {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
} else {
    http_response_code(500);
    echo "Error updating request: " . $con->error;
}