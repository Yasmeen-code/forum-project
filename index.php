<?php
session_start();
require_once 'includes/db.php';

$stmt = $pdo->query("SELECT threads.*, users.username FROM threads 
                     JOIN users ON threads.user_id = users.id 
                     ORDER BY created_at DESC");
$threads = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>

<head>
  <title>Forum Home</title>
  <link rel="stylesheet" href="css/forum.css">
  <style>
    .threads-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
    }
  </style>
</head>

<body>
  <div class="navbar">
    <h2>Forum Threads</h2>
    <div class="welcome">
      <?php if (isset($_SESSION['username'])): ?>
        Welcome, <?= htmlspecialchars($_SESSION['username']) ?> |
        <a href="create-thread.php">+ New Thread</a> |
        <a href="logout.php">Logout</a>
      <?php else: ?>
        <a href="login.php">Login</a> or <a href="register.php">Register</a>
      <?php endif; ?>
    </div>
  </div>

  <div class="threads-grid">
    <?php if ($threads): ?>
      <?php foreach ($threads as $thread): ?>
        <div class="thread-card">
          <div>
            <div class="thread-title"><?= htmlspecialchars($thread['title']) ?></div>
            <div class="thread-snippet">
              <?= nl2br(htmlspecialchars(substr($thread['content'], 0, 120))) ?>...
            </div>
          </div>
          <div class="view-link">
            <a href="thread.php?id=<?= $thread['id'] ?>">View More →</a>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>No threads yet. Be the first to post!</p>
    <?php endif; ?>
  </div>
</body>

</html>