# 🏋️ Titan Gym & Fitness - Proiect Dezvoltarea Aplicațiilor Web (DAW)

Aplicație web completă pentru gestiunea unei săli de fitness, programări la clase de grup, achiziție de abonamente online prin Stripe și panou de administrare CRUD.

Proiectul a fost dezvoltat respectând **100% criteriile din Baremul de Evaluare DAW**, acoperind toate cerințele de la **Nota 2** până la **Nota 10**.

---

## 📋 Tabel de Corespondență cu Baremul de Evaluare

| Notă | Criteriu din Barem | Unde se regăsește în proiect / Cum este implementat |
| :--- | :--- | :--- |
| **NOTA 2** | **01. Bază de date 3NF** | `database.sql` conține 10 tabele normalizate în 3NF (`roles`, `users`, `categories`, `trainers`, `classes`, `memberships`, `subscriptions`, `bookings`, `payments`, `activity_logs`). Fără redundanță, chei externe (FK) legate cu `ON DELETE CASCADE/RESTRICT`. |
| | **02. Funcționalități principale (min 4)** | **1.** Explorare și filtrare clase după categorie; **2.** Cumpărare abonament cu plată online Stripe; **3.** Rezervare și anulare loc la clasă; **4.** Panou Admin complet (CRUD clase, management rezervări). |
| | **03. Arhitectură software** | Arhitectură curată **MVC** (`app/Controllers`, `views`, `app/Models`) combinată cu **Repository Pattern** (`app/Repositories`). |
| | **04. Conținut și multimedia** | Conține imagini, texte descriptive, orar tabelar săptămânal și tabele de gestiune a rezervărilor. |
| **NOTA 3** | **01. Utilizarea claselor (min 3)** | Peste 15 clase: `User`, `GymClass`, `Booking`, `Database`, `BaseController`, `ClassRepository`, `Security`, etc. |
| | **02. Utilizarea interfețelor (min 1)** | `app/Repositories/RepositoryInterface.php` definește contractul de date și este implementată în `BaseRepository` / `ClassRepository`. |
| | **03. Autentificare** | `AuthController.php` cu hashing `password_hash($pwd, PASSWORD_BCRYPT)` și validare prin `password_verify()`. |
| | **04. Autorizare (drepturi / roluri)** | Control pe roluri: Rolul 1 (`admin`) vs Rolul 2 (`client`). Metodele `requireAuth()` și `requireAdmin()` blochează accesul neautorizat. |
| **NOTA 4** | **01. Moștenire (min 2 clase)** | `BaseController` este moștenit de toate controllerele; `BaseModel` este moștenit de toate modelele; `AppException` moștenește `\Exception`. |
| | **02. Gestionarea excepțiilor (min 2)** | `ValidationException` și `AuthException` (ambele derivate din `AppException`), tratate prin blocuri `try...catch` în router. |
| | **03. Carusel de imagini (slideshow)** | Pe prima pagină (`public/assets/js/carousel.js`), slideshow funcțional cu auto-play la 5s, tranziții, butoane Prev/Next și indicators. |
| | **04. Animație** | Animații CSS (`@keyframes fadeIn`, `@keyframes pulseGlow`, efecte hover cu `transform` și `transition`). |
| **NOTA 5** | **01. Organizarea stilurilor CSS** | Minimum 2 fișiere CSS separate: `public/assets/css/main.css` și `public/assets/css/components.css`, cu peste 25 de proprietăți CSS distincte. |
| | **02. Adaptare diferite dispozitive** | Design responsive (Desktop, Tabletă, Telefon) prin `@media (max-width: 900px)` și `@media (max-width: 768px)`, plus meniu mobil hamburger. |
| **NOTA 6** | **01. Teste unitare (min 5)** | Fișierul `tests/UnitTest.php` conține **6 teste unitare** automate (hashing parolă, CSRF, upload 12MB, CAPTCHA, capacitate clase, roluri). |
| | **02. Versionarea codului (Git)** | Repository Git versionat cu istoric de commit-uri organizate pe branch-ul `main`. |
| | **03. CI/CD (GitHub Actions)** | Fișier `.github/workflows/ci.yml` configurat pentru rularea automată a testelor unitare la fiecare `push` pe GitHub. |
| **NOTA 7** | **01. Logging / Jurnalizare activități** | `app/Core/Logger.php` scrie automat orice acțiune în `storage/logs/app.log`, consultabil în pagina Admin Logs. |
| | **02. Găzduirea aplicației (hosting)** | Directorul `docs/index.html` este pregătit pentru publicare gratuită pe **GitHub Pages** (suficient conform baremului: *"inclusiv publicarea unei singure pagini HTML"*). |
| **NOTA 8** | **01. Optimizare SEO (>= 90/100)** | HTML5 semantic, meta tags complete (description, viewport, OpenGraph), `robots.txt` și `sitemap.xml`. |
| | **02. Sistem de plăți online (Stripe)** | Integrare funcțională a sistemului de plăți Stripe (`PaymentController.php`), cu formular de checkout, confirmare și salvare tranzacție. |
| **NOTA 9** | **01. Protecție CSRF** | Token unic de sesiune generat în `Security::generateCsrfToken()`, verificat la fiecare cerere POST prin `validateCsrf()`. |
| | **02. Protecție XSS** | Toate ieșirile dinamice sunt filtrate prin `Security::escape()` (`htmlspecialchars` cu `ENT_QUOTES`). |
| | **03. Protecție SQL Injection** | Folosire exclusivă a interfeței PDO cu **Prepared Statements** și parametri bind-uiți (`:param`). |
| | **04. Limitarea dimensiunii fișierelor** | Verificare în `Security::handleFileUpload()`: fișierele mai mari de **12 MB** sunt respinse automat. |
| | **05. Restricționarea extensiilor** | Whitelist strict: acceptă doar `.jpg`, `.jpeg`, `.png`, `.pdf` și verifică MIME-type real prin `finfo_file`. |
| **NOTA 10**| **01. Rate limiting (100 cereri / 1 min)**| `app/Core/RateLimiter.php`: dacă un IP depășește 100 de cereri în 60 de secunde, cererile suplimentare sunt blocate cu HTTP 429. |
| | **02. Implementarea CAPTCHA** | Sistem nativ anti-bot (`app/Core/Captcha.php`) ce generează provocări matematice verificate pe sesiune la înregistrare. |
| | **03. Protecție contra steganografiei** | În `Security::neutralizeSteganography()`: imaginea încărcată este decodată ca matrice brută de pixeli și re-generată de la zero via GD, distrugând complet orice date ascunse sau blocuri EXIF malițioase. |
| | **04. Antete de securitate (Calificativ A)**| Configurate atât în `Security::sendSecurityHeaders()`, cât și în `public/.htaccess`: CSP, HSTS, X-Content-Type-Options: nosniff, X-Frame-Options: SAMEORIGIN. |
| | **05. Protejarea prin .htaccess** | Fișiere `.htaccess` în rădăcină, în `public/` și în `storage/`: rescriere URL, `Options -Indexes`, blocare acces direct la fisiere interne (`.log`, `.sqlite`, `.env`). |

---

## 🚀 Cum se rulează proiectul local

### Varianta A: Folosind serverul încorporat din PHP (Fără bătăi de cap)
1. Deschide PowerShell în folderul proiectului (`D:\VS\DAW\Proiect`).
2. Pornește serverul:
   ```powershell
   php -S localhost:8000 -t public
   ```
3. Deschide browserul la adresa: **`http://localhost:8000`**  
*(Baza de date locală SQLite din `storage/database.sqlite` este creată și inițializată automat cu toate datele la prima rulare!)*

### Varianta B: În XAMPP (Apache + MySQL)
1. Copiază folderul `Proiect` în `C:\xampp\htdocs\Proiect`.
2. Pornește **Apache** și **MySQL** din XAMPP Control Panel.
3. Deschide `http://localhost/phpmyadmin`, creează baza de date `gym_db` și importă fișierul `database.sql`.
4. În `config/database.php`, schimbă `'default' => 'mysql'`.
5. Accesează în browser: **`http://localhost/Proiect/public/`**

---

## 🧪 Rularea Testelor Unitare (Barem Nota 6)

Pentru a rula suita de 6 teste unitare:
```powershell
php tests/UnitTest.php
```

---

## 🔑 Credențiale Demo Prestabilite

| Rol | Adresă Email | Parolă | Drepturi de acces |
| :--- | :--- | :--- | :--- |
| **Administrator** | `admin@gym.ro` | `Parola123!` | Acces complet la Panoul Admin, CRUD clase, rezervări clienți, audit logs |
| **Client / Membru** | `ion@gmail.com` | `Parola123!` | Rezervare clase, anulare rezervări, achiziție abonamente Stripe |

---

## 🎓 Ghid Rapid de Răspunsuri pentru Susținerea Orală (Examen)

1. **Ce este arhitectura MVC și cum ai organizat codul?**
   * *Răspuns:* „Am separat codul în 3 componente distincte: **Modelul** (`app/Models`) reprezintă datele, **View-ul** (`views/`) se ocupă exclusiv de prezentarea HTML, iar **Controller-ul** (`app/Controllers`) preia cererile utilizatorului, apelează modelele/repository-urile și returnează view-ul corespunzător. Pentru accesul la baza de date am folosit **Repository Pattern** cu o interfață dedicată (`RepositoryInterface`), izolând complet interogările SQL de logica de prezentare.”

2. **De ce baza de date respectă 3NF (A treia formă normală)?**
   * *Răspuns:* „Respectă 1NF deoarece fiecare coloană conține valori atomice și fiecare tabel are cheie primară unică. Respectă 2NF deoarece toate atributele depind în totalitate de cheia primară. Respectă 3NF deoarece nu există dependențe tranzitive – de exemplu, datele antrenorului nu sunt duplicate în tabelul `classes`, ci sunt izolate în tabelul `trainers` și legate prin cheie externă (`trainer_id`). La fel și categoriile (`category_id`) sau rolurile (`role_id`).”

3. **Ce este Singleton în `Database.php`?**
   * *Răspuns:* „Este un șablon de proiectare care garantează că există o singură conexiune deschisă la baza de date în toată aplicația. Are constructorul privat pentru a împiedica apelarea `new Database()`, iar metoda statică `getConnection()` refolosește instanța existentă, economisind resursele serverului.”

4. **Cum ai asigurat protecția împotriva atacurilor de securitate (Nota 9 & 10)?**
   * **SQL Injection:** „Folosim exclusiv PDO cu Prepared Statements și parametri binduiți (ex: `:email`), ceea ce împiedică interpretarea datelor transmise de utilizator ca instrucțiuni SQL executabile.”
   * **CSRF:** „Fiecare formular conține un token ascuns generat criptografic și salvat în sesiune. La submit pe POST, `validateCsrf()` verifică dacă tokenul trimis corespunde cu cel din sesiune folosind `hash_equals`.”
   * **XSS:** „Toate datele dinamice afișate în HTML sunt trecute prin `Security::escape()`, care apelează `htmlspecialchars($str, ENT_QUOTES, 'UTF-8')`.”
   * **Steganografie:** „Steganografia ascunde informații în metadate sau în biții cel mai puțin importanți (LSB) ai imaginii. La upload, decodăm imaginea într-un stream brut de pixeli și o rescriem de la zero cu biblioteca GD din PHP (`imagecreatefromjpeg`/`imagepng`), eliminând complet metadatele EXIF și neutralizând orice payload ascuns.”
   * **Rate Limiting:** „În `RateLimiter.php` monitorizăm cererile per IP. Dacă un IP face peste 100 de cereri într-un minut, returnăm codul de eroare HTTP 429 Too Many Requests.”
