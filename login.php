<?php
require_once __DIR__ . '/includes/util.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';
    $users = read_json('users.json');

    foreach ($users as $u) {
        if (strcasecmp($u['email'], $email) === 0 && password_verify($pass, $u['password'])) {
            $_SESSION['user'] = [
                'id' => $u['id'],
                'name' => $u['name'],
                'email' => $u['email'],
                'role' => $u['role']
            ];

            // Redirect based on role
            if ($u['role'] === 'admin') {
                header('Location: admin/index.php');
            } elseif ($u['role'] === 'delivery') {
                header('Location: delivery/orders.php');
            } else {
                header('Location: index.html');
            }
            exit;
        }
    }

    $err = "Invalid email or password!";
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login | BookBazaar</title>
  <link rel="stylesheet" href="css/style.css?v=2">
</head>
<body>

  <div class="auth-wrapper">
    <div class="auth-card">
      <h1>Login</h1>
      
      <?php if (!empty($err)): ?>
        <p class="error-msg"><?= htmlspecialchars($err) ?></p>
      <?php endif; ?>

      <form method="post">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
      </form>

      <p>Don't have an account? <a href="signup.php">Sign up</a></p>
    </div>
  </div>

</body>
</html>
