<?php use App\Core\Security; ?>

<div class="container animate-fade-in" style="max-width: 580px; text-align: center; padding: 30px 15px;">
    <div class="card" style="padding: 35px;">
        <div style="font-size: 3rem; color: var(--success); margin-bottom: 14px;">✓</div>
        <h1 style="font-size: 1.8rem; font-weight: 800; margin-bottom: 10px;">Plată Efectuată cu Succes!</h1>
        <p style="color: var(--text-muted); margin-bottom: 24px;">
            Abonamentul <strong><?= Security::escape($membership['name']) ?></strong> a fost activat pentru următoarele <?= $membership['duration_days'] ?> de zile.
        </p>

        <div style="background: rgba(255, 255, 255, 0.03); border: 1px solid var(--border-color); border-radius: 8px; padding: 18px; text-align: left; margin-bottom: 25px; font-size: 0.92rem;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                <span style="color: var(--text-muted);">Tranzacție Stripe:</span>
                <code><?= Security::escape($transactionId) ?></code>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                <span style="color: var(--text-muted);">Sumă Încasată:</span>
                <strong><?= number_format($amount, 2) ?> RON</strong>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span style="color: var(--text-muted);">Status:</span>
                <span class="badge badge-success">Confirmat & Jurnalizat</span>
            </div>
        </div>

        <div style="display: flex; gap: 10px; justify-content: center;">
            <a href="<?= APP_URL ?>/classes" class="btn btn-primary">Rezervă o Clasă</a>
            <a href="<?= APP_URL ?>/" class="btn btn-outline">Acasă</a>
        </div>
    </div>
</div>
