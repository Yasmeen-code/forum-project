<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$thread_id = $_POST['thread_id'] ?? null;

if ($thread_id) {
    $stmt = $pdo->prepare("SELECT * FROM likes WHERE user_id = :user_id AND thread_id = :thread_id");
    $stmt->execute(['user_id' => $user_id, 'thread_id' => $thread_id]);

    if ($stmt->rowCount() == 0) {
        $stmt = $pdo->prepare("INSERT INTO likes (user_id, thread_id) VALUES (:user_id, :thread_id)");
        $stmt->execute(['user_id' => $user_id, 'thread_id' => $thread_id]);
    }
}

header("Location: index.php");
exit();
