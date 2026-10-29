<?php
require_once "../../config/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $full_name = trim($_POST["full_name"]);
    $email = trim($_POST["email"]);
    $roll_no = trim($_POST["roll_no"]);
    $department = trim($_POST["department"]);
    $class = trim($_POST["class"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if (
        empty($full_name) ||
        empty($email) ||
        empty($roll_no) ||
        empty($department) ||
        empty($class) ||
        empty($password)
    ) {
        $message = "Please fill in all required fields.";
    } elseif ($password !== $confirm_password) {
        $message = "Passwords do not match.";
    } else {

        // Check whether email or roll number already exists
        $check = $conn->prepare(
            "SELECT id FROM students WHERE email = ? OR roll_no = ?"
        );

        $check->bind_param("ss", $email, $roll_no);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $message = "Email or Roll Number is already registered.";
        } else {

            // Generate Student ID
            $student_id = "STU" . date("Y") . rand(1000, 9999);

            // Securely hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare(
                "INSERT INTO students
                (student_id, full_name, email, roll_no, department, class, password)
                VALUES (?, ?, ?, ?, ?, ?, ?)"
            );

            $stmt->bind_param(
                "sssssss",
                $student_id,
                $full_name,
                $email,
                $roll_no,
                $department,
                $class,
                $hashed_password
            );

            if ($stmt->execute()) {
                $message = "Registration successful! Your Student ID is: " . $student_id;
            } else {
                $message = "Registration failed. Please try again.";
            }

            $stmt->close();
        }

        $check->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration - Campus Grievance Portal</title>

    <link rel="stylesheet" href="../../css/style.css">
</head>

<body>

    <h1>Student Registration</h1>

    <?php if (!empty($message)): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form method="POST">

        <label>Full Name</label>
        <input type="text" name="full_name" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Roll Number</label>
        <input type="text" name="roll_no" required>

        <label>Department</label>
        <input type="text" name="department" required>

        <label>Class</label>
        <input type="text" name="class" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <label>Confirm Password</label>
        <input type="password" name="confirm_password" required>

        <button type="submit">Register</button>

    </form>
	<p>
    Already have an account?
    <a href="student_login.php">Login here</a>
</p>

</body>
</html>