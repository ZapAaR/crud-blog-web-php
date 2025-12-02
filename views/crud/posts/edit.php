<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../layouts/header.php';
$pdo = pdo();

$id = $_GET['id'];

$stmt = $pdo->prepare("select * from posts where id = :id");
$stmt->execute(['id' => $id]);
$data = $stmt->fetch();

if (!$data) {
    header('Location: index.php');
    exit;
}

$target = __DIR__ . '/../../../public/upload/';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf($_POST['csrf'])) {
        die('invalid csrf token');
    }

    $judul = trim($_POST['judul']);
    $body = trim($_POST['body']);
    $author  = $_SESSION['user_id'];

    $foto = $data['image'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $newfoto = $_FILES['image'];
        $allowed = ['jpg,', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($newfoto['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            die('file tidak mendukung');
        }

        $oldfoto = $data['image'];
        if ($oldfoto && file_exists($target . $oldfoto)) {
            unlink($target . $oldfoto);
        }

        $namafile = time() . '_' . uniqid() . '.' . $ext;
        $destination = $target . $namafile;

        if (move_uploaded_file($newfoto['tmp_name'], $destination)) {
            $foto = $namafile;
        } else {
            die('gagal');
        }
    } else {
      $foto = $data['image'];
    }
    $stmt = $pdo->prepare("update posts set judul = :judul, body = :body, image = :image where id = :id and user_id = :uid");
    $stmt->execute([
        'judul' => $judul,
        'body' => $body,
        'image' => $foto,
        'id' => $id,
        'uid' => $author
    ]);

    header('Location: index.php');
    exit;
}
?>

<?php require_once __DIR__ . '/../../layouts/sidebar.php'; ?>

<?php require_once __DIR__ . '/../../layouts/navbar.php'; ?>

<div class="min-h-screen flex items-center justify-center">
  <div class="w-full max-w-2xl bg-white p-8 rounded-2xl shadow">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold text-slate-700">✏️ Edit Postingan</h1>
      <a href="index.php"
        class="px-4 py-2 rounded-lg bg-slate-200 text-slate-700 hover:bg-slate-300">
        Kembali
      </a>
    </div>

    <!-- Form -->
    <form method="POST" enctype="multipart/form-data" class="space-y-5">
      <input type="hidden" name="csrf" value="<?= token_csrf() ?>">

      <input type="hidden" name="id" value="<?= e($data['id']) ?>">

      <!-- Judul -->
      <div>
        <label class="block text-sm font-medium text-slate-600 mb-1">Judul</label>
        <input
          type="text"
          name="judul"
          required
          class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-200 outline-none"
          value="<?= e($data['judul']) ?>">
      </div>

      <!-- Konten -->
      <div>
        <label class="block text-sm font-medium text-slate-600 mb-1">Konten</label>
        <textarea
          name="body"
          rows="5"
          required
          class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-200 outline-none"
          ><?= e($data['body']) ?></textarea>
      </div>

      <!-- Preview Gambar Lama -->
      <div>
        <label class="block text-sm font-medium text-slate-600 mb-1">Gambar Saat Ini</label>
        <img src="/blog_web/public/upload/<?= e($data['image']) ?>" 
             class="w-32 h-32 object-cover rounded-lg border mb-2">
        <p class="text-xs text-gray-500">Kosongkan jika tidak ingin mengganti gambar</p>
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