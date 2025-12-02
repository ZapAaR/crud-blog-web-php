<?php
require_once __DIR__ . '/../../config/database.php';

$pdo = pdo();

$msg = [];

if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf($_POST['csrf'] ?? '')) {
        $msg[] = 'invalid csrf Token';
    }

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($name) || empty($email) || empty($password)) {
        $msg[] = 'semua field harus diisi';
    }

    if (empty($msg)) {
        $stmt = $pdo->prepare("select * from users where email = :email limit 1");
        $stmt->execute([
            'email' => $email
        ]);
        $user = $stmt->fetch();

        if ($user) {
            $msg[] = 'email sudah terdaftar';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("insert into users (name, email, password) values (:name, :email, :password)");
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':password' => $hash
            ]);

            header('Location: login.php?msg=' . urlencode('registrasi berhasil, silahkan login'));
            exit;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Registrasi</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-slate-50 min-h-screen flex items-center justify-center p-6">
    <main class="w-full max-w-lg bg-white rounded-2xl shadow-lg p-8">
        <header class="mb-6 text-center">
            <h1 class="text-2xl font-semibold">Daftar Akun</h1>
            <p class="text-sm text-slate-500 mt-1">Buat akun untuk akses fitur eksklusif 🎉</p>
            <?php foreach ($msg as $m) { ?>
                <div class="p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50" role="alert">
                    <?= e($m) ?>
                </div>
            <?php } ?>
        </header>


        <form id="regForm" novalidate class="space-y-4" method="post">
            <input type="hidden" name="csrf" value="<?= e(token_csrf()) ?>">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700">Nama lengkap</label>
                <input id="name" name="name" required type="text" placeholder="Nama kamu" class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                <p class="mt-1 text-xs text-red-500 hidden" data-error-for="name"></p>
            </div>


            <div>
                <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                <input id="email" name="email" required type="email" placeholder="email@contoh.com" class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                <p class="mt-1 text-xs text-red-500 hidden" data-error-for="email"></p>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                <div class="relative mt-1">
                    <input id="password" name="password" required type="password" placeholder="Masukan Password" class="peer block w-full rounded-lg border-gray-200 shadow-sm pr-12 focus:border-indigo-500 focus:ring-indigo-500" />
                    <button type="button" id="togglePassword" class="absolute right-2 top-2 text-sm text-slate-500">Tampil</button>
                </div>
                <p class="mt-1 text-xs text-red-500 hidden" data-error-for="password"></p>
            </div>

            <div class="flex items-center justify-between">
                <label class="inline-flex items-center gap-2">
                    <input id="agree" name="agree" type="checkbox" class="rounded" />
                    <span class="text-sm text-slate-600">Saya setuju dengan <a href="#" class="text-indigo-600 underline">syarat & ketentuan</a></span>
                </label>
            </div>


            <div>
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-white font-medium shadow hover:bg-indigo-700 focus:outline-none">Daftar</button>
            </div>


            <p class="text-center text-sm text-slate-500">Sudah punya akun? <a href="login.php" class="text-indigo-600 underline">Masuk</a></p>
        </form>


        <div id="msg" class="mt-4 text-sm"></div>
    </main>


    <script>
        function showError(fieldName, text) {
            const p = document.querySelector(`[data-error-for="${fieldName}"]`);
            if (!p) return;
            p.textContent = text;
            p.classList.remove('hidden');
            const input = document.getElementById(fieldName);
            if (input) input.classList.add('input-error');
        }


        function clearErrors() {
            document.querySelectorAll('[data-error-for]').forEach(el => {
                el.textContent = '';
                el.classList.add('hidden');
            });
            document.querySelectorAll('.input-error').forEach(el => el.classList.remove('input-error'));
        }


        togglePassword.addEventListener('click', () => {
            const pw = document.getElementById('password');
            if (pw.type === 'password') {
                pw.type = 'text';
                togglePassword.textContent = 'Sembunyikan';
            } else {
                pw.type = 'password';
                togglePassword.textContent = 'Tampil';
            }
        });

        form.addEventListener('submit', (e) => {
            e.preventDefault();
            clearErrors();
            msg.textContent = '';


            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const agree = document.getElementById('agree').checked;


            let valid = true;


            if (name.length < 2) {
                showError('name', 'Nama minimal 2 karakter');
                valid = false;
            }
            const emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRe.test(email)) {
                showError('email', 'Email tidak valid');
                valid = false;
            }
            if (!agree) {
                msg.innerHTML = '<span class="text-red-500">Kamu harus menyetujui syarat & ketentuan</span>';
                valid = false;
            }


            if (!valid) return;

            const payload = {
                name,
                email
            };
            msg.innerHTML = '<span class="text-green-600">Berhasil. Mengirim data ke server...</span>';

            setTimeout(() => {
                form.reset();
                avatarPreview.classList.add('hidden');
                msg.innerHTML = '<span class="text-slate-600">Selesai. (Ini hanya demo)</span>';
            }, 1200);
        });
    </script>
</body>

</html>