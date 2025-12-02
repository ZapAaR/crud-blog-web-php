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

    if(!$user){
        header('Location: login.php');
    }

?>

<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Profil <?= e($user['name']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">

    <main class="w-full max-w-3xl bg-white rounded-2xl shadow-lg overflow-hidden">
        <form id="profileForm" class="space-y-5" method="post" enctype="multipart/form-data" action="edit_profile.php">
        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-6 text-white">
            <div class="flex flex-col sm:flex-row items-center gap-4">
                <div class="relative">
                    <a href="../../public/uploads/<?= e($user['foto']) ?>" target="_blank" rel="noopener noreferrer"><img id="avatarPreview" src="../../public/uploads/<?= e($user['foto']) ?>" class="w-28 h-28 rounded-full border-4 border-white object-cover" /></a>
                    <label class="absolute bottom-0 right-0 bg-white text-indigo-600 w-9 h-9 flex items-center justify-center rounded-full shadow cursor-pointer">
                        ✎
                        <input type="file" accept="image/*" name="foto" class="hidden" id="avatarInput" />
                    </label>
                </div>

                <div class="text-center sm:text-left">
                    <h1 class="text-2xl font-bold"><?= e($user['name']) ?></h1>
                    <p class="text-white/80 text-sm"><?= e($user['email']) ?></p>
                </div>
            </div>
        </div>

        <!-- Body -->
        <div class="p-6 space-y-6">
                <input type="hidden" name="csrf" value="<?= e(token_csrf()) ?>">
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Nama Lengkap</label>
                        <input type="text" name="name" id="name" value="<?= e($user['name']) ?>" class="mt-1 w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Nama lengkap"/>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Password Baru</label>
                        <input type="password" name="password" id="password" class="mt-1 w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="********" />
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">Email</label>
                    <input type="email" id="email" name="email" value="<?= e($user['email']) ?>" disabled class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed focus:ring-0 focus:border-gray-300" placeholder="email@contoh.com" />
                </div>

                <div class="flex flex-col sm:flex-row gap-3 justify-end">
                    <a href="../index.php" class="px-5 py-2 rounded-lg bg-slate-200 text-slate-700 hover:bg-slate-300 inline-block">
                        Kembali
                    </a>
                    <button type="submit" class="px-5 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">Simpan Perubahan</button>
                </div>

            <div id="msg" class="text-sm"></div>
        </div>
        </form>
    </main>

    <script>
        const avatarInput = document.getElementById('avatarInput');
        const avatarPreview = document.getElementById('avatarPreview');
        const form = document.getElementById('profileForm');
        const msg = document.getElementById('msg');

        avatarInput.addEventListener('change', () => {
            const file = avatarInput.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (e) => avatarPreview.src = e.target.result;
            reader.readAsDataURL(file);
        });

    </script>

</body>

</html>