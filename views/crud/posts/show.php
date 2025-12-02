<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../layouts/header.php';

$pdo = pdo();

$stmt = $pdo->prepare("select p.*, u.name from posts p join users u on p.user_id = u.id");
$stmt->execute();
$post = $stmt->fetch();
?>

<?php require_once __DIR__ . '/../../layouts/sidebar.php'; ?>

<?php require_once __DIR__ . '/../../layouts/navbar.php'; ?>

<div class="max-w-4xl mx-auto px-6 py-10">

  <!-- Card -->
  <div class="bg-white rounded-2xl shadow overflow-hidden">

    <!-- Gambar -->
    <img src="/blog_web/public/upload/<?= e($post['image']) ?>"
      class="w-full h-64 object-cover">

    <!-- Content -->
    <div class="p-8">

      <!-- Title -->
      <h1 class="text-3xl font-bold text-slate-800 mb-2">
        <?= e($post['judul']) ?>
      </h1>

      <!-- Meta -->
      <div class="flex flex-wrap gap-4 text-sm text-slate-500 mb-6">
        <span>✍️ <?= $post['name'] ?></span>
        <span>📅 <?= date("d M Y : H:i", strtotime($post['created_at'])) ?></span>
      </div>

      <!-- Konten -->
      <div class="prose max-w-none text-slate-700 leading-relaxed">
        <?= nl2br(htmlspecialchars($post['body'])) ?>
      </div>

      <!-- Tombol aksi -->
      <div class="mt-8 flex gap-3">
        <a href="index.php"
          class="px-5 py-2 rounded-lg bg-slate-200 text-slate-700 hover:bg-slate-300">
          ⬅ Kembali
        </a>

        <a href="edit.php?id=<?= $post['id'] ?>"
          class="px-5 py-2 rounded-lg bg-yellow-500 text-white hover:bg-yellow-600">
          ✏️ Edit
        </a>
      </div>

    </div>
  </div>

</div>

<?php require_once __DIR__ . '/../../layouts/navbar_mobile.php' ?>

<?php require_once __DIR__ . '/../../layouts/footer.php' ?>