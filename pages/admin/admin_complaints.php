<?php

session_start();

require_once "../../config/db.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit();
}


/* Search */

$search = "";

if (isset($_GET["search"])) {
    $search = trim($_GET["search"]);
}


/* Status filter */

$status_filter = "";

if (isset($_GET["status"])) {
    $status_filter = trim($_GET["status"]);
}


/* Build query */

$sql = "
    SELECT complaint_id,
           student_id,
           category,
           location,
           description,
           status,
           created_at
    FROM complaints
    WHERE 1=1
";

$params = [];
$types = "";


/* Search condition */

if ($search !== "") {

    $sql .= "
        AND (
            complaint_id LIKE ?
            OR student_id LIKE ?
            OR category LIKE ?
            OR location LIKE ?
        )
    ";

    $search_value = "%" . $search . "%";

    $params[] = $search_value;
    $params[] = $search_value;
    $params[] = $search_value;
    $params[] = $search_value;

    $types .= "ssss";
}


/* Status condition */

if (
    $status_filter === "Pending" ||
    $status_filter === "In Progress" ||
    $status_filter === "Resolved"
) {

    $sql .= " AND status = ?";

    $params[] = $status_filter;

    $types .= "s";
}


$sql .= " ORDER BY created_at DESC";


$stmt = $conn->prepare($sql);


if (!empty($params)) {

    $stmt->bind_param(
        $types,
        ...$params
    );

}


$stmt->execute();

$complaints = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Complaints - CGMP</title>

    <link rel="stylesheet" href="../../css/style.css">

    <style>

        .admin-container {
            max-width: 1250px;
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

        .filter-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
        }

        .filter-form {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            align-items: center;
        }

        .filter-form input,
        .filter-form select {
            padding: 11px;
            border: 1px solid #ccc;
            border-radius: 7px;
            font-size: 14px;
        }

        .filter-form input {
            flex: 1;
            min-width: 250px;
        }

        .filter-btn {
            background: #0b3d91;
            color: white;
            border: none;
            padding: 11px 18px;
            border-radius: 7px;
            cursor: pointer;
            font-weight: bold;
        }

        .filter-btn:hover {
            background: #08306b;
        }

        .clear-btn {
            background: #6c757d;
            color: white;
            padding: 11px 18px;
            border-radius: 7px;
            text-decoration: none;
            font-weight: bold;
        }

        .complaints-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
        }

        .complaints-card h2 {
            color: #0b3d91;
            margin-bottom: 20px;
        }

        .table-container {
            overflow-x: auto;
        }

        .complaints-table {
            width: 100%;
            border-collapse: collapse;
        }

        .complaints-table th {
            background: #0b3d91;
            color: white;
            padding: 13px;
            text-align: left;
            white-space: nowrap;
        }

        .complaints-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            vertical-align: top;
        }

        .complaints-table tr:hover {
            background: #f7f9fc;
        }

        .complaint-id {
            color: #0b3d91;
            font-weight: bold;
        }

        .description {
            max-width: 250px;
            line-height: 1.5;
        }

        .status {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
            white-space: nowrap;
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
            gap: 5px;
            align-items: center;
        }

        .update-form select {
            padding: 7px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .update-form button {
            background: #28a745;
            color: white;
            border: none;
            padding: 7px 10px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }

        .update-form button:hover {
            background: #218838;
        }

        .empty-message {
            text-align: center;
            padding: 40px;
            color: #666;
        }

        @media (max-width: 700px) {

            .filter-form {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-form input {
                min-width: 100%;
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

    <a href="admin_logout.php">
        🚪 Logout
    </a>

</div>


<!-- MAIN -->

<div class="main">

    <div class="admin-container">


        <!-- HEADER -->

        <div class="page-header">

            <h1>
                📋 Manage Complaints
            </h1>

            <p>
                View, search and manage all student complaints.
            </p>

        </div>


        <!-- SEARCH & FILTER -->

        <div class="filter-card">

            <form
                method="GET"
                class="filter-form"
            >

                <input
                    type="text"
                    name="search"
                    placeholder="🔎 Search Complaint ID, Student ID, Category or Location"
                    value="<?php echo htmlspecialchars($search); ?>"
                >


                <select name="status">

                    <option value="">
                        All Status
                    </option>

                    <option
                        value="Pending"
                        <?php
                        if ($status_filter === "Pending") {
                            echo "selected";
                        }
                        ?>
                    >
                        Pending
                    </option>

                    <option
                        value="In Progress"
                        <?php
                        if ($status_filter === "In Progress") {
                            echo "selected";
                        }
                        ?>
                    >
                        In Progress
                    </option>

                    <option
                        value="Resolved"
                        <?php
                        if ($status_filter === "Resolved") {
                            echo "selected";
                        }
                        ?>
                    >
                        Resolved
                    </option>

                </select>


                <button
                    type="submit"
                    class="filter-btn"
                >
                    🔎 Search
                </button>


                <a
                    href="admin_complaints.php"
                    class="clear-btn"
                >
                    Clear
                </a>

            </form>

        </div>


        <!-- COMPLAINT TABLE -->

        <div class="complaints-card">

            <h2>
                All Complaints
            </h2>


            <?php if ($complaints && $complaints->num_rows > 0): ?>


                <div class="table-container">

                    <table class="complaints-table">

                        <thead>

                            <tr>

                                <th>Complaint ID</th>

                                <th>Student ID</th>

                                <th>Category</th>

                                <th>Location</th>

                                <th>Description</th>

                                <th>Status</th>

                                <th>Update</th>

                                <th>Date</th>

                            </tr>

                        </thead>


                        <tbody>


                        <?php while ($complaint = $complaints->fetch_assoc()): ?>


                            <tr>


                                <td class="complaint-id">

    <a
        href="admin_complaint_details.php?complaint_id=<?php echo urlencode($complaint["complaint_id"]); ?>"
        style="
            display:inline-block;
            background:#0b3d91;
            color:white;
            padding:8px 12px;
            border-radius:6px;
            text-decoration:none;
            font-weight:bold;
        "
    >
        View Details
    </a>

    <br>

    <small>
        <?php
        echo htmlspecialchars($complaint["complaint_id"]);
        ?>
    </small>

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


                                <td class="description">

                                    <?php
                                    echo htmlspecialchars(
                                        $complaint["description"]
                                    );
                                    ?>

                                </td>


                                <td>

                                    <?php

                                    $status =
                                        $complaint["status"];

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

                                        echo '<span class="status">
                                                ' .
                                                htmlspecialchars($status)
                                                .
                                             '</span>';

                                    }

                                    ?>

                                </td>


                                <td>


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
                                                if (
                                                    $status === "Pending"
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
                                                    $status === "In Progress"
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
                                                    $status === "Resolved"
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

                    📭 No complaints found.

                </div>


            <?php endif; ?>


        </div>


    </div>

</div>


</body>

</html>

<?php

$stmt->close();

?>