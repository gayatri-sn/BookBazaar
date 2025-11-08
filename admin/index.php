<?php
require_once __DIR__ . '/../includes/util.php';
$user = current_user();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard | BookBazaar</title>
  <link rel="stylesheet" href="../css/style.css?v=2">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lucide-icons@latest/font/lucide.css">
</head>
<body>

<?php include '../includes/header.php'; ?>

<section class="admin-dashboard">
  <div class="welcome-card">
    <h1>Welcome, <?= htmlspecialchars($user['name'] ?? 'Admin') ?>!</h1>
    <p>Here’s what you can manage today.</p>
  </div>

  <div class="admin-cards">
    <a href="books.php" class="admin-card">
      <i class="lucide-book-open"></i>
      <h3>Manage Books</h3>
      <p>Add, edit, or delete book listings.</p>
    </a>

    <a href="orders.php" class="admin-card">
      <i class="lucide-shopping-bag"></i>
      <h3>View Orders</h3>
      <p>Track customer orders and delivery status.</p>
    </a>

  </div>
</section>

</body>
</html>
