<?php
require_once __DIR__ . '/../includes/util.php';
header('Content-Type: application/json');
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = []; // id => qty

$books = read_json('books.json');
$byId = []; foreach($books as $b){ $byId[$b['id']]=$b; }

if ($_SERVER['REQUEST_METHOD']==='POST') {
  $in = json_decode(file_get_contents('php://input'), true);
  $action = $in['action'] ?? '';
  $id = (int)($in['id'] ?? 0);
  $qty = (int)($in['qty'] ?? 1);

  if ($action==='add' && isset($byId[$id])) {
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + max(1,$qty);
  } elseif ($action==='update' && isset($byId[$id])) {
    if ($qty<=0) unset($_SESSION['cart'][$id]); else $_SESSION['cart'][$id]= $qty;
  } elseif ($action==='remove') {
    unset($_SESSION['cart'][$id]);
  } elseif ($action==='clear') {
    $_SESSION['cart'] = [];
  }
}

$out = [];
$total=0;
foreach ($_SESSION['cart'] as $id=>$q) {
  $b = $byId[$id]; $line = $b['price']*$q; $total += $line;
  $out[] = ['id'=>$id,'title'=>$b['title'],'price'=>$b['price'],'qty'=>$q,'line'=>$line,'image'=>$b['image']];
}
echo json_encode(['items'=>$out,'total'=>$total]);
