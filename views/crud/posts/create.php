<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../layouts/header.php';

$pdo = pdo();

$msg = [];

$posts = $pdo->prepare("select * from users");
$posts->execute();
$post = $posts->fetchAll();

$target = __DIR__ . '/../../../public/upload/';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!validate_csrf($_POST['csrf'])) {
    $msg[] = 'invalid token csrf';
  }

  $judul = trim($_POST['judul'] ?? '');
  $body = trim($_POST['body']);
  $gambar = $_FILES['image'];
  $author = $_SESSION['user_id'];

  if ($judul === '') $msg[] = 'Judul harus diisi!';

  $filename = time() . '_' . basename($gambar['name']);
  $path = $target . $filename;

  if (move_uploaded_file($gambar['tmp_name'], $path)) {
    $stmt = $pdo->prepare("insert into posts (user_id, judul, body, image) values (:user_id, :judul, :body, :image)");
    $stmt->execute([
      'user_id' => $author,
      'judul' => $judul,
      'body' => $body,
      'image' => $filename
    ]);

    header('Location: index.php');
    exit;
  }
}
?>

<?php require_once __DIR__ . '/../../layouts/sidebar.php'; ?>

<?php require_once __DIR__ . '/../../layouts/navbar.php'; ?>

<div class="min-h-screen flex items-center justify-center">
  <div class="w-full max-w-2xl bg-white p-8 rounded-2xl shadow">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold text-slate-700">➕ Tambah Postingan</h1>
      <a href="index.php"
        class="px-4 py-2 rounded-lg bg-slate-200 text-slate-700 hover:bg-slate-300">
        Kembali
      </a>
    </div>

    <!-- Form -->
    <form action="" method="POST" enctype="multipart/form-data" class="space-y-5">
      <input type="hidden" name="csrf" value="<?= token_csrf() ?>">

      <!-- Judul -->
      <div>
        <label class="block text-sm font-medium text-slate-600 mb-1">Judul</label>
        <input
          type="text"
          name="judul"
          required
          placeholder="Masukkan judul post..."
          class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-200 outline-none">
      </div>

      <!-- Konten -->
      <div>
        <label class="block text-sm font-medium text-slate-600 mb-1">Konten</label>
        <textarea
          name="body"
          rows="5"
          required
          placeholder="Tulis isi post..."
          class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-200 outline-none"></textarea>
      </div>

      <!-- Gambar -->
      <div>
        <label class="block text-sm font-medium text-slate-600 mb-1">Upload Gambar</label>
        <input
          type="file"
          name="image"
          class="w-full file:mr-4 file:py-2 file:px-4 
                 file:rounded-lg file:border-0 
                 file:bg-blue-100 file:text-blue-700 
                 hover:file:bg-blue-200">
      </div>

      <!-- Author -->
      <div>
        <label class="block text-sm font-medium text-slate-600 mb-1">Author</label>
        <input
          type="text"
          name="user_id"
          disabled
          placeholder="Masukkan Author post..."
          class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-200 outline-none"
          value="<?= $_SESSION['name'] ?>">
      </div>

      <!-- Button -->
      <div class="flex justify-end space-x-3 pt-4">
        <button type="reset"
          class="px-5 py-2 rounded-lg bg-slate-200 text-slate-700 hover:bg-slate-300">
          Reset
        </button>

        <button type="submit"
          class="px-5 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
          Simpan
        </button>
      </div>

    </form>
  </div>
</div>

<?php require_once __DIR__ . '/../../layouts/navbar_mobile.php' ?>

<?php require_once __DIR__ . '/../../layouts/footer.php' ?>