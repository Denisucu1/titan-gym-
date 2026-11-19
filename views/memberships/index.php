<?php use App\Core\Security; ?>

<div class="container animate-fade-in">
    <div style="text-align: center; max-width: 650px; margin: 0 auto 40px auto;">
        <span class="badge badge-primary" style="margin-bottom: 10px;">Abonamente & Tarife</span>
        <h1 style="font-size: 2.3rem; font-weight: 800; margin-bottom: 10px;">Pachete Membri Titan Gym</h1>
        <p style="color: var(--text-muted);">Alege pachetul potrivit. Plată online 100% securizată prin Stripe cu activare instantanee.</p>
    </div>

    <div class="grid-3">
        <?php foreach ($memberships as $m): ?>
            <div class="card" style="position: relative;">
                <?php if (!empty($m['badge'])): ?>
                    <div style="position: absolute; top: 14px; right: 14px;">
                        <span class="badge badge-primary"><?= Security::escape($m['badge']) ?></span>
                    </div>
                <?php endif; ?>
                <div class="card-body" style="padding: 30px 24px;">
                    <h3 style="font-size: 1.4rem; margin-bottom: 10px;"><?= Security::escape($m['name']) ?></h3>
                    <div style="margin-bottom: 20px;">
                        <span style="font-size: 2.6rem; font-weight: 800; color: #fff;"><?= number_format($m['price'], 0) ?></span>
                        <span style="color: var(--text-muted); font-size: 0.95rem;">RON / <?= $m['duration_days'] ?> zile</span>
                    </div>
                    <p class="card-text" style="border-bottom: 1px solid var(--border-color); padding-bottom: 16px;">
                        <?= Security::escape($m['description']) ?>
                    </p>
                    <ul style="list-style: none; margin-bottom: 24px; color: var(--text-muted); font-size: 0.9rem;">
                        <li style="margin-bottom: 8px;">✓ Acces zonă fitness & forță</li>
                        <li style="margin-bottom: 8px;">✓ Vestiare și dușuri moderne</li>
                        <li style="margin-bottom: 8px;">✓ Rezervare online clase inclusă</li>
                    </ul>
                    <a href="<?= APP_URL ?>/memberships/checkout/<?= $m['id'] ?>" class="btn btn-primary" style="width: 100%;">
                        Plătește prin Stripe &rarr;
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
