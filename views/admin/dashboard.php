<?php use App\Core\Security; ?>

<div class="container animate-fade-in">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 2rem; font-weight: 800; margin-bottom: 4px;">Panou de Administrare</h1>
            <p style="color: var(--text-muted);">Centrul de control Titan Gym.</p>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="<?= APP_URL ?>/admin/classes/create" class="btn btn-primary btn-sm">+ Adaugă Clasă</a>
            <a href="<?= APP_URL ?>/admin/logs" class="btn btn-outline btn-sm">📜 Jurnal Audit (Logs)</a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 30px;">
        <div class="card" style="padding: 20px;">
            <span style="color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase;">Clase Active</span>
            <div style="font-size: 2rem; font-weight: 800; color: var(--primary); margin-top: 4px;"><?= $classesCount ?></div>
            <a href="<?= APP_URL ?>/admin/classes" style="color: var(--text-muted); font-size: 0.85rem; text-decoration: none;">Gestiune clase &rarr;</a>
        </div>
        <div class="card" style="padding: 20px;">
            <span style="color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase;">Membri Înregistrați</span>
            <div style="font-size: 2rem; font-weight: 800; color: #fff; margin-top: 4px;"><?= $usersCount ?></div>
            <span style="color: var(--text-muted); font-size: 0.85rem;">Admin & Clienți</span>
        </div>
        <div class="card" style="padding: 20px;">
            <span style="color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase;">Total Rezervări</span>
            <div style="font-size: 2rem; font-weight: 800; color: var(--success); margin-top: 4px;"><?= $bookingsCount ?></div>
            <a href="<?= APP_URL ?>/admin/bookings" style="color: var(--text-muted); font-size: 0.85rem; text-decoration: none;">Vezi toate &rarr;</a>
        </div>
        <div class="card" style="padding: 20px;">
            <span style="color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase;">Încasări Stripe</span>
            <div style="font-size: 2rem; font-weight: 800; color: var(--warning); margin-top: 4px;"><?= number_format($totalRevenue, 2) ?> RON</div>
            <span style="color: var(--text-muted); font-size: 0.85rem;">Tranzacții validate</span>
        </div>
    </div>

    <div>
        <h2 style="font-size: 1.4rem; font-weight: 800; margin-bottom: 14px;">Ultimele Rezervări</h2>
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Client</th>
                        <th>Email</th>
                        <th>Clasă</th>
                        <th>Data Rezervării</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recentBookings)): ?>
                        <tr><td colspan="6" style="text-align:center; color: var(--text-muted);">Nicio rezervare înregistrată.</td></tr>
                    <?php else: ?>
                        <?php foreach ($recentBookings as $b): ?>
                            <tr>
                                <td>#<?= $b['id'] ?></td>
                                <td><strong><?= Security::escape($b['user_name']) ?></strong></td>
                                <td><?= Security::escape($b['user_email']) ?></td>
                                <td><?= Security::escape($b['class_title']) ?></td>
                                <td><?= Security::escape($b['booking_date']) ?></td>
                                <td>
                                    <?= ($b['status'] === 'confirmed') ? '<span class="badge badge-success">Confirmată</span>' : '<span class="badge badge-warning">Anulată</span>' ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
