    <!-- NAVBAR -->
    <header class="bg-white shadow p-4 flex items-center justify-between">
      <h1 class="text-lg font-semibold">Dashboard Blog</h1>

      <div class="relative" id="profileMenu">
        <button class="flex items-center gap-3" id="profileBtn">
          <img src="/blog_web/public/uploads/<?= e($user['foto']) ?>" class="w-10 h-10 rounded-full" />
          <span class="font-medium"><?= e($user['email']) ?></span>
        </button>

        <div id="dropdown" class="absolute right-0 mt-2 w-40 bg-white border rounded-lg shadow-md hidden">
          <a href="/blog_web/views/auth/profile.php?id=<?= e($user['id']) ?>" class="block px-4 py-2 hover:bg-slate-100">Edit Profil</a>
          <a href="/blog_web/views/auth/logout.php" class="block px-4 py-2 hover:bg-slate-100">Logout</a>
        </div>
      </div>
    </header>