<?php use App\Core\Security; ?>

<div class="container animate-fade-in">
    <div class="form-card">
        <div style="text-align: center; margin-bottom: 24px;">
            <span style="font-size: 2.2rem;">📝</span>
            <h1 style="font-size: 1.7rem; font-weight: 800; margin-top: 8px;">Creează Cont Titan Gym</h1>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Devino membru pentru a rezerva clase și cumpăra abonamente</p>
        </div>

        <form action="<?= APP_URL ?>/register" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

            <div class="form-group">
                <label for="name">Nume Complet:</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Ion Popescu" required>
            </div>

            <div class="form-group">
                <label for="email">Adresă Email:</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="ion@gmail.com" required>
            </div>

            <div class="form-group">
                <label for="phone">Telefon:</label>
                <input type="tel" id="phone" name="phone" class="form-control" placeholder="07xxxxxxxx">
            </div>

            <div class="form-group">
                <label for="password">Parolă (min. 6 caractere):</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>

            <div class="form-group">
                <label for="password_confirm">Confirmare Parolă:</label>
                <input type="password" id="password_confirm" name="password_confirm" class="form-control" placeholder="••••••••" required>
            </div>

            <!-- Barem Nota 10.02: CAPTCHA -->
            <div class="form-group">
                <label for="captcha">Verificare Anti-Robot (CAPTCHA):</label>
                <div class="captcha-box">
                    <span>🤖</span>
                    <span class="captcha-question"><?= Security::escape($captchaQuestion) ?></span>
                </div>
                <input type="text" id="captcha" name="captcha" class="form-control" placeholder="Introdu rezultatul numeric de mai sus" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 8px;">Creează Contul</button>
        </form>

        <div style="margin-top: 20px; text-align: center; border-top: 1px solid var(--border-color); padding-top: 16px; font-size: 0.88rem; color: var(--text-muted);">
            Ai deja un cont? <a href="<?= APP_URL ?>/login" style="color: var(--primary); text-decoration: none; font-weight: 600;">Autentifică-te</a>
        </div>
    </div>
</div>
