<?php
require_once __DIR__ . '/../includes/util.php';
header('Content-Type: application/json');

$q      = strtolower(trim($_GET['q'] ?? ''));
$author = strtolower(trim($_GET['author'] ?? ''));
$genre  = strtolower(trim($_GET['genre'] ?? ''));
$sort   = $_GET['sort'] ?? ''; // price_asc|price_desc|title

$books = read_json('books.json');

$filtered = array_values(array_filter($books, function($b) use($q,$author,$genre){
  $ok = true;
  if ($q) {
    $ok = $ok && (str_contains(strtolower($b['title']),$q) || str_contains(strtolower($b['author']),$q));
  }
  if ($author) $ok = $ok && str_contains(strtolower($b['author']), $author);
  if ($genre)  $ok = $ok && str_contains(strtolower($b['genre']),  $genre);
  return $ok;
}));

if ($sort==='price_asc')  usort($filtered, fn($a,$b)=>$a['price']<=>$b['price']);
if ($sort==='price_desc') usort($filtered, fn($a,$b)=>$b['price']<=>$a['price']);
if ($sort==='title')      usort($filtered, fn($a,$b)=>strcmp($a['title'],$b['title']));

echo json_encode($filtered, JSON_UNESCAPED_UNICODE);
