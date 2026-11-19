<div class="container animate-fade-in" style="max-width: 550px; text-align: center; padding: 50px 15px;">
    <div class="card" style="padding: 35px;">
        <div style="font-size: 3rem; color: var(--danger); margin-bottom: 8px;">⚠️</div>
        <h1 style="font-size: 1.6rem; margin-bottom: 10px;"><?= htmlspecialchars($title ?? 'Notificare') ?></h1>
        <p style="color: var(--text-muted); margin-bottom: 20px;"><?= htmlspecialchars($message ?? 'A apărut o problemă.') ?></p>
        <a href="<?= APP_URL ?>/" class="btn btn-primary">Acasă</a>
    </div>
</div>
