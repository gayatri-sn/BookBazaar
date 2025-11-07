<?php include __DIR__.'/includes/util.php'; $books=read_json('books.json'); $byId=[]; foreach($books as $b){$byId[$b['id']]=$b;} $wish=$_SESSION['wishlist']??[]; ?>
<!doctype html><html><head>
<meta charset="utf-8"><title>Wishlist</title><link rel="stylesheet" href="css/style.css">
</head><body>
<?php include __DIR__.'/includes/header.php'; ?>
<h1>Your Wishlist</h1>
<section class="book-grid">
<?php foreach($wish as $id): $b=$byId[$id]??null; if(!$b) continue; ?>
  <div class="card">
    <img src="assets/images/<?=$b['image']?>" alt="<?=$b['title']?>">
    <h3><?=$b['title']?></h3>
    <p><?=$b['author']?></p>
    <a class="btn" href="book.php?id=<?=$b['id']?>">View</a>
  </div>
<?php endforeach; if(!$wish) echo "<p>Empty.</p>"; ?>
</section>
</body></html>
