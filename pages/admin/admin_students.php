<?php

session_start();

require_once "../../config/db.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit();
}


/* Get all registered students */

$stmt = $conn->prepare(
    "SELECT student_id, full_name, email, roll_no, department, class, created_at
     FROM students
     ORDER BY created_at DESC"
);

$stmt->execute();

$students = $stmt->get_result();

$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Students - CGMP</title>

    <link rel="stylesheet" href="../../css/style.css">

    <style>

        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .page-header {
            background: linear-gradient(135deg, #0b3d91, #1769d1);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.12);
        }

        .page-header h1 {
            margin-bottom: 8px;
        }

        .page-header p {
            margin: 0;
        }

        .students-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
        }

        .students-card h2 {
            color: #0b3d91;
            margin-bottom: 20px;
        }

        .table-container {
            overflow-x: auto;
        }

        .students-table {
            width: 100%;
            border-collapse: collapse;
        }

        .students-table th {
            background: #0b3d91;
            color: white;
            padding: 13px;
            text-align: left;
            white-space: nowrap;
        }

        .students-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }

        .students-table tr:hover {
            background: #f7f9fc;
        }

        .student-id {
            font-weight: bold;
            color: #0b3d91;
        }

        .empty-message {
            text-align: center;
            padding: 40px;
            color: #666;
        }

    </style>

</head>


<body>


<!-- SIDEBAR -->

<div class="sidebar">

    <h2>CGMP</h2>

    <a href="admin_dashboard.php">
        📊 Dashboard
    </a>

    <a href="admin_students.php">
        👨‍🎓 Students
    </a>

    <a href="admin_complaints.php">
        📋 Complaints
    </a>

    <a href="admin_logout.php">
        🚪 Logout
    </a>

</div>


<!-- MAIN CONTENT -->

<div class="main">

    <div class="admin-container">


        <div class="page-header">

            <h1>
                👨‍🎓 Registered Students
            </h1>

            <p>
                View all students registered in the Campus Grievance Portal.
            </p>

        </div>


        <div class="students-card">

            <h2>
                Student List
            </h2>


            <?php if ($students && $students->num_rows > 0): ?>


                <div class="table-container">

                    <table class="students-table">

                        <thead>

                            <tr>

                                <th>Student ID</th>

                                <th>Full Name</th>

                                <th>Email</th>

                                <th>Roll Number</th>

                                <th>Department</th>

                                <th>Class</th>

                                <th>Registered On</th>

                            </tr>

                        </thead>


                        <tbody>


                        <?php while ($student = $students->fetch_assoc()): ?>


                            <tr>

                                <td class="student-id">

                                    <?php
                                    echo htmlspecialchars(
                                        $student["student_id"]
                                    );
                                    ?>

                                </td>


                                <td>

                                    <?php
                                    echo htmlspecialchars(
                                        $student["full_name"]
                                    );
                                    ?>

                                </td>


                                <td>

                                    <?php
                                    echo htmlspecialchars(
                                        $student["email"]
                                    );
                                    ?>

                                </td>


                                <td>

                                    <?php
                                    echo htmlspecialchars(
                                        $student["roll_no"]
                                    );
                                    ?>

                                </td>


                                <td>

                                    <?php
                                    echo htmlspecialchars(
                                        $student["department"]
                                    );
                                    ?>

                                </td>


                                <td>

                                    <?php
                                    echo htmlspecialchars(
                                        $student["class"]
                                    );
                                    ?>

                                </td>


                                <td>

                                    <?php
                                    echo htmlspecialchars(
                                        $student["created_at"]
                                    );
                                    ?>

                                </td>

                            </tr>


                        <?php endwhile; ?>


                        </tbody>

                    </table>

                </div>


            <?php else: ?>


                <div class="empty-message">

                    👨‍🎓 No students have registered yet.

                </div>


            <?php endif; ?>


        </div>

    </div>

</div>


</body>

</html>