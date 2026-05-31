(() => {
  const toggle = document.getElementById('sidebar-toggle');
  const sidebar = document.getElementById('sidebar');
  if (toggle && sidebar) {
    toggle.addEventListener('click', () => {
      sidebar.classList.toggle('hidden');
      sidebar.classList.toggle('flex');
    });
  }

  // "Sil" formlari icin onay
  document.querySelectorAll('form[data-confirm]').forEach((form) => {
    form.addEventListener('submit', (e) => {
      if (!confirm(form.dataset.confirm || 'Emin misiniz?')) {
        e.preventDefault();
      }
    });
  });

  // Medya URL'sini kopyalama
  document.querySelectorAll('[data-copy]').forEach((btn) => {
    btn.addEventListener('click', async () => {
      try {
        await navigator.clipboard.writeText(btn.dataset.copy);
        const orig = btn.textContent;
        btn.textContent = 'Kopyalandı';
        setTimeout(() => (btn.textContent = orig), 1200);
      } catch (e) {
        alert(btn.dataset.copy);
      }
    });
  });
})();
