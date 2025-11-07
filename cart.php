<?php include __DIR__.'/includes/util.php'; ?>
<!doctype html><html><head>
<meta charset="utf-8"><title>Cart</title><link rel="stylesheet" href="css/style.css">
</head><body>
<?php include __DIR__.'/includes/header.php'; ?>
<h1>Your Cart</h1>
<div id="cart"></div>
<div class="cart-footer">
  <h2 id="total"></h2>
  <a class="btn" href="checkout.php">Proceed to Checkout</a>
</div>
<script>
async function load(){
  const r = await fetch('api/cart_api.php'); const data = await r.json();
  document.getElementById('cart').innerHTML = data.items.map(it=>`
    <div class="cart-row">
      <img src="assets/images/${it.image}">
      <div><b>${it.title}</b><p>₹${it.price}</p></div>
      <input type="number" min="0" value="${it.qty}" data-id="${it.id}">
      <span>₹${it.line}</span>
      <button data-id="${it.id}" class="rm">×</button>
    </div>`).join('') || "<p>Cart is empty.</p>";
  document.getElementById('total').textContent = "Total: ₹"+data.total;
  document.querySelectorAll('input[type=number]').forEach(inp=>{
    inp.onchange = async ()=>{
      await fetch('api/cart_api.php',{method:'POST', headers:{'Content-Type':'application/json'},
        body: JSON.stringify({action:'update', id:parseInt(inp.dataset.id), qty:parseInt(inp.value)})});
      load();
    };
  });
  document.querySelectorAll('.rm').forEach(btn=>{
    btn.onclick = async ()=>{
      await fetch('api/cart_api.php',{method:'POST', headers:{'Content-Type':'application/json'},
        body: JSON.stringify({action:'remove', id:parseInt(btn.dataset.id)})});
      load();
    };
  });
}
load();
</script>
</body></html>
