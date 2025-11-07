<?php
require_once __DIR__ . '/../includes/util.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD']==='POST') {
  $user = current_user(); if (!$user){ http_response_code(401); echo json_encode(['error'=>'login']); exit; }
  $in = json_decode(file_get_contents('php://input'), true);
  if (($in['action'] ?? '')==='create') {
    $cart = $_SESSION['cart'] ?? [];
    if (!$cart) { echo json_encode(['error'=>'empty']); exit; }

    $books = read_json('books.json'); $byId=[]; foreach($books as $b){$byId[$b['id']]=$b;}
    $items=[]; $total=0;
    foreach($cart as $id=>$q){ if(isset($byId[$id])){ $items[]=['id'=>$id,'qty'=>$q,'price'=>$byId[$id]['price']]; $total += $byId[$id]['price']*$q; } }
    $orders = read_json('orders.json');
    $order = [
      'id' => next_id($orders),
      'user_id'=>$user['id'],
      'items'=>$items,
      'total'=>$total,
      'address'=> trim($in['address'] ?? ''),
      'status'=>'Pending',
      'created_at'=> date('c')
    ];
    $orders[] = $order;
    write_json('orders.json',$orders);
    $_SESSION['cart'] = [];
    echo json_encode(['ok'=>true,'order_id'=>$order['id']]);
    exit;
  }

  // Admin/Delivery status updates:
  if (($in['action'] ?? '')==='status' && isset($in['order_id'],$in['status'])) {
    require_role(['admin','delivery']);
    $orders = read_json('orders.json');
    foreach ($orders as &$o){ if ($o['id']==(int)$in['order_id']) { $o['status'] = $in['status']; break; } }
    write_json('orders.json',$orders);
    echo json_encode(['ok'=>true]); exit;
  }
}

if ($_SERVER['REQUEST_METHOD']==='GET') {
  $u = current_user();
  $orders = read_json('orders.json');
  if ($u && $u['role']==='admin') { echo json_encode($orders); exit; }
  if ($u && $u['role']==='delivery') { echo json_encode($orders); exit; }
  if ($u) { echo json_encode(array_values(array_filter($orders, fn($o)=>$o['user_id']===$u['id']))); exit; }
  http_response_code(401); echo json_encode(['error'=>'login']); exit;
}
