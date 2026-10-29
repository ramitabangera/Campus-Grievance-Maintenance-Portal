<?php
session_start();
require_once "../../config/db.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $complaint_id = trim($_POST["complaint_id"]);
    $status = trim($_POST["status"]);

    $allowed_statuses = [
        "Pending",
        "In Progress",
        "Resolved"
    ];

    if (!in_array($status, $allowed_statuses)) {
        die("Invalid status.");
    }

    $stmt = $conn->prepare(
        "UPDATE complaints
         SET status = ?
         WHERE complaint_id = ?"
    );

    $stmt->bind_param(
        "ss",
        $status,
        $complaint_id
    );

    $stmt->execute();

    $stmt->close();

    header("Location: admin_dashboard.php");
    exit();
}
?>