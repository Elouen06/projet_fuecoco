
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `activities` (
  `id` int(11) NOT NULL,
  `activity_name` varchar(100) NOT NULL,
  `activity_description` text NOT NULL,
  `activity_location` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `blocked_dates` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `blocked_dates` (`id`, `date`) VALUES
(1, '2024-07-25'),
(2, '2024-07-24'),
(3, '2024-07-26');

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `response` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `num_guests` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(50) DEFAULT 'Pending',
  `confirmed` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `reservations` (`id`, `user_id`, `start_date`, `end_date`, `num_guests`, `total_price`, `created_at`, `status`, `confirmed`) VALUES
(51, 5, '2024-07-30', '2024-07-31', 1, '600.00', '2024-07-09 12:03:35', 'Pending', 1),
(56, 5, '2024-07-16', '2024-07-17', 1, '100.00', '2024-07-11 12:24:50', 'Pending', 0);

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `pw` varchar(255) NOT NULL,
  `id_level` int(11) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `reset_token` varchar(255) DEFAULT NULL,
  `confirmation_token` varchar(64) DEFAULT NULL,
  `is_confirmed` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`id`, `username`, `pw`, `id_level`, `email`, `created_at`, `reset_token`, `confirmation_token`, `is_confirmed`) VALUES
(1, 'test', '$2y$10$bu2LUqrIeMZ0N6C8x3jS/.Tq46jxz73kOrJFQRb6zeY2pgW/EyY8q', NULL, 'test@test.com', '2024-06-25 09:14:51', NULL, NULL, 0),
(2, 'super', '$2y$10$pg1s7JgEk1RpceWiGmN6FOYj1x6VVlX0FXxJ2U6gX/hz5RgI/NoCy', NULL, 'super@super.com', '2024-06-25 14:05:52', NULL, NULL, 0),
(3, 'elouen', '$2y$10$T6aaTDPo/SyCdkRbft/cuufbYiV/xFya7pvq43bn3ZwBy6jhhxJem', NULL, 'mercierelouen@gmail.com', '2024-06-26 09:27:22', NULL, NULL, 1),
(5, 'loulou', '$2y$10$uODgxtPHAprtZUN2uDZNo.1jaYSqwMjyMr5QdNzsX22lGGPdstpJO', 2, 'loulougamess@gmail.com', '2024-06-26 12:06:55', NULL, NULL, 1);

CREATE TABLE `user_lvl` (
  `id` int(11) NOT NULL,
  `level` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `user_lvl` (`id`, `level`) VALUES
(1, 'guest'),
(2, 'admin');

ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `blocked_dates`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_level` (`id_level`);

ALTER TABLE `user_lvl`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `blocked_dates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `user_lvl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`id_level`) REFERENCES `user_lvl` (`id`);

SET GLOBAL event_scheduler = ON;

DELIMITER //

CREATE EVENT IF NOT EXISTS clean_up_unconfirmed_reservations
ON SCHEDULE EVERY 15 MINUTE
DO
BEGIN
    DELETE FROM reservations
    WHERE confirmed = 0 AND created_at < NOW() - INTERVAL 15 MINUTE;
END//

DELIMITER ;
