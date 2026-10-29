<?php

session_start();

require_once "../../config/db.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit();
}

$complaint_id = trim($_GET["complaint_id"] ?? "");

if (empty($complaint_id)) {
    die("Invalid Complaint ID.");
}

$stmt = $conn->prepare(
    "SELECT 
        c.complaint_id,
        c.student_id,
        c.category,
        c.location,
        c.description,
        c.status,
        c.created_at,
        s.full_name,
        s.email,
        s.roll_no,
        s.department,
        s.class
     FROM complaints c
     LEFT JOIN students s
        ON c.student_id = s.student_id
     WHERE c.complaint_id = ?"
);

$stmt->bind_param("s", $complaint_id);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Complaint not found.");
}

$complaint = $result->fetch_assoc();

$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Complaint Details - CGMP
    </title>

    <link rel="stylesheet" href="../../css/style.css">

    <style>

        .details-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .details-header {
            background: linear-gradient(
                135deg,
                #0b3d91,
                #1769d1
            );

            color: white;

            padding: 30px;

            border-radius: 15px;

            margin-bottom: 25px;

            box-shadow:
                0 5px 15px rgba(0,0,0,0.12);
        }

        .details-header h1 {
            margin-bottom: 8px;
        }

        .details-card {
            background: white;

            padding: 25px;

            border-radius: 12px;

            margin-bottom: 25px;

            box-shadow:
                0 3px 12px rgba(0,0,0,0.08);
        }

        .details-card h2 {
            color: #0b3d91;

            margin-bottom: 20px;

            border-bottom: 2px solid #eef4ff;

            padding-bottom: 10px;
        }

        .detail-row {
            display: grid;

            grid-template-columns: 180px 1fr;

            gap: 20px;

            padding: 13px 0;

            border-bottom: 1px solid #eee;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: bold;

            color: #555;
        }

        .detail-value {
            color: #222;

            word-break: break-word;
        }

        .complaint-id {
            color: #0b3d91;

            font-weight: bold;

            font-size: 18px;
        }

        .description-box {
            background: #f7f9fc;

            padding: 18px;

            border-radius: 8px;

            line-height: 1.6;
        }

        .status {
            display: inline-block;

            padding: 7px 14px;

            border-radius: 20px;

            font-weight: bold;

            font-size: 14px;
        }

        .pending {
            background: #fff3cd;

            color: #856404;
        }

        .progress {
            background: #cfe2ff;

            color: #084298;
        }

        .resolved {
            background: #d1e7dd;

            color: #0f5132;
        }

        .update-form {
            display: flex;

            gap: 12px;

            align-items: center;

            flex-wrap: wrap;
        }

        .update-form select {
            padding: 11px;

            border: 1px solid #ccc;

            border-radius: 7px;

            min-width: 180px;
        }

        .update-btn {
            background: #28a745;

            color: white;

            border: none;

            padding: 11px 20px;

            border-radius: 7px;

            cursor: pointer;

            font-weight: bold;
        }

        .update-btn:hover {
            background: #218838;
        }

        .back-btn-details {
            display: inline-block;

            background: #0b3d91;

            color: white;

            text-decoration: none;

            padding: 11px 20px;

            border-radius: 7px;

            font-weight: bold;
        }

        .back-btn-details:hover {
            background: #08306b;
        }

        @media (max-width: 650px) {

            .detail-row {
                grid-template-columns: 1fr;

                gap: 5px;
            }

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

    <a href="admin_profile.php">
        👤 Profile
    </a>

    <a href="admin_logout.php">
        🚪 Logout
    </a>

</div>


<!-- MAIN -->

<div class="main">

    <div class="details-container">


        <!-- HEADER -->

        <div class="details-header">

            <h1>
                📋 Complaint Details
            </h1>

            <p>
                Complete information about this complaint.
            </p>

        </div>


        <!-- COMPLAINT INFORMATION -->

        <div class="details-card">

            <h2>
                Complaint Information
            </h2>


            <div class="detail-row">

                <div class="detail-label">
                    Complaint ID
                </div>

                <div class="detail-value complaint-id">

                    <?php
                    echo htmlspecialchars(
                        $complaint["complaint_id"]
                    );
                    ?>

                </div>

            </div>


            <div class="detail-row">

                <div class="detail-label">
                    Category
                </div>

                <div class="detail-value">

                    <?php
                    echo htmlspecialchars(
                        $complaint["category"]
                    );
                    ?>

                </div>

            </div>


            <div class="detail-row">

                <div class="detail-label">
                    Location
                </div>

                <div class="detail-value">

                    <?php
                    echo htmlspecialchars(
                        $complaint["location"]
                    );
                    ?>

                </div>

            </div>


            <div class="detail-row">

                <div class="detail-label">
                    Submitted On
                </div>

                <div class="detail-value">

                    <?php
                    echo htmlspecialchars(
                        $complaint["created_at"]
                    );
                    ?>

                </div>

            </div>


            <div class="detail-row">

                <div class="detail-label">
                    Current Status
                </div>

                <div class="detail-value">

                    <?php

                    $status = $complaint["status"];

                    if ($status === "Pending") {

                        echo '<span class="status pending">
                                🟡 Pending
                              </span>';

                    } elseif ($status === "In Progress") {

                        echo '<span class="status progress">
                                🔵 In Progress
                              </span>';

                    } elseif ($status === "Resolved") {

                        echo '<span class="status resolved">
                                🟢 Resolved
                              </span>';

                    } else {

                        echo htmlspecialchars($status);

                    }

                    ?>

                </div>

            </div>


            <div class="detail-row">

                <div class="detail-label">
                    Description
                </div>

                <div class="detail-value">

                    <div class="description-box">

                        <?php
                        echo nl2br(
                            htmlspecialchars(
                                $complaint["description"]
                            )
                        );
                        ?>

                    </div>

                </div>

            </div>

        </div>


        <!-- STUDENT INFORMATION -->

        <div class="details-card">

            <h2>
                👨‍🎓 Student Information
            </h2>


            <div class="detail-row">

                <div class="detail-label">
                    Student ID
                </div>

                <div class="detail-value">

                    <?php
                    echo htmlspecialchars(
                        $complaint["student_id"]
                    );
                    ?>

                </div>

            </div>


            <div class="detail-row">

                <div class="detail-label">
                    Full Name
                </div>

                <div class="detail-value">

                    <?php
                    echo htmlspecialchars(
                        $complaint["full_name"] ?? "N/A"
                    );
                    ?>

                </div>

            </div>


            <div class="detail-row">

                <div class="detail-label">
                    Email
                </div>

                <div class="detail-value">

                    <?php
                    echo htmlspecialchars(
                        $complaint["email"] ?? "N/A"
                    );
                    ?>

                </div>

            </div>


            <div class="detail-row">

                <div class="detail-label">
                    Roll Number
                </div>

                <div class="detail-value">

                    <?php
                    echo htmlspecialchars(
                        $complaint["roll_no"] ?? "N/A"
                    );
                    ?>

                </div>

            </div>


            <div class="detail-row">

                <div class="detail-label">
                    Department
                </div>

                <div class="detail-value">

                    <?php
                    echo htmlspecialchars(
                        $complaint["department"] ?? "N/A"
                    );
                    ?>

                </div>

            </div>


            <div class="detail-row">

                <div class="detail-label">
                    Class
                </div>

                <div class="detail-value">

                    <?php
                    echo htmlspecialchars(
                        $complaint["class"] ?? "N/A"
                    );
                    ?>

                </div>

            </div>

        </div>


        <!-- UPDATE STATUS -->

        <div class="details-card">

            <h2>
                🔄 Update Complaint Status
            </h2>


            <form
                method="POST"
                action="update_complaint.php"
                class="update-form"
            >

                <input
                    type="hidden"
                    name="complaint_id"
                    value="<?php
                    echo htmlspecialchars(
                        $complaint["complaint_id"]
                    );
                    ?>"
                >


                <select name="status">

                    <option
                        value="Pending"
                        <?php
                        if ($status === "Pending") {
                            echo "selected";
                        }
                        ?>
                    >
                        Pending
                    </option>

                    <option
                        value="In Progress"
                        <?php
                        if ($status === "In Progress") {
                            echo "selected";
                        }
                        ?>
                    >
                        In Progress
                    </option>

                    <option
                        value="Resolved"
                        <?php
                        if ($status === "Resolved") {
                            echo "selected";
                        }
                        ?>
                    >
                        Resolved
                    </option>

                </select>


                <button
                    type="submit"
                    class="update-btn"
                >
                    Update Status
                </button>

            </form>

        </div>


        <a
            href="admin_complaints.php"
            class="back-btn-details"
        >
            ← Back to Complaints
        </a>


    </div>

</div>


</body>

</html>