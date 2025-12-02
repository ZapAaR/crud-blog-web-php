<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . "/../../layouts/header.php";
$pdo = pdo();

$id = $_SESSION['user_id'];

//cari
$cari = $_GET['q'] ?? '';
$cari = trim($cari);

//page
$limit = 3;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$page = $page < 1 ? 1 : $page;
$offset = ($page - 1) * $limit;

if (!empty($cari)) {
  $count = $pdo->prepare("select count(*) from comments k
                        join posts p on k.posts_id = p.id 
                        where k.user_id = :id and p.judul like :cari or p.body like :cari");
  $count->execute([
    ':id' => $id,
    ':cari' => "%$cari%"
  ]);
} else {
  $count = $pdo->prepare("select count(*) from comments where user_id = :id");
  $count->execute([':id' => $id]);
}

$data = $count->fetchColumn();
$totalpage = ceil($data / $limit);

if (!empty($cari)) {
  $stmt = $pdo->prepare("select k.*, u.name, p.judul, p.body from comments k
   join users u on k.user_id = u.id 
   join posts p on k.posts_id = p.id
   where p.body like :cari and k.user_id = :id
   order by k.id desc
   limit :limit offset :offset");
  $stmt->bindValue(':cari', "%$cari%", PDO::PARAM_STR);
  $stmt->bindValue(':id', $id, PDO::PARAM_INT);
} else {
  $stmt = $pdo->prepare("select k.*, u.name, p.judul, p.body from comments k
   join users u on k.user_id = u.id
   join posts p on k.posts_id = p.id
  where k.user_id = :id 
  order by k.id desc
  limit :limit offset :offset");
  $stmt->bindValue(':id', $id, PDO::PARAM_INT);
}

$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$history = $stmt->fetchAll();

?>

<?php require_once __DIR__ . '/../../layouts/sidebar.php' ?>
<?php require_once __DIR__ . '/../../layouts/navbar.php' ?>

<body class="bg-gray-100 min-h-screen p-4">

  <div class="max-w-4xl mx-auto">

    <h1 class="text-2xl font-bold mb-6 text-gray-800">History Komentar <?= e($_SESSION['name']) ?></h1>

    <!-- Wrapper Card -->
    <div class="bg-white shadow rounded-xl p-6">

      <!-- ====== SEARCH ====== -->
      <form method="GET" class="mb-6">
        <input
          type="text"
          name="q"
          value="<?= e($cari) ?>"
          placeholder="Cari komentar..."
          class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:ring-2 focus:ring-blue-500">
      </form>

      <!-- ====== LIST KOMENTAR ====== -->
      <div class="space-y-4">

        <!-- Komentar Item -->
        <?php if (!empty($history)) { ?>
          <?php foreach ($history as $his) { ?>
            <div class="border rounded-lg p-4 hover:bg-gray-50 transition">
              <div class="flex justify-between items-center mb-1">
                <p class="font-semibold text-gray-700">Komentar pada:
                  <a href="/blog_web/views/detail.php?id=<?= $his['posts_id'] ?>" class="text-blue-600 hover:underline">
                    <?= e($his['judul']) ?>
                  </a>
                </p>
                <span class="text-xs text-gray-400"><?= e(date("d M Y : H:i", strtotime($his['created_at']))) ?></span>
              </div>

              <p class="text-gray-600"><?= e($his['komentar']) ?></p>
            </div>
          <?php } ?>
        <?php } else { ?>
          <p class="text-center text-gray-500 text-sm">Belum ada komentar nih… 🥲</p>
        <?php } ?>
      </div>

      <!-- ====== PAGINATION ====== -->
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
          <a href="?q=<?= urlencode($cari) ?>&page=<?= $page + 1 ?>"
            class="px-4 py-2 bg-white border rounded-lg hover:bg-gray-100">
            Next ▶
          </a>
        <?php } ?>
      </div>

    </div>
  </div>

  <?php require_once __DIR__ . '/../../layouts/navbar_mobile.php' ?>
  <?php require_once __DIR__ . '/../../layouts/footer.php' ?>