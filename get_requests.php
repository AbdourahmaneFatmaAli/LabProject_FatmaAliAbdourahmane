<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

session_start();
require 'db.php';


    if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'Faculty' && $_SESSION['role'] !== 'Faculty Intern' )) {
        http_response_code(403);
        echo json_encode(['error' => 'Unauthorized']);
        exit();
    }
    $courseId = $_GET['course_id'] ?? 0;


    $stmt = $con->prepare('
        SELECT 1 FROM courses
        WHERE course_id = ? AND faculty_id= ?');
    
    $stmt->bind_param('ii', $courseId, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();



    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'course not found or access is denied']);
        exit();
    }

    $stmt = $con->prepare("
        SELECT 
            u.user_id as student_id,
            CONCAT(u.first_name, ' ', u.last_name) as student_name,
            u.email,
            csl.requested_at
        FROM course_student_list csl
        JOIN users u ON csl.student_id = u.user_id
        WHERE csl.course_id = ? 
        AND csl.status = 'pending'
        ORDER BY csl.requested_at
    ");
    $stmt->bind_param('i', $courseId);
    $stmt->execute();
    $result = $stmt->get_result();
    $requests = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode($requests);
?>