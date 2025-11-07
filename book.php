<?php
require_once __DIR__.'/includes/util.php';
$books = read_json('books.json');
$id = (int)($_GET['id'] ?? 0);
$book = null; foreach($books as $b){ if($b['id']===$id){ $book=$b; break; } }
if(!$book){ http_response_code(404); echo "Book not found"; exit; }
?>
<!doctype html><html><head>
<meta charset="utf-8"><title><?=$book['title']?></title>
<link rel="stylesheet" href="css/style.css">
</head><body>
<?php include __DIR__.'/includes/header.php'; ?>
<section class="detail">
  <img src="assets/images/<?=$book['image']?>" alt="<?=$book['title']?>">
  <div>
    <h1><?=$book['title']?></h1>
    <p class="muted"><?=$book['author']?> · <em><?=$book['genre']?></em></p>
    <p><?=$book['description']?></p>
    <h2>₹<?=$book['price']?></h2>
    <button class="btn" id="addCart">Add to Cart</button>
    <button class="btn ghost" id="wish">♡ Wishlist</button>
  </div>
</section>

<section class="reviews">
  <h2>Reviews</h2>
  <div id="reviewList"></div>
  <?php if (current_user()): ?>
  <form id="reviewForm">
    <label>Rating:
      <select name="rating" required>
        <option value="5">★★★★★</option>
        <option value="4">★★★★☆</option>
        <option value="3">★★★☆☆</option>
        <option value="2">★★☆☆☆</option>
        <option value="1">★☆☆☆☆</option>
      </select>
    </label>
    <textarea name="comment" placeholder="Write a review..." required></textarea>
    <button class="btn" type="submit">Submit Review</button>
  </form>
  <?php else: ?>
    <p>Login to write a review.</p>
  <?php endif; ?>
</section>

<script>
const id = <?=$book['id']?>;
document.getElementById('addCart').onclick = async ()=>{
  await fetch('api/cart_api.php',{method:'POST', headers:{'Content-Type':'application/json'},
    body: JSON.stringify({action:'add', id, qty:1})});
  alert('Added to cart!');
};
document.getElementById('wish').onclick = async ()=>{
  await fetch('api/wishlist_api.php',{method:'POST', headers:{'Content-Type':'application/json'},
    body: JSON.stringify({action:'toggle', id})});
  alert('Wishlist updated');
};

async function loadReviews(){
  const r = await fetch('api/review_api.php?book_id='+id);
  const data = await r.json();
  const box = document.getElementById('reviewList');
  box.innerHTML = data.map(rv=>`
    <div class="rev"><b>${rv.user_name}</b> · ${'★'.repeat(rv.rating)}${'☆'.repeat(5-rv.rating)}
      <p>${rv.comment}</p></div>`).join('') || "<p>No reviews yet.</p>";
}
loadReviews();

const frm = document.getElementById('reviewForm');
if (frm) frm.onsubmit = async (e)=>{
  e.preventDefault();
  const fd = new FormData(frm);
  const payload = {book_id:id, rating:parseInt(fd.get('rating')), comment:fd.get('comment')};
  const res = await fetch('api/review_api.php',{method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(payload)});
  if (res.ok){ frm.reset(); loadReviews(); } else alert('Failed to submit review');
};
</script>
</body></html>
