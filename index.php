<?php
session_start();
require_once 'includes/db.php';

// جلب كل المواضيع مع عدد اللايكات
$stmt = $pdo->query("SELECT t.*, u.username, 
                        (SELECT COUNT(*) FROM likes WHERE thread_id = t.id) AS likes_count 
                     FROM threads t 
                     JOIN users u ON t.user_id = u.id 
                     ORDER BY t.created_at DESC");
$threads = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>

<head>
  <title>Forum Home</title>
  <link rel="stylesheet" href="css/forum.css">
  <style>
    body {
      margin: 0;
      padding: 0;
      background: #120f2f;
      font-family: sans-serif;
      color: #fff;
    }

    .navbar {
      background: #1e1b4b;
      padding: 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      color: #a78bfa;
    }

    .navbar h2 {
      margin: 0;
    }

    .welcome a {
      color: #6f73ff;
      margin-left: 10px;
      text-decoration: none;
      font-weight: bold;
    }

    .threads-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
      gap: 20px;
      max-width: 1200px;
      margin: 30px auto;
      padding: 20px;
    }

    .thread-card {
      background: rgba(255, 255, 255, 0.05);
      border-radius: 15px;
      backdrop-filter: blur(8px);
      padding: 20px;
      box-shadow: 0 0 15px rgba(111, 115, 255, 0.2);
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      height: 100%;
    }

    .thread-title {
      font-size: 1.2em;
      color: #a78bfa;
      margin-bottom: 10px;
      font-weight: bold;
    }

    .thread-snippet {
      color: #ddd;
      font-size: 0.95em;
      margin-bottom: 15px;
    }

    .view-link {
      margin-top: auto;
    }

    .view-link a {
      text-decoration: none;
      color: #6f73ff;
      font-weight: bold;
    }

    .thread-meta {
      margin-top: 10px;
      font-size: 0.85em;
      color: #aaa;
    }

    .thread-image-preview {
      max-width: 100%;
      max-height: 160px;
      object-fit: cover;
      margin-bottom: 10px;
      border-radius: 10px;
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
          <?php if (!empty($thread['image'])): ?>
            <img class="thread-image-preview" src="images/<?= htmlspecialchars($thread['image']) ?>" alt="Preview">
          <?php endif; ?>

          <div class="thread-title"><?= htmlspecialchars($thread['title']) ?></div>
          <div class="thread-snippet">
            <?= nl2br(htmlspecialchars(substr($thread['content'], 0, 120))) ?>...
          </div>
          <div class="thread-meta">
            By <strong><?= htmlspecialchars($thread['username']) ?></strong> |
            ❤️ <?= $thread['likes_count'] ?> Likes
          </div>
          <div class="view-link">
            <a href="thread.php?id=<?= $thread['id'] ?>">View More →</a>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p style="text-align: center; color: #999;">No threads yet. Be the first to post!</p>
    <?php endif; ?>
  </div>
</body>

</html>
