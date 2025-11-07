<?php
require_once __DIR__ . '/includes/util.php';

$user = current_user();

if (!$user) {
    // Not logged in → go to login page
    header('Location: login.php');
    exit;
}

// Logged in → redirect based on role
switch ($user['role']) {
    case 'admin':
        header('Location: admin/index.php');
        break;
    case 'delivery':
        header('Location: delivery/orders.php');
        break;
    default:
        header('Location: index.html');
}
exit;
