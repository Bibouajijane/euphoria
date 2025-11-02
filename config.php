<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "euphoria";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Discord OAuth Config
$discord_client_id = "987842074764255302";
$discord_client_secret = "PwGjqdwWOluRa7Qw6kDsNaiKZ5ws3xy_";
$discord_redirect_uri = "http://localhost/seven/discord_callback.php";
?>
