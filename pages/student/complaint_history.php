<?php
session_start();
require_once "../../config/db.php";

if (!isset($_SESSION["student_id"])) {
    header("Location: student_login.php");
    exit();
}

$student_id = $_SESSION["student_id"];

$stmt = $conn->prepare(
    "SELECT complaint_id, category, location, description, status, created_at
     FROM complaints
     WHERE student_id = ?
     ORDER BY created_at DESC"
);

$stmt->bind_param("s", $student_id);
$stmt->execute();

$result = $stmt->get_result();
?>
    <!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Complaint History - CGMP</title>

    <link rel="stylesheet" href="../../css/style.css">

    <style>

        .history-container {
            margin-top: 20px;
        }

        .history-header {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
        }

        .history-header h1 {
            color: #0b3d91;
            margin-bottom: 5px;
        }

        .history-header p {
            color: #666;
        }

        .table-container {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
            overflow-x: auto;
        }

        .complaint-table {
            width: 100%;
            border-collapse: collapse;
        }

        .complaint-table th {
            background: #0b3d91;
            color: white;
            padding: 14px;
            text-align: left;
        }

        .complaint-table td {
            padding: 13px;
            border-bottom: 1px solid #eee;
        }

        .complaint-table tr:hover {
            background: #f7f9fc;
        }

        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-progress {
            background: #cfe2ff;
            color: #084298;
        }

        .status-resolved {
            background: #d1e7dd;
            color: #0f5132;
        }

        .empty-message {
            text-align: center;
            padding: 40px;
            color: #666;
            font-size: 17px;
        }

    </style>

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


<!-- MAIN -->

<div class="main">

    <div class="history-container">

        <div class="history-header">

            <h1>My Complaint History 📜</h1>

            <p>
                View all complaints submitted by you.
            </p>

        </div>


        <?php if ($result->num_rows > 0): ?>

            <div class="table-container">

                <table class="complaint-table">

                    <thead>

                        <tr>

                            <th>Complaint ID</th>

                            <th>Category</th>

                            <th>Location</th>

                            <th>Description</th>

                            <th>Status</th>

                            <th>Date</th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php while ($complaint = $result->fetch_assoc()): ?>

                            <tr>

                                <td>
    <a
        href="track_complaint.php?complaint_id=<?php echo urlencode($complaint["complaint_id"]); ?>"
        style="color:#0b3d91; font-weight:bold; text-decoration:none;"
    >
        <?php echo htmlspecialchars($complaint["complaint_id"]); ?>
    </a>
</td>

                                <td>
                                    <?php echo htmlspecialchars($complaint["category"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($complaint["location"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($complaint["description"]); ?>
                                </td>

                                <td>

                                    <?php

                                    $status = strtolower($complaint["status"]);

                                    if ($status == "pending") {

                                        echo '<span class="status status-pending">Pending</span>';

                                    } elseif ($status == "in progress") {

                                        echo '<span class="status status-progress">In Progress</span>';

                                    } elseif ($status == "resolved") {

                                        echo '<span class="status status-resolved">Resolved</span>';

                                    } else {

                                        echo '<span class="status">' .
                                             htmlspecialchars($complaint["status"]) .
                                             '</span>';

                                    }

                                    ?>

                                </td>

                                <td>
                                    <?php echo htmlspecialchars($complaint["created_at"]); ?>
                                </td>

                            </tr>

                        <?php endwhile; ?>

                    </tbody>

                </table>

            </div>


        <?php else: ?>

            <div class="table-container">

                <div class="empty-message">

                    📭 You haven't submitted any complaints yet.

                    <br><br>

                    <a href="raise_complaint.php" class="btn">
                        📝 Raise Your First Complaint
                    </a>

                </div>

            </div>

        <?php endif; ?>

    </div>

</div>

</body>

</html>