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

$likeStmt = $pdo->prepare("SELECT COUNT(*) FROM likes WHERE thread_id = :thread_id");
$likeStmt->execute(['thread_id' => $thread_id]);
$likesCount = $likeStmt->fetchColumn();

$userLiked = false;
if (isset($_SESSION['user_id'])) {
  $checkLikeStmt = $pdo->prepare("SELECT * FROM likes WHERE thread_id = :thread_id AND user_id = :user_id");
  $checkLikeStmt->execute([
    'thread_id' => $thread_id,
    'user_id' => $_SESSION['user_id']
  ]);
  $userLiked = $checkLikeStmt->rowCount() > 0;
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


    <!-- Like Button -->
    <div style="margin-top: 20px;">
      <?php if (isset($_SESSION['user_id'])): ?>
        <?php if ($userLiked): ?>
          <span style="color: #a78bfa; font-weight: bold;">❤️ You liked this post</span>
        <?php else: ?>
          <form action="like.php" method="POST" style="display:inline;">
            <input type="hidden" name="thread_id" value="<?= $thread_id ?>">
            <button type="submit" name="like" style="background: none; border: none; color: #6f73ff; cursor: pointer; font-size: 1em;">
              ❤️ Like
            </button>
          </form>
        <?php endif; ?>
        <span style="margin-left: 10px; color: #ccc;">Total Likes: <?= $likesCount ?></span>
      <?php else: ?>
        <span style="color: #888;">Login to like this post ❤️</span>
      <?php endif; ?>
    </div>

    <div class="thread-meta" style="margin-top: 15px;">
      Posted by <strong><?= htmlspecialchars($thread['username']) ?></strong> on <?= $thread['created_at'] ?>
    </div>

  </div>
</body>

</html>