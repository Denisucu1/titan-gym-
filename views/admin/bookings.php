<?php use App\Core\Security; ?>

<div class="container animate-fade-in">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h1 style="font-size: 2rem; font-weight: 800; margin-bottom: 4px;">Toate Rezervările</h1>
            <p style="color: var(--text-muted);">Monitorizare prezențe și rezervări în sală.</p>
        </div>
        <a href="<?= APP_URL ?>/admin" class="btn btn-outline btn-sm">&larr; Dashboard</a>
    </div>

    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Client</th>
                    <th>Email</th>
                    <th>Clasă</th>
                    <th>Data</th>
                    <th>Program</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($bookings)): ?>
                    <tr><td colspan="7" style="text-align:center; color:var(--text-muted);">Nicio rezervare înregistrată.</td></tr>
                <?php else: ?>
                    <?php foreach ($bookings as $b): ?>
                        <tr>
                            <td>#<?= $b['id'] ?></td>
                            <td><strong><?= Security::escape($b['user_name']) ?></strong></td>
                            <td><?= Security::escape($b['user_email']) ?></td>
                            <td><?= Security::escape($b['class_title']) ?></td>
                            <td><?= Security::escape($b['booking_date']) ?></td>
                            <td><?= Security::escape($b['schedule_day']) ?></td>
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
