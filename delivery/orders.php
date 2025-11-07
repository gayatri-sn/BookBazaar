<?php
require_once __DIR__.'/../includes/util.php'; require_role(['delivery']);
$orders = read_json('orders.json');
?>
<!doctype html><html><head>
<meta charset="utf-8"><title>Delivery · Orders</title><link rel="stylesheet" href="../css/style.css">
</head><body>
<?php include __DIR__.'/../includes/header.php'; ?>
<h1>Delivery Orders</h1>
<table class="table">
<tr><th>ID</th><th>Total</th><th>Status</th><th>Update</th></tr>
<?php foreach($orders as $o): ?>
<tr>
  <td><?=$o['id']?></td><td>₹<?=$o['total']?></td><td><?=$o['status']?></td>
  <td>
    <form onsubmit="return false;">
      <select data-id="<?=$o['id']?>">
        <option <?=$o['status']==='Pending'?'selected':''?>>Pending</option>
        <option <?=$o['status']==='Out for Delivery'?'selected':''?>>Out for Delivery</option>
        <option <?=$o['status']==='Delivered'?'selected':''?>>Delivered</option>
      </select>
      <button class="btn" onclick="upd(this)">Save</button>
    </form>
  </td>
</tr>
<?php endforeach; ?>
</table>
<script>
async function upd(btn){
  const sel = btn.parentElement.querySelector('select');
  await fetch('../api/order_api.php',{method:'POST', headers:{'Content-Type':'application/json'},
    body: JSON.stringify({action:'status', order_id:parseInt(sel.dataset.id), status: sel.value})});
  alert('Status updated');
  location.reload();
}
</script>
</body></html>
