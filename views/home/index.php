<?php use App\Core\Security; ?>

<div class="container">
    <!-- Barem Nota 4.03: Carusel de imagini funcțional (Slideshow) -->
    <div class="carousel-container animate-fade-in">
        <div class="carousel-slide active" style="background-image: url('https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=1200&q=80');">
            <div class="carousel-overlay">
                <div class="carousel-content">
                    <span class="badge badge-primary" style="margin-bottom: 10px;">Sala Ta De Elită</span>
                    <h2>DEPĂȘEȘTE-ȚI LIMITELE LA TITAN GYM</h2>
                    <p>Echipamente profesionale, antrenamente ghidate și o atmosferă ce inspiră forță.</p>
                    <a href="<?= APP_URL ?>/memberships" class="btn btn-primary animate-pulse">Alege Abonamentul Tău</a>
                </div>
            </div>
        </div>
        <div class="carousel-slide" style="background-image: url('https://images.unsplash.com/photo-1518611012118-696072aa579a?auto=format&fit=crop&w=1200&q=80');">
            <div class="carousel-overlay">
                <div class="carousel-content">
                    <span class="badge badge-success" style="margin-bottom: 10px;">Energie & Comunitate</span>
                    <h2>CLASE DE GRUP CU ANTRENORI CERTIFICAȚI</h2>
                    <p>CrossFit, Yoga, Cycling și Bootcamp. Rezervă-ți locul direct din contul tău online.</p>
                    <a href="<?= APP_URL ?>/classes" class="btn btn-primary">Vezi Toate Clasele</a>
                </div>
            </div>
        </div>
        <div class="carousel-slide" style="background-image: url('https://images.unsplash.com/photo-1571902943202-507ec2618e8f?auto=format&fit=crop&w=1200&q=80');">
            <div class="carousel-overlay">
                <div class="carousel-content">
                    <span class="badge badge-warning" style="margin-bottom: 10px;">Flexibilitate & Siguranță</span>
                    <h2>PLĂȚI ONLINE SECURE PRIN STRIPE</h2>
                    <p>Activare instantanee fără contracte pe termen lung sau taxe ascunse.</p>
                    <a href="<?= APP_URL ?>/memberships" class="btn btn-outline">Consultă Tarifele</a>
                </div>
            </div>
        </div>

        <button class="carousel-btn prev" aria-label="Anterior">&#10094;</button>
        <button class="carousel-btn next" aria-label="Următor">&#10095;</button>

        <div class="carousel-indicators">
            <button class="carousel-dot active"></button>
            <button class="carousel-dot"></button>
            <button class="carousel-dot"></button>
        </div>
    </div>

    <!-- Beneficii -->
    <section style="margin: 40px 0;">
        <div style="text-align: center; margin-bottom: 30px;">
            <h2 style="font-size: 2rem; font-weight: 800;">De ce să alegi Titan Gym?</h2>
            <p style="color: var(--text-muted);">Un mediu complet conceput pentru progres constant și performanță.</p>
        </div>
        <div class="grid-3">
            <div class="card" style="padding: 22px; text-align: center;">
                <div style="font-size: 2.2rem; margin-bottom: 10px;">🏋️</div>
                <h3>Echipamente Profesionale</h3>
                <p style="color: var(--text-muted); font-size: 0.92rem; margin-top: 8px;">Gantere de la 2kg la 60kg, rack-uri olimpice și aparate cu discuri.</p>
            </div>
            <div class="card" style="padding: 22px; text-align: center;">
                <div style="font-size: 2.2rem; margin-bottom: 10px;">📅</div>
                <h3>Rezervare Simplă</h3>
                <p style="color: var(--text-muted); font-size: 0.92rem; margin-top: 8px;">Sistem de programări în timp real cu afișarea locurilor libere.</p>
            </div>
            <div class="card" style="padding: 22px; text-align: center;">
                <div style="font-size: 2.2rem; margin-bottom: 10px;">💳</div>
                <h3>Plăți Online Stripe</h3>
                <p style="color: var(--text-muted); font-size: 0.92rem; margin-top: 8px;">Plătește abonamentul de pe telefon sau PC, 100% sigur și rapid.</p>
            </div>
        </div>
    </section>

    <!-- Barem Nota 2.04: Tabel Orar Săptămânal Clase -->
    <section style="margin: 50px 0;">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 16px;">
            <div>
                <h2 style="font-size: 1.7rem; font-weight: 800;">Orarul Claselor de Fitness</h2>
                <p style="color: var(--text-muted);">Programul săptămânal și locurile disponibile în sală.</p>
            </div>
            <a href="<?= APP_URL ?>/classes" class="btn btn-outline btn-sm">Vezi Toate &rarr;</a>
        </div>

        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Clasă Antrenament</th>
                        <th>Categorie</th>
                        <th>Antrenor</th>
                        <th>Program</th>
                        <th>Durată</th>
                        <th>Disponibilitate</th>
                        <th>Tarif</th>
                        <th>Acțiune</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($allClasses as $c): ?>
                        <tr>
                            <td><strong><?= Security::escape($c->getTitle()) ?></strong></td>
                            <td><span class="badge badge-primary"><?= Security::escape($c->getCategoryName()) ?></span></td>
                            <td><?= Security::escape($c->getTrainerName()) ?></td>
                            <td><?= Security::escape($c->getScheduleDay()) ?></td>
                            <td><?= $c->getDurationMinutes() ?> min</td>
                            <td>
                                <?php if ($c->isFull()): ?>
                                    <span class="badge badge-warning">Complet Ocupat</span>
                                <?php else: ?>
                                    <span class="badge badge-success"><?= $c->getAvailableSpots() ?> locuri libere</span>
                                <?php endif; ?>
                            </td>
                            <td><strong><?= number_format($c->getPrice(), 2) ?> RON</strong></td>
                            <td>
                                <a href="<?= APP_URL ?>/classes/<?= $c->getId() ?>" class="btn btn-primary btn-sm">Rezervă</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- Antrenori -->
    <section style="margin: 50px 0;">
        <div style="text-align: center; margin-bottom: 30px;">
            <h2 style="font-size: 1.8rem; font-weight: 800;">Echipa de Antrenori</h2>
            <p style="color: var(--text-muted);">Ghidaj profesionist pentru rezultate optime.</p>
        </div>
        <div class="grid-3">
            <?php foreach ($trainers as $t): ?>
                <div class="card" style="text-align: center; padding: 22px;">
                    <div style="font-size: 2.5rem; margin-bottom: 8px;">🏆</div>
                    <h3 class="card-title"><?= Security::escape($t['name']) ?></h3>
                    <p style="color: var(--primary); font-weight: 600; font-size: 0.9rem; margin-bottom: 10px;"><?= Security::escape($t['specialty']) ?></p>
                    <p class="card-text"><?= Security::escape($t['bio']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>
