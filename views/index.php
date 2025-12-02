<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . "/layouts/header.php";

$pdo = pdo();

$id = $_SESSION['user_id'];

//cari
$cari = $_GET['q'] ?? '';
$cari = trim($cari);

//page
$limit = 4;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$page = $page < 1 ? 1 : $page;
$offset = ($page - 1) * $limit;

if (!empty($cari)){
  $count = $pdo->prepare("select count(*) from posts where judul like :cari");
  $count->execute([
    ':cari' => "%$cari%"
  ]);
} else {
  $count = $pdo->prepare("select count(*) from posts");
  $count->execute();
}

$data = $count->fetchColumn();
$totalpage = ceil($data / $limit);

if (!empty($cari)) {
  $stmt = $pdo->prepare("select p.*, u.name from posts p 
                      join users u on p.user_id = u.id where p.judul like :cari
                      order by p.id desc limit :limit offset :offset");
  $stmt->bindValue(':cari', "%$cari%", PDO::PARAM_STR);
} else {
  $stmt = $pdo->prepare("select p.*, u.name from posts p
                       join users u on p.user_id = u.id
                       order by p.id desc
                       limit :limit offset :offset");
}

$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$posts = $stmt->fetchAll();

?>

<?php
require_once __DIR__ . "/layouts/sidebar.php";
?>

<?php
require_once __DIR__ . "/layouts/navbar.php";
?>

<!-- CONTENT -->
<main class="flex-1 p-6">
  <h2 class="text-xl font-semibold mb-4">Selamat datang <?= e($user['name']) ?>!</h2>
  <p class="text-slate-600">Ini adalah dashboard blog kamu. Gaskeun produktif ✍️🔥</p>

  <!-- Search -->
  <form method="get" id="cariform" class="flex justify-center">
    <div class="mb-6  w-full flex justify-center">
      <input
        type="text"
        name="q"
        id="cari"
        value="<?= e($cari) ?>"
        placeholder="Cari post..."
        class="w-full md:w-1/3 px-4 py-2 border rounded-lg focus:ring focus:ring-blue-200 outline-none">
    </div>
  </form>

  <div class="p-6 min-h-screen bg-gray-100">
    <h1 class="text-2xl font-bold mb-6">📌 Dashboard Postingan</h1>

    <?php if (empty($posts)) { ?>
      <!-- Kalau tidak ada postingan -->
      <div class="flex flex-col items-center justify-center bg-white p-10 rounded-xl shadow text-center">
        <img src="https://cdn-icons-png.flaticon.com/512/4076/4076503.png" class="w-32 mb-4" alt="Empty">
        <h2 class="text-xl font-semibold text-gray-700">Belum ada postingan</h2>
        <p class="text-gray-500 mt-2">Silakan buat postingan dulu 🚀</p>
      </div>

    <?php } else { ?>
      <!-- Kalau ada postingan -->
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php foreach ($posts as $post) { ?>
          <div class="bg-white rounded-2xl shadow hover:shadow-lg transition overflow-hidden">
            <img src="/blog_web/public/upload/<?= e($post['image']) ?>" class="w-full h-40 object-cover" alt="Gambar">

            <div class="p-4">
              <h3 class="text-lg font-semibold text-gray-800"><?= e($post['judul']) ?></h3>
              <p class="text-sm text-gray-600 mt-2"><?= e($post['body']) ?></p>

              <div class="mt-4 flex justify-between items-center">
                <a href="detail.php?id=<?= e($post['id']) ?>" class="text-blue-600 text-sm font-medium hover:underline">Detail</a>
                <span class="text-s text-gray-400"><?= e($post['name']) ?></span>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>
    <?php } ?>

      <div class="mt-10 flex justify-center flex-wrap gap-2">
        <?php if ($page >  1) { ?>
          <a href="?q=<?= e(urlencode($cari)) ?>&page=<?= $page - 1 ?>" class="px-4 py-2 bg-white border rounded-lg hover:bg-gray-100">◀ Prev</a>
        <?php } ?>
        <?php for ($i = 1; $i <= $totalpage; $i++) { ?>
          <a href="?q=<?= e(urlencode($cari)) ?>&page=<?= $i ?>" class="px-4 py-2 border rounded-lg <?= $i == $page ? 'bg-blue-600 text-white' : 'bg-white hover:bg-gray-100' ?>">
            <?= $i ?>
          </a>
        <?php } ?>
        <?php if ($page < $totalpage) { ?>
          <a href="?q=<?= e(urlencode($cari)) ?>&page=<?= $page + 1 ?>"
            class="px-4 py-2 bg-white border rounded-lg hover:bg-gray-100">
            Next ▶
          </a>
        <?php } ?>
      </div>

  </div>
</main>


<script>
  const form = document.getElementById('cariform');
  const input = document.getElementById('cari');

  let timer;

  input.addEventListener('input', function() {
    clearTimeout(timer);

    timer = setTimeout(() => {
      form.submit();
    }, 400);
  })
</script>
<?php
require_once __DIR__ . "/layouts/navbar_mobile.php";
?>

<?php require_once __DIR__ . "/layouts/footer.php"; ?>