<?php
session_start();
require_once 'includes/db.php';

$error = '';

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username OR email = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: index.php");
        exit();
    } else {
        $error = "❌ Incorrect username or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div class="container">
    <div class="register-box">
      <h2>Login</h2>

      <?php if (!empty($error)): ?>
        <p style="color: #ff8080; text-align: center;"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>

      <form method="POST" action="">
        <div class="input-box">
          <input type="text" name="username" required placeholder="Username or Email">
        </div>
        <div class="input-box">
          <input type="password" name="password" required placeholder="Password">
        </div>
        <button class="btn" type="submit" name="login">Login</button>
      </form>

      <div class="login-link">
        <p>Don't have an account? <a href="register.php">Register here</a></p>
      </div>
    </div>
  </div>
</body>
</html>
