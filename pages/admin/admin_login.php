<?php
session_start();
require_once "../../config/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $admin_id = trim($_POST["admin_id"]);
    $password = $_POST["password"];

    if (empty($admin_id) || empty($password)) {
        $message = "Please enter Admin ID and Password.";
    } else {

        $stmt = $conn->prepare(
            "SELECT admin_id, full_name, password
             FROM admins
             WHERE admin_id = ?"
        );

        $stmt->bind_param("s", $admin_id);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 1) {

            $admin = $result->fetch_assoc();

            if (password_verify($password, $admin["password"])) {

                $_SESSION["admin_id"] = $admin["admin_id"];
                $_SESSION["admin_name"] = $admin["full_name"];

                header("Location: admin_dashboard.php");
                exit();

            } else {
                $message = "Invalid Admin ID or Password.";
            }

        } else {
            $message = "Invalid Admin ID or Password.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Login - CGMP</title>

    <link rel="stylesheet" href="../../css/style.css">

    <style>

        .admin-login-wrapper {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f4f7fc;
            padding: 20px;
        }

        .admin-login-card {
            width: 420px;
            max-width: 100%;
            background: white;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.12);
        }

        .admin-icon {
            width: 70px;
            height: 70px;
            margin: 0 auto 20px;
            background: #0b3d91;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
        }

        .admin-login-card h1 {
            text-align: center;
            color: #0b3d91;
            margin-bottom: 8px;
        }

        .admin-subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 25px;
        }

        .admin-login-card label {
            display: block;
            margin-top: 15px;
            margin-bottom: 6px;
            font-weight: bold;
            color: #444;
        }

        .admin-login-card input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 7px;
            font-size: 15px;
            outline: none;
        }

        .admin-login-card input:focus {
            border-color: #0b3d91;
            box-shadow: 0 0 0 2px rgba(11,61,145,0.1);
        }

        .admin-login-card button {
            width: 100%;
            padding: 13px;
            margin-top: 25px;
            background: #0b3d91;
            color: white;
            border: none;
            border-radius: 7px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        .admin-login-card button:hover {
            background: #08306b;
        }

        .error-message {
            background: #f8d7da;
            color: #842029;
            padding: 12px;
            border-radius: 7px;
            margin-bottom: 15px;
            text-align: center;
        }

        .back-home {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #0b3d91;
            text-decoration: none;
            font-weight: bold;
        }

        .back-home:hover {
            text-decoration: underline;
        }

    </style>

</head>

<body>

<div class="admin-login-wrapper">

    <div class="admin-login-card">

        <div class="admin-icon">
            🔐
        </div>

        <h1>Admin Login</h1>

        <p class="admin-subtitle">
            Campus Grievance & Maintenance Portal
        </p>


        <?php if (!empty($message)): ?>

            <div class="error-message">
                <?php echo htmlspecialchars($message); ?>
            </div>

        <?php endif; ?>


        <form method="POST">

            <label for="admin_id">
                Admin ID
            </label>

            <input
                type="text"
                id="admin_id"
                name="admin_id"
                placeholder="Enter Admin ID"
                required
            >


            <label for="password">
                Password
            </label>

            <input
                type="password"
                id="password"
                name="password"
                placeholder="Enter Password"
                required
            >


            <button type="submit">
                🔑 Login
            </button>

        </form>


        <a href="../../index.php" class="back-home">
            ← Back to Home
        </a>

    </div>

</div>

</body>

</html>