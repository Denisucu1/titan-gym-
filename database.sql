CREATE DATABASE IF NOT EXISTS 'gym_db' character set utf8mb4 collate utf8mb4_unicode_ci;
use 'gym_db';

Create table IF NOT EXISTS 'roles' (
    'id' INT AUTO_INCREMENT PRIMARY KEY,
    'name' VARCHAR(50) NOT NULL UNIQUE
    'description' VARCHAR(255) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

Create table if not exists 'users' (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `role_id` INT NOT NULL DEFAULT 2,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(30) NULL,
    `avatar` VARCHAR(255) NULL DEFAULT 'default-avatar.png',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL UNIQUE,
    `description` TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `trainers` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `specialty` VARCHAR(100) NOT NULL,
    `bio` TEXT NULL,
    `photo_url` VARCHAR(255) NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `classes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `category_id` INT NOT NULL,
    `trainer_id` INT NOT NULL,
    `title` VARCHAR(150) NOT NULL,
    `description` TEXT NOT NULL,
    `schedule_day` VARCHAR(50) NOT NULL,
    `duration_minutes` INT NOT NULL DEFAULT 60,
    `capacity` INT NOT NULL DEFAULT 20,
    `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `image_url` VARCHAR(255) NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_classes_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_classes_trainer` FOREIGN KEY (`trainer_id`) REFERENCES `trainers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `memberships` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `price` DECIMAL(10,2) NOT NULL,
    `duration_days` INT NOT NULL,
    `description` TEXT NOT NULL,
    `badge` VARCHAR(50) DEFAULT 'Standard'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `payments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `stripe_payment_id` VARCHAR(100) NOT NULL,
    `amount` DECIMAL(10,2) NOT NULL,
    `currency` VARCHAR(10) NOT NULL DEFAULT 'ron',
    `status` VARCHAR(50) NOT NULL,
    `item_type` VARCHAR(50) NOT NULL,
    `item_id` INT NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_payments_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `subscriptions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `membership_id` INT NOT NULL,
    `payment_id` INT NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE NOT NULL,
    `status` VARCHAR(30) NOT NULL DEFAULT 'active',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_sub_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_sub_membership` FOREIGN KEY (`membership_id`) REFERENCES `memberships` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_sub_payment` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `bookings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `class_id` INT NOT NULL,
    `booking_date` DATE NOT NULL,
    `status` VARCHAR(30) NOT NULL DEFAULT 'confirmed',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_bookings_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_bookings_class` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
    UNIQUE KEY `unique_user_class_date` (`user_id`, `class_id`, `booking_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `activity_logs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NULL,
    `action` VARCHAR(100) NOT NULL,
    `details` TEXT NULL,
    `ip_address` VARCHAR(45) NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_logs_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `roles` (`id`, `name`, `description`) VALUES
(1, 'admin', 'Administrator cu drepturi depline'),
(2, 'client', 'Membru client al sălii')
ON DUPLICATE KEY UPDATE `name`=`name`;

INSERT INTO `users` (`id`, `role_id`, `name`, `email`, `password_hash`, `phone`) VALUES
(1, 1, 'Administrator Titan', 'admin@gym.ro', '$2y$10$wK1bJzIoiuT0m6Wv7dUG8eH8vP.BqV9F29HlhM5zL.3kE1pUu7Gsa', '0700000000'),
(2, 2, 'Ion Popescu', 'ion@gmail.com', '$2y$10$wK1bJzIoiuT0m6Wv7dUG8eH8vP.BqV9F29HlhM5zL.3kE1pUu7Gsa', '0711111111')
ON DUPLICATE KEY UPDATE `email`=`email`;

INSERT INTO `categories` (`id`, `name`, `description`) VALUES
(1, 'CrossFit & Funcțional', 'Antrenamente intense cu greutăți și greutatea corpului'),
(2, 'Yoga & Flexibilitate', 'Relaxare, mobilitate și controlul respirației'),
(3, 'Cardio & Cycling', 'Arderea caloriilor și rezistență cardiovasculară')
ON DUPLICATE KEY UPDATE `name`=`name`;

INSERT INTO `trainers` (`id`, `name`, `specialty`, `bio`, `photo_url`) VALUES
(1, 'Alexandru Radu', 'Master Trainer CrossFit', '10 ani experiență, campion național haltere.', 'trainer-alex.jpg'),
(2, 'Elena Dumitrescu', 'Instructor Certificat Yoga', 'Pasionată de biomecanică și respirație conștientă.', 'trainer-elena.jpg'),
(3, 'Mihai Vasilescu', 'Specialist Cycling & HIIT', 'Energie debordantă și playlist-uri dinamice.', 'trainer-mihai.jpg')
ON DUPLICATE KEY UPDATE `name`=`name`;

INSERT INTO `classes` (`id`, `category_id`, `trainer_id`, `title`, `description`, `schedule_day`, `duration_minutes`, `capacity`, `price`, `image_url`) VALUES
(1, 1, 1, 'CrossFit WOD Power', 'Antrenamentul zilei: forță explozivă, circuite metabolice și anduranță.', 'Luni & Miercuri, 18:30', 60, 15, 45.00, 'class-crossfit.jpg'),
(2, 2, 2, 'Vinyasa Flow Yoga', 'Sesiune dinamică de posturi sincronizate cu respirația.', 'Marți & Joi, 08:30', 60, 20, 35.00, 'class-yoga.jpg'),
(3, 3, 3, 'Spinning Indoor Cycling', 'Pedalare pe ritm intens cu urcări simulate.', 'Vineri, 19:00', 50, 25, 40.00, 'class-cycling.jpg')
ON DUPLICATE KEY UPDATE `title`=`title`;

INSERT INTO `memberships` (`id`, `name`, `price`, `duration_days`, `description`, `badge`) VALUES
(1, 'Abonament Day-Time', 150.00, 30, 'Acces sală de forță în intervalul 07:00 - 16:00.', 'Economic'),
(2, 'Abonament Full Titan', 250.00, 30, 'Acces 24/7 + rezervări clase grup + saună inclusă.', 'Cel mai popular'),
(3, 'Abonament VIP 3 Luni', 650.00, 90, 'Acces total 24/7, clase nelimitate și plan nutrițional.', 'Recomandat')
ON DUPLICATE KEY UPDATE `name`=`name`;