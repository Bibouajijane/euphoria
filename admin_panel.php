<?php
session_start();
require 'config.php';

// ✅ Admin check
if(!isset($_SESSION['email']) || $_SESSION['role'] != 'admin'){
    header("Location: whitelist.php");
    exit();
}

// Get type from URL
$type = $_GET['type'] ?? 'whitelist';

// Table selection
if($type == 'faction'){
    $table_name = 'faction_gang_requests';
} elseif($type == 'project'){
    $table_name = 'request_project'; // Table ديالك
} else {
    $table_name = "{$type}_requests"; // whitelist_requests أو staff_requests
}

// Handle Accept / Reject
if(isset($_POST['accept']) || isset($_POST['reject'])){
    $id = intval($_POST['id']);
    $new_status = isset($_POST['accept']) ? 'Accepted' : 'Rejected';
    $conn->query("UPDATE {$table_name} SET status='$new_status' WHERE id=$id");
    header("Location: admin_panel.php?type=$type");
    exit();
}

// Fetch data
$result = $conn->query("SELECT * FROM {$table_name} ORDER BY id DESC");
if(!$result){
    echo "<div style='color:red; text-align:center;'>Table '{$table_name}' does not exist!</div>";
    $result = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Panel - EUPHORIA RP</title>
<link rel="icon" type="image/png" href="logo.png">
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap');

body {
    font-family: 'Inter', sans-serif;
    background: url('sc10.jpg') no-repeat center center fixed;
    background-size: cover;
    margin: 0;
    color: #fff;
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
    width: 95%;
    max-width: 1200px;
    margin: 40px auto;
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

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}
th, td {
    padding: 12px;
    text-align: center;
    border-bottom: 1px solid rgba(255,255,255,0.2);
}
th {
    background: #2563eb;
}
td {
    background: rgba(255,255,255,0.05);
}
.status {
    padding: 6px 12px;
    border-radius: 6px;
    font-weight: 600;
}
.status.Pending {background: gray;}
.status.Accepted {background: #2ecc71;}
.status.Rejected {background: #e74c3c;}

button {
    padding: 6px 12px;
    border: none;
    border-radius: 6px;
    margin: 2px;
    cursor: pointer;
    font-weight: 600;
    transition: 0.3s;
}
button.accept {background:#2ecc71;color:#fff;}
button.reject {background:#e74c3c;color:#fff;}
button:hover {opacity:0.85;}

.details {
    display: none;
    text-align: left;
    background: rgba(255,255,255,0.05);
    padding: 10px;
    border-radius: 8px;
    font-size: 14px;
}

.toggle-button {
    cursor:pointer;
    font-weight:600;
    color:#00b7ff;
}

.logout {
    background: #333;
    padding: 8px 14px;
    border-radius: 6px;
    color: #fff;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s;
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
        <a href="?type=whitelist">Whitelist</a>
        <a href="?type=project">Project</a>
        <a href="?type=faction">Faction</a>
        <a href="?type=staff">Staff</a>
    </nav>
    <form action="logout.php" method="post">
        <button type="submit" class="logout">Logout</button>
    </form>
</header>

<div class="container">
    <h2>Admin Control Panel</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Email</th>
            <th>Form Data</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['user_email']) ?></td>
            <td><span class="toggle-button">▶ View</span></td>
            <td><span class="status <?= $row['status'] ?>"><?= $row['status'] ?></span></td>
            <td>
                <form method="post">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button type="submit" name="accept" class="accept">Accept</button>
                    <button type="submit" name="reject" class="reject">Reject</button>
                </form>
            </td>
        </tr>
        <tr class="details">
            <td colspan="5">
                <?php
                foreach($row as $key=>$value){
                    if(!in_array($key,['id','status','user_email'])){
                        echo "<b>".ucfirst(str_replace('_',' ',$key)).":</b> ".htmlspecialchars($value)."<br>";
                    }
                }
                ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<script>
// Toggle details rows
document.querySelectorAll('.toggle-button').forEach(btn=>{
    btn.addEventListener('click', ()=>{
        let detailRow = btn.closest('tr').nextElementSibling;
        if(detailRow.style.display === 'table-row'){
            detailRow.style.display = 'none';
            btn.textContent = "▶ View";
        } else {
            detailRow.style.display = 'table-row';
            btn.textContent = "▼ Hide";
        }
    });
});
</script>

</body>
</html>