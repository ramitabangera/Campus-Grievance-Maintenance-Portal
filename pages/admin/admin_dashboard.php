<?php
session_start();
require_once "../../config/db.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit();
}

$admin_name = $_SESSION["admin_name"];

/* Complaint counts */
$count_query = "
    SELECT
        COUNT(*) AS total,
        SUM(status = 'Pending') AS pending,
        SUM(status = 'In Progress') AS in_progress,
        SUM(status = 'Resolved') AS resolved
    FROM complaints
";

$count_result = $conn->query($count_query);
$counts = $count_result->fetch_assoc();

$total = $counts["total"] ?? 0;
$pending = $counts["pending"] ?? 0;
$in_progress = $counts["in_progress"] ?? 0;
$resolved = $counts["resolved"] ?? 0;

/* Total registered students */
$student_result = $conn->query("SELECT COUNT(*) AS total_students FROM students");
$student_count = $student_result->fetch_assoc();

$total_students = $student_count["total_students"] ?? 0;

/* Recent complaints */
$complaints = $conn->query(
    "SELECT complaint_id, student_id, category, location, status, created_at
     FROM complaints
     ORDER BY created_at DESC"
);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard - CGMP</title>

    <link rel="stylesheet" href="../../css/style.css">

    <style>

        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .admin-header {
            background: linear-gradient(135deg, #0b3d91, #1769d1);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.12);
        }

        .admin-header h1 {
            margin-bottom: 8px;
        }

        .admin-header p {
            margin-bottom: 15px;
        }

        .logout-btn {
            display: inline-block;
            background: #dc3545;
            color: white;
            padding: 10px 18px;
            border-radius: 7px;
            text-decoration: none;
            font-weight: bold;
        }

        .logout-btn:hover {
            background: #b02a37;
        }

        .dashboard-title {
            color: #0b3d91;
            margin-bottom: 20px;
        }

        .admin-stats {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 18px;
            margin-bottom: 35px;
        }

        .admin-stat {
            background: white;
            padding: 25px 15px;
            text-align: center;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
            transition: 0.3s;
        }

        .admin-stat:hover {
            transform: translateY(-5px);
        }

        .admin-stat h2 {
            color: #0b3d91;
            font-size: 32px;
            margin-bottom: 5px;
        }

        .admin-stat p {
            color: #666;
            font-weight: bold;
        }

        .complaints-section {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
        }

        .complaints-section h2 {
            color: #0b3d91;
            margin-bottom: 20px;
        }

        .table-container {
            overflow-x: auto;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
        }

        .admin-table th {
            background: #0b3d91;
            color: white;
            padding: 13px;
            text-align: left;
        }

        .admin-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }

        .admin-table tr:hover {
            background: #f7f9fc;
        }

        .status-select {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .update-btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            margin-left: 5px;
        }

        .update-btn:hover {
            background: #218838;
        }

        .empty-message {
            text-align: center;
            padding: 35px;
            color: #666;
        }


        @media (max-width: 1000px) {

            .admin-stats {
                grid-template-columns: repeat(2, 1fr);
            }

        }


        @media (max-width: 600px) {

            .admin-stats {
                grid-template-columns: 1fr;
            }

        }
		
		/* =========================
   ADMIN QUICK ACTIONS
========================= */

.quick-actions {
    margin-top: 30px;
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 3px 12px rgba(0,0,0,0.08);
}

.quick-actions h2 {
    color: #0b3d91;
    margin-bottom: 20px;
}

.quick-action-container {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 18px;
}

.quick-action-card {
    background: #f7f9fc;
    padding: 22px;
    border-radius: 10px;
    text-decoration: none;
    color: #333;
    text-align: center;
    border: 1px solid #e5e5e5;
    transition: 0.3s;
}

.quick-action-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.12);
    border-color: #0b3d91;
}

.quick-icon {
    display: block;
    font-size: 32px;
    margin-bottom: 10px;
}

.quick-action-card h3 {
    color: #0b3d91;
    margin-bottom: 8px;
}

.quick-action-card p {
    color: #666;
    font-size: 14px;
}

.logout-card:hover {
    border-color: #dc3545;
}

.logout-card h3 {
    color: #dc3545;
}

@media (max-width: 900px) {

    .quick-action-container {
        grid-template-columns: repeat(2, 1fr);
    }

}

@media (max-width: 550px) {

    .quick-action-container {
        grid-template-columns: 1fr;
    }

}

    </style>

</head>


<body>


<div class="sidebar">
    <h2>CGMP</h2>

    <a href="admin_dashboard.php">📊 Dashboard</a>

    <a href="admin_students.php">👨‍🎓 Students</a>

    <a href="admin_complaints.php">📋 Complaints</a>

    <a href="admin_profile.php">👤 Admin Profile</a>

    <a href="admin_logout.php">🚪 Logout</a>
</div>


<div class="main">

    <div class="admin-container">


        <!-- ADMIN HEADER -->

        <div class="admin-header">

            <h1>
                Campus Grievance & Maintenance Portal
            </h1>

            <p>
                Welcome, <?php echo htmlspecialchars($admin_name); ?> 👋
            </p>

            <a href="admin_logout.php" class="logout-btn">
                🚪 Logout
            </a>

        </div>


        <!-- DASHBOARD -->

        <h2 class="dashboard-title">
            Admin Dashboard 📊
        </h2>


        <!-- STATISTICS -->

        <div class="admin-stats">


            <div class="admin-stat">

                <h2>
                    <?php echo $total_students; ?>
                </h2>

                <p>
                    👨‍🎓 Total Students
                </p>

            </div>


            <div class="admin-stat">

                <h2>
                    <?php echo $total; ?>
                </h2>

                <p>
                    📋 Total Complaints
                </p>

            </div>


            <div class="admin-stat">

                <h2>
                    <?php echo $pending; ?>
                </h2>

                <p>
                    🟡 Pending
                </p>

            </div>


            <div class="admin-stat">

                <h2>
                    <?php echo $in_progress; ?>
                </h2>

                <p>
                    🔵 In Progress
                </p>

            </div>


            <div class="admin-stat">

                <h2>
                    <?php echo $resolved; ?>
                </h2>

                <p>
                    🟢 Resolved
                </p>

            </div>


        </div>


        <!-- COMPLAINTS -->

        <div class="complaints-section">

            <h2>
                All Complaints 📋
            </h2>


            <?php if ($complaints && $complaints->num_rows > 0): ?>

                <div class="table-container">

                    <table class="admin-table">

                        <thead>

                            <tr>

                                <th>Complaint ID</th>

                                <th>Student ID</th>

                                <th>Category</th>

                                <th>Location</th>

                                <th>Status</th>

                                <th>Update Status</th>

                                <th>Date</th>

                            </tr>

                        </thead>


                        <tbody>


                        <?php while ($complaint = $complaints->fetch_assoc()): ?>


                            <tr>


                                <td>

                                    <strong>

                                        <?php
                                        echo htmlspecialchars(
                                            $complaint["complaint_id"]
                                        );
                                        ?>

                                    </strong>

                                </td>


                                <td>

                                    <?php
                                    echo htmlspecialchars(
                                        $complaint["student_id"]
                                    );
                                    ?>

                                </td>


                                <td>

                                    <?php
                                    echo htmlspecialchars(
                                        $complaint["category"]
                                    );
                                    ?>

                                </td>


                                <td>

                                    <?php
                                    echo htmlspecialchars(
                                        $complaint["location"]
                                    );
                                    ?>

                                </td>


                                <td>

                                    <?php
                                    echo htmlspecialchars(
                                        $complaint["status"]
                                    );
                                    ?>

                                </td>


                                <td>

                                    <form
                                        method="POST"
                                        action="update_complaint.php"
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


                                        <select
                                            name="status"
                                            class="status-select"
                                        >


                                            <option
                                                value="Pending"
                                                <?php
                                                if (
                                                    $complaint["status"]
                                                    === "Pending"
                                                ) {
                                                    echo "selected";
                                                }
                                                ?>
                                            >
                                                Pending
                                            </option>


                                            <option
                                                value="In Progress"
                                                <?php
                                                if (
                                                    $complaint["status"]
                                                    === "In Progress"
                                                ) {
                                                    echo "selected";
                                                }
                                                ?>
                                            >
                                                In Progress
                                            </option>


                                            <option
                                                value="Resolved"
                                                <?php
                                                if (
                                                    $complaint["status"]
                                                    === "Resolved"
                                                ) {
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
                                            Update
                                        </button>


                                    </form>

                                </td>


                                <td>

                                    <?php
                                    echo htmlspecialchars(
                                        $complaint["created_at"]
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

                    📭 No complaints have been submitted yet.

                </div>


            <?php endif; ?>


        </div>


    </div>
	
	<!-- QUICK ACTIONS -->

<div class="quick-actions">

    <h2>Quick Actions ⚡</h2>

    <div class="quick-action-container">

        <a href="admin_students.php" class="quick-action-card">
            <span class="quick-icon">👨‍🎓</span>
            <h3>Manage Students</h3>
            <p>View all registered students</p>
        </a>
		
		<a href="admin_profile.php" class="quick-action-card">
    <span class="quick-icon">👤</span>
    <h3>Admin Profile</h3>
    <p>View your admin account</p>
</a>


        <a href="admin_complaints.php" class="quick-action-card">
            <span class="quick-icon">📋</span>
            <h3>Manage Complaints</h3>
            <p>View and update complaints</p>
        </a>


        <a href="admin_complaints.php" class="quick-action-card">
            <span class="quick-icon">🔎</span>
            <h3>Search Complaints</h3>
            <p>Find complaints quickly</p>
        </a>


        <a href="admin_logout.php" class="quick-action-card logout-card">
            <span class="quick-icon">🚪</span>
            <h3>Logout</h3>
            <p>Sign out of admin account</p>
        </a>

    </div>

</div>

</div>


</body>

</html>