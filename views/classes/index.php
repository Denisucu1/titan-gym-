<?php use App\Core\Security; ?>

<div class="container animate-fade-in">
    <div style="margin-bottom: 24px;">
        <h1 style="font-size: 2.2rem; font-weight: 800; margin-bottom: 6px;">Clase & Antrenamente de Grup</h1>
        <p style="color: var(--text-muted);">Alege disciplina preferată și rezervă-ți locul la următoarea sesiune.</p>
    </div>

    <!-- Filtre categorii -->
    <div style="display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 25px;">
        <a href="<?= APP_URL ?>/classes" class="btn <?= empty($selectedCategory) ? 'btn-primary' : 'btn-outline' ?> btn-sm">Toate Categoriile</a>
        <?php foreach ($categories as $cat): ?>
            <a href="<?= APP_URL ?>/classes?category=<?= $cat['id'] ?>" class="btn <?= ($selectedCategory == $cat['id']) ? 'btn-primary' : 'btn-outline' ?> btn-sm">
                <?= Security::escape($cat['name']) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="grid-3">
        <?php foreach ($classes as $c): ?>
            <div class="card">
                <img src="https://images.unsplash.com/photo-1549060279-7e168fcee0c2?auto=format&fit=crop&w=600&q=80" alt="<?= Security::escape($c->getTitle()) ?>" class="card-img">
                <div class="card-body">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span class="badge badge-primary"><?= Security::escape($c->getCategoryName()) ?></span>
                        <span style="font-size: 0.85rem; color: var(--text-muted);">⏱ <?= $c->getDurationMinutes() ?> min</span>
                    </div>
                    <h3 class="card-title"><?= Security::escape($c->getTitle()) ?></h3>
                    <p class="card-text"><?= Security::escape($c->getDescription()) ?></p>
                    <p style="font-size: 0.88rem; color: var(--text-muted); margin-bottom: 14px;">
                        👤 Antrenor: <strong><?= Security::escape($c->getTrainerName()) ?></strong><br>
                        📅 Program: <strong><?= Security::escape($c->getScheduleDay()) ?></strong>
                    </p>
                    <div class="card-footer">
                        <div>
                            <span style="font-size: 1.25rem; font-weight: 800;"><?= number_format($c->getPrice(), 2) ?> RON</span>
                            <div style="font-size: 0.78rem; color: var(--text-muted);"><?= $c->getAvailableSpots() ?> locuri libere</div>
                        </div>
                        <a href="<?= APP_URL ?>/classes/<?= $c->getId() ?>" class="btn btn-primary btn-sm">Detalii & Rezervă</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
