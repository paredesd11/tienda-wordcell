/**
 * Admin Panel — Space Theme JS
 * Handles: animated stars, sidebar active state
 */

/* ============================================================
   1. ANIMATED STARS
   ============================================================ */
(function generateStars() {
    const container = document.getElementById('stars-bg');
    if (!container) return;

    const count = 90;
    for (let i = 0; i < count; i++) {
        const star = document.createElement('div');
        star.className = 'star';

        const size = Math.random() * 2.5 + 0.5; // 0.5px - 3px
        const x = Math.random() * 100;
        const y = Math.random() * 100;
        const dur = (Math.random() * 5 + 3).toFixed(2);   // 3s - 8s
        const delay = (Math.random() * 8).toFixed(2);       // 0s - 8s
        const maxOp = (Math.random() * 0.5 + 0.15).toFixed(2); // 0.15 - 0.65

        star.style.cssText = `
            width:${size}px;
            height:${size}px;
            left:${x}%;
            top:${y}%;
            --dur:${dur}s;
            --delay:-${delay}s;
            --max-opacity:${maxOp};
        `;
        container.appendChild(star);
    }
})();

/* ============================================================
   2. SIDEBAR ACTIVE STATE
   ============================================================ */
(function highlightSidebar() {
    const path = window.location.pathname;
    const navItems = document.querySelectorAll('#adminSidebar .sidebar-nav li[data-page]');

    navItems.forEach(li => {
        const page = li.getAttribute('data-page');
        // Match if the URL contains /admin/<page>
        if (path.includes('/admin/' + page) || path.includes('admin/' + page)) {
            li.classList.add('active');
        }
    });
})();

/* ============================================================
   3. FILE INPUT — Display filename label
   ============================================================ */
document.querySelectorAll('.file-input-wrapper input[type="file"]').forEach(function (input) {
    input.addEventListener('change', function () {
        const label = this.closest('.file-input-wrapper').querySelector('.file-input-label');
        if (label) {
            if (this.files.length > 1) {
                label.textContent = this.files.length + ' archivos seleccionados';
            } else if (this.files.length === 1) {
                label.textContent = this.files[0].name;
            } else {
                label.textContent = 'No se ha seleccionado ningún archivo';
            }
        }
    });
});

/* ============================================================
   4. FLASH MESSAGE AUTO-DISMISS
   ============================================================ */
document.querySelectorAll('.alert').forEach(function (el) {
    setTimeout(function () {
        el.style.transition = 'opacity 0.5s ease';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 500);
    }, 4000);
});
