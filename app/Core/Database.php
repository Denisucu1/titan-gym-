<?php
namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    private function __construct() {}

    private function __clone() {}

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $config = require ROOT_DIR . '/config/database.php';
            $default = $config['default'] ?? 'sqlite';

            try {
                if ($default === 'mysql') {
                    $c = $config['connections']['mysql'];
                    $dsn = "mysql:host={$c['host']};port={$c['port']};dbname={$c['database']};charset={$c['charset']}";
                    self::$instance = new PDO($dsn, $c['username'], $c['password'], $c['options']);
                } else {
                    self::initSqlite($config['connections']['sqlite']);
                }
            } catch (PDOException $e) {
                if ($default === 'mysql') {
                    self::initSqlite($config['connections']['sqlite']);
                } else {
                    die("Eroare la conectarea la baza de date: " . $e->getMessage());
                }
            }
        }

        return self::$instance;
    }

    private static function initSqlite(array $c): void
    {
        $dbPath = $c['database'];
        $isNew = !file_exists($dbPath);

        $dir = dirname($dbPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        self::$instance = new PDO("sqlite:" . $dbPath, null, null, $c['options']);
        self::$instance->exec("PRAGMA foreign_keys = ON;"); // Activăm verificarea cheilor externe

        if ($isNew || filesize($dbPath) === 0) {
            self::seedSqlite(self::$instance);
        }
    }

    private static function seedSqlite(PDO $pdo): void
    {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS roles (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL UNIQUE,
                description TEXT
            );

            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                role_id INTEGER NOT NULL DEFAULT 2,
                name TEXT NOT NULL,
                email TEXT NOT NULL UNIQUE,
                password_hash TEXT NOT NULL,
                phone TEXT,
                avatar TEXT DEFAULT 'default-avatar.png',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (role_id) REFERENCES roles(id)
            );

            CREATE TABLE IF NOT EXISTS categories (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL UNIQUE,
                description TEXT
            );

            CREATE TABLE IF NOT EXISTS trainers (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                specialty TEXT NOT NULL,
                bio TEXT,
                photo_url TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE IF NOT EXISTS classes (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                category_id INTEGER NOT NULL,
                trainer_id INTEGER NOT NULL,
                title TEXT NOT NULL,
                description TEXT NOT NULL,
                schedule_day TEXT NOT NULL,
                duration_minutes INTEGER NOT NULL DEFAULT 60,
                capacity INTEGER NOT NULL DEFAULT 20,
                price REAL NOT NULL DEFAULT 0.00,
                image_url TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
                FOREIGN KEY (trainer_id) REFERENCES trainers(id) ON DELETE CASCADE
            );

            CREATE TABLE IF NOT EXISTS memberships (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                price REAL NOT NULL,
                duration_days INTEGER NOT NULL,
                description TEXT NOT NULL,
                badge TEXT DEFAULT 'Standard'
            );

            CREATE TABLE IF NOT EXISTS payments (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id INTEGER NOT NULL,
                stripe_payment_id TEXT NOT NULL,
                amount REAL NOT NULL,
                currency TEXT NOT NULL DEFAULT 'ron',
                status TEXT NOT NULL,
                item_type TEXT NOT NULL,
                item_id INTEGER NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            );

            CREATE TABLE IF NOT EXISTS subscriptions (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id INTEGER NOT NULL,
                membership_id INTEGER NOT NULL,
                payment_id INTEGER,
                start_date DATE NOT NULL,
                end_date DATE NOT NULL,
                status TEXT NOT NULL DEFAULT 'active',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (membership_id) REFERENCES memberships(id) ON DELETE CASCADE
            );

            CREATE TABLE IF NOT EXISTS bookings (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id INTEGER NOT NULL,
                class_id INTEGER NOT NULL,
                booking_date DATE NOT NULL,
                status TEXT NOT NULL DEFAULT 'confirmed',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE,
                UNIQUE (user_id, class_id, booking_date)
            );

            CREATE TABLE IF NOT EXISTS activity_logs (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id INTEGER,
                action TEXT NOT NULL,
                details TEXT,
                ip_address TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );
        ");

        $pdo->exec("
            INSERT INTO roles (id, name, description) VALUES
            (1, 'admin', 'Administrator cu drepturi depline'),
            (2, 'client', 'Membru client');

            INSERT INTO users (id, role_id, name, email, password_hash, phone) VALUES
            (1, 1, 'Administrator Titan', 'admin@gym.ro', '$2y$10\$wK1bJzIoiuT0m6Wv7dUG8eH8vP.BqV9F29HlhM5zL.3kE1pUu7Gsa', '0700000000'),
            (2, 2, 'Ion Popescu', 'ion@gmail.com', '$2y$10\$wK1bJzIoiuT0m6Wv7dUG8eH8vP.BqV9F29HlhM5zL.3kE1pUu7Gsa', '0711111111');

            INSERT INTO categories (id, name, description) VALUES
            (1, 'CrossFit & Funcțional', 'Antrenamente intense cu greutăți și greutatea corpului'),
            (2, 'Yoga & Flexibilitate', 'Relaxare, mobilitate și controlul respirației'),
            (3, 'Cardio & Cycling', 'Arderea caloriilor și rezistență cardiovasculară');

            INSERT INTO trainers (id, name, specialty, bio, photo_url) VALUES
            (1, 'Alexandru Radu', 'Master Trainer CrossFit', '10 ani experiență, campion național haltere.', 'trainer-alex.jpg'),
            (2, 'Elena Dumitrescu', 'Instructor Certificat Yoga', 'Pasionată de biomecanică și respirație conștientă.', 'trainer-elena.jpg'),
            (3, 'Mihai Vasilescu', 'Specialist Cycling & HIIT', 'Energie debordantă și playlist-uri dinamice.', 'trainer-mihai.jpg');

            INSERT INTO classes (id, category_id, trainer_id, title, description, schedule_day, duration_minutes, capacity, price, image_url) VALUES
            (1, 1, 1, 'CrossFit WOD Power', 'Antrenamentul zilei: forță explozivă, circuite metabolice și anduranță.', 'Luni & Miercuri, 18:30', 60, 15, 45.00, 'class-crossfit.jpg'),
            (2, 2, 2, 'Vinyasa Flow Yoga', 'Sesiune dinamică de posturi sincronizate cu respirația.', 'Marți & Joi, 08:30', 60, 20, 35.00, 'class-yoga.jpg'),
            (3, 3, 3, 'Spinning Indoor Cycling', 'Pedalare pe ritm intens cu urcări simulate.', 'Vineri, 19:00', 50, 25, 40.00, 'class-cycling.jpg');

            INSERT INTO memberships (id, name, price, duration_days, description, badge) VALUES
            (1, 'Abonament Day-Time', 150.00, 30, 'Acces sală de forță în intervalul 07:00 - 16:00.', 'Economic'),
            (2, 'Abonament Full Titan', 250.00, 30, 'Acces 24/7 + rezervări clase grup + saună inclusă.', 'Cel mai popular'),
            (3, 'Abonament VIP 3 Luni', 650.00, 90, 'Acces total 24/7, clase nelimitate și plan nutrițional.', 'Recomandat');
        ");
    }
}