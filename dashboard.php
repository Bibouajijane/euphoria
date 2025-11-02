<?php
session_start();
require 'config.php';

// ✅ Check login
if(!isset($_SESSION['email'])){
    header("Location: login.php");
    exit();
}

$user_email = $_SESSION['email'];

// ✅ Fetch all requests
$tables = ['whitelist_requests', 'faction_gang_requests', 'staff_requests', 'request_project'];
$requests = [];

foreach ($tables as $table) {
    $result = $conn->query("SELECT id, '$table' AS source, status, created_at 
                            FROM $table WHERE user_email='$user_email'");
    while($row = $result->fetch_assoc()){
        $requests[] = $row;
    }
}

// Sort by date (newest first)
usort($requests, function($a, $b){
    return strtotime($b['created_at']) - strtotime($a['created_at']);
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>EUPHORIA RP - Dashboard</title>
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

/* Header Style (same as whitelist) */
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
    padding: 40px;
    width: 90%;
    max-width: 900px;
    margin: 60px auto;
    box-shadow: 0 0 20px rgba(255,255,255,0.1);
    animation: fadeIn 0.8s ease-in-out;
    text-align: center;
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

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    border-radius: 12px;
    overflow: hidden;
}
th, td {
    padding: 12px;
    text-align: center;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}
th {
    background: #5865F2;
    font-weight: 600;
}
td {
    background: rgba(255,255,255,0.05);
}
tr:hover td {
    background: rgba(255,255,255,0.1);
    transition: 0.3s;
}

.status {
    padding: 6px 12px;
    border-radius: 6px;
    font-weight: 600;
}
.status.Pending {background: gray;}
.status.Accepted {background: #2ecc71;}
.status.Rejected {background: #e74c3c;}

.logout {
    background: #ff0000ff;
    border: none;
    color: #fff;
    padding: 10px;
    border-radius: 8px;
    cursor: pointer;
    margin-top: 20px;
    width: 120px;
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
    
        <a href="whitelist.php">REQUEST WHITELIST</a>
        
        <a href="request_faction.php">RESQUEST FACTION</a>
        <a href="request_staff.php">REQUEST STAFF</a>
        <a href="request_project.php">REQUEST PROJECT</a>
    </nav>
</header>

<div class="container">
    <h2>Welcome <?= htmlspecialchars($user_email) ?> 👋</h2>
    <p style="color:#bbb;margin-bottom:30px;">Here are your submitted requests and their current status:</p>

    <table>
        <tr>
            <th>ID</th>
            <th>Request Type</th>
            <th>Date</th>
            <th>Status</th>
        </tr>

        <?php if(empty($requests)): ?>
        <tr>
            <td colspan="4" style="color:#bbb;">No requests submitted yet.</td>
        </tr>
        <?php else: ?>
        <?php foreach($requests as $req): ?>
        <tr>
            <td><?= $req['id'] ?></td>
            <td>
                <?php
                switch($req['source']){
                    case 'whitelist_requests': echo 'Whitelist'; break;
                    case 'faction_gang_requests': echo 'Faction'; break;
                    case 'staff_requests': echo 'Staff'; break;
                    case 'request_project': echo 'Project'; break;
                }
                ?>
            </td>
            <td><?= htmlspecialchars($req['created_at']) ?></td>
            <td><span class="status <?= $req['status'] ?>"><?= $req['status'] ?></span></td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
    </table>

    <form action="logout.php" method="post">
        <button type="submit" class="logout">Logout</button>
    </form>
</div>

</body>
</html>