-- RoomRent / Rentivo Full Database Dump (MySQL 8.0 / MariaDB)
-- Contains all tables, indexes, and initial sample data (Admin, Owners, User, Listings, Unlocks, Settings)

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- Table structure for `users`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `otp_code` varchar(255) DEFAULT NULL,
  `otp_expires_at` timestamp NULL DEFAULT NULL,
  `role` enum('admin','owner','user') NOT NULL DEFAULT 'user',
  `phone` varchar(255) DEFAULT NULL,
  `referral_code` varchar(255) DEFAULT NULL,
  `referred_by` bigint(20) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_referral_code_unique` (`referral_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample Users (Password is: password)
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `otp_code`, `otp_expires_at`, `role`, `phone`, `referral_code`, `referred_by`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin User', 'admin@roomrent.com', '2026-08-10 10:00:00', '$2y$12$eC.T5P2zHk9U.e/K1F9/c.rY3B3C3B3C3B3C3B3C3B3C3B3C3B3C3', NULL, NULL, 'admin', '9800000000', 'ADMINREF', NULL, 1, NULL, NOW(), NOW()),
(2, 'Ram Sharma', 'owner@roomrent.com', '2026-08-10 10:00:00', '$2y$12$eC.T5P2zHk9U.e/K1F9/c.rY3B3C3B3C3B3C3B3C3B3C3B3C3B3C3', NULL, NULL, 'owner', '9841000001', 'OWNERREF', NULL, 1, NULL, NOW(), NOW()),
(3, 'Sita Thapa', 'owner2@roomrent.com', '2026-08-10 10:00:00', '$2y$12$eC.T5P2zHk9U.e/K1F9/c.rY3B3C3B3C3B3C3B3C3B3C3B3C3B3C3', NULL, NULL, 'owner', '9841000002', 'OWNER2RF', NULL, 1, NULL, NOW(), NOW()),
(4, 'Test User', 'user@roomrent.com', '2026-08-10 10:00:00', '$2y$12$eC.T5P2zHk9U.e/K1F9/c.rY3B3C3B3C3B3C3B3C3B3C3B3C3B3C3', NULL, NULL, 'user', '9841000003', 'USERREF1', NULL, 1, NULL, NOW(), NOW());

-- --------------------------------------------------------
-- Table structure for `listings`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `listings`;
CREATE TABLE `listings` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `city` varchar(255) NOT NULL,
  `area` varchar(255) NOT NULL,
  `exact_address` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `lat` decimal(10,7) NOT NULL,
  `lng` decimal(10,7) NOT NULL,
  `approx_lat` decimal(10,7) NOT NULL,
  `approx_lng` decimal(10,7) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `unlock_fee` decimal(8,2) NOT NULL DEFAULT 50.00,
  `bedrooms` int(11) NOT NULL DEFAULT 1,
  `bathrooms` int(11) NOT NULL DEFAULT 1,
  `room_type` enum('single','double','apartment','hostel') NOT NULL DEFAULT 'single',
  `amenities` json DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `views` int(11) NOT NULL DEFAULT 0,
  `rejection_reason` text DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `listings_user_id_foreign` (`user_id`),
  CONSTRAINT `listings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample Listings
INSERT INTO `listings` (`id`, `user_id`, `title`, `description`, `price`, `city`, `area`, `exact_address`, `phone`, `lat`, `lng`, `approx_lat`, `approx_lng`, `image_path`, `status`, `unlock_fee`, `bedrooms`, `bathrooms`, `room_type`, `amenities`, `is_available`, `views`, `rejection_reason`, `created_at`, `updated_at`) VALUES
(1, 2, 'Cozy Single Room in Thamel', 'A bright and clean single room located in the heart of Thamel. Close to restaurants, cafes, and public transport.', '8000.00', 'Kathmandu', 'Thamel', 'House No. 23, Thamel Marg, Kathmandu-29', '9841000001', '27.7150000', '85.3123000', '27.7153000', '85.3127000', NULL, 'approved', '50.00', 1, 1, 'single', '["WiFi", "Attached Bathroom", "Hot Water", "Furnished"]', 1, 142, NULL, NOW(), NOW()),
(2, 2, 'Spacious Double Room in Lazimpat', 'Large double room with attached bathroom in a quiet residential area of Lazimpat. Perfect for couples or two friends sharing.', '15000.00', 'Kathmandu', 'Lazimpat', 'Flat 4B, Lazimpat Heights Apartments, Lazimpat-2', '9841000001', '27.7230000', '85.3180000', '27.7233000', '85.3183000', NULL, 'approved', '50.00', 2, 1, 'double', '["WiFi", "Kitchen", "Balcony", "Security", "Parking"]', 1, 87, NULL, NOW(), NOW()),
(3, 3, 'Modern 2BHK Apartment in Baneshwor', 'Fully furnished 2BHK apartment in a prime location of New Baneshwor. Modern kitchen with appliances.', '25000.00', 'Kathmandu', 'Baneshwor', 'Tower C, Sunrise Apartments, New Baneshwor-10', '9841000002', '27.6938000', '85.3384000', '27.6940000', '85.3388000', NULL, 'approved', '75.00', 2, 2, 'apartment', '["WiFi", "Generator", "Parking", "Security", "Lift", "AC"]', 1, 203, NULL, NOW(), NOW());

-- --------------------------------------------------------
-- Table structure for `unlocks`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `unlocks`;
CREATE TABLE `unlocks` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `listing_id` bigint(20) UNSIGNED NOT NULL,
  `amount_paid` decimal(8,2) NOT NULL,
  `payment_method` varchar(255) NOT NULL DEFAULT 'khalti',
  `transaction_id` varchar(255) DEFAULT NULL,
  `khalti_token` varchar(255) DEFAULT NULL,
  `khalti_idx` varchar(255) DEFAULT NULL,
  `payment_status` enum('pending','completed','failed') NOT NULL DEFAULT 'pending',
  `paid_at` timestamp NULL DEFAULT NULL,
  `payment_response` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unlocks_user_id_listing_id_unique` (`user_id`,`listing_id`),
  KEY `unlocks_listing_id_foreign` (`listing_id`),
  CONSTRAINT `unlocks_listing_id_foreign` FOREIGN KEY (`listing_id`) REFERENCES `listings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `unlocks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for `settings`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `group` varchar(255) NOT NULL DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `settings` (`key`, `value`, `group`, `created_at`, `updated_at`) VALUES
('site_name', 'Rentivo', 'general', NOW(), NOW()),
('support_email', 'support@rentivo.rent', 'general', NOW(), NOW()),
('support_phone', '+977 9800000000', 'general', NOW(), NOW()),
('default_unlock_fee', '50', 'general', NOW(), NOW()),
('referral_reward', '25', 'general', NOW(), NOW()),
('khalti_fake_mode', '0', 'payment', NOW(), NOW());

-- --------------------------------------------------------
-- Table structure for `audit_logs`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `audit_logs`;
CREATE TABLE `audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `target_type` varchar(255) DEFAULT NULL,
  `target_id` bigint(20) UNSIGNED DEFAULT NULL,
  `details` json DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audit_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS=1;
COMMIT;
