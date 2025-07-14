<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title']);
  $content = trim($_POST['content']);
  $imageName = null;

  if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $uploadDir = 'images/';
    $imageName = uniqid() . '_' . basename($_FILES['image']['name']);
    $uploadPath = $uploadDir . $imageName;
    move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath);
  }

  if (empty($title) || empty($content)) {
    $error = "Please fill in all fields.";
  } else {
    $stmt = $pdo->prepare("INSERT INTO threads (user_id, title, content, image) 
                               VALUES (:user_id, :title, :content, :image)");
    $stmt->execute([
      'user_id' => $_SESSION['user_id'],
      'title' => $title,
      'content' => $content,
      'image' => $imageName
    ]);

    header("Location: index.php");
    exit();
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Create Thread</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      height: 100vh;
      background: #120f2f;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: sans-serif;
      color: #fff;
    }

    .container {
      width: 400px;
      padding: 30px;
      border-radius: 20px;
      background: rgba(255, 255, 255, 0.05);
      backdrop-filter: blur(10px);
      box-shadow: 0 0 20px rgba(111, 115, 255, 0.3);
    }

    h2 {
      font-size: 1.8em;
      color: #a78bfa;
      text-align: center;
      margin-bottom: 20px;
    }

    .input-box {
      margin-bottom: 20px;
    }

    label {
      display: block;
      margin-bottom: 5px;
      font-weight: 600;
      color: #ccc;
    }

    input[type="text"],
    textarea,
    input[type="file"] {
      width: 100%;
      padding: 12px 15px;
      border-radius: 10px;
      border: 2px solid #4f46e5;
      background: transparent;
      color: #eee;
      font-size: 1em;
      box-sizing: border-box;
      outline: none;
    }

    input[type="text"]:focus,
    textarea:focus {
      border-color: #a78bfa;
      transform: scale(1.02);
    }

    textarea {
      resize: vertical;
      min-height: 120px;
    }

    .btn {
      width: 100%;
      height: 45px;
      background: #6f73ff;
      border: none;
      border-radius: 40px;
      cursor: pointer;
      font-size: 1em;
      color: #120f2f;
      font-weight: bold;
      margin-top: 10px;
      transition: 0.3s;
    }

    .btn:hover {
      transform: scale(1.05);
    }

    .back-link {
      text-align: center;
      margin-top: 15px;
    }

    .back-link a {
      color: #6f73ff;
      text-decoration: none;
      font-weight: bold;
    }

    .back-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="register-box">
      <h2>Create New Thread</h2>

      <?php if (!empty($error)): ?>
        <p style="color: #ff7070; text-align: center;"><?= htmlspecialchars($error) ?></p>
      <?php elseif (!empty($success)): ?>
        <p style="color: #90ee90; text-align: center;"><?= htmlspecialchars($success) ?></p>
      <?php endif; ?>

      <form method="POST" action="" enctype="multipart/form-data">
        <div class="input-box">
          <label>Thread Title</label>
          <input type="text" name="title" required>
        </div>
        <div class="input-box">
          <label>Content</label>
          <textarea name="content" rows="5" style="width: 100%; padding: 15px; border-radius: 15px;" required></textarea>
        </div>
        <div class="input-box">
          <label style="color:white;">Upload Image (optional)</label>
          <input type="file" name="image" accept="image/*">
        </div>
        <button class="btn" type="submit">Post</button>
      </form>

    </div>
  </div>
</body>

</html>