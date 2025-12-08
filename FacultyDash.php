<?php
session_start();


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Faculty') {
    header('Location: login.html');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FcultyDashboard</title>
    

    <style>
        * { 
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body{
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1a64a9ff 100%);
            min-height: 100vh;
            display: flex;
        }
        .container{
            width: 100%;
            height: 100%;
            display: flex;
           

        }
        .sidebar{
            width:280px;
            background: white;
            backdrop-filter: blur(10px);
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
            

        }
        .sidebar-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .sidebar-header h2{
            margin-bottom: 5px;
            font-size: 24px;
            color: orange;

        }
        .sidebar-header p{
            font-size: 14px;
            color: #888;

        }
        .nav-menu {
            flex: 1;
        }
        .nav-item {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            margin-bottom: 10px;
            color: grey;
            border-radius: 12px;
            font-weight: 500;
        }

        .nav-item:hover {
            background: linear-gradient(blue);
            color: white;
            transform: translateX(5px);
        }

        .nav-item.active {
            background: linear-gradient(135deg, pink);
            color: white;
        }
        .nav-icon {
            width: 20px;
            height: 20px;
            margin-right: 15px;
        }
        .logout-btn {
            margin-top: auto;
            background: blue;
            color: white;
        }


        .sidebar a{
            color: white;
            padding: 15px;
            text-decoration: none;
            border-radius:4px;
            transition: background 0.3s;
            margin-bottom: 10px;
        }
        
        .main-content{
            flex: 1;
            padding:40px;
        }

        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .main-content h1{
            font-size: 28px;
            margin-bottom: 20px;

        }
        
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmaxx(320px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }
        .card{
            background: white;
            padding:30px;
            border-radius: 20px;
            position: relative;
            overflow: hidden;
          
        }
        .card::before{
            content: '';
            position: absolute;
            width: 100%;
            height: 4px;
        }
        .card-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            font-size: 30px;
        }
        

        

        .card-button{
            display: inline-block;
            padding: 10px 25px;
            background: linear-gradient(135deg, #667eea 100%);
            color: white;
            border-radius: 8px;
            font-weight: 600;

        
        }

        
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>Faculty</h2>
                <p>Year 2025</p>
            </div>
            
            <nav class="nav-menu">
                <a href="FacultyDash.php" class="nav-item active">
                    <span class="nav-icon">🏠</span>
                    Dashboard
                </a>
                <a href="manage_courses.php" class="nav-item active">
                    <span class="nav-icon">📖</span>
                    My Courses
                </a>
                <a href="Sessions.php" class="nav-item active">
                    <span class="nav-icon">📊</span>
                    Sessions
                </a>
                
                <a href="Report.html" class="nav-item active">
                    <span class="nav-icon">📊</span>
                    Grade report
                </a>
            </nav>

            <a href="login.html" class="nav-item logout-btn">
                <span class="nav-icon">🚪</span>
                logout
            </a>
        </div>

        <div class="main-content">
            <h1>Welcome to your Dashboard</h1>
            <p>An overview of your teaching activities</p>
            


            <div class="cards-grid">
                <div class="card">
                    <div class="card-icon courses">📚</div>
                    <h3>My courses</h3>
                    <p>Manage your course materials</p>
                    <a href="manage_courses.php" class="card-button">view course</a>
            </div>

            <div class="card">
                    <div class="card-icon sessions">📚</div>
                    <h3>Class Sessions</h3>
                    <p> Schedule classes, and attendance</p>
                    <a href="Sessions.php" class="card-button">view sessions</a>
            </div>
                
            <div class="card">
                    <div class="card-icon Grades">📚</div>
                    <h3>Grades Management</h3>
                    <p> Enter grades reports</p>
                    <a href="Report.html" class="card-button">view grade</a>
            </div>
                  
        </div>

        

                
    </div>

</body>

</html>    

