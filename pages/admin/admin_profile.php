<?php

session_start();

require_once "../../config/db.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit();
}

$admin_id = $_SESSION["admin_id"];

$stmt = $conn->prepare(
    "SELECT admin_id, full_name, email, created_at
     FROM admins
     WHERE admin_id = ?"
);

$stmt->bind_param("s", $admin_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Admin account not found.");
}

$admin = $result->fetch_assoc();

$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Profile - CGMP</title>

    <link rel="stylesheet" href="../../css/style.css">

    <style>

        .profile-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .profile-header {
            background: linear-gradient(135deg, #0b3d91, #1769d1);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 25px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.12);
        }

        .profile-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 15px;
            background: white;
            color: #0b3d91;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 38px;
        }

        .profile-header h1 {
            margin-bottom: 5px;
        }

        .profile-card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
        }

        .profile-card h2 {
            color: #0b3d91;
            margin-bottom: 20px;
        }

        .profile-row {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            padding: 15px 0;
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
            text-align: right;
        }

        .profile-actions {
            margin-top: 25px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .profile-btn {
            display: inline-block;
            padding: 11px 18px;
            border-radius: 7px;
            text-decoration: none;
            font-weight: bold;
        }

        .dashboard-btn {
            background: #0b3d91;
            color: white;
        }

        .dashboard-btn:hover {
            background: #08306b;
        }

        .password-btn {
            background: #28a745;
            color: white;
        }

        .password-btn:hover {
            background: #218838;
        }

        @media (max-width: 600px) {

            .profile-row {
                flex-direction: column;
                gap: 5px;
            }

            .profile-value {
                text-align: left;
            }

        }

    </style>

</head>


<body>


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

    <a href="admin_profile.php">
        👤 Profile
    </a>

    <a href="admin_logout.php">
        🚪 Logout
    </a>

</div>


<div class="main">

    <div class="profile-container">


        <div class="profile-header">

            <div class="profile-icon">
                👤
            </div>

            <h1>
                Admin Profile
            </h1>

            <p>
                Campus Grievance & Maintenance Portal
            </p>

        </div>


        <div class="profile-card">

            <h2>
                Account Information
            </h2>


            <div class="profile-row">

                <span class="profile-label">
                    🆔 Admin ID
                </span>

                <span class="profile-value">
                    <?php
                    echo htmlspecialchars($admin["admin_id"]);
                    ?>
                </span>

            </div>


            <div class="profile-row">

                <span class="profile-label">
                    👤 Full Name
                </span>

                <span class="profile-value">
                    <?php
                    echo htmlspecialchars($admin["full_name"]);
                    ?>
                </span>

            </div>


            <div class="profile-row">

                <span class="profile-label">
                    📧 Email
                </span>

                <span class="profile-value">
                    <?php
                    echo htmlspecialchars($admin["email"]);
                    ?>
                </span>

            </div>


            <div class="profile-row">

                <span class="profile-label">
                    📅 Account Created
                </span>

                <span class="profile-value">
                    <?php
                    echo htmlspecialchars($admin["created_at"]);
                    ?>
                </span>

            </div>


            <div class="profile-actions">

                <a
                    href="admin_dashboard.php"
                    class="profile-btn dashboard-btn"
                >
                    ← Dashboard
                </a>

                <a
                    href="admin_change_password.php"
                    class="profile-btn password-btn"
                >
                    🔐 Change Password
                </a>

            </div>


        </div>

    </div>

</div>


</body>

</html>