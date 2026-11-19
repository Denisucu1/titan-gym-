<?php use App\Core\Security; ?>

<div class="container animate-fade-in">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h1 style="font-size: 2rem; font-weight: 800; margin-bottom: 4px;">Jurnal de Activități (Audit Logs)</h1>
            <p style="color: var(--text-muted);">Barem Nota 7.01: Jurnalizarea acțiunilor în fișierul fizic <code>storage/logs/app.log</code>.</p>
        </div>
        <a href="<?= APP_URL ?>/admin" class="btn btn-outline btn-sm">&larr; Dashboard</a>
    </div>

    <div class="card" style="padding: 20px;">
        <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 0.9rem;">
            <span>Afișare ultimele <?= count($logs) ?> linii de log:</span>
            <span class="badge badge-success">storage/logs/app.log</span>
        </div>

        <div style="background: #0b0e14; border: 1px solid var(--border-color); border-radius: 8px; padding: 16px; font-family: monospace; font-size: 0.85rem; max-height: 500px; overflow-y: auto; line-height: 1.6; color: #a4b0be;">
            <?php if (empty($logs)): ?>
                <div style="text-align: center; color: var(--text-muted); padding: 15px;">Niciun eveniment înregistrat în log până acum.</div>
            <?php else: ?>
                <?php foreach ($logs as $line): ?>
                    <div style="border-bottom: 1px solid rgba(255,255,255,0.03); padding: 3px 0;">
                        <?php if (strpos($line, '[SECURITY_WARNING]') !== false || strpos($line, '[CRITICAL]') !== false): ?>
                            <span style="color: var(--danger); font-weight: bold;"><?= Security::escape($line) ?></span>
                        <?php elseif (strpos($line, '[WARNING]') !== false): ?>
                            <span style="color: var(--warning);"><?= Security::escape($line) ?></span>
                        <?php else: ?>
                            <span style="color: #7bed9f;"><?= Security::escape($line) ?></span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
