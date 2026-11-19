<?php use App\Core\Security; ?>

<div class="container animate-fade-in" style="max-width: 850px;">
    <div style="margin-bottom: 18px;">
        <a href="<?= APP_URL ?>/classes" style="color: var(--text-muted); text-decoration: none; font-size: 0.9rem;">&larr; Înapoi la lista de clase</a>
    </div>

    <div class="card">
        <img src="https://images.unsplash.com/photo-1549060279-7e168fcee0c2?auto=format&fit=crop&w=900&q=80" alt="<?= Security::escape($class->getTitle()) ?>" style="width: 100%; height: 320px; object-fit: cover;">
        <div class="card-body" style="padding: 30px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
                <span class="badge badge-primary"><?= Security::escape($class->getCategoryName()) ?></span>
                <span style="font-size: 1.4rem; font-weight: 800; color: var(--primary);"><?= number_format($class->getPrice(), 2) ?> RON</span>
            </div>

            <h1 style="font-size: 2rem; font-weight: 800; margin-bottom: 12px;"><?= Security::escape($class->getTitle()) ?></h1>
            <p style="font-size: 1.05rem; color: var(--text-muted); margin-bottom: 22px;"><?= Security::escape($class->getDescription()) ?></p>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; padding: 18px; background: rgba(255, 255, 255, 0.03); border-radius: var(--border-radius); margin-bottom: 25px;">
                <div>
                    <span style="color: var(--text-muted); font-size: 0.8rem;">Antrenor</span>
                    <h4 style="margin-top: 3px;"><?= Security::escape($class->getTrainerName()) ?></h4>
                </div>
                <div>
                    <span style="color: var(--text-muted); font-size: 0.8rem;">Program</span>
                    <h4 style="margin-top: 3px;"><?= Security::escape($class->getScheduleDay()) ?></h4>
                </div>
                <div>
                    <span style="color: var(--text-muted); font-size: 0.8rem;">Durată</span>
                    <h4 style="margin-top: 3px;"><?= $class->getDurationMinutes() ?> Minute</h4>
                </div>
                <div>
                    <span style="color: var(--text-muted); font-size: 0.8rem;">Disponibilitate</span>
                    <h4 style="margin-top: 3px;">
                        <?= $class->isFull() ? '<span style="color:var(--danger)">Complet</span>' : '<span style="color:var(--success)">' . $class->getAvailableSpots() . ' locuri libere</span>' ?>
                    </h4>
                </div>
            </div>

            <!-- Formular Rezervare (Scenariul 3 & CSRF) -->
            <div style="border-top: 1px solid var(--border-color); padding-top: 20px;">
                <h3>Rezervă un loc la acest antrenament</h3>
                <p style="color: var(--text-muted); margin-bottom: 16px;">Selectează data dorită.</p>

                <?php if ($currentUser): ?>
                    <?php if ($class->isFull()): ?>
                        <div class="alert alert-error">Toate locurile sunt ocupate pentru această clasă!</div>
                    <?php else: ?>
                        <form action="<?= APP_URL ?>/bookings" method="POST" style="max-width: 380px;">
                            <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                            <input type="hidden" name="class_id" value="<?= $class->getId() ?>">

                            <div class="form-group">
                                <label for="booking_date">Data participării:</label>
                                <input type="date" id="booking_date" name="booking_date" class="form-control" min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d') ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary animate-pulse" style="width: 100%;">Confirmă Rezervarea</button>
                        </form>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="alert alert-error">Trebuie să fii autentificat pentru a rezerva un loc.</div>
                    <a href="<?= APP_URL ?>/login" class="btn btn-primary">Autentifică-te pentru a rezerva</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
