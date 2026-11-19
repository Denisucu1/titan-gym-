<div class="container animate-fade-in" style="max-width: 550px; text-align: center; padding: 50px 15px;">
    <div class="card" style="padding: 35px;">
        <div style="font-size: 3.5rem; color: var(--danger); font-weight: 800;">403</div>
        <h1 style="font-size: 1.6rem; margin: 10px 0;">Acces Interzis</h1>
        <p style="color: var(--text-muted); margin-bottom: 20px;"><?= htmlspecialchars($message ?? 'Nu ai permisiunile necesare pentru a accesa această pagină.') ?></p>
        <a href="<?= APP_URL ?>/" class="btn btn-primary">Acasă</a>
    </div>
</div>
