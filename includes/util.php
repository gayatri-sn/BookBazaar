<?php
if (session_status() === PHP_SESSION_NONE) session_start();

function data_path($file) {
  return __DIR__ . '/../data/' . $file;
}

function read_json($file) {
  $path = data_path($file);
  if (!file_exists($path)) return [];
  $raw = file_get_contents($path);
  return $raw ? json_decode($raw, true) : [];
}

function write_json($file, $data) {
  $path = data_path($file);
  return file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
}

function next_id($items) {
  $max = 0; foreach ($items as $i) { if (($i['id'] ?? 0) > $max) $max = $i['id']; }
  return $max + 1;
}

function current_user() {
  return $_SESSION['user'] ?? null;
}

function require_role($roles = []) {
  $u = current_user();
  if (!$u || (!empty($roles) && !in_array($u['role'], $roles))) {
    http_response_code(403);
    echo "Forbidden";
    exit;
  }
}
