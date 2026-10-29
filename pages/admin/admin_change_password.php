<?php

session_start();

require_once "../../config/db.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit();
}

$admin_id = $_SESSION["admin_id"];
$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $current_password = $_POST["current_password"] ?? "";
    $new_password = $_POST["new_password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";

    if (
        empty($current_password) ||
        empty($new_password) ||
        empty($confirm_password)
    ) {

        $message = "Please fill in all fields.";
        $message_type = "error";

    } elseif ($new_password !== $confirm_password) {

        $message = "New password and confirm password do not match.";
        $message_type = "error";

    } elseif (strlen($new_password) < 8) {

        $message = "New password must be at least 8 characters long.";
        $message_type = "error";

    } else {

        /* Get current password */

        $stmt = $conn->prepare(
            "SELECT password
             FROM admins
             WHERE admin_id = ?"
        );

        $stmt->bind_param("s", $admin_id);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows !== 1) {

            $message = "Admin account not found.";
            $message_type = "error";

        } else {

            $admin = $result->fetch_assoc();

            /* Verify current password */

            if (!password_verify($current_password, $admin["password"])) {

                $message = "Current password is incorrect.";
                $message_type = "error";

            } else {

                /* Hash new password */

                $new_password_hash = password_hash(
                    $new_password,
                    PASSWORD_DEFAULT
                );

                /* Update password */

                $update = $conn->prepare(
                    "UPDATE admins
                     SET password = ?
                     WHERE admin_id = ?"
                );

                $update->bind_param(
                    "ss",
                    $new_password_hash,
                    $admin_id
                );

                if ($update->execute()) {

                    $message = "Password changed successfully!";
                    $message_type = "success";

                } else {

                    $message = "Unable to change password.";
                    $message_type = "error";
                }

                $update->close();
            }
        }

        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Change Password - CGMP</title>

    <link
        rel="stylesheet"
        href="../../css/style.css"
    >

    <style>

        .password-container {
            max-width: 600px;
            margin: 0 auto;
        }

        .password-header {
            background: linear-gradient(
                135deg,
                #0b3d91,
                #1769d1
            );

            color: white;

            padding: 30px;

            border-radius: 15px;

            margin-bottom: 25px;

            text-align: center;

            box-shadow:
                0 5px 15px rgba(0,0,0,0.12);
        }

        .password-header h1 {
            margin-bottom: 8px;
        }

        .password-card {
            background: white;

            padding: 30px;

            border-radius: 12px;

            box-shadow:
                0 3px 12px rgba(0,0,0,0.08);
        }

        .password-card h2 {
            color: #0b3d91;

            margin-bottom: 20px;
        }

        .password-card label {
            display: block;

            margin-top: 15px;

            font-weight: bold;
        }

        .password-card input {
            width: 100%;

            padding: 11px;

            margin-top: 6px;

            border: 1px solid #ccc;

            border-radius: 7px;

            font-size: 15px;
        }

        .password-card input:focus {
            outline: none;

            border-color: #0b3d91;

            box-shadow:
                0 0 0 2px rgba(11,61,145,0.1);
        }

        .change-btn {
            width: 100%;

            background: #28a745;

            color: white;

            border: none;

            padding: 12px;

            border-radius: 7px;

            font-size: 16px;

            font-weight: bold;

            cursor: pointer;

            margin-top: 25px;
        }

        .change-btn:hover {
            background: #218838;
        }

        .back-btn-custom {
            display: inline-block;

            margin-top: 15px;

            padding: 10px 18px;

            background: #0b3d91;

            color: white;

            text-decoration: none;

            border-radius: 7px;

            font-weight: bold;
        }

        .back-btn-custom:hover {
            background: #08306b;
        }

        .message {
            padding: 12px;

            border-radius: 7px;

            margin-bottom: 20px;

            font-weight: bold;
        }

        .success {
            background: #d1e7dd;

            color: #0f5132;
        }

        .error {
            background: #f8d7da;

            color: #842029;
        }

        .password-note {
            margin-top: 18px;

            color: #666;

            font-size: 14px;

            line-height: 1.5;
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

    <div class="password-container">


        <div class="password-header">

            <h1>
                🔐 Change Password
            </h1>

            <p>
                Keep your administrator account secure.
            </p>

        </div>


        <div class="password-card">

            <h2>
                Update Your Password
            </h2>


            <?php if (!empty($message)): ?>

                <div
                    class="message <?php echo $message_type; ?>"
                >

                    <?php
                    echo htmlspecialchars($message);
                    ?>

                </div>

            <?php endif; ?>


            <form method="POST">


                <label for="current_password">
                    Current Password
                </label>

                <input
                    type="password"
                    id="current_password"
                    name="current_password"
                    placeholder="Enter current password"
                    required
                >


                <label for="new_password">
                    New Password
                </label>

                <input
                    type="password"
                    id="new_password"
                    name="new_password"
                    placeholder="Enter new password"
                    minlength="8"
                    required
                >


                <label for="confirm_password">
                    Confirm New Password
                </label>

                <input
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    placeholder="Re-enter new password"
                    minlength="8"
                    required
                >


                <button
                    type="submit"
                    class="change-btn"
                >
                    🔐 Change Password
                </button>


            </form>


            <p class="password-note">
                Your password must be at least 8 characters long.
                The password is stored securely using password hashing.
            </p>


            <a
                href="admin_profile.php"
                class="back-btn-custom"
            >
                ← Back to Profile
            </a>


        </div>

    </div>

</div>


</body>

</html>