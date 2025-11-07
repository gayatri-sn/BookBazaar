<?php
require_once __DIR__ . '/util.php';
$user = current_user();
?>
<header class="navbar">
  <div class="logo">
  <a href="/bookbazaar/">
    <!-- <img src="/bookbazaar/assets/images/logo.jpeg" alt="BookBazaar logo"> -->
    <span>BookBazaar</span>
  </a>
</div>
  <nav class="nav-links">
    <?php if ($user): ?>
      <?php if ($user['role'] === 'user'): ?>
        <a href="/bookbazaar/books.php">Books</a>
        <a href="/bookbazaar/cart.php">Cart</a>
        <a href="/bookbazaar/wishlist.php">Wishlist</a>
      <?php endif; ?>

      <span class="user-greeting">Hi, <?= htmlspecialchars($user['name']) ?></span>
      <a href="/bookbazaar/logout.php" class="logout-btn">Logout</a>

    <?php else: ?>
      <a href="/bookbazaar/login.php">Login</a>
      <a href="/bookbazaar/signup.php">Sign Up</a>
    <?php endif; ?>
  </nav>
</header>
