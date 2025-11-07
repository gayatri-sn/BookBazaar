<?php
require_once __DIR__ . '/../includes/util.php';
header('Content-Type: application/json');
if (!isset($_SESSION['wishlist'])) $_SESSION['wishlist'] = []; // array of ids

if ($_SERVER['REQUEST_METHOD']==='POST') {
  $in = json_decode(file_get_contents('php://input'), true);
  $id = (int)($in['id'] ?? 0);
  if (($in['action'] ?? '')==='toggle') {
    if (in_array($id, $_SESSION['wishlist'])) {
      $_SESSION['wishlist'] = array_values(array_filter($_SESSION['wishlist'], fn($x)=>$x!==$id));
    } else {
      $_SESSION['wishlist'][] = $id;
    }
  }
}
echo json_encode(['wishlist'=>$_SESSION['wishlist']]);
