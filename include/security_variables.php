<?php

$token = sha1(uniqid(microtime(), true));
$_SESSION['token-value'] = $token;

$naid = sha1(uniqid(microtime(), true));
$_SESSION['form-naid'] = $naid;

$id = md5(uniqid(microtime(), true));
$_SESSION['form-id'] = $id;

$network = ip();
$_SESSION['user-ip'] = $network['ip'];

$user_agent = _isset($_SERVER, "HTTP_USER_AGENT");
$_SESSION['user-agent'] = $user_agent;

$_SESSION['user-referer'] = _isset($_SERVER, 'HTTP_REFERER');
?>
