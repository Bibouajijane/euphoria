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
$stmt = $conn->prepare("SELECT * FROM faction_gang_requests WHERE user_email=? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$request_data = $result->fetch_assoc();
$stmt->close();

// Handle form
if(isset($_POST['send_request']) || isset($_POST['update_request'])){
    $name_hrp = $_POST['name_hrp'];
    $age_hrp = $_POST['age_hrp'];
    $name_rp = $_POST['name_rp'];
    $age_rp = $_POST['age_rp'];
    $type = $_POST['type'];
    $faction_gang_name = $_POST['faction_gang_name'];
    $story = $_POST['story'];

    if($request_data){
        $stmt = $conn->prepare("UPDATE faction_gang_requests SET name_hrp=?, age_hrp=?, name_rp=?, age_rp=?, type=?, faction_gang_name=?, story=?, status='Pending' WHERE user_email=?");
        $stmt->bind_param("sissssss",$name_hrp, $age_hrp, $name_rp, $age_rp, $type, $faction_gang_name, $story, $email);
        $stmt->execute();
        $stmt->close();
        $success_msg = "Your request has been updated successfully!";
    } else {
        $stmt = $conn->prepare("INSERT INTO faction_gang_requests (user_email, name_hrp, age_hrp, name_rp, age_rp, type, faction_gang_name, story, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Pending')");
        $stmt->bind_param("ssisssss",$email,$name_hrp,$age_hrp,$name_rp,$age_rp,$type,$faction_gang_name,$story);
        $stmt->execute();
        $stmt->close();
        $success_msg = "Your request has been sent successfully!";
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
<title>EUPHORIA RP - Faction Request</title>
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

/* ===== HEADER ===== */
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

.header nav {display: flex;gap: 15px;}
.header nav a {
  color: white;text-decoration: none;background: #111;
  padding: 8px 14px;border-radius: 6px;transition: 0.3s;font-size: 14px;
}
.header nav a:hover {background: #5865F2;}

/* ===== FORM CONTAINER ===== */
.container {
  background: rgba(20, 20, 20, 0.9);
  border-radius: 16px;
  padding: 30px 40px;
  width: 520px;
  margin: 60px auto;
  box-shadow: 0 0 20px rgba(0,0,0,0.8);
  animation: fadeIn 0.8s ease-in-out;
}
@keyframes fadeIn {from {opacity: 0; transform: scale(0.97);} to {opacity: 1; transform: scale(1);}}
h2 {text-align: center;color: #fff;margin-bottom: 25px;font-weight: 600;letter-spacing: 1px;}
label {display: block;font-size: 14px;margin-top: 10px;color: #ddd;}

/* ===== INPUTS & TEXTAREA ===== */
input, textarea, select {
  width: 100%;padding: 10px;margin-top: 5px;
  border: 1px solid rgba(255,255,255,0.1);
  background: #111;
  color: #fff;
  border-radius: 8px;
  outline: none;
  font-size: 15px;
  transition: 0.3s;
}
input:focus, textarea:focus, select:focus {
  border-color: #5865F2;
  box-shadow: 0 0 8px rgba(88,101,242,0.3);
}
textarea {resize: none;height: 100px;}

/* ===== SELECT DROPDOWN FIX ===== */
select option {
  background: #111;
  color: #fff;
}

/* ===== BUTTON ===== */
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
button:hover {background: #4752C4;}

/* ===== SUCCESS & STATUS ===== */
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

/* ===== LOGOUT ===== */
.logout {background: #333;margin-top: 10px;}
.logout:hover {background: #222;}
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
  <h2>Faction / Gang Request Form</h2>

  <?php if($success_msg): ?>
    <div class="success"><?= $success_msg ?></div>
  <?php endif; ?>

  <div class="status">
    Current Status: <span style="color: <?= $status_color ?>;"><?= htmlspecialchars($status) ?></span>
  </div>

  <form method="POST">
    <label>Real Name (HRP)</label>
    <input type="text" name="name_hrp" value="<?= htmlspecialchars($request_data['name_hrp'] ?? '') ?>" required>

    <label>Real Age (HRP)</label>
    <input type="number" name="age_hrp" value="<?= htmlspecialchars($request_data['age_hrp'] ?? '') ?>" required>

    <label>Character Name (RP)</label>
    <input type="text" name="name_rp" value="<?= htmlspecialchars($request_data['name_rp'] ?? '') ?>" required>

    <label>Character Age (RP)</label>
    <input type="number" name="age_rp" value="<?= htmlspecialchars($request_data['age_rp'] ?? '') ?>" required>

    <label>Type</label>
    <select name="type" id="typeSelect" required>
      <option value="">-- Select Type --</option>
      <option value="legal" <?= isset($request_data['type']) && $request_data['type']=='legal' ? 'selected' : '' ?>>Legal</option>
      <option value="illegal" <?= isset($request_data['type']) && $request_data['type']=='illegal' ? 'selected' : '' ?>>Illegal</option>
    </select>

    <label>Faction / Gang Name</label>
    <select name="faction_gang_name" id="factionSelect" required>
      <option value="">-- Select Faction --</option>
    </select>

    <label>Character Story</label>
    <textarea name="story" placeholder="Write your RP story here..." required><?= htmlspecialchars($request_data['story'] ?? '') ?></textarea>

    <?php if($request_data): ?>
      <button type="submit" name="update_request">Update Request</button>
    <?php else: ?>
      <button type="submit" name="send_request">Send Request</button>
    <?php endif; ?>
  </form>

  
</div>

<script>
const typeSelect = document.getElementById("typeSelect");
const factionSelect = document.getElementById("factionSelect");

const factions = {
  legal: ["LSPD", "LSMC", "Government", "News Agency", "Taxi Company"],
  illegal: ["Ballas", "Vagos", "Families", "Mafia", "Cartel"]
};

function loadFactions(selectedType, selectedValue = "") {
  factionSelect.innerHTML = "<option value=''>-- Select Faction --</option>";
  if (selectedType && factions[selectedType]) {
    factions[selectedType].forEach(f => {
      const opt = document.createElement("option");
      opt.value = f;
      opt.textContent = f;
      if (f === selectedValue) opt.selected = true;
      factionSelect.appendChild(opt);
    });
  }
}

typeSelect.addEventListener("change", function() {
  loadFactions(this.value);
});

window.onload = function() {
  const currentType = "<?= htmlspecialchars($request_data['type'] ?? '') ?>";
  const currentFaction = "<?= htmlspecialchars($request_data['faction_gang_name'] ?? '') ?>";
  if (currentType) {
    loadFactions(currentType, currentFaction);
  }
};
</script>
</body>
</html>