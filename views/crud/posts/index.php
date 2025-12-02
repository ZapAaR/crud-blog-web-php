<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../layouts/header.php';

$pdo = pdo();

$id = $_SESSION['user_id'];

//search
$cari = $_GET['q'] ?? '';
$cari = trim($cari);

//pagination
$limit = 4;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$page = $page < 1 ? 1 : $page;
$offset = ($page - 1) * $limit;

//total pagination
if (!empty($cari)) {
  $count = $pdo->prepare("select count(*) from posts p where p.user_id = :id and p.judul like :cari");
  $count->execute([
    'id' => $id,
    'cari' => "%$cari%"
  ]);
} else {
  $count = $pdo->prepare("select count(*) from posts where user_id = :id");
  $count->execute(['id' => $id]);
}

$data = $count->fetchColumn();
$totalpage = ceil($data / $limit);

//ambil cari
if (!empty($cari)) {
  $stmt = $pdo->prepare("select p.id, p.judul, u.name, p.image from posts p
                        join users u on p.user_id = u.id
                        where p.user_id = :id and p.judul like :cari
                        order by p.judul asc
                        limit :limit offset :offset");
  $stmt->bindValue(':id', $id, PDO::PARAM_STR);
  $stmt->bindValue(':cari', "%$cari%", PDO::PARAM_STR);
} else {
  $stmt = $pdo->prepare("select p.id, p.judul, u.name, p.image from posts p
                        join users u on p.user_id = u.id
                        where p.user_id = :id
                        order by p.judul asc
                        limit :limit offset :offset");
  $stmt->bindValue(':id', $id, PDO::PARAM_INT);
}

$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$posts = $stmt->fetchAll();

?>

<?php require_once __DIR__ . '/../../layouts/sidebar.php'; ?>

<?php require_once __DIR__ . '/../../layouts/navbar.php'; ?>

<!-- Header -->
<div>
  <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
    <h1 class="text-xl font-bold text-slate-700">📝 Blog Posts</h1>
    <a href="/blog_web/views/crud/posts/create.php"
      class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
      + Tambah Postingan
    </a>
  </div>
</div>

<!-- Content -->
<div class="max-w-7xl mx-auto p-6 pb-32 md:pb-6">

  <!-- Alert (opsional) -->
  <?php if (isset($_GET['success'])): ?>
    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
      ✅ Post berhasil diproses.
    </div>
  <?php endif; ?>

  <!-- Search -->
  <form method="get" id="cariform">
    <div class="mb-6">
      <input
        type="text"
        name="q"
        id="cari"
        placeholder="Cari post..."
        value="<?= e($cari) ?>"
        class="w-full md:w-1/3 px-4 py-2 border rounded-lg focus:ring focus:ring-blue-200 outline-none">
    </div>
  </form>

  <!-- Table -->
  <div class="bg-white rounded-xl shadow overflow-x-auto">
    <table class="min-w-full text-sm text-left">
      <thead class="bg-slate-100 text-slate-600 uppercase text-xs">
        <tr>
          <th class="px-6 py-3">No</th>
          <th class="px-6 py-3">Judul</th>
          <th class="px-6 py-3">Author</th>
          <th class="px-6 py-3">Gambar</th>
          <th class="px-6 py-3 text-center">Aksi</th>
        </tr>
      </thead>

      <tbody class="divide-y">
        <?php
        $no = 1;
        foreach ($posts as $post) {
        ?>
          <tr class="hover:bg-slate-50">
            <td class="px-6 py-4"><?= $no++ ?></td>
            <td class="px-6 py-4 font-medium text-slate-700">
              <?= $post['judul'] ?>
            </td>
            <td class="px-6 py-4"><?= $post['name'] ?></td>
            <td class="px-6 py-4"><a href="/blog_web/public/upload/<?= $post['image'] ?>" target="_blank" rel="noopener noreferrer"><img src="/blog_web/public/upload/<?= $post['image'] ?>" alt="Gambar"
                  class="w-32 h-32 md:w-36 md:h-36 object-cover rounded-lg shadow-sm"
                  loading="lazy"></a></td>
            <td class="px-6 py-4 text-center space-x-2">
              <a href="show.php?id=<?= e($post['id']) ?>"
                class="px-3 py-1 bg-sky-100 text-sky-700 rounded hover:bg-sky-200">
                Detail
              </a>
              <a href="edit.php?id=<?= e($post['id']) ?>"
                class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200">
                Edit
              </a>
              <a href="delete.php?id=<?= e($post['id']) ?>"
                class="px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200"
                onclick="return confirm('Yakin mau hapus?')">Delete
              </a>
            </td>
          </tr>
        <?php } ?>
        <?php if (empty($posts)) { ?>
          <td class="px-6 py-4 font-medium text-slate-700 text-center">Tidak ada postingan!
  </div>
<?php } ?>
</tbody>
</table>
<div class="mt-6 flex flex-wrap justify-center gap-2">
  <?php for ($i = 1; $i <= $totalpage; $i++) { ?>
    <a href="?q=<?= $cari ?>&page=<?= $i ?>" class="px-4 py-2 text-sm font-medium rounded-lg border transition
              <?= $page == $i ? 'bg-black text-white border-black'
                : 'bg-white text-black border-gray-300 hover:bg-gray-100' ?>">
      <?= $i ?>
    </a>
  <?php } ?>
</div>
</div>

</div>

<script>
  const input = document.getElementById('cari');
  const form = document.getElementById('cariform');

  let timer;

  input.addEventListener('input', function() {
    clearTimeout(timer);

    timer = setTimeout(() => {
      form.submit();
    }, 400);
  });
</script>

<?php require_once __DIR__ . '/../../layouts/navbar_mobile.php'; ?>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>