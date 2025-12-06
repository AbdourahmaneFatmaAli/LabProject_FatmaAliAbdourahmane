<?php
session_start();
require 'db.php';



if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'Faculty' && $_SESSION['role'] !== 'Faculty Intern')) {
    header('Location: login.html');

    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_course'])) {
        
        $faculty_id = $_SESSION['user_id'];
        

        $check_faculty = $con->prepare("SELECT faculty_id FROM faculty WHERE faculty_id = ?");
        $check_faculty->bind_param('i', $faculty_id);
        $check_faculty->execute();
        $faculty_result = $check_faculty->get_result();
        
        if ($faculty_result->num_rows === 0) {
            
            $is_intern = ($_SESSION['role'] === 'Faculty Intern') ? 1 : 0;
            $insert_faculty = $con->prepare("INSERT INTO faculty (faculty_id, is_intern) VALUES (?, ?)");
            $insert_faculty->bind_param('ii', $faculty_id, $is_intern);
            $insert_faculty->execute();
        }

        $stmt = $con->prepare('INSERT INTO courses(course_code, course_name, faculty_id) VALUES(?, ?, ?)');
        $stmt->bind_param('ssi', 
            trim($_POST['course_code']), 
            trim($_POST['course_name']), 
            $faculty_id
        );
        
        if ($stmt->execute()) {
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "Error adding course: " . $con->error;
        }
    }
}



$faculty_id = $_SESSION['user_id'];


$stmt = $con->prepare("
    SELECT c.*, 
           (SELECT COUNT(*) FROM course_student_list 
            WHERE course_id = c.course_id AND status = 'pending') as pending_requests
    FROM courses c 
    WHERE c.faculty_id = ?
");
$stmt->bind_param('i', $faculty_id);
$stmt->execute();
$courses = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage courses</title>
    <link rel="stylesheet" href="allSectionsstyle.css">
    <style>

        .request-badge {
            background: #ff9800;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
            margin-left: 5px;
        }


        .request-item {
            padding: 10px;
            border-bottom: 1px solid;

        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 1000;
        }

        .modal-content {
            background: white;
            margin: 10% auto;
            padding: 20px;
            width: 60%;
            max-width: 600px;
            border-radius: 5px;
            position: relative;
        }

    </style>

</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h2>Faculty</h2>
            <a href ='FacultyDash.html'>Dashboard</a>
            <a href="Sessions.html">Sessions</a>
            <a href="Report.html">Reports</a>
            <a href="login.html">Logout</a>

        </div>
        <div class="main-content">
            <h2>Course Management</h2>

            <section>
                <h3>My Courses</h3>
                    <table border="1" width="100%" cellpadding="8">
                        <tr>
                            <th>Course code</th>
                            <th>Course title</th>
                            <th>Actions</th>
                        </tr>
                        <?php foreach ($courses as $course): ?>
                        <tr>

                            <td><?= htmlspecialchars($course['course_code']) ?></td>
                            <td><?= htmlspecialchars($course['course_name']) ?></td>
                            <td>

                                <a href="#" onclick="showRequests(<?= $course['course_id'] ?>, '<?= htmlspecialchars($course['course_code']) ?>'); return false;">
                                View Requests
                                <?php if ($course['pending_requests'] > 0): ?>
                                    <span class="request-badge"><?= $course['pending_requests'] ?></span>
                                <?php endif; ?>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </section>



            <section style ='margin-top: 20px;'>
                <h3>Add new course</h3>
                <form method = 'POST'>
                    <input type='text' name='course_code' placeholder= 'Course Code' required>
                    <input type='text' name='course_name' placeholder= 'Course Title' required>
                    <button type='submit' name='add_course'>Add Course</button>

                </form>
            </section>

        </div>
    </div>

    <div id="requestsModal" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 1000;">
    <div class="modal-content" style="background: white; margin: 10% auto; padding: 20px; width: 60%; max-width: 600px; border-radius: 5px; position: relative;">
        <span style="position: absolute; right: 20px; top: 10px; font-size: 28px; font-weight: bold; cursor: pointer;" onclick="closeModal()">&times;</span>
        <h3 id="modalTitle">Enrollment Requests</h3>
        <div id="requestsList">Loading requests...</div>
    </div>
</div>



    <script>
        function showRequests(courseId, courseCode) {
            console.log('Opening modal for courses:', courseId, courseCode)

            const modal = document.getElementById('requestsModal');
            const container = document.getElementById('requestsList');
            document.getElementById('modalTitle').textContent = `Enrollment Requests - ${courseCode}`;
            modal.style.display = 'block';
            container.innerHTML = 'Loading requests...';


            fetch(`get_requests.php?course_id=${courseId}`)
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok){
                    throw new Error ('failed to loadf requests');

                }

                return response.json();
            })
            .then(requests => {
                console.log('received request:', requests);

                if (!requests || requests.length === 0) {
                    container.innerHTML = '<p>No pending requests for this course.</p>';
                    return;
                }  

                    container.innerHTML = requests.map(request => `
                            <div style="padding: 10px; margin: 10px 0; border-bottom: 1px solid #eee;">
                    <p><strong>${request.student_name || 'Student'}</strong> 
                    (${request.email || 'No email'})</p>
                    <p>Requested on: ${request.requested_at ? 
                        new Date(request.requested_at).toLocaleString() : 'N/A'}</p>
                    <form method="POST" action="update_requests.php" style="margin-top: 10px;">
                        <input type="hidden" name="course_id" value="${courseId}">
                        <input type="hidden" name="student_id" value="${request.student_id}">
                        <button type="submit" name="status" value="approved" 
                            style="background: #4CAF50; color: white; padding: 5px 10px; margin-right: 10px; border: none; border-radius: 3px; cursor: pointer;">
                            Approve
                        </button>
                        <button type="submit" name="status" value="rejected"
                            style="background: #f44336; color: white; padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer;">
                            Reject
                        </button>
                    </form>
                </div>
            `).join('');
        })
        .catch(error => {
            console.error('Error:', error);
            container.innerHTML = `<p style="color: red;">Error: ${error.message}</p>`;
        });
    }
        function closeModal() {
        document.getElementById('requestsModal').style.display = 'none';
        }
        window.onclick = function(event) {
        const modal = document.getElementById('requestsModal');
            if (event.target === modal) {
                closeModal();
            }
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });
</script>
</body>