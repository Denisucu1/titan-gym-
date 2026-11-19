/**
 * Script Principal Frontend - Meniu mobil, alerte dismiss, confirmări
 */
document.addEventListener('DOMContentLoaded', () => {
    // 1. Meniu mobil responsive (Nota 5.02)
    const toggle = document.querySelector('.mobile-toggle');
    const links = document.querySelector('.nav-links');
    if (toggle && links) {
        toggle.addEventListener('click', () => links.classList.toggle('show'));
    }

    // 2. Confirmare acțiuni critice (ex: ștergere clasă / anulare rezervare)
    document.querySelectorAll('[data-confirm]').forEach(btn => {
        btn.addEventListener('click', e => {
            if (!confirm(btn.getAttribute('data-confirm') || 'Ești sigur?')) {
                e.preventDefault();
            }
        });
    });

    // 3. Auto-dismiss alerte flash după 5 secunde
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
});
