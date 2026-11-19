<?php use App\Core\Security; ?>
<header class="site-header">
    <div class="container">
        <nav class="navbar">
            <a href="<?= APP_URL ?>/" class="nav-brand">💪 TITAN<span>GYM</span></a>
            <button class="mobile-toggle" aria-label="Meniu">☰</button>

            <ul class="nav-links">
                <li><a href="<?= APP_URL ?>/">Acasă</a></li>
                <li><a href="<?= APP_URL ?>/classes">Clase & Orar</a></li>
                <li><a href="<?= APP_URL ?>/memberships">Abonamente</a></li>
                <?php if ($currentUser): ?>
                    <li><a href="<?= APP_URL ?>/my-bookings">Rezervările Mele</a></li>
                    <?php if (!empty($currentUser['is_admin'])): ?>
                        <li><a href="<?= APP_URL ?>/admin" style="color: var(--primary); font-weight: 700;">★ Admin</a></li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>

            <div class="nav-actions">
                <?php if ($currentUser): ?>
                    <span style="font-size: 0.9rem; color: var(--text-muted);">👤 <?= Security::escape($currentUser['name']) ?></span>
                    <a href="<?= APP_URL ?>/logout" class="btn btn-outline btn-sm">Ieșire</a>
                <?php else: ?>
                    <a href="<?= APP_URL ?>/login" class="btn btn-outline btn-sm">Autentificare</a>
                    <a href="<?= APP_URL ?>/register" class="btn btn-primary btn-sm animate-pulse">Devino Membru</a>
                <?php endif; ?>
            </div>
        </nav>
    </div>
</header>
