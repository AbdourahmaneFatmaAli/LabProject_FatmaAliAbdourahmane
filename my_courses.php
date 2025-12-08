<?php
session_start();
require 'db.php';

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Student' ) {
         header('Location: login.html');

        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['course_id'])) {
   

        $stmt= $con->prepare(' SELECT 1 FROM course_student_list WHERE course_id = ? AND student_id= ?');
        $stmt->bind_param('ii' , $_POST['course_id'], $_SESSION['user_id']);
        $stmt->execute();

        if ($stmt->get_result()->num_rows === 0) {
        
            $stmt = $con->prepare("INSERT IGNORE INTO students (student_id) VALUES (?)");
            $stmt->bind_param('i', $_SESSION['user_id']);
            $stmt->execute();

            $stmt = $con->prepare("
              INSERT INTO course_student_list (course_id, student_id, status)
              VALUES (?, ?, 'pending')
              ");

             $stmt->bind_param('ii' , $_POST['course_id'], $_SESSION['user_id']);
             $stmt->execute();

        }

        header('Location: '. $_SERVER['PHP_SELF']);
        exit();
    }

    $stmt = $con->prepare("
        SELECT c.*, CONCAT(u.first_name, ' ', u.last_name) as faculty_name 
        FROM courses c
        JOIN users u ON c.faculty_id = u.user_id
        WHERE c.course_id NOT IN (
            SELECT course_id FROM course_student_list 
            WHERE student_id = ?
        )
    ");

    $stmt->bind_param('i', $_SESSION['user_id']);
    $stmt->execute();
    $available_courses = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $stmt = $con->prepare('
        SELECT c.*, csl.status 
        FROM courses c
        JOIN course_student_list csl ON c.course_id = csl.course_id
        WHERE csl.student_id = ?');

    $stmt->bind_param('i', $_SESSION['user_id']);
    $stmt->execute();
    $enrolled_courses = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MY courses</title>
    <link rel="stylesheet" href="allSectionsstyle.css">
    <style>

        .status-pending {color: #ff9800; }
        .status-approved { color: blue;}
        .status-rejected{color: red;}
        .course-card {
            border: 1px solid #ddd;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
        }

        .available-courses {
            margin-top: 30px;
        }

    </style>
</head>
<body> 
    <div class='container'>
        <div class= 'sidebar'>
            <h2>Student</h2>
            <a href='StudentDash.php'>Dashboard</a>
            
    </div>

    <div class='main-content'>
        <h1>My Courses</h1>

        <h2>My Enrollments</h2>
        <?php if (count($enrolled_courses) > 0): ?>
            <?php foreach ($enrolled_courses as $course): ?>
                <div class='course-card'>
                    <h3><?= htmlspecialchars($course['course_code']) ?> - <?= htmlspecialchars($course['course_name']) ?></h3>
                    <p>Status:
                        <span class='status-<?= $course['status'] ?>'>
                            <?= ucfirst($course['status']) ?>
                            </span>
                        </p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>You are not enrolled in any courses yet.</p>
            <?php endif; ?>

            <div class="available-courses">
                <h2>Available Courses</h2>
                <?php if (count($available_courses) > 0): ?>
                    <?php foreach ($available_courses as $course): ?>
                        <div class="course-card">
                            <h3><?= htmlspecialchars($course['course_code']) ?> - <?= htmlspecialchars($course['course_name']) ?></h3>
                            <p>Instructor: <?= htmlspecialchars($course['faculty_name']) ?></p>
                            <form method="POST">
                                <input type="hidden" name="course_id" value="<?= $course['course_id'] ?>">
                                <button type="submit">Request to Enroll</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No available courses to enroll in at the moment.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>





