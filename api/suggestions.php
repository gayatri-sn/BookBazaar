<?php
require_once __DIR__ . '/../includes/util.php';
header('Content-Type: application/json');

$q = strtolower(trim($_GET['q'] ?? ''));
$books = read_json('books.json');

$titles = [];
$authors= [];
foreach ($books as $b){
  if ($q && str_contains(strtolower($b['title']),$q))  $titles[$b['title']] = true;
  if ($q && str_contains(strtolower($b['author']),$q)) $authors[$b['author']] = true;
}
echo json_encode([
  'titles'  => array_slice(array_keys($titles),0,8),
  'authors' => array_slice(array_keys($authors),0,8)
], JSON_UNESCAPED_UNICODE);
