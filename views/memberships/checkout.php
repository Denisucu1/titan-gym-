<?php use App\Core\Security; ?>

<div class="container animate-fade-in" style="max-width: 600px;">
    <div style="margin-bottom: 20px;">
        <a href="<?= APP_URL ?>/memberships" style="color: var(--text-muted); text-decoration: none; font-size: 0.9rem;">&larr; Înapoi la abonamente</a>
    </div>

    <div class="card" style="padding: 30px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 16px;">
            <div>
                <span class="badge badge-primary" style="margin-bottom: 6px;">Checkout Stripe</span>
                <h2 style="font-size: 1.7rem; font-weight: 800;"><?= Security::escape($membership['name']) ?></h2>
                <p style="color: var(--text-muted);">Valabilitate: <?= $membership['duration_days'] ?> zile</p>
            </div>
            <span style="font-size: 1.8rem; font-weight: 800; color: var(--primary);">
                <?= number_format($membership['price'], 2) ?> RON
            </span>
        </div>

        <div style="display: flex; align-items: center; gap: 10px; background: rgba(255, 71, 87, 0.08); padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 0.88rem;">
            <span>💳</span>
            <div><strong>Mod Test Stripe:</strong> Folosește cardul demo implicit <code>4242 •••• •••• 4242</code>.</div>
        </div>

        <form action="<?= APP_URL ?>/memberships/process" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
            <input type="hidden" name="membership_id" value="<?= $membership['id'] ?>">

            <div class="form-group">
                <label for="card_holder">Nume Titular Card:</label>
                <input type="text" id="card_holder" name="card_holder" class="form-control" value="<?= Security::escape($currentUser['name'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="card_number">Număr Card (Stripe Test):</label>
                <input type="text" id="card_number" name="card_number" class="form-control" value="4242 4242 4242 4242" maxlength="19" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                <div class="form-group">
                    <label for="card_expiry">Expirare (LL/AA):</label>
                    <input type="text" id="card_expiry" name="card_expiry" class="form-control" value="12/28" required>
                </div>
                <div class="form-group">
                    <label for="card_cvc">CVC:</label>
                    <input type="text" id="card_cvc" name="card_cvc" class="form-control" value="123" maxlength="4" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary animate-pulse" style="width: 100%; padding: 12px; font-size: 1.05rem; margin-top: 8px;">
                Plătește <?= number_format($membership['price'], 2) ?> RON prin Stripe
            </button>
        </form>
    </div>
</div>
