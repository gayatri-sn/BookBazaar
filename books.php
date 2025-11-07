<?php include __DIR__.'/includes/util.php'; ?>
<!doctype html><html><head>
<meta charset="utf-8"><title>Books</title>
<link rel="stylesheet" href="css/style.css">
</head><body>
<?php include __DIR__.'/includes/header.php'; ?>
<section class="search-panel">
  <input id="q" placeholder="Search by title or author">
  <input id="author" placeholder="Author">
  <select id="genre">
    <option value="">All genres</option>
    <option>Fiction</option><option>Drama</option><option>Autobiography</option>
  </select>
  <select id="sort">
    <option value="">Sort</option>
    <option value="title">Title</option>
    <option value="price_asc">Price ↑</option>
    <option value="price_desc">Price ↓</option>
  </select>
  <button id="searchBtn">Search</button>
  <div id="suggestions"></div>
</section>

<section id="results" class="book-grid"></section>

<script src="js/search.js"></script>
</body></html>
