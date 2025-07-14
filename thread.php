<?php
session_start();
require_once 'includes/db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  echo "Invalid thread ID.";
  exit();
}

$thread_id = $_GET['id'];

$stmt = $pdo->prepare("SELECT threads.*, users.username FROM threads
                       JOIN users ON threads.user_id = users.id
                       WHERE threads.id = :id");
$stmt->execute(['id' => $thread_id]);
$thread = $stmt->fetch();

if (!$thread) {
  echo "Thread not found.";
  exit();
}
?>

<!DOCTYPE html>
<html>

<head>
  <title><?= htmlspecialchars($thread['title']) ?></title>
  <link rel="stylesheet" href="css/forum.css">
</head>

<body style="padding: 40px;">
  <a href="index.php" style="color:#6f73ff; font-weight: bold;">← Back to Threads</a>

  <div class="thread-card" style="max-width: 800px; margin: 30px auto; height: auto;">
    <h2 class="thread-title"><?= htmlspecialchars($thread['title']) ?></h2>
    <p class="thread-content"><?= nl2br(htmlspecialchars($thread['content'])) ?></p>
  
    <?php if ($thread['image']): ?>
      <div style="margin: 15px 0;">
        <img src="images/<?= htmlspecialchars($thread['image']) ?>" alt="Thread Image" style="max-width:100%; border-radius: 10px;">
      </div>
    <?php endif; ?>
    <div class="thread-meta" style="margin-top: 15px;">
      Posted by <strong><?= htmlspecialchars($thread['username']) ?></strong> on <?= $thread['created_at'] ?>
    </div>
  </div>

</body>

</html>