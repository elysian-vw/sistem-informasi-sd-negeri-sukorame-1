// ── Sidebar Toggle ──────────────────────────────
const sidebar    = document.getElementById('sidebar');
const toggleBtn  = document.getElementById('sidebarToggle');
const menuToggle = document.querySelector('.menu-toggle');

// Buat overlay
const overlay = document.createElement('div');
overlay.classList.add('sidebar-overlay');
overlay.id = 'sidebarOverlay';
document.body.appendChild(overlay);

function openSidebar() {
    sidebar.classList.add('open');
    overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
    if (toggleBtn) toggleBtn.classList.add('active');    // ☰ → X
}

function closeSidebar() {
    sidebar.classList.remove('open');
    overlay.classList.remove('active');
    document.body.style.overflow = '';
    if (toggleBtn) toggleBtn.classList.remove('active'); // X → ☰
}

if (toggleBtn && sidebar) {
    toggleBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
    });
}

if (menuToggle && sidebar) {
    menuToggle.addEventListener('click', function (e) {
        e.stopPropagation();
        sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
    });
}

overlay.addEventListener('click', closeSidebar);

document.querySelectorAll('.sidebar .nav-item').forEach(function (item) {
    item.addEventListener('click', closeSidebar);
});

window.addEventListener('resize', function () {
    if (window.innerWidth > 768) closeSidebar();
});

// ── Auto-close alert ─────────────────────────────
document.querySelectorAll('.alert').forEach(function (alert) {
    setTimeout(function () {
        alert.style.transition = 'opacity 0.5s';
        alert.style.opacity = '0';
        setTimeout(function () { alert.remove(); }, 500);
    }, 4000);
});

// ── Konfirmasi hapus ─────────────────────────────
document.querySelectorAll('[data-confirm]').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
        if (!confirm(btn.dataset.confirm || 'Yakin ingin menghapus?')) {
            e.preventDefault();
        }
    });
});