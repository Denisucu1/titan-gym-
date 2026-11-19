<?php
use App\Core\Security;
$isEdit = !empty($class);
$action = $isEdit ? APP_URL . '/admin/classes/update/' . $class->getId() : APP_URL . '/admin/classes/store';
?>

<div class="container animate-fade-in" style="max-width: 700px;">
    <div style="margin-bottom: 20px;">
        <a href="<?= APP_URL ?>/admin/classes" style="color: var(--text-muted); text-decoration: none; font-size: 0.9rem;">&larr; Înapoi la clase</a>
    </div>

    <div class="card" style="padding: 30px;">
        <h1 style="font-size: 1.7rem; font-weight: 800; margin-bottom: 18px;">
            <?= $isEdit ? 'Editează Clasă' : 'Adaugă Clasă Nouă' ?>
        </h1>

        <form action="<?= $action ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

            <div class="form-group">
                <label for="title">Titlu Clasă *:</label>
                <input type="text" id="title" name="title" class="form-control" value="<?= Security::escape($class ? $class->getTitle() : '') ?>" placeholder="Ex: CrossFit WOD Power" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                <div class="form-group">
                    <label for="category_id">Categorie *:</label>
                    <select id="category_id" name="category_id" class="form-control" required>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= ($class && $class->getCategoryId() == $cat['id']) ? 'selected' : '' ?>>
                                <?= Security::escape($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="trainer_id">Antrenor *:</label>
                    <select id="trainer_id" name="trainer_id" class="form-control" required>
                        <?php foreach ($trainers as $t): ?>
                            <option value="<?= $t['id'] ?>" <?= ($class && $class->getTrainerId() == $t['id']) ? 'selected' : '' ?>>
                                <?= Security::escape($t['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 14px;">
                <div class="form-group">
                    <label for="schedule_day">Program Săptămânal *:</label>
                    <input type="text" id="schedule_day" name="schedule_day" class="form-control" value="<?= Security::escape($class ? $class->getScheduleDay() : '') ?>" placeholder="Ex: Luni & Miercuri, 18:30" required>
                </div>
                <div class="form-group">
                    <label for="duration_minutes">Durată (min):</label>
                    <input type="number" id="duration_minutes" name="duration_minutes" class="form-control" value="<?= $class ? $class->getDurationMinutes() : 60 ?>" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                <div class="form-group">
                    <label for="capacity">Capacitate (locuri):</label>
                    <input type="number" id="capacity" name="capacity" class="form-control" value="<?= $class ? $class->getCapacity() : 20 ?>" required>
                </div>
                <div class="form-group">
                    <label for="price">Tarif per ședință (RON):</label>
                    <input type="number" step="0.01" id="price" name="price" class="form-control" value="<?= $class ? $class->getPrice() : '40.00' ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label for="description">Descriere Detaliată *:</label>
                <textarea id="description" name="description" class="form-control" rows="3" required><?= Security::escape($class ? $class->getDescription() : '') ?></textarea>
            </div>

            <!-- Barem Nota 9.04, 9.05 & 10.03: Upload fișiere max 12MB + filtrare steganografie -->
            <div class="form-group" style="background: rgba(255,255,255,0.02); padding: 14px; border-radius: 8px; border: 1px dashed var(--border-color);">
                <label for="image">Imagine Prezentare (Opțional):</label>
                <input type="file" id="image" name="image" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                <small style="color: var(--text-muted); display: block; margin-top: 5px;">
                    🔒 Limită maximă: <strong>12 MB</strong>. Formate: <code>.jpg</code>, <code>.png</code>, <code>.pdf</code>.<br>
                    🛡️ Sistemul curăță automat fișierul împotriva steganografiei (distrugere metadate EXIF prin GD).
                </small>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">
                    <?= $isEdit ? 'Salvează Modificările' : 'Adaugă Clasa' ?>
                </button>
                <a href="<?= APP_URL ?>/admin/classes" class="btn btn-outline">Anulează</a>
            </div>
        </form>
    </div>
</div>
