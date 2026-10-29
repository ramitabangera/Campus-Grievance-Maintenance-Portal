<?php

session_start();

require_once "../../config/db.php";

if (!isset($_SESSION["student_id"])) {
    header("Location: student_login.php");
    exit();
}

$student_id = $_SESSION["student_id"];

$sql = "SELECT student_id, full_name, email, roll_no, department, class
        FROM students
        WHERE student_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $student_id);
$stmt->execute();

$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    die("Student information not found.");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>My Profile - CGMP</title>

<link rel="stylesheet" href="../../css/style.css">

<style>

.profile-container {
    max-width: 850px;
    margin: 30px auto;
}

.profile-header {
    background: linear-gradient(135deg, #0b3d91, #1769d1);
    color: white;
    padding: 30px;
    border-radius: 15px;
    margin-bottom: 25px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.15);
}

.profile-header h1 {
    margin-bottom: 8px;
}

.profile-header p {
    margin: 5px 0;
}

.profile-card {
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.10);
}

.profile-card h2 {
    color: #0b3d91;
    margin-bottom: 25px;
}

.profile-row {
    display: flex;
    justify-content: space-between;
    padding: 16px 5px;
    border-bottom: 1px solid #eee;
}

.profile-row:last-child {
    border-bottom: none;
}

.profile-label {
    font-weight: bold;
    color: #555;
}

.profile-value {
    color: #222;
}

.profile-actions {
    margin-top: 25px;
}

.profile-actions a {
    display: inline-block;
    background: #0b3d91;
    color: white;
    padding: 12px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: bold;
    margin-right: 10px;
}

.profile-actions a:hover {
    background: #08306b;
}

</style>

</head>

<body>

<div class="sidebar">

    <h2>CGMP</h2>

    <a href="student_dashboard.php">🏠 Dashboard</a>

    <a href="raise_complaint.php">📝 Raise Complaint</a>

    <a href="track_complaint.php">📋 Track Complaint</a>

    <a href="complaint_history.php">📜 Complaint History</a>

    <a href="profile.php">👤 Profile</a>

    <a href="student_logout.php">🚪 Logout</a>

</div>


<div class="main">

    <div class="profile-container">

        <div class="profile-header">

            <h1>My Profile 👤</h1>

            <p>Campus Grievance & Maintenance Portal</p>

            <p>
                Student ID:
                <strong>
                    <?php echo htmlspecialchars($student["student_id"]); ?>
                </strong>
            </p>

        </div>


        <div class="profile-card">

            <h2>Student Information</h2>

            <div class="profile-row">
                <span class="profile-label">Full Name</span>
                <span class="profile-value">
                    <?php echo htmlspecialchars($student["full_name"]); ?>
                </span>
            </div>


            <div class="profile-row">
                <span class="profile-label">Student ID</span>
                <span class="profile-value">
                    <?php echo htmlspecialchars($student["student_id"]); ?>
                </span>
            </div>


            <div class="profile-row">
                <span class="profile-label">Email</span>
                <span class="profile-value">
                    <?php echo htmlspecialchars($student["email"]); ?>
                </span>
            </div>


            <div class="profile-row">
                <span class="profile-label">Roll Number</span>
                <span class="profile-value">
                    <?php echo htmlspecialchars($student["roll_no"]); ?>
                </span>
            </div>


            <div class="profile-row">
                <span class="profile-label">Department</span>
                <span class="profile-value">
                    <?php echo htmlspecialchars($student["department"]); ?>
                </span>
            </div>


            <div class="profile-row">
                <span class="profile-label">Class</span>
                <span class="profile-value">
                    <?php echo htmlspecialchars($student["class"]); ?>
                </span>
            </div>


            <div class="profile-actions">

                <a href="student_dashboard.php">
                    ← Back to Dashboard
                </a>

                <a href="student_logout.php">
                    🚪 Logout
                </a>

            </div>

        </div>

    </div>

</div>

</body>

</html>