<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . "/layouts/header.php";

$pdo = pdo();

$posts_id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("select k.*, u.name from comments k
                    join users u on k.user_id = u.id
                    where k.posts_id = :posts_id
                    order by k.id desc");

$stmt->execute([':posts_id' => $posts_id]);
$komens = $stmt->fetchAll();

$post = $pdo->prepare("select p.*, u.name from posts p
                    join users u on p.user_id = u.id
                    where p.id = :id
                    limit 1");
$post->execute([':id' => $posts_id]);
$data_post = $post->fetch();

?>

<?php
require_once __DIR__ . "/layouts/sidebar.php";
?>

<?php
require_once __DIR__ . "/layouts/navbar.php";
?>

<div class="max-w-3xl mx-auto p-4">

    <!-- ===== Card Detail Post ===== -->
    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-2"><?= e($data_post['judul']) ?></h1>
        <p class="text-gray-500 text-sm mb-4">Diposting oleh <b><?= e($_SESSION['name']) ?></b> • <?= e(date("d M Y : H:i", strtotime($data_post['created_at']))) ?></p>

        <a href="/blog_web/public/upload/<?= e($data_post['image']) ?>" target="_blank" rel="noopener noreferrer"><img src="/blog_web/public/upload/<?= e($data_post['image']) ?>" class="rounded-lg mb-4"></a>

        <p class="text-gray-700 leading-relaxed">
            <?= e($data_post['body']) ?>
        </p>
    </div>


    <!-- ===== Form Komentar ===== -->
    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Tulis Komentar</h2>

        <?php if (isset($_SESSION['user_id'])) { ?>
            <form action="/blog_web/views/crud/komentar/proses_komentar.php" method="POST" class="space-y-4">
                <input type="hidden" name="csrf" value="<?= e(token_csrf()) ?>">
                <input type="hidden" name="posts_id" value="<?= e($posts_id) ?>">

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Nama</label>
                    <input type="text" disabled
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        value="<?= e($_SESSION['name']) ?>">
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Komentar</label>
                    <textarea name="komentar" rows="3" required
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <button type="submit"
                    class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition">
                    Kirim Komentar
                </button>
            </form>
    </div>

    <!-- Jika belum ada komentar -->
<?php } else { ?>
    <p class="text-gray-500 text-sm text-center">Login dulu buat komentar 🔒</p>
<?php } ?>

<div class="bg-white rounded-xl shadow p-6">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Komentar</h2>

    <?php if (count($komens) > 0) { ?>
        <?php foreach ($komens as $komen) { ?>
            <div class="border-b pb-4 mb-4">
                <div class="flex justify-between mb-1">
                    <span class="font-semibold text-gray-700"><?= e($komen['name']) ?></span>
                    <span class="text-xs text-gray-400"><?= e(date("d M Y : H:i", strtotime($komen['created_at']))) ?></span>
                </div>
                <p class="text-gray-600"><?= e($komen['komentar']) ?></p>
                <?php if ($komen['user_id'] == $_SESSION['user_id']) { ?>
                    <a href="/blog_web/views/crud/komentar/hapus.php?id=<?= $komen['id'] ?>&posts_id=<?= $posts_id ?>"
                        class="text-red-600 hover:underline"
                        onclick="return confirm('Hapus komentar ini?')">
                        🗑️
                    </a>
                <?php } ?>
            </div>
        <?php } ?>
    <?php } else { ?>
        <p class="text-gray-500 text-sm text-center">Belum ada komentar.</p>
    <?php } ?>
</div>

<?php
require_once __DIR__ . "/layouts/navbar_mobile.php";
?>

<?php require_once __DIR__ . "/layouts/footer.php"; ?>