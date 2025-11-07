<?php require_once __DIR__.'/includes/util.php'; $u=current_user(); ?>
<!doctype html><html><head>
<meta charset="utf-8"><title>Checkout</title><link rel="stylesheet" href="css/style.css">
</head><body>
<?php include __DIR__.'/includes/header.php'; ?>
<h1>Checkout</h1>
<?php if(!$u): ?>
  <p>Please <a href="login.php">login</a> to place order.</p>
<?php else: ?>
  <form id="co">
    <input name="address" placeholder="Delivery address" required>
    <button class="btn" type="submit">Place Order</button>
  </form>
  <div id="msg"></div>
  <script>
    document.getElementById('co').onsubmit = async (e)=>{
      e.preventDefault();
      const fd = new FormData(e.target);
      const r = await fetch('api/order_api.php',{method:'POST', headers:{'Content-Type':'application/json'},
         body: JSON.stringify({action:'create', address: fd.get('address')})});
      const data = await r.json();
      if (data.ok){ document.getElementById('msg').textContent = "Order placed! ID: "+data.order_id; }
      else alert('Failed');
    };
  </script>
<?php endif; ?>
</body></html>
