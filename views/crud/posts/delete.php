<?php
require_once __DIR__ . '/../../../config/database.php';

$pdo = pdo();

$id = $_GET['id'];

$stmt = $pdo->prepare("select image from posts where id = :id");
$stmt->execute([':id' => $id]);
$data = $stmt->fetch();

if ($data && !empty($data['image'])){
    $path = __DIR__ . '/../../../public/upload/' . $data['image'];

    if (file_exists($path)){
        unlink($path);
    }
}

$stmt = $pdo->prepare("delete from posts where id = :id");
$stmt->execute([':id' => $id]);

header('Location: index.php');
exit;
?>