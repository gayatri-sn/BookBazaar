<?php require_once __DIR__.'/../includes/util.php'; require_role(['admin']); $orders=read_json('orders.json'); $books=read_json('books.json'); $byId=[]; foreach($books as $b){$byId[$b['id']]=$b;} ?>
<!doctype html><html><head>
<meta charset="utf-8"><title>Admin · Orders</title><link rel="stylesheet" href="../css/style.css">
</head><body>
<?php include __DIR__.'/../includes/header.php'; ?>
<h1>All Orders</h1>
<table class="table">
<tr><th>ID</th><th>User ID</th><th>Total</th><th>Status</th><th>When</th><th>Items</th></tr>
<?php foreach($orders as $o): ?>
<tr>
  <td><?=$o['id']?></td><td><?=$o['user_id']?></td><td>₹<?=$o['total']?></td>
  <td><?=$o['status']?></td><td><?=$o['created_at']?></td>
  <td><?php foreach($o['items'] as $it){ $bk=$byId[$it['id']]??null; echo $bk? $bk['title'].' x'.$it['qty'].'<br>':''; } ?></td>
</tr>
<?php endforeach; ?>
</table>
</body></html>
