<?php
require_once "config/db.php";

$total_students = 0;
$total_complaints = 0;
$pending = 0;
$resolved = 0;

$result = $conn->query("SELECT COUNT(*) AS total FROM students");
if ($result) {
    $row = $result->fetch_assoc();
    $total_students = $row["total"];
}

$result = $conn->query("SELECT COUNT(*) AS total FROM complaints");
if ($result) {
    $row = $result->fetch_assoc();
    $total_complaints = $row["total"];
}

$result = $conn->query("SELECT COUNT(*) AS total FROM complaints WHERE status = 'Pending'");
if ($result) {
    $row = $result->fetch_assoc();
    $pending = $row["total"];
}

$result = $conn->query("SELECT COUNT(*) AS total FROM complaints WHERE status = 'Resolved'");
if ($result) {
    $row = $result->fetch_assoc();
    $resolved = $row["total"];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Campus Grievance & Maintenance Portal</title>

<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/home.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>

<header>

<div class="logo">
    <h2>CGMP</h2>
    <small>Campus Grievance & Maintenance Portal</small>
</div>

<nav>

<a href="index.php">Home</a>

<a href="pages/about.html">About</a>

<a href="#services">Services</a>

<a href="pages/student/track_complaint.php">Track</a>

<a href="pages/contact.html">Contact</a>

<a href="pages/login_selection.html" class="login-btn">
Login
</a>

</nav>

</header>

<section class="hero">

<div class="hero-text">

<h1>
Report Campus Problems
Without Standing in a Queue
</h1>

<p>

Raise complaints about Wi-Fi, classroom maintenance,
laboratory equipment, library, canteen,
electricity, water supply and much more.

Track everything online using a Ticket ID.

</p>

<a href="pages/student/student_register.php" class="btn">
Get Started
</a>

<a href="pages/student/track_complaint.php"
class="btn secondary-btn">

Track Complaint

</a>

</div>

<div class="hero-image" style = "width:50%;">

<img src="images/sies.jpg" alt="Campus Portal" style ="width :100%; max-width:650px; height :380px; object-fit:cover; border-radius:18px; box-shadow:0 8px 25px rgba (0,0,0,0.25);">

</div>

</section>
<section class="features">
    <h2>Why Choose Our Portal?</h2>

    <div class="feature-container">

        <div class="card">
            <div class="feature-icon">📝</div>
            <h3>Easy Complaint Registration</h3>
            <p>
                Students can easily register complaints
                about campus facilities and services.
            </p>
        </div>

        <div class="card">
            <div class="feature-icon">🔍</div>
            <h3>Track Complaints</h3>
            <p>
                Track the status of your complaint
                anytime using your Complaint ID.
            </p>
        </div>

        <div class="card">
            <div class="feature-icon">⚡</div>
            <h3>Quick Resolution</h3>
            <p>
                Complaints are monitored by the
                administration for faster resolution.
            </p>
        </div>

        <div class="card">
            <div class="feature-icon">🔒</div>
            <h3>Secure & Reliable</h3>
            <p>
                Your complaints and student information
                are handled securely.
            </p>
        </div>

    </div>

</section>

<div class="feature-container">

<div class="card">

<h3>Easy Complaint Submission</h3>

<p>
Students can report issues in less than one minute.
</p>

</div>

<div class="card">

<h3>Real-Time Tracking</h3>

<p>
Track complaint progress using your Ticket ID.
</p>

</div>

<div class="card">

<h3>Department Assignment</h3>

<p>

Complaints automatically reach the correct department.

</p>

</div>

<div class="card">

<h3>Secure Login</h3>

<p>

Student and Admin login are protected.

</p>

</div>

</div>

</section>

<section class="services" id="services">

    <h2>Our Services</h2>

    <p class="section-subtitle">
        Simple and reliable services to manage your campus complaints and maintenance requests.
    </p>

    <div class="services-container">

        <!-- Service 1 -->
        <div class="service-card">

            <div class="service-icon">
                📝
            </div>

            <h3>Complaint Registration</h3>

            <p>
                Register your campus complaints easily and receive a unique Complaint ID
                for future tracking.
            </p>

            <a href="pages/student/student_register.php" class="service-btn">
                Register Complaint
            </a>

        </div>


        <!-- Service 2 -->
        <div class="service-card">

            <div class="service-icon">
                📋
            </div>

            <h3>Ticket Tracking</h3>

            <p>
                Track the current status and progress of your complaint anytime using
                your Complaint ID.
            </p>

            <a href="pages/student/track_complaint.php" class="service-btn">
                Track Complaint
            </a>

        </div>


        <!-- Service 3 -->
        <div class="service-card">

            <div class="service-icon">
                🔧
            </div>

            <h3>Maintenance Requests</h3>

            <p>
                Report problems related to classrooms, laboratories, electricity,
                water supply, Wi-Fi and other campus facilities.
            </p>

            <a href="pages/student/raise_complaint.php" class="service-btn">
                Raise Request
            </a>

        </div>


        <!-- Service 4 -->
        <div class="service-card">

            <div class="service-icon">
                💬
            </div>

            <h3>Feedback System</h3>

            <p>
                Share your valuable feedback and suggestions to help improve
                campus facilities and student services.
            </p>

            <a href="pages/contact.html" class="service-btn">
                Give Feedback
            </a>

        </div>

    </div>

</section>
<section class="categories">

<h2>Complaint Categories</h2>

<div class="category-grid">

<div class="category">🏫 Classroom</div>

<div class="category">🧪 Laboratory</div>

<div class="category">📚 Library</div>

<div class="category">🚻 Washroom</div>

<div class="category">🍽 Canteen</div>

<div class="category">📶 Wi-Fi</div>

<div class="category">💡 Electricity</div>

<div class="category">💧 Water Supply</div>

<div class="category">🧹 Cleanliness</div>

<div class="category">🛡 Security</div>

</div>

</section>

<section class="steps">

<h2>How It Works</h2>

<div class="step-box">1️⃣ Register</div>
<div class="step-box">2️⃣ Login</div>
<div class="step-box">3️⃣ Raise Complaint</div>
<div class="step-box">4️⃣ Get Ticket ID</div>
<div class="step-box">5️⃣ Track Status</div>
<div class="step-box">6️⃣ Complaint Resolved</div>

</section>

<section class="announcements">

<h2>Latest Announcements</h2>

<div class="announcement-box">

<p>📢 Campus Wi-Fi maintenance on Sunday from 10:00 AM to 12:00 PM.</p>

<p>📢 Library will remain open till 8:00 PM during examinations.</p>

<p>📢 Students can now upload images while submitting complaints.</p>

</div>

</section>

<section class="statistics">

<h2>Portal Statistics</h2>

<div class="stats-container">

<div class="stat-card">
<h1><?php echo $total_students; ?></h1>
<p>Students Registered</p>
</div>

<div class="stat-card">
<h1><?php echo $total_complaints; ?></h1>
<p>Total Complaints</p>
</div>

<div class="stat-card">
<h1><?php echo $pending; ?></h1>
<p>Pending</p>
</div>

<div class="stat-card">
<h1><?php echo $resolved; ?></h1>
<p>Resolved</p>
</div>

</div>

</section>
<section class="faq">

<h2>Frequently Asked Questions</h2>

<div class="card">
<h3>How do I raise a complaint?</h3>
<p>Register, log in, and click on "Raise Complaint".</p>
</div>

<div class="card">
<h3>How can I track my complaint?</h3>
<p>Use your Ticket ID on the Track Complaint page.</p>
</div>

<div class="card">
<h3>Will I receive updates?</h3>
<p>Yes. The complaint status will be updated in your dashboard.</p>
</div>

</section>

<section class="departments">

<h2>Departments Covered</h2>

<div class="feature-container">

<div class="card">IT Department</div>

<div class="card">Library</div>

<div class="card">Maintenance</div>

<div class="card">Electrical</div>

<div class="card">Housekeeping</div>

<div class="card">Security</div>

</div>

</section>

<section class="priority">

<h2>Complaint Priority Guide</h2>

<div class="priority-container">

<div class="priority-card high">
<h3>High Priority 🔴</h3>
<p>
Electrical hazards, water leakage, fire safety issues,
security threats.
</p>
</div>

<div class="priority-card medium">
<h3>Medium Priority 🟡</h3>
<p>
Wi-Fi issues, projector not working,
laboratory equipment problems.
</p>
</div>

<div class="priority-card low">
<h3>Low Priority 🟢</h3>
<p>
Suggestions, cleanliness requests,
minor maintenance issues.
</p>
</div>

</div>

</section>

<section class="team">

<h2>Departments Handling Complaints</h2>

<div class="feature-container">

<div class="card">
<h3>IT Department</h3>
<p>Wi-Fi, computers and software.</p>
</div>

<div class="card">
<h3>Maintenance</h3>
<p>Furniture, classrooms and repairs.</p>
</div>

<div class="card">
<h3>Electrical</h3>
<p>Lights, fans and electrical safety.</p>
</div>

<div class="card">
<h3>Library</h3>
<p>Library facilities and services.</p>
</div>

</div>

</section>

<section class="cta">

<h2>Help Us Improve Our Campus</h2>

<p>
Your feedback and complaints help us create a better learning environment.
</p>

<a href="pages/student/student_register.php" class="btn">
Register Now
</a>

<a href="pages/student/raise_complaint.php" class="btn">
Raise Complaint
</a>

</section>
<footer>

<h2>Campus Grievance & Maintenance Portal</h2>

<p><strong>SIES College of Arts, Science & Commerce, Nerul</strong></p>

<p>📧 support@college.edu</p>

<p>☎ +91 XXXXX XXXXX</p>

<p style="margin-top:20px;">
Developed by <strong>Ramita Bangera</strong>
</p>

<p style="margin-top:10px;">
© 2026 All Rights Reserved
</p>

</footer>

<button onclick="topFunction()" id="topBtn">↑</button>

<script>
window.onscroll=function(){
    scrollFunction();
};

function scrollFunction(){
    if(document.body.scrollTop>100 || document.documentElement.scrollTop>100){
        document.getElementById("topBtn").style.display="block";
    }else{
        document.getElementById("topBtn").style.display="none";
    }
}

function topFunction(){
    document.body.scrollTop=0;
    document.documentElement.scrollTop=0;
}
</script>
</body>
</html>