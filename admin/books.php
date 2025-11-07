<?php
require_once __DIR__.'/../includes/util.php'; require_role(['admin']);
$books = read_json('books.json');

if ($_SERVER['REQUEST_METHOD']==='POST') {
  $action = $_POST['action'] ?? '';
  if ($action==='create') {
    $books[] = [
      'id'=> next_id($books),
      'title'=> $_POST['title'],
      'author'=> $_POST['author'],
      'genre'=> $_POST['genre'],
      'price'=> (int)$_POST['price'],
      'image'=> $_POST['image'],
      'description'=> $_POST['description']
    ];
  } elseif ($action==='delete') {
    $id=(int)$_POST['id']; $books = array_values(array_filter($books, fn($b)=>$b['id']!==$id));
  } elseif ($action==='update') {
    $id=(int)$_POST['id'];
    foreach($books as &$b){ if($b['id']===$id){
      $b['title']=$_POST['title']; $b['author']=$_POST['author']; $b['genre']=$_POST['genre'];
      $b['price']=(int)$_POST['price']; $b['image']=$_POST['image']; $b['description']=$_POST['description']; break;
    }}
  }
  write_json('books.json',$books);
  header('Location: books.php'); exit;
}
?>
<!doctype html><html><head>
<meta charset="utf-8"><title>Admin · Books</title><link rel="stylesheet" href="../css/style.css">
</head><body>
<?php include __DIR__.'/../includes/header.php'; ?>
<h1>Manage Books</h1>
<form method="post" class="card form">
  <input type="hidden" name="action" value="create">
  <input name="title" placeholder="Title" required>
  <input name="author" placeholder="Author" required>
  <input name="genre" placeholder="Genre" required>
  <input name="price" type="number" placeholder="Price" required>
  <input name="image" placeholder="Image filename" required>
  <textarea name="description" placeholder="Description" required></textarea>
  <button class="btn" type="submit">Add Book</button>
</form>

<table class="table">
  <tr><th>ID</th><th>Title</th><th>Author</th><th>Price</th><th>Actions</th></tr>
  <?php foreach($books as $b): ?>
    <tr>
      <td><?=$b['id']?></td>
      <td><?=$b['title']?></td>
      <td><?=$b['author']?></td>
      <td>₹<?=$b['price']?></td>
      <td class="actions">
        <form method="post" class="inline">
          <input type="hidden" name="action" value="delete">
          <input type="hidden" name="id" value="<?=$b['id']?>">
          <button class="btn danger">Delete</button>
        </form>
        <details>
          <summary class="btn ghost">Edit</summary>
          <form method="post" class="card form">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" value="<?=$b['id']?>">
            <input name="title" value="<?=$b['title']?>" required>
            <input name="author" value="<?=$b['author']?>" required>
            <input name="genre" value="<?=$b['genre']?>" required>
            <input name="price" type="number" value="<?=$b['price']?>" required>
            <input name="image" value="<?=$b['image']?>" required>
            <textarea name="description" required><?=$b['description']?></textarea>
            <button class="btn">Save</button>
          </form>
        </details>
      </td>
    </tr>
  <?php endforeach; ?>
</table>
</body></html>
