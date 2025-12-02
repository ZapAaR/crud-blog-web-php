<?php
require_once __DIR__ . '/../../../config/database.php';
$pdo = pdo();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf($_POST['csrf'])){
        die('invalid token csrf');
    }

    $post_id = $_POST['posts_id'] ?? null;
    $id = $_SESSION['user_id'] ?? null;
    $komen = trim($_POST['komentar'] ?? '');

    if (!$post_id || !$id || $komen === '') {
        header("location: /blog_web/views/detail.php?id=" . $post_id);
        exit;
    }

    $stmt = $pdo->prepare("insert into comments (posts_id, user_id, komentar) values(:posts_id, :user_id, :komentar)");
    $stmt->execute([
        ':posts_id' => $post_id,
        ':user_id' => $id,
        ':komentar' => $komen
    ]);

    header("location: /blog_web/views/detail.php?id=" . $post_id);
    exit;
}
