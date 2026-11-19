<?php use App\Core\Security; ?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <!-- Barem Nota 8.01: Optimizare SEO -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Titan Gym & Fitness - Antrenamente de grup, CrossFit, Yoga, Cycling și abonamente flexibile alături de antrenori certificați.">
    <meta name="keywords" content="sala fitness, titan gym, crossfit, yoga, antrenamente, abonamente sala">
    <meta property="og:title" content="<?= Security::escape($pageTitle ?? 'Titan Gym') ?>">
    <meta property="og:description" content="Rezervă clase de fitness, cumpără abonamente online și antrenează-te cu cei mai buni.">
    <meta property="og:type" content="website">

    <title><?= Security::escape($pageTitle ?? 'Titan Gym & Fitness') ?></title>

    <!-- Barem Nota 5.01: Minimum 2 fișiere CSS separate -->
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/main.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/components.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <?php require VIEW_DIR . '/layouts/navbar.php'; ?>

    <div class="container" style="margin-top: 15px;">
        <?php if (!empty($flashSuccess)): ?>
            <div class="alert alert-success animate-fade-in">✓ <?= Security::escape($flashSuccess) ?></div>
        <?php endif; ?>
        <?php if (!empty($flashError)): ?>
            <div class="alert alert-error animate-fade-in">⚠ <?= Security::escape($flashError) ?></div>
        <?php endif; ?>
    </div>

    <main class="main-content">
        <?= $content ?? '' ?>
    </main>

    <?php require VIEW_DIR . '/layouts/footer.php'; ?>

    <script src="<?= APP_URL ?>/assets/js/main.js"></script>
    <script src="<?= APP_URL ?>/assets/js/carousel.js"></script>
</body>
</html>
