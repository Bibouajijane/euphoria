<?php
require 'config.php';

$params = [
    'client_id' => $discord_client_id,
    'redirect_uri' => $discord_redirect_uri,
    'response_type' => 'code',
    'scope' => 'identify email'
];

header('Location: https://discord.com/api/oauth2/authorize?' . http_build_query($params));
exit();
