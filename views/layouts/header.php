<?php 
require_once __DIR__ . '/../../config/database.php';

$pdo = pdo();

$msg = [];

if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit;
}

$id = $_SESSION['user_id'];

$stmt = $pdo->prepare("select * from users where id = :id");
$stmt->execute(['id' => $id]);
$user = $stmt->fetch();
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard Blog</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen flex">