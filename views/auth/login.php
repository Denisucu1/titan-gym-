<?php use App\Core\Security; ?>

<div class="container animate-fade-in">
    <div class="form-card">
        <div style="text-align: center; margin-bottom: 24px;">
            <span style="font-size: 2.2rem;">🔐</span>
            <h1 style="font-size: 1.7rem; font-weight: 800; margin-top: 8px;">Autentificare Membru</h1>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Introdu datele contului pentru a continua</p>
        </div>

        <form action="<?= APP_URL ?>/login" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

            <div class="form-group">
                <label for="email">Adresă de Email:</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="admin@gym.ro sau ion@gmail.com" required>
            </div>

            <div class="form-group">
                <label for="password">Parolă:</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 8px;">Conectare</button>
        </form>

        <div style="margin-top: 20px; text-align: center; border-top: 1px solid var(--border-color); padding-top: 16px; font-size: 0.88rem; color: var(--text-muted);">
            Nu ai cont? <a href="<?= APP_URL ?>/register" style="color: var(--primary); text-decoration: none; font-weight: 600;">Înregistrează-te</a>
        </div>

        <div style="margin-top: 18px; padding: 10px; background: rgba(255,255,255,0.03); border-radius: 8px; font-size: 0.8rem; color: var(--text-muted);">
            <strong>Conturi demo:</strong><br>
            • Admin: <code>admin@gym.ro</code> / <code>Parola123!</code><br>
            • Client: <code>ion@gmail.com</code> / <code>Parola123!</code>
        </div>
    </div>
</div>
