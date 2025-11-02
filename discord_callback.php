<?php
session_start();
require 'config.php';

if (isset($_GET['code'])) {
    $code = $_GET['code'];

    // تبادل الكود مع التوكن
    $data = [
        'client_id' => $discord_client_id,
        'client_secret' => $discord_client_secret,
        'grant_type' => 'authorization_code',
        'code' => $code,
        'redirect_uri' => $discord_redirect_uri,
        'scope' => 'identify email'
    ];

    $ch = curl_init('https://discord.com/api/oauth2/token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    $response = json_decode(curl_exec($ch), true);
    curl_close($ch);

    if (isset($response['access_token'])) {
        $access_token = $response['access_token'];

        // جلب بيانات المستخدم
        $ch = curl_init('https://discord.com/api/users/@me');
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $access_token]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $user = json_decode(curl_exec($ch), true);
        curl_close($ch);

        $email = $user['email'];
        $discord_id = $user['id'];
        $username = $user['username'] . '#' . $user['discriminator'];

        // تحقق واش المستخدم موجود
        $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            // إنشاء مستخدم جديد
            $stmt = $conn->prepare("INSERT INTO users (email, discord_id, username) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $email, $discord_id, $username);
            $stmt->execute();
        }

        $_SESSION['email'] = $email;
        $_SESSION['discord_user'] = $username;

        header("Location: dashboard.php");
        exit();
    } else {
        echo "❌ فشل تسجيل الدخول عبر Discord!";
    }
} else {
    echo "❌ لم يتم تلقي الكود!";
}
?>
