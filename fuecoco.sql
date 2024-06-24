CREATE TABLE `user_lvl` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `level` TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) UNIQUE NOT NULL,
    `pw` VARCHAR(255) NOT NULL,
    `id_level` INT,
    `email` VARCHAR(100) UNIQUE NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`id_level`) REFERENCES `user_lvl`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `reservations` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT,
    `start_date` DATE NOT NULL,
    `end_date` DATE NOT NULL,
    `num_guests` INT NOT NULL,
    `total_price` DECIMAL(10, 2) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `status` VARCHAR(50) DEFAULT 'Pending', 
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `payments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `reservation_id` INT,
    `amount` DECIMAL(10, 2) NOT NULL,
    `payment_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `payment_method` VARCHAR(50) NOT NULL,
    FOREIGN KEY (`reservation_id`) REFERENCES `reservations`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `comments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT,
    `rating` INT CHECK (`rating` >= 1 AND `rating` <= 5),
    `comment` TEXT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `response` TEXT NULL,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `activities` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `activity_name` VARCHAR(100) NOT NULL,
    `activity_description` TEXT NOT NULL,
    `activity_location` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
