<?php
session_start();
require_once "../../config/db.php";

if (!isset($_SESSION["student_id"])) {
    header("Location: student_login.php");
    exit();
}

$student_id = $_SESSION["student_id"];
$complaint = null;
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $complaint_id = trim($_POST["complaint_id"] ?? "");

} elseif (isset($_GET["complaint_id"])) {

    $complaint_id = trim($_GET["complaint_id"]);

} else {

    $complaint_id = "";
}


if (!empty($complaint_id)) {

        $stmt = $conn->prepare(
            "SELECT complaint_id, category, location, description, status, created_at
             FROM complaints
             WHERE complaint_id = ? AND student_id = ?"
        );

        $stmt->bind_param("ss", $complaint_id, $student_id);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $complaint = $result->fetch_assoc();
        } else {
            $message = "Complaint not found or it does not belong to your account.";
        }

        $stmt->close();
    }

?>

    <!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Track Complaint - CGMP</title>

    <link rel="stylesheet" href="../../css/style.css">

    <style>

        .track-container {
            max-width: 900px;
            margin: 20px auto;
        }

        .track-header {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
        }

        .track-header h1 {
            color: #0b3d91;
            margin-bottom: 5px;
        }

        .track-header p {
            color: #666;
        }

        .track-form {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
        }

        .track-form h2 {
            color: #0b3d91;
            margin-bottom: 15px;
        }

        .track-form input {
            width: 100%;
            padding: 13px;
            border: 1px solid #ccc;
            border-radius: 7px;
            margin-bottom: 15px;
            font-size: 15px;
        }

        .track-form button {
            background: #0b3d91;
            color: white;
            padding: 12px 22px;
            border: none;
            border-radius: 7px;
            font-weight: bold;
            font-size: 15px;
        }

        .track-form button:hover {
            background: #08306b;
        }

        .message {
            background: #fff3cd;
            color: #664d03;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .complaint-card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
        }

        .complaint-card h2 {
            color: #0b3d91;
            margin-bottom: 20px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            padding: 14px 5px;
            border-bottom: 1px solid #eee;
        }

        .detail-label {
            font-weight: bold;
            color: #555;
        }

        .detail-value {
            text-align: right;
        }

        .progress {
            margin-top: 30px;
        }

        .progress h2 {
            margin-bottom: 20px;
        }

        .progress-step {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            margin: 8px 0;
            border-radius: 8px;
            background: #f5f7fa;
        }

        .active-step {
            background: #e8f1ff;
            color: #0b3d91;
        }

        .resolved-step {
            background: #d1e7dd;
            color: #0f5132;
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

    <div class="track-container">

        <div class="track-header">

            <h1>Track Your Complaint 🔎</h1>

            <p>
                Enter your complaint ID to view its current status.
            </p>
			</div>

        <div class="track-form">

            <h2>Enter Complaint ID</h2>

            <form method="POST">

                <input
                    type="text"
                    name="complaint_id"
                    placeholder="Example: CGMP-2026-33231"
                    required
                >

                <button type="submit">
                    🔎 Track Complaint
                </button>

            </form>

        </div>


        <?php if (!empty($message)): ?>

            <div class="message">

                <?php echo htmlspecialchars($message); ?>

            </div>

        <?php endif; ?>


        <?php if ($complaint): ?>

            <div class="complaint-card">

                <h2>Complaint Details</h2>


                <div class="detail-row">

                    <span class="detail-label">
                        Complaint ID
                    </span>

                    <span class="detail-value">
                        <strong>
                            <?php echo htmlspecialchars($complaint["complaint_id"]); ?>
                        </strong>
                    </span>

                </div>


                <div class="detail-row">

                    <span class="detail-label">
                        Category
                    </span>

                    <span class="detail-value">
                        <?php echo htmlspecialchars($complaint["category"]); ?>
                    </span>

                </div>


                <div class="detail-row">

                    <span class="detail-label">
                        Location
                    </span>

                    <span class="detail-value">
                        <?php echo htmlspecialchars($complaint["location"]); ?>
                    </span>

                </div>


                <div class="detail-row">

                    <span class="detail-label">
                        Description
                    </span>

                    <span class="detail-value">
                        <?php echo htmlspecialchars($complaint["description"]); ?>
                    </span>

                </div>


                <div class="detail-row">

                    <span class="detail-label">
                        Status
                    </span>

                    <span class="detail-value">
                        <?php echo htmlspecialchars($complaint["status"]); ?>
                    </span>

                </div>


                <div class="detail-row">

                    <span class="detail-label">
                        Submitted
                    </span>

                    <span class="detail-value">
                        <?php echo htmlspecialchars($complaint["created_at"]); ?>
                    </span>

                </div>


                <!-- PROGRESS -->

                <div class="progress">

                    <h2>Complaint Progress</h2>


                    <?php if ($complaint["status"] === "Pending"): ?>

                        <div class="progress-step active-step">
                            🟡 <strong>Complaint Submitted</strong>
                        </div>

                        <div class="progress-step">
                            ⚪ In Progress
                        </div>

                        <div class="progress-step">
                            ⚪ Resolved
                        </div>


                    <?php elseif ($complaint["status"] === "In Progress"): ?>

                        <div class="progress-step resolved-step">
                            🟢 <strong>Complaint Submitted</strong>
                        </div>

                        <div class="progress-step active-step">
                            🟡 <strong>In Progress</strong>
                        </div>

                        <div class="progress-step">
                            ⚪ Resolved
                        </div>


                    <?php elseif ($complaint["status"] === "Resolved"): ?>

                        <div class="progress-step resolved-step">
                            🟢 <strong>Complaint Submitted</strong>
                        </div>

                        <div class="progress-step resolved-step">
                            🟢 <strong>In Progress</strong>
                        </div>

                        <div class="progress-step resolved-step">
                            🟢 <strong>Complaint Resolved</strong>
                        </div>

                    <?php endif; ?>

                </div>

            </div>

        <?php endif; ?>

    </div>

</div>

</body>

</html>