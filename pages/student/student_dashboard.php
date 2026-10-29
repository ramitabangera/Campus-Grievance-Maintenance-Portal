<?php
session_start();
require_once "../../config/db.php";

if (!isset($_SESSION["student_id"])) {
    header("Location: student_login.php");
    exit();
}

$student_id = $_SESSION["student_id"];

// Get logged-in student's details
$stmt = $conn->prepare(
    "SELECT student_id, full_name, email, roll_no, department, class
     FROM students
     WHERE student_id = ?"
);

$stmt->bind_param("s", $student_id);
$stmt->execute();

$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    session_destroy();
    header("Location: student_login.php");
    exit();
}

$stmt->close();

// Get complaint counts
$count_stmt = $conn->prepare(
    "SELECT
        COUNT(*) AS total,
        SUM(status = 'Pending') AS pending,
        SUM(status = 'In Progress') AS in_progress,
        SUM(status = 'Resolved') AS resolved
     FROM complaints
     WHERE student_id = ?"
);

$count_stmt->bind_param("s", $student_id);
$count_stmt->execute();

$count_result = $count_stmt->get_result();
$counts = $count_result->fetch_assoc();

$count_stmt->close();

$total = $counts["total"] ?? 0;
$pending = $counts["pending"] ?? 0;
$in_progress = $counts["in_progress"] ?? 0;
$resolved = $counts["resolved"] ?? 0;
?>
    <!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Dashboard - Campus Grievance Portal</title>

    <link rel="stylesheet" href="../../css/style.css">

</head>

<body>

<!-- SIDEBAR -->

<div class="sidebar">

    <h2>CGMP</h2>

    <a href="student_dashboard.php">
        🏠 Dashboard
    </a>

    <a href="raise_complaint.php">
        📝 Raise Complaint
    </a>

    <a href="track_complaint.php">
        📋 Track Complaint
    </a>

    <a href="complaint_history.php">
        📜 Complaint History
    </a>

    <a href="profile.php">
        👤 Profile
    </a>

    <a href="student_logout.php">
        🚪 Logout
    </a>

</div>


<!-- MAIN CONTENT -->

<div class="main">

    <!-- TOPBAR -->

    <div class="topbar">

        <h2>
            Welcome,
            <?php echo htmlspecialchars($student["full_name"]); ?>
            👋
        </h2>

        <p>
            Campus Grievance & Maintenance Portal
        </p>

        <p>
            Student ID:
            <strong>
                <?php echo htmlspecialchars($student["student_id"]); ?>
            </strong>
        </p>

    </div>


    <!-- PROFILE -->

    <section class="dashboard-section">

        <h2>My Profile</h2>

        <div class="profile-grid">

            <div>
                <strong>Name</strong>
                <p>
                    <?php echo htmlspecialchars($student["full_name"]); ?>
                </p>
            </div>

            <div>
                <strong>Email</strong>
                <p>
                    <?php echo htmlspecialchars($student["email"]); ?>
                </p>
            </div>

            <div>
                <strong>Roll Number</strong>
                <p>
                    <?php echo htmlspecialchars($student["roll_no"]); ?>
                </p>
            </div>

            <div>
                <strong>Department</strong>
                <p>
                    <?php echo htmlspecialchars($student["department"]); ?>
                </p>
            </div>

            <div>
                <strong>Class</strong>
                <p>
                    <?php echo htmlspecialchars($student["class"]); ?>
                </p>
            </div>

        </div>

    </section>


    <!-- COMPLAINT SUMMARY -->

    <section>

        <h2 class="section-title">
            Complaint Summary
        </h2>

        <div class="stats">

            <div class="stat-box">

                <h2>
                    <?php echo $total; ?>
                </h2>

                <p>Total Complaints</p>

            </div>


            <div class="stat-box">

                <h2>
                    <?php echo $pending; ?>
                </h2>

                <p>Pending</p>

            </div>


            <div class="stat-box">

                <h2>
                    <?php echo $in_progress; ?>
                </h2>

                <p>In Progress</p>

            </div>


            <div class="stat-box">

                <h2>
                    <?php echo $resolved; ?>
                </h2>

                <p>Resolved</p>

            </div>

        </div>

    </section>


    <!-- QUICK ACTIONS -->

   <section style="
    background:white;
    padding:30px;
    margin-top:30px;
    border-radius:12px;
    box-shadow:0 4px 15px rgba(0,0,0,0.10);
">

    <h2 style="
        color:#0b3d91;
        margin-bottom:20px;
    ">
        Quick Actions
    </h2>

    <div style="
        display:flex;
        gap:15px;
        flex-wrap:wrap;
    ">

        <a href="raise_complaint.php"
           style="
           display:inline-block;
           background:#0b3d91;
           color:white;
           padding:13px 22px;
           border-radius:8px;
           text-decoration:none;
           font-weight:bold;
           ">
           📝 Raise a Complaint
        </a>

        <a href="track_complaint.php"
           style="
           display:inline-block;
           background:#0b3d91;
           color:white;
           padding:13px 22px;
           border-radius:8px;
           text-decoration:none;
           font-weight:bold;
           ">
           📋 Track Complaint
        </a>

        <a href="complaint_history.php"
           style="
           display:inline-block;
           background:#0b3d91;
           color:white;
           padding:13px 22px;
           border-radius:8px;
           text-decoration:none;
           font-weight:bold;
           ">
           📜 Complaint History
        </a>

    </div>

</section>
</div>

</body>

</html>