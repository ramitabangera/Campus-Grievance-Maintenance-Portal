
<?php
session_start();
require_once "../../config/db.php";

if (!isset($_SESSION["student_id"])) {
    header("Location: student_login.php");
    exit();
}

$student_id = $_SESSION["student_id"];
$message = "";
$success = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $category = trim($_POST["category"]);
    $location = trim($_POST["location"]);
    $description = trim($_POST["description"]);

    if (empty($category) || empty($location) || empty($description)) {
        $message = "Please fill in all fields.";
    } else {

        $complaint_id = "CGMP-" . date("Y") . "-" . rand(10000, 99999);

        $stmt = $conn->prepare(
            "INSERT INTO complaints
            (complaint_id, student_id, category, location, description)
            VALUES (?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "sssss",
            $complaint_id,
            $student_id,
            $category,
            $location,
            $description
        );

        if ($stmt->execute()) {
            $success = true;
            $message = "Complaint submitted successfully!";
        } else {
            $message = "Unable to submit complaint. Please try again.";
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

    <title>Raise Complaint - Campus Grievance Portal</title>

    <link rel="stylesheet" href="../../css/style.css">
</head>

<body>

    <div class="form-container">

        <h2>Raise a Complaint</h2>

        <?php if (!empty($message)): ?>

            <p>
                <?php echo htmlspecialchars($message); ?>
            </p>

            <?php if ($success): ?>

                <p>
                    Your Complaint ID is:
                    <strong>
                        <?php echo htmlspecialchars($complaint_id); ?>
                    </strong>
                </p>

                <p>
                    Please save this Complaint ID for tracking.
                </p>

                <p>
                    <a href="track_complaint.php" class="btn">
                        Track Your Complaint
                    </a>
                </p>

            <?php endif; ?>

        <?php endif; ?>


        <?php if (!$success): ?>

            <form method="POST">

                <label for="category">
                    Complaint Category
                </label>

                <select name="category" id="category" required>

                    <option value="">
                        Select Category
                    </option>

                    <option value="Electrical">
                        Electrical
                    </option>

                    <option value="Plumbing">
                        Plumbing
                    </option>

                    <option value="Classroom">
                        Classroom
                    </option>

                    <option value="Computer/Lab">
                        Computer / Lab
                    </option>

                    <option value="Cleanliness">
                        Cleanliness
                    </option>

                    <option value="Other">
                        Other
                    </option>

                </select>


                <label for="location">
                    Location
                </label>

                <input
                    type="text"
                    name="location"
                    id="location"
                    placeholder="Example: Lab 2, 3rd Floor"
                    required
                >


                <label for="description">
                    Complaint Description
                </label>

                <textarea
                    name="description"
                    id="description"
                    rows="6"
                    placeholder="Describe your complaint..."
                    required
                ></textarea>


                <button type="submit">
                    Submit Complaint
                </button>

            </form>

        <?php endif; ?>


        <p>
            <a href="student_dashboard.php" class="back-btn">
                ← Back to Dashboard
            </a>
        </p>

    </div>

</body>

</html>
```