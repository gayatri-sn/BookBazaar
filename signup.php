<?php
require_once __DIR__ . '/includes/util.php';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $pass = $_POST['password'] ?? '';
  if (!$name || !$email || !$pass) { $err="All fields required"; }
  else {
    $users = read_json('users.json');
    foreach($users as $u){ if(strcasecmp($u['email'],$email)===0){ $err="Email already registered"; break; } }
    if (empty($err)) {
      $users[] = [
        'id' => next_id($users),
        'name' => $name,
        'email'=> $email,
        'password'=> password_hash($pass, PASSWORD_BCRYPT),
        'role'=> 'user'
      ];
      write_json('users.json',$users);
      $_SESSION['user'] = ['id'=>end($users)['id'],'name'=>$name,'email'=>$email,'role'=>'user'];
      header('Location: index.html'); exit;
    }
  }
}
?>
<!doctype html><html><head>
<meta charset="utf-8"><title>Sign Up</title><link rel="stylesheet" href="css/style.css">
</head><body>
<?php include __DIR__ . '/includes/header.php'; ?>
<div class="auth-card">
  <h1>Create account</h1>
  <?php if (!empty($err)): ?><p class="error"><?=$err?></p><?php endif; ?>
  <form method="post">
    <input name="name" placeholder="Name" required>
    <input name="email" type="email" placeholder="Email" required>
    <input name="password" type="password" placeholder="Password" required>
    <button type="submit">Sign up</button>
  </form>
</div>
</body></html>
