<?php
require_once __DIR__ . '/includes/util.php';
session_destroy();
header('Location: login.php');
exit;
