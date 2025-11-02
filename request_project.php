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

// Fetch existing request
$stmt = $conn->prepare("SELECT * FROM request_project WHERE user_email=? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$request_data = $result->fetch_assoc();
$stmt->close();

// Handle form
if(isset($_POST['send_request']) || isset($_POST['update_request'])){
    $project_name = $_POST['project_name'];
    $description = $_POST['description'];
    $members = $_POST['members'];

    if($request_data){
        $stmt = $conn->prepare("UPDATE request_project SET project_name=?, description=?, members=?, status='Pending' WHERE user_email=?");
        $stmt->bind_param("ssss", $project_name, $description, $members, $email);
        $stmt->execute();
        $stmt->close();
        $success_msg = "Project request updated successfully!";
    } else {
        $stmt = $conn->prepare("INSERT INTO request_project (user_email, project_name, description, members, status) VALUES (?, ?, ?, ?, 'Pending')");
        $stmt->bind_param("ssss", $email, $project_name, $description, $members);
        $stmt->execute();
        $stmt->close();
        $success_msg = "Project request sent successfully!";
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
<title>EUPHORIA RP - Project Request</title>
<link rel="icon" type="image/png" href="logo.png">
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap');

body {
    font-family: 'Inter', sans-serif;
    background: url('sc10.jpg') no-repeat center center fixed;
    background-size: cover;
    margin: 0;
    color: #fff;
    backdrop-filter: blur(6px);
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: rgba(0, 0, 0, 0.85);
    padding: 10px 40px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.6);
}

.header img {
    height: 50px;
    border-radius: 50%;
}

.header nav {
    display: flex;
    gap: 15px;
}

.header nav a {
    color: white;
    text-decoration: none;
    background: #111;
    padding: 8px 14px;
    border-radius: 6px;
    transition: 0.3s;
    font-size: 14px;
}
.header nav a:hover {
    background: #5865F2;
}

.container {
    background: rgba(0, 0, 0, 0.8);
    border-radius: 16px;
    padding: 30px 40px;
    width: 520px;
    margin: 60px auto;
    box-shadow: 0 0 20px rgba(255,255,255,0.1);
    animation: fadeIn 0.8s ease-in-out;
}
@keyframes fadeIn {
    from {opacity: 0; transform: scale(0.97);}
    to {opacity: 1; transform: scale(1);}
}
h2 {
    text-align: center;
    color: #fff;
    margin-bottom: 25px;
    font-weight: 600;
    letter-spacing: 1px;
}

label {
    display: block;
    font-size: 14px;
    margin-top: 10px;
    color: #ddd;
}
input, textarea {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border: none;
    border-bottom: 2px solid #555;
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
.success {
    background: rgba(46, 204, 113, 0.2);
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
}
.logout {
    background: #333;
    margin-top: 10px;
}
.logout:hover {
    background: #222;
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
    <h2>Project Request Form</h2>

    <?php if($success_msg): ?>
        <div class="success"><?= $success_msg ?></div>
    <?php endif; ?>

    <div class="status">
        Current Status: <span style="color: <?= $status_color ?>;"><?= htmlspecialchars($status) ?></span>
    </div>

    <form method="POST">
        <label>Project Name</label>
        <input type="text" name="project_name" value="<?= htmlspecialchars($request_data['project_name'] ?? '') ?>" required>

        <label>Project Description</label>
        <textarea name="description" placeholder="Describe your project..." required><?= htmlspecialchars($request_data['description'] ?? '') ?></textarea>

        <label>Project Members</label>
        <input type="text" name="members" value="<?= htmlspecialchars($request_data['members'] ?? '') ?>" placeholder="List of members (comma-separated)" required>

        <?php if($request_data): ?>
            <button type="submit" name="update_request">Update Request</button>
        <?php else: ?>
            <button type="submit" name="send_request">Send Request</button>
        <?php endif; ?>
    </form>

    
</div>

</body>
</html>