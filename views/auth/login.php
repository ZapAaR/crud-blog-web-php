<?php
require_once __DIR__ . '/../../config/database.php';

$pdo = pdo();

$msg = [];

if (isset($_SESSION['user_id'])){
    header('Location: ../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf($_POST['csrf'] ?? '')) {
        $msg[] = 'invalid csrf token';
    }

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email)) $msg[] = 'email harus diisi';
    if (empty($password)) $msg[] = 'password harus diisi';

    if (empty($msg)) {
        $stmt = $pdo->prepare("select * from users where email = :email limit 1");
        $stmt->execute([
            'email' => $email
        ]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];

            header('Location: ../index.php');
            exit;
        } else {
            $msg[] = 'email atau password salah';
        }
    }
}
?>

<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-slate-100 min-h-screen flex items-center justify-center p-6">
    <main class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">
        <h1 class="text-2xl font-semibold text-center mb-1">Masuk</h1>
        <p class="text-center text-slate-500 text-sm mb-6">Ayo balik lagi, produktif terus 🚀</p>

        <form id="loginForm" class="space-y-4" novalidate method="post">
            <?php foreach ($msg as $m) { ?>
                <div class="p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50" role="alert">
                    <?= e($m) ?>
                </div>
            <?php } ?>
            <input type="hidden" name="csrf" value="<?= e(token_csrf()) ?>">
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                <input id="email" name="email" type="email" required placeholder="email@contoh.com" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                <p class="text-xs text-red-500 mt-1 hidden" data-error-for="email"></p>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                <div class="relative mt-1">
                    <input id="password" name="password" type="password" required placeholder="••••••••" class="block w-full rounded-lg border-gray-300 shadow-sm pr-12 focus:ring-indigo-500 focus:border-indigo-500" />
                    <button type="button" id="togglePassword" class="absolute top-2 right-3 text-sm text-slate-500">Tampil</button>
                </div>
                <p class="text-xs text-red-500 mt-1 hidden" data-error-for="password"></p>
            </div>

            <div class="flex items-center justify-between text-sm">
                <label class="inline-flex items-center gap-2">
                    <input id="remember" type="checkbox" class="rounded" />
                    <span class="text-slate-700">Ingat saya</span>
                </label>
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white rounded-lg py-2.5 font-medium shadow hover:bg-indigo-700">
                Masuk
            </button>

            <p class="text-center text-sm text-slate-600">Belum punya akun? <a href="registrasi.php" class="text-indigo-600 underline">Daftar dulu</a></p>
        </form>

        <div id="msg" class="mt-4 text-sm"></div>
    </main>

    <script>
        const form = document.getElementById('loginForm');
        const msg = document.getElementById('msg');
        const togglePassword = document.getElementById('togglePassword');

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

        function showError(field, text) {
            const p = document.querySelector(`[data-error-for="${field}"]`);
            p.textContent = text;
            p.classList.remove('hidden');
        }

        function clearErrors() {
            document.querySelectorAll('[data-error-for]').forEach(el => {
                el.textContent = '';
                el.classList.add('hidden');
            });
        }

        form.addEventListener('submit', (e) => {

            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;

            let valid = true;

            const emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!valid) return;

            msg.innerHTML = '<p class="text-green-600">Sedang memproses login...</p>';

        });
    </script>
</body>

</html>