<?php
$conn = new mysqli("localhost","root","","euphoria");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Total accounts
$result = $conn->query("SELECT COUNT(*) AS total FROM users");
$accounts = $result->fetch_assoc()['total'] ?? 0;

// Whitelisted accounts
$result = $conn->query("SELECT COUNT(*) AS total FROM whitelist_requests WHERE status='Accepted'");
$whitelist = $result->fetch_assoc()['total'] ?? 0;

// Staffs
$result = $conn->query("SELECT COUNT(*) AS total FROM staff_requests WHERE status='Accepted'");
$staffs = $result->fetch_assoc()['total'] ?? 0;

// Discord members
$discord = 1568;
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link rel="icon" type="image/png" href="logo.png">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EUPHORIA RP</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');
    *{box-sizing:border-box;margin:0;padding:0}
    body{
      font-family:'Poppins', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;
      color:#fff;
      background: url('SC10.jpg') no-repeat center center fixed;
      background-size: cover;
      line-height:1.5;
    }
    .overlay{
      background: rgba(0,0,0,0.65);
      min-height: 100vh;
      padding: 40px 20px;
    }
    .container{
      max-width:1100px;
      margin:0 auto;
      background: rgba(15,15,25,0.85);
      border-radius:15px;
      padding:30px;
      box-shadow:0 8px 25px rgba(0,0,0,0.3);
    }
    .hero{display:flex;gap:24px;align-items:center}
    .hero-left{flex:1}
    .logo{font-weight:800;font-size:28px;color:#00b7ff;margin-bottom:8px;}
    .tag{margin-top:8px;color:#ccc}
    .btn-row{margin-top:18px;display:flex;gap:12px;flex-wrap:wrap}
    .btn{
      background:#ffffff;
      padding:10px 14px;
      border-radius:8px;
      border:2px solid #0066ff;
      color:#0066ff;
      font-weight:600;
      cursor:pointer;
      transition:0.3s ease;
      text-decoration:none;
      text-align:center;
    }
    .btn:hover{background:#e6f0ff}
    .btn.primary{
      background:#0066ff;
      color:#fff;
    }
    .btn.primary:hover{background:#0052cc}
    .hero-right{
      width:340px;
      height:200px;
      border-radius:12px;
      display:flex;
      flex-direction:column;
      gap:12px;
      justify-content:center;
    }
    .connect-box{
      flex:1;
      background:linear-gradient(135deg,#5865F2 0%,#7289DA 100%);
      border-radius:12px;
      display:flex;
      align-items:center;
      justify-content:center;
      color:#fff;
      box-shadow:0 8px 25px rgba(0,102,255,0.4);
      transition:0.3s ease;
      text-decoration:none;
    }
    .connect-box:hover{transform:scale(1.03)}
    .stats{
      display:grid;
      grid-template-columns:repeat(4,1fr);
      gap:12px;
      margin-top:28px;
    }
    .stat{
      background:#ffffff;
      padding:14px;
      border-radius:10px;
      text-align:center;
      border:1px solid #cce0ff;
      box-shadow:0 4px 10px rgba(0,0,0,0.05);
      color:#1a1a1a;
      transition:0.3s;
    }
    .stat:hover{transform:translateY(-3px);}
    .stat .num{font-size:20px;font-weight:700;color:#0066ff}
    .stat .label{font-size:12px;color:#666;margin-top:6px}
    .staff{margin-top:28px}
    .staff-grid{display:flex;flex-wrap:wrap;gap:12px}
    .member{
      background:#ffffff;
      padding:14px;
      border-radius:10px;
      min-width:140px;
      flex:0 0 140px;
      text-align:center;
      border:1px solid #cce0ff;
      box-shadow:0 4px 10px rgba(0,0,0,0.05);
      color:#1a1a1a;
    }
    .avatar img{
      width:64px;
      height:64px;
      border-radius:50%;
      margin-bottom:8px;
      object-fit:cover;
    }
    .member .name{font-weight:700;color:#1a1a1a}
    .member .role{font-size:12px;color:#555;margin-top:4px}
    footer{
      margin-top:36px;
      padding:18px 0;
      border-top:1px solid #cce0ff;
      text-align:center;
      color:#ccc;
      font-size:14px;
    }
    @media (max-width:800px){
      .hero{flex-direction:column}
      .hero-right{width:100%;height:auto;gap:12px;}
      .stats{grid-template-columns:repeat(2,1fr)}
    }
  </style>
</head>
<body>
<div class="overlay">
  <div class="container">
    <header class="hero">
      <div class="hero-left">
        <div class="logo">EUPHORIA RP</div>
        <div class="tag">We are not just an MTA Server — We are <strong style="color:#00b7ff">EUPHORIA</strong>.</div>
        <div class="btn-row">
          <a class="btn primary" href="login.php">Login</a>
          <a class="btn" href="https://www.mediafire.com/file/g6w05btbfro7bag/GTA_San_Andreas_Stone_Age.rar/file" target="_blank">Download GTA</a>
          <a class="btn" href="https://multitheftauto.com/" target="_blank">Download MTA</a>
        </div>
        <div class="stats">
          <div class="stat"><div class="num"><?= $accounts ?></div><div class="label">Accounts</div></div>
          <div class="stat"><div class="num"><?= $whitelist ?></div><div class="label">Whitelisted</div></div>
          <div class="stat"><div class="num"><?= $staffs ?></div><div class="label">Staffs</div></div>
          <div class="stat"><div class="num"><?= $discord ?></div><div class="label">Discord Members</div></div>
        </div>
      </div>

      <div class="hero-right">
        <!-- Discord connect -->
        <a href="https://discord.gg/8GnPegNPvN" target="_blank" class="connect-box">
          <div>
            <div style="font-weight:700;font-size:18px;margin-bottom:6px">Connect To Discord !</div>
            <div style="font-size:13px;">Join our Community</div>
          </div>
        </a>
        <!-- MTA Server connect -->
        <a href="mtasa://123.45.67.89:22003" class="connect-box">
          <div>
            <div style="font-weight:700;font-size:18px;margin-bottom:6px">Connect To MTA Server !</div>
            <div style="font-size:13px;">Click here to join our server</div>
          </div>
        </a>
      </div>
    </header>

    <section class="staff">
      <h3 style="color:#00b7ff;margin-bottom:10px">Our Servers</h3>
      <div class="staff-grid">
        <div class="member">
          <div class="avatar"><img src="euphoria_mods.png" alt="Euphoria Mods"></div>
          <div class="name">Euphoria Mods</div>
        </div>
        <div class="member">
          <div class="avatar"><img src="euphoria_shop.png" alt="Euphoria Shop"></div>
          <div class="name">Euphoria Shop</div>
        </div>
        <div class="member">
          <div class="avatar"><img src="euphoria_anticheats.png" alt="Euphoria Anticheats"></div>
          <div class="name">Euphoria Anticheats</div>
        </div>
        <div class="member">
          <div class="avatar"><img src="euphoria_roleplay.png" alt="Euphoria Roleplay"></div>
          <div class="name">Euphoria Roleplay</div>
        </div>
      </div>
    </section>

    <footer>
      © 2025 <strong style="color:#00b7ff;">EUPHORIA RP</strong> All rights Reserved
    </footer>
  </div>
</div>
</body>
</html>