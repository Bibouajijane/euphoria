<?php
session_start();
require 'config.php';

$message = '';

// ✅ Signup
if(isset($_POST['signup'])){
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'user'; // every new account is a user

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $message = "⚠ Account already exists!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (email,password,role) VALUES (?,?,?)");
        $stmt->bind_param("sss",$email,$password,$role);
        $stmt->execute();
        $message = "✅ Account created successfully! You can now log in.";
    }
}

// ✅ Signin
if(isset($_POST['signin'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $user = $result->fetch_assoc();
        if(password_verify($password,$user['password'])){
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            // ✅ Redirect based on role
            if($user['role'] === 'admin'){
                header("Location: admin_panel.php");
            } else {
                header("Location: dashboard.php");
            }
            exit();
        } else {
            $message = "❌ Incorrect password!";
        }
    } else {
        $message = "⚠ Account not found!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>EUPHORIA RP - Login</title>
<link rel="icon" type="image/png" href="logo.png">
<style>
/* ----- Base ----- */
body {
    font-family: 'Poppins', sans-serif;
    background: url('SC10.jpg') no-repeat center center fixed;
    background-size: cover;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
    color: #fff;
}

/* ----- Container ----- */
.container {
    width: 320px;
    padding: 40px;
    background: rgba(15,15,25,0.9);
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.4);
    text-align: center;
}

/* ----- Logo ----- */
.logo {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    margin-bottom: 20px;
    object-fit: cover;
    box-shadow: 0 0 15px rgba(0,183,255,0.5);
}

/* ----- Headings ----- */
h2 {
    font-size: 26px;
    margin-bottom: 25px;
    color: #fff;
}

/* ----- Inputs ----- */
input[type=email], input[type=password] {
    width: 100%;
    padding: 12px;
    margin: 8px 0;
    border-radius: 10px;
    border: 1px solid #c7d2fe;
    background: #f9fbff;
    font-size: 15px;
    transition: 0.3s;
}
input:focus {
    border-color: #3b82f6;
    outline: none;
    background: #fff;
}

/* ----- Buttons ----- */
button {
    width: 100%;
    padding: 12px;
    margin-top: 12px;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 15px;
    cursor: pointer;
    transition: 0.3s;
}
button.signin { background: #2563eb; color: #fff; }
button.signup { background: #1e40af; color: #fff; }
button.discord { background: #5865F2; color: #fff; margin-top: 10px; }
button:hover { opacity: 0.9; }

/* ----- Messages & Links ----- */
.message { margin: 12px 0; font-weight: 500; color: #93c5fd; }
.switch { margin-top: 14px; font-size: 14px; }
.switch a { color: #2563eb; text-decoration: none; font-weight: 600; }
.switch a:hover { text-decoration: underline; }
.divider { margin: 15px 0; font-weight: 600; color: #fff; }
</style>
</head>
<body>
<div class="container">
    <img src="logo.png" alt="Logo" class="logo">
    <h2>EUPHORIA RP</h2>
    <?php if($message != '') echo "<div class='message'>$message</div>"; ?>

    <!-- Sign In Form -->
    <form method="post" id="signinForm">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="signin" class="signin">Sign In</button>

        <div class="divider">OR</div>

        <a href="discord_login.php" style="text-decoration:none;">
            <button type="button" class="discord">Sign in with Discord</button>
        </a>

        <div class="switch">Don't have an account? <a href="#" onclick="showSignUp()">Sign up</a></div>
    </form>

    <!-- Sign Up Form -->
    <form method="post" id="signupForm" style="display:none;">
        <a href="discord_login.php" style="text-decoration:none;">
            <button type="button" class="discord">Register with Discord</button>
        </a>
        <div class="switch">Already have an account? <a href="#" onclick="showSignIn()">Sign in</a></div>
    </form>
</div>

<script>
function showSignUp(){
    document.getElementById('signinForm').style.display='none';
    document.getElementById('signupForm').style.display='block';
}
function showSignIn(){
    document.getElementById('signinForm').style.display='block';
    document.getElementById('signupForm').style.display='none';
}
</script>
</body>
</html>