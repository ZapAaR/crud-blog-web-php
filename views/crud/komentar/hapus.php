<?php
require_once __DIR__ . '/../../../config/database.php';

$pdo = pdo();

$id = $_GET['id'] ?? null;
$posts_id = $_GET['posts_id'] ?? 0;

if (!$id){
    header("Location: /blog_web/views/detail.php?id=" . $posts_id);
    exit;
}

$cek = $pdo->prepare("select * from comments where id = :id");
$cek->execute([':id' => $id]);
$komen = $cek->fetch();

if ($komen['user_id'] != $_SESSION['user_id']){
    die("bukan komentar kamu");
}

$stmt = $pdo->prepare("delete from comments where id = :id");
$stmt->execute([':id' => $id]);

header("Location: /blog_web/views/detail.php?id=" . $posts_id);
exit;
?>