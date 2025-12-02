  <script>
    const btn = document.getElementById('profileBtn');
    const drop = document.getElementById('dropdown');

    btn.addEventListener('click', () => {
      drop.classList.toggle('hidden');
    });

    document.addEventListener('click', (e) => {
      if (!btn.contains(e.target) && !drop.contains(e.target)) {
        drop.classList.add('hidden');
      }
    });
  </script>
</body>
</html>