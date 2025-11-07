const el = (s)=>document.querySelector(s);
const results = el('#results');
const q = el('#q'), author = el('#author'), genre = el('#genre'), sort = el('#sort');
const suggestBox = el('#suggestions');

function render(list){
  results.innerHTML = list.map(b => `
    <div class="card">
      <img src="assets/images/${b.image}" alt="${b.title}">
      <h3>${b.title}</h3>
      <p>${b.author}</p>
      <p class="price">₹${b.price}</p>
      <a class="btn" href="book.php?id=${b.id}">View</a>
      <button class="btn add-cart" data-id="${b.id}">Add to Cart</button>
      <button class="btn ghost add-wish" data-id="${b.id}">♡ Wishlist</button>
    </div>`).join('');
  bindActions();
}

async function search(){
  const params = new URLSearchParams({
    q: q.value, author: author.value, genre: genre.value, sort: sort.value
  });
  const res = await fetch(`api/search.php?${params.toString()}`);
  const data = await res.json();
  render(data);
}
el('#searchBtn').addEventListener('click', search);
[q,author,genre,sort].forEach(i=>i.addEventListener('change', search));
q.addEventListener('input', async ()=>{
  if (!q.value.trim()){suggestBox.innerHTML=''; return;}
  const r = await fetch('api/suggestions.php?q='+encodeURIComponent(q.value));
  const s = await r.json();
  suggestBox.innerHTML = `
    <div class="sugg-row">${s.titles.map(t=>`<span class="pill title">${t}</span>`).join('')}</div>
    <div class="sugg-row">${s.authors.map(a=>`<span class="pill author">${a}</span>`).join('')}</div>`;
  suggestBox.querySelectorAll('.pill.title').forEach(p=>p.onclick=()=>{q.value=p.textContent; search();});
  suggestBox.querySelectorAll('.pill.author').forEach(p=>p.onclick=()=>{author.value=p.textContent; search();});
});
search(); // initial load

function bindActions(){
  document.querySelectorAll('.add-cart').forEach(btn=>{
    btn.onclick = async ()=>{
      await fetch('api/cart_api.php', {method:'POST', headers:{'Content-Type':'application/json'},
        body: JSON.stringify({action:'add', id: parseInt(btn.dataset.id), qty:1})});
      alert('Added to cart!');
    };
  });
  document.querySelectorAll('.add-wish').forEach(btn=>{
    btn.onclick = async ()=>{
      await fetch('api/wishlist_api.php', {method:'POST', headers:{'Content-Type':'application/json'},
        body: JSON.stringify({action:'toggle', id: parseInt(btn.dataset.id)})});
      alert('Wishlist updated');
    };
  });
}
