<?php use App\Core\Security; ?>

<div class="container animate-fade-in">
    <div style="margin-bottom: 24px;">
        <h1 style="font-size: 2rem; font-weight: 800; margin-bottom: 6px;">Rezervările Mele</h1>
        <p style="color: var(--text-muted);">Consultă clasele rezervate și statusul participării tale.</p>
    </div>

    <?php if (empty($bookings)): ?>
        <div class="card" style="padding: 35px; text-align: center;">
            <div style="font-size: 2.5rem; margin-bottom: 12px;">📅</div>
            <h3>Nu ai nicio rezervare activă.</h3>
            <p style="color: var(--text-muted); margin: 10px 0 20px 0;">Descoperă programul și rezervă primul tău antrenament!</p>
            <a href="<?= APP_URL ?>/classes" class="btn btn-primary">Vezi Clase Disponibile</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Clasă</th>
                        <th>Data Rezervată</th>
                        <th>Program</th>
                        <th>Durată</th>
                        <th>Status</th>
                        <th>Acțiuni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $b): ?>
                        <tr>
                            <td>#<?= $b['id'] ?></td>
                            <td><strong><?= Security::escape($b['class_title']) ?></strong></td>
                            <td><?= Security::escape($b['booking_date']) ?></td>
                            <td><?= Security::escape($b['schedule_day']) ?></td>
                            <td><?= $b['duration_minutes'] ?> min</td>
                            <td>
                                <?= ($b['status'] === 'confirmed') ? '<span class="badge badge-success">Confirmată</span>' : '<span class="badge badge-warning">Anulată</span>' ?>
                            </td>
                            <td>
                                <?php if ($b['status'] === 'confirmed'): ?>
                                    <form action="<?= APP_URL ?>/bookings/cancel" method="POST" style="display:inline;">
                                        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                                        <input type="hidden" name="booking_id" value="<?= $b['id'] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" data-confirm="Ești sigur că vrei să anulezi rezervarea?">Anulează</button>
                                    </form>
                                <?php else: ?>
                                    <span style="color: var(--text-muted); font-size: 0.85rem;">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
