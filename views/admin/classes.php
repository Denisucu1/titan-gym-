<?php use App\Core\Security; ?>

<div class="container animate-fade-in">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h1 style="font-size: 2rem; font-weight: 800; margin-bottom: 4px;">Gestiune Clase Fitness</h1>
            <p style="color: var(--text-muted);">Adaugă, editează sau șterge clasele din ofertă.</p>
        </div>
        <a href="<?= APP_URL ?>/admin/classes/create" class="btn btn-primary btn-sm">+ Adaugă Clasă Nouă</a>
    </div>

    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Titlu</th>
                    <th>Categorie</th>
                    <th>Antrenor</th>
                    <th>Program</th>
                    <th>Capacitate</th>
                    <th>Tarif</th>
                    <th>Acțiuni</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($classes as $c): ?>
                    <tr>
                        <td>#<?= $c->getId() ?></td>
                        <td><strong><?= Security::escape($c->getTitle()) ?></strong></td>
                        <td><span class="badge badge-primary"><?= Security::escape($c->getCategoryName()) ?></span></td>
                        <td><?= Security::escape($c->getTrainerName()) ?></td>
                        <td><?= Security::escape($c->getScheduleDay()) ?></td>
                        <td><?= $c->getCapacity() ?> pers.</td>
                        <td><strong><?= number_format($c->getPrice(), 2) ?> RON</strong></td>
                        <td>
                            <div style="display: flex; gap: 6px;">
                                <a href="<?= APP_URL ?>/admin/classes/edit/<?= $c->getId() ?>" class="btn btn-outline btn-sm">Editează</a>
                                <form action="<?= APP_URL ?>/admin/classes/delete/<?= $c->getId() ?>" method="POST" style="display:inline;">
                                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                                    <button type="submit" class="btn btn-danger btn-sm" data-confirm="Ștergi clasa?">Șterge</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
