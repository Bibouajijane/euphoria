<?php
session_start();
if(!isset($_SESSION['email'])){
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost","root","","euphoria");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_SESSION['email'];
$success_msg = "";

// Fetch existing staff request
$stmt = $conn->prepare("SELECT * FROM staff_requests WHERE user_email=? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$request_data = $result->fetch_assoc();
$stmt->close();

// Handle form submission
if(isset($_POST['send_request']) || isset($_POST['update_request'])){
    $name_irl = $_POST['name_irl'];
    $age_irl = $_POST['age_irl'];
    $experience = $_POST['experience'];
    $mic_status = $_POST['mic_status'];
    $active_hours = $_POST['active_hours'];
    $reason = $_POST['reason'];
    $user_type = $_POST['user_type'];

    if($request_data){ // update
        $stmt = $conn->prepare("UPDATE staff_requests SET name_irl=?, age_irl=?, experience=?, mic_status=?, active_hours=?, reason=?, user_type=?, status='Pending' WHERE user_email=?");
        $stmt->bind_param("sissssss", $name_irl, $age_irl, $experience, $mic_status, $active_hours, $reason, $user_type, $email);
        $stmt->execute();
        $stmt->close();
        $success_msg = "✅ Staff request updated successfully!";
    } else { // insert
        $stmt = $conn->prepare("INSERT INTO staff_requests (user_email, name_irl, age_irl, experience, mic_status, active_hours, reason, user_type, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Pending')");
        $stmt->bind_param("sissssss", $email, $name_irl, $age_irl, $experience, $mic_status, $active_hours, $reason, $user_type);
        $stmt->execute();
        $stmt->close();
        $success_msg = "✅ Staff request sent successfully!";
    }
}

$status = $request_data['status'] ?? 'Pending';
$status_color = '#aaa';
if($status == 'Accepted') $status_color = '#2ecc71';
elseif($status == 'Rejected') $status_color = '#e74c3c';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>EUPHORIA RP - Staff Request</title>
<link rel="icon" type="image/png" href="logo.png">
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap');

body {
    font-family: 'Inter', sans-serif;
    background: url('sc10.jpg') no-repeat center center fixed;
    background-size: cover;
    margin: 0;
    color: #fff;
    backdrop-filter: blur(8px);
}

/* Header */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: rgba(10, 10, 10, 0.85);
    padding: 10px 40px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.6);
}
.header img {
    height: 50px;
    border-radius: 50%;
}
.header nav {
    display: flex;
    gap: 12px;
}
.header nav a {
    color: white;
    text-decoration: none;
    background: #0e0e0e;
    padding: 8px 14px;
    border-radius: 6px;
    transition: 0.3s;
    font-size: 14px;
}
.header nav a:hover {
    background: #5865F2;
}

/* Container */
.container {
    background: rgba(0, 0, 0, 0.85);
    border-radius: 18px;
    padding: 30px 40px;
    width: 520px;
    margin: 60px auto;
    box-shadow: 0 0 25px rgba(255,255,255,0.1);
    animation: fadeIn 0.7s ease-in-out;
}
@keyframes fadeIn {
    from {opacity: 0; transform: translateY(10px);}
    to {opacity: 1; transform: translateY(0);}
}

h2 {
    text-align: center;
    color: #fff;
    margin-bottom: 25px;
    font-weight: 600;
    letter-spacing: 1px;
}

/* Labels and Inputs */
label {
    display: block;
    font-size: 14px;
    margin-top: 12px;
    color: #bbb;
}
input, textarea {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border: none;
    border-bottom: 2px solid #444;
    background: transparent;
    color: #fff;
    outline: none;
    font-size: 15px;
    transition: 0.3s;
}
input:focus, textarea:focus {
    border-color: #5865F2;
}
textarea {
    resize: none;
    height: 100px;
}

/* Buttons */
button {
    background: #5865F2;
    color: white;
    border: none;
    padding: 10px;
    width: 100%;
    border-radius: 8px;
    cursor: pointer;
    font-size: 16px;
    margin-top: 15px;
    transition: 0.3s;
}
button:hover {
    background: #4752C4;
}
.logout {
    background: #333;
    margin-top: 10px;
}
.logout:hover {
    background: #222;
}

/* Status & Alerts */
.success {
    background: rgba(46, 204, 113, 0.15);
    border-left: 4px solid #2ecc71;
    color: #2ecc71;
    padding: 10px;
    border-radius: 8px;
    margin-bottom: 15px;
}
.status {
    background: rgba(255,255,255,0.1);
    padding: 8px;
    border-radius: 8px;
    margin-top: 10px;
    text-align: center;
    font-weight: 500;
}
</style>
</head>
<body>

<header class="header">
    <img src="logo.png" alt="EUPHORIA Logo">
    <nav>
        <a href="dashboard.php">DASHBOARD</a>
       
    </nav>
</header>

<div class="container">
    <h2>Staff Request Form</h2>

    <?php if($success_msg): ?>
        <div class="success"><?= $success_msg ?></div>
    <?php endif; ?>

    <div class="status">
        Current Status: <span style="color: <?= $status_color ?>;"><?= htmlspecialchars($status) ?></span>
    </div>

    <form method="POST">
        <label>Real Name</label>
        <input type="text" name="name_irl" value="<?= htmlspecialchars($request_data['name_irl'] ?? '') ?>" required>

        <label>Real Age</label>
        <input type="number" name="age_irl" value="<?= htmlspecialchars($request_data['age_irl'] ?? '') ?>" required>

        <label>Experience</label>
        <input name="experience" placeholder="Describe your experience"><?= htmlspecialchars($request_data['experience'] ?? '') ?></input>

        <label>Microphone Status</label>
        <input type="text" name="mic_status" placeholder="Do you have a working mic?" value="<?= htmlspecialchars($request_data['mic_status'] ?? '') ?>">

        <label>Active Hours</label>
        <input type="text" name="active_hours" placeholder="Example: 4-6 hours per day" value="<?= htmlspecialchars($request_data['active_hours'] ?? '') ?>">

        <label>Reason for Joining</label>
        <input name="reason" placeholder="Why do you want to join the staff?"><?= htmlspecialchars($request_data['reason'] ?? '') ?></input>

        <label>Username</label>
        <input type="text" name="user_type" placeholder="Your Username in Server MTA" value="<?= htmlspecialchars($request_data['user_type'] ?? '') ?>">

        <?php if($request_data): ?>
            <button type="submit" name="update_request">Update Request</button>
        <?php else: ?>
            <button type="submit" name="send_request">Send Request</button>
        <?php endif; ?>
    </form>

    
</div>

</body>
</html>