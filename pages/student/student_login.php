
<?php
session_start();
require_once "../../config/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $student_id = trim($_POST["student_id"]);
    $password = $_POST["password"];

    if (empty($student_id) || empty($password)) {
        $message = "Please enter Student ID and Password.";
    } else {

        $stmt = $conn->prepare(
            "SELECT student_id, full_name, password
             FROM students
             WHERE student_id = ?"
        );

        $stmt->bind_param("s", $student_id);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 1) {

            $student = $result->fetch_assoc();

            if (password_verify($password, $student["password"])) {

                $_SESSION["student_id"] = $student["student_id"];
                $_SESSION["student_name"] = $student["full_name"];

                header("Location: student_dashboard.php");
                exit();

            } else {
                $message = "Invalid Student ID or Password.";
            }

        } else {
            $message = "Invalid Student ID or Password.";
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

    <title>Student Login - Campus Grievance Portal</title>

    <link rel="stylesheet" href="../../css/style.css">
</head>

<body>

    <div class="form-container">

        <h2>Student Login</h2>

        <?php if (!empty($message)): ?>
            <p>
                <?php echo htmlspecialchars($message); ?>
            </p>
        <?php endif; ?>

        <form method="POST">

            <label>Student ID</label>

            <input
                type="text"
                name="student_id"
                placeholder="Enter Student ID"
                required
            >

            <label>Password</label>

            <input
                type="password"
                name="password"
                placeholder="Enter Password"
                required
            >

            <button type="submit" class="btn">
                Login
            </button>

        </form>

        <p>
            Don't have an account?
            <a href="student_register.php">Register here</a>
        </p>

        <a href="../../index.php" class="back-btn">
            ← Back to Home
        </a>

    </div>

</body>
</html>