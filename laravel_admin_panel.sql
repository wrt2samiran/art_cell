-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 25, 2020 at 11:56 PM
-- Server version: 5.7.31-0ubuntu0.18.04.1
-- PHP Version: 7.2.24-0ubuntu0.18.04.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laravel_admin_panel`
--

-- --------------------------------------------------------

--
-- Table structure for table `ia_modules`
--

CREATE TABLE `ia_modules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `module_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `module_description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('A','I','D') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'A-active, I-Inactive, D-Delete',
  `is_deleted` enum('Y','N') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N' COMMENT 'Y-yes, N-no',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) NOT NULL,
  `updated_by` bigint(20) NOT NULL DEFAULT '0',
  `deleted_by` bigint(20) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ia_modules`
--

INSERT INTO `ia_modules` (`id`, `parent_id`, `module_name`, `module_description`, `slug`, `status`, `is_deleted`, `created_at`, `updated_at`, `created_by`, `updated_by`, `deleted_by`, `deleted_at`) VALUES
(1, 0, 'Access Control', 'Access Control Description', 'access.control', 'A', 'N', '2020-07-31 00:39:15', '2020-07-31 09:30:39', 1, 1, 0, NULL),
(2, 0, 'User Management', 'User Management List', 'user.management', 'A', 'N', '2020-07-31 00:51:48', '2020-07-31 00:51:48', 1, 1, 0, NULL),
(3, 0, 'Subscription Management', 'Subscription Management Description.', 'subscription.management', 'A', 'Y', '2020-07-31 00:54:06', '2020-09-25 11:47:49', 1, 1, 1, '2020-09-25 11:47:49');

-- --------------------------------------------------------

--
-- Table structure for table `ia_module_functionalities`
--

CREATE TABLE `ia_module_functionalities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `module_id` bigint(20) UNSIGNED NOT NULL,
  `function_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `function_description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT '1',
  `status` enum('A','I','D') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'A-active,I-inactive,D-delete',
  `is_deleted` enum('Y','N') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N' COMMENT 'Y-yes,N-no',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) NOT NULL,
  `updated_by` bigint(20) NOT NULL DEFAULT '0',
  `deleted_by` bigint(20) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ia_module_functionalities`
--

INSERT INTO `ia_module_functionalities` (`id`, `module_id`, `function_name`, `function_description`, `slug`, `sort_order`, `status`, `is_deleted`, `created_at`, `updated_at`, `created_by`, `updated_by`, `deleted_by`, `deleted_at`) VALUES
(1, 1, 'Module List Access', 'Module List Access Description', 'module-management.module.list', 1, 'A', 'N', '2020-07-31 00:42:10', '2020-07-31 00:42:10', 1, 1, 0, NULL),
(2, 1, 'Module Create', 'Module Create Description', 'module-management.module.add', 1, 'A', 'N', '2020-07-31 00:44:15', '2020-07-31 00:44:15', 1, 1, 0, NULL),
(3, 1, 'Module Update', 'Module Update Description', 'module-management.edit', 1, 'A', 'N', '2020-07-31 00:45:14', '2020-07-31 00:45:14', 1, 1, 0, NULL),
(4, 1, 'Functionality List', 'Functionality List Description.', 'module-management.functionality.list', 1, 'A', 'N', '2020-07-31 00:47:25', '2020-07-31 00:47:25', 1, 1, 0, NULL),
(5, 2, 'User List Access', 'User List Access Description.', 'user-management.user.list', 1, 'A', 'N', '2020-07-31 00:53:04', '2020-07-31 00:53:04', 1, 1, 0, NULL),
(6, 3, 'Subscription List Access', 'Subscription List  Access Description', 'subscription-management.list', 1, 'A', 'N', '2020-07-31 00:55:24', '2020-07-31 05:36:00', 1, 1, 0, NULL),
(7, 1, 'Functionality Create', 'Functionality Create Description', 'module-management.function.add', 1, 'A', 'N', '2020-07-31 01:49:16', '2020-07-31 01:52:01', 1, 1, 0, NULL),
(8, 1, 'Functionality Update', 'Functionality Update Description', 'module-management.functionality-edit', 1, 'A', 'N', '2020-07-31 01:54:46', '2020-07-31 01:55:35', 1, 1, 0, NULL),
(9, 2, 'User Create', 'User Create Description', 'user-management.user.add', 1, 'A', 'N', '2020-07-31 01:57:04', '2020-07-31 01:57:04', 1, 1, 0, NULL),
(10, 2, 'User Update', 'User Update Description', 'user-management.user-edit', 1, 'A', 'N', '2020-07-31 01:59:58', '2020-07-31 01:59:58', 1, 1, 0, NULL),
(11, 3, 'Subscription Create', 'Subscription Create Description', 'subscription-management.subscription.add', 1, 'A', 'N', '2020-07-31 02:02:31', '2020-07-31 02:02:31', 1, 1, 0, NULL),
(12, 3, 'Subscription Update', 'Subscription Update Description', 'subscription-management.edit', 1, 'A', 'N', '2020-07-31 02:04:46', '2020-07-31 02:04:46', 1, 1, 0, NULL),
(13, 1, 'Module Status', 'Module Status Description', 'module-management.reset-module-status', 1, 'A', 'N', '2020-07-31 10:30:26', '2020-07-31 10:30:26', 1, 1, 0, NULL),
(14, 1, 'Module Delete', 'Module Delete Description', 'module-management.module-delete', 1, 'A', 'N', '2020-08-02 23:26:19', '2020-08-02 23:26:52', 1, 1, 0, NULL),
(15, 1, 'Functionality Delete', 'Functionality Delete Description', 'module-management.function-delete', 1, 'A', 'N', '2020-08-02 23:29:09', '2020-08-02 23:29:09', 1, 1, 0, NULL),
(16, 1, 'Functionality Status', 'Functionality  Status Description', 'module-management.reset-function-status', 1, 'A', 'N', '2020-08-02 23:31:17', '2020-08-02 23:31:17', 1, 1, 0, NULL),
(17, 2, 'User Delete', 'User Delete Description', 'user-management.user-delete', 1, 'A', 'N', '2020-08-02 23:33:37', '2020-08-02 23:33:37', 1, 1, 0, NULL),
(18, 2, 'User Status', 'User Status Description', 'user-management.reset-user-status', 1, 'A', 'N', '2020-08-02 23:35:28', '2020-08-02 23:35:28', 1, 1, 0, NULL),
(19, 3, 'Subscription Delete', 'Subscription Delete Description', 'subscription-management.delete', 1, 'A', 'N', '2020-08-02 23:37:55', '2020-08-02 23:37:55', 1, 1, 0, NULL),
(20, 3, 'Subscription Status', 'Subscription Status Description', 'subscription-management.reset-subscribe-status', 1, 'A', 'N', '2020-08-02 23:44:06', '2020-08-02 23:44:06', 1, 1, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ia_roles`
--

CREATE TABLE `ia_roles` (
  `id` bigint(20) NOT NULL,
  `role_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('A','I','D') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'A-active,I-inactive,D-delete',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) NOT NULL,
  `updated_by` bigint(20) NOT NULL,
  `is_deleted` enum('Y','N') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N' COMMENT 'Y-yes,N-no',
  `deleted_by` bigint(20) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ia_roles`
--

INSERT INTO `ia_roles` (`id`, `role_name`, `role_description`, `status`, `created_at`, `updated_at`, `created_by`, `updated_by`, `is_deleted`, `deleted_by`, `deleted_at`) VALUES
(1, 'Super admin', 'Super admin Description.', 'A', '2020-07-21 13:10:38', '2020-07-22 02:43:52', 1, 1, 'N', NULL, NULL),
(5, 'Sub Admin', 'Sub Admin Description.', 'A', '2020-09-25 09:27:52', '2020-09-25 09:27:52', 1, 1, 'N', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ia_role_permissions`
--

CREATE TABLE `ia_role_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `module_id` bigint(20) UNSIGNED NOT NULL,
  `module_functionality_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `status` enum('A','I','D') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'A-active,I-inactive,D-delete',
  `is_deleted` enum('Y','N') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N' COMMENT 'Y-yes,N-no',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) NOT NULL,
  `updated_by` bigint(20) NOT NULL DEFAULT '0',
  `deleted_by` bigint(20) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ia_role_permissions`
--

INSERT INTO `ia_role_permissions` (`id`, `role_id`, `module_id`, `module_functionality_id`, `status`, `is_deleted`, `created_at`, `updated_at`, `created_by`, `updated_by`, `deleted_by`, `deleted_at`) VALUES
(1, 5, 1, 1, 'A', 'N', '2020-09-25 09:27:52', '2020-09-25 09:28:17', 1, 1, 0, NULL),
(2, 5, 1, 2, 'A', 'N', '2020-09-25 09:27:53', '2020-09-25 09:28:17', 1, 1, 0, NULL),
(3, 5, 1, 3, 'A', 'N', '2020-09-25 09:27:53', '2020-09-25 09:28:17', 1, 1, 0, NULL),
(4, 5, 1, 4, 'A', 'N', '2020-09-25 09:27:53', '2020-09-25 09:28:17', 1, 1, 0, NULL),
(5, 5, 2, 5, 'A', 'N', '2020-09-25 09:27:53', '2020-09-25 09:28:17', 1, 1, 0, NULL),
(6, 5, 3, 6, 'A', 'N', '2020-09-25 09:27:53', '2020-09-25 09:28:17', 1, 1, 0, NULL),
(7, 5, 1, 7, 'A', 'N', '2020-09-25 09:27:53', '2020-09-25 09:28:17', 1, 1, 0, NULL),
(8, 5, 1, 8, 'A', 'N', '2020-09-25 09:27:53', '2020-09-25 09:28:17', 1, 1, 0, NULL),
(9, 5, 2, 9, 'A', 'N', '2020-09-25 09:27:53', '2020-09-25 09:28:17', 1, 1, 0, NULL),
(10, 5, 2, 10, 'A', 'N', '2020-09-25 09:27:53', '2020-09-25 09:28:17', 1, 1, 0, NULL),
(11, 5, 3, 11, 'A', 'N', '2020-09-25 09:27:53', '2020-09-25 09:28:17', 1, 1, 0, NULL),
(12, 5, 3, 12, 'A', 'N', '2020-09-25 09:27:53', '2020-09-25 09:28:17', 1, 1, 0, NULL),
(13, 5, 1, 13, 'A', 'N', '2020-09-25 09:27:53', '2020-09-25 09:28:18', 1, 1, 0, NULL),
(14, 5, 1, 14, 'A', 'N', '2020-09-25 09:27:53', '2020-09-25 09:28:18', 1, 1, 0, NULL),
(15, 5, 1, 15, 'A', 'N', '2020-09-25 09:27:53', '2020-09-25 09:28:18', 1, 1, 0, NULL),
(16, 5, 1, 16, 'A', 'N', '2020-09-25 09:27:53', '2020-09-25 09:28:18', 1, 1, 0, NULL),
(17, 5, 2, 17, 'A', 'N', '2020-09-25 09:27:53', '2020-09-25 09:28:18', 1, 1, 0, NULL),
(18, 5, 2, 18, 'A', 'N', '2020-09-25 09:27:53', '2020-09-25 09:28:18', 1, 1, 0, NULL),
(19, 5, 3, 19, 'A', 'N', '2020-09-25 09:27:54', '2020-09-25 09:28:18', 1, 1, 0, NULL),
(20, 5, 3, 20, 'A', 'N', '2020-09-25 09:27:54', '2020-09-25 09:28:18', 1, 1, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ia_timezones`
--

CREATE TABLE `ia_timezones` (
  `id` int(11) NOT NULL,
  `country_id` int(11) DEFAULT NULL COMMENT 'Id of country table',
  `tz_name` varchar(255) DEFAULT NULL,
  `current_utc_offset` varchar(150) DEFAULT NULL,
  `status` enum('A','I') NOT NULL DEFAULT 'A' COMMENT 'A => Active,  I => Inactive',
  `priority` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ia_timezones`
--

INSERT INTO `ia_timezones` (`id`, `country_id`, `tz_name`, `current_utc_offset`, `status`, `priority`, `created_at`, `updated_at`) VALUES
(1, 1, 'Asia/Kabul', '+04:30', 'A', 1, '2019-12-26 15:37:58', '2019-12-27 12:07:10'),
(2, 2, 'Europe/Tirane', '+01:00', 'A', 1, '2019-12-26 15:37:58', '2019-12-27 11:46:17'),
(3, 3, 'Africa/Algiers', '+01:00', 'A', 1, '2019-12-26 15:37:58', '2019-12-27 11:46:17'),
(4, 4, 'Pacific/Pago_Pago', '-11:00', 'A', 1, '2019-12-26 15:37:58', '2019-12-27 11:46:17'),
(5, 5, 'Europe/Andorra', '+01:00', 'A', 1, '2019-12-26 15:37:58', '2019-12-27 11:46:17'),
(6, 6, 'Africa/Luanda', '+01:00', 'A', 1, '2019-12-26 15:37:58', '2019-12-27 11:46:18'),
(7, 7, 'America/Anguilla', '-04:00', 'A', 1, '2019-12-26 15:37:58', '2019-12-27 11:46:18'),
(8, 8, 'Antarctica/Casey', '+08:00', 'A', 1, '2019-12-26 15:37:58', '2019-12-27 11:46:18'),
(9, 8, 'Antarctica/Davis', '+07:00', 'A', 1, '2019-12-26 15:37:58', '2019-12-27 11:46:18'),
(10, 8, 'Antarctica/DumontDUrville', '+10:00', 'A', 1, '2019-12-26 15:37:58', '2019-12-27 11:46:18'),
(11, 8, 'Antarctica/Mawson', '+05:00', 'A', 1, '2019-12-26 15:37:58', '2019-12-27 11:46:18'),
(12, 8, 'Antarctica/McMurdo', '+13:00', 'A', 1, '2019-12-26 15:37:58', '2019-12-27 11:46:18'),
(13, 8, 'Antarctica/Palmer', '-03:00', 'A', 1, '2019-12-26 15:37:58', '2019-12-27 11:46:18'),
(14, 8, 'Antarctica/Rothera', '-03:00', 'A', 1, '2019-12-26 15:37:58', '2019-12-27 11:46:18'),
(15, 8, 'Antarctica/Syowa', '+03:00', 'A', 1, '2019-12-26 15:37:58', '2019-12-27 11:46:18'),
(16, 8, 'Antarctica/Troll', 'UTC', 'A', 1, '2019-12-26 15:37:58', '2019-12-27 11:46:18'),
(17, 8, 'Antarctica/Vostok', '+06:00', 'A', 1, '2019-12-26 15:37:58', '2019-12-27 11:46:18'),
(18, 9, 'America/Antigua', '-04:00', 'A', 1, '2019-12-26 15:37:58', '2019-12-27 11:46:18'),
(19, 10, 'America/Argentina/Buenos_Aires', '-03:00', 'A', 1, '2019-12-26 15:37:58', '2019-12-27 11:46:18'),
(20, 10, 'America/Argentina/Catamarca', '-03:00', 'A', 1, '2019-12-26 15:37:58', '2019-12-27 11:46:18'),
(21, 10, 'America/Argentina/Cordoba', '-03:00', 'A', 1, '2019-12-26 15:37:58', '2019-12-27 11:46:18'),
(22, 10, 'America/Argentina/Jujuy', '-03:00', 'A', 1, '2019-12-26 15:37:58', '2019-12-27 11:46:18'),
(23, 10, 'America/Argentina/La_Rioja', '-03:00', 'A', 1, '2019-12-26 15:37:58', '2019-12-27 11:46:18'),
(24, 10, 'America/Argentina/Mendoza', '-03:00', 'A', 1, '2019-12-26 15:37:58', '2019-12-27 11:46:19'),
(25, 10, 'America/Argentina/Rio_Gallegos', '-03:00', 'A', 1, '2019-12-26 15:37:58', '2019-12-27 11:46:19'),
(26, 10, 'America/Argentina/Salta', '-03:00', 'A', 1, '2019-12-26 15:37:58', '2019-12-27 11:46:19'),
(27, 10, 'America/Argentina/San_Juan', '-03:00', 'A', 1, '2019-12-26 15:37:58', '2019-12-27 11:46:19'),
(28, 10, 'America/Argentina/San_Luis', '-03:00', 'A', 1, '2019-12-26 15:37:58', '2019-12-27 11:46:19'),
(29, 10, 'America/Argentina/Tucuman', '-03:00', 'A', 1, '2019-12-26 15:37:58', '2019-12-27 11:46:19'),
(30, 10, 'America/Argentina/Ushuaia', '-03:00', 'A', 1, '2019-12-26 15:54:22', '2019-12-27 11:46:19'),
(31, 11, 'Asia/Yerevan', '+04:00', 'A', 1, '2019-12-26 15:54:22', '2019-12-27 11:46:19'),
(32, 12, 'America/Aruba', '-04:00', 'A', 1, '2019-12-26 15:54:22', '2019-12-27 11:46:19'),
(33, 13, 'Antarctica/Macquarie', '+11:00', 'A', 1, '2019-12-26 15:54:22', '2019-12-27 11:46:19'),
(34, 13, 'Australia/Adelaide', '+10:30', 'A', 1, '2019-12-26 15:54:22', '2019-12-27 11:46:19'),
(35, 13, 'Australia/Brisbane', '+10:00', 'A', 1, '2019-12-26 15:54:22', '2019-12-27 11:46:19'),
(36, 13, 'Australia/Broken_Hill', '+10:30', 'A', 1, '2019-12-26 15:54:22', '2019-12-27 11:46:19'),
(37, 13, 'Australia/Currie', '+11:00', 'A', 1, '2019-12-26 15:54:22', '2019-12-27 11:46:19'),
(38, 13, 'Australia/Darwin', '+09:30', 'A', 1, '2019-12-26 15:54:22', '2019-12-27 11:46:19'),
(39, 13, 'Australia/Eucla', '+08:45', 'A', 1, '2019-12-26 15:54:22', '2019-12-27 11:46:19'),
(40, 13, 'Australia/Hobart', '+11:00', 'A', 1, '2019-12-26 15:54:22', '2019-12-27 11:46:19'),
(41, 13, 'Australia/Lindeman', '+10:00', 'A', 1, '2019-12-26 15:54:22', '2019-12-27 11:46:20'),
(42, 13, 'Australia/Lord_Howe', '+11:00', 'A', 1, '2019-12-26 15:54:22', '2019-12-27 11:46:20'),
(43, 13, 'Australia/Melbourne', '+11:00', 'A', 1, '2019-12-26 15:54:22', '2019-12-27 11:46:20'),
(44, 13, 'Australia/Perth', '+08:00', 'A', 1, '2019-12-26 15:54:22', '2019-12-27 11:46:20'),
(45, 13, 'Australia/Sydney', '+11:00', 'A', 1, '2019-12-26 15:54:22', '2019-12-27 11:46:20'),
(46, 14, 'Europe/Vienna', '+01:00', 'A', 1, '2019-12-26 15:54:22', '2019-12-27 11:46:20'),
(47, 15, 'Asia/Baku', '+04:00', 'A', 1, '2019-12-26 15:54:22', '2019-12-27 11:46:20'),
(48, 16, 'America/Nassau', '-05:00', 'A', 1, '2019-12-26 15:54:22', '2019-12-27 11:46:20'),
(49, 17, 'Asia/Bahrain', '+03:00', 'A', 1, '2019-12-26 15:54:22', '2019-12-27 11:46:20'),
(50, 18, 'Asia/Dhaka', '+06:00', 'A', 1, '2019-12-26 15:54:22', '2019-12-27 11:46:20'),
(51, 19, 'America/Barbados', '-04:00', 'A', 1, '2019-12-26 15:54:22', '2019-12-27 11:46:20'),
(52, 20, 'Europe/Minsk', '+03:00', 'A', 1, '2019-12-26 15:54:22', '2019-12-27 11:46:20'),
(53, 21, 'Europe/Brussels', '+01:00', 'A', 1, '2019-12-26 15:54:22', '2019-12-27 11:46:20'),
(54, 22, 'America/Belize', '-06:00', 'A', 1, '2019-12-26 15:54:22', '2019-12-27 11:46:20'),
(55, 23, 'Africa/Porto-Novo', '+01:00', 'A', 1, '2019-12-26 15:54:22', '2019-12-27 11:46:20'),
(56, 24, 'Atlantic/Bermuda', '-04:00', 'A', 1, '2019-12-26 15:54:22', '2019-12-27 11:46:20'),
(57, 25, 'Asia/Thimphu', '+06:00', 'A', 1, '2019-12-26 15:54:22', '2019-12-27 11:46:21'),
(58, 26, 'America/La_Paz', '-04:00', 'A', 1, '2019-12-26 15:54:22', '2019-12-27 11:46:21'),
(60, 27, 'Europe/Sarajevo', '+01:00', 'A', 1, '2019-12-26 15:54:22', '2019-12-27 11:47:59'),
(61, 28, 'Africa/Gaborone', '+02:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:47:59'),
(62, 30, 'America/Araguaina', '-03:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:47:59'),
(63, 30, 'America/Bahia', '-03:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:47:59'),
(64, 30, 'America/Belem', '-03:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:47:59'),
(65, 30, 'America/Boa_Vista', '-04:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:47:59'),
(66, 30, 'America/Campo_Grande', '-04:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:47:59'),
(67, 30, 'America/Cuiaba', '-04:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:00'),
(68, 30, 'America/Eirunepe', '-05:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:00'),
(69, 30, 'America/Fortaleza', '-03:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:00'),
(70, 30, 'America/Maceio', '-03:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:00'),
(71, 30, 'America/Manaus', '-04:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:00'),
(72, 30, 'America/Noronha', '-02:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:00'),
(73, 30, 'America/Porto_Velho', '-04:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:00'),
(74, 30, 'America/Recife', '-03:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:00'),
(75, 30, 'America/Rio_Branco', '-05:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:00'),
(76, 30, 'America/Santarem', '-03:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:00'),
(77, 30, 'America/Sao_Paulo', '-03:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:00'),
(78, 31, 'Indian/Chagos', '+06:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:00'),
(79, 32, 'Asia/Brunei', '+08:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:00'),
(80, 33, 'Europe/Sofia', '+02:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:00'),
(81, 34, 'Africa/Ouagadougou', 'UTC', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:00'),
(82, 35, 'Africa/Bujumbura', '+02:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:00'),
(83, 36, 'Asia/Phnom_Penh', '+07:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:00'),
(84, 37, 'Africa/Douala', '+01:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:01'),
(85, 38, 'America/Atikokan', '-05:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:01'),
(86, 38, 'America/Blanc-Sablon', '-04:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:01'),
(87, 38, 'America/Cambridge_Bay', '-07:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:01'),
(88, 38, 'America/Creston', '-07:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:01'),
(89, 38, 'America/Dawson', '-08:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:01'),
(90, 38, 'America/Dawson_Creek', '-07:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:01'),
(91, 38, 'America/Edmonton', '-07:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:01'),
(92, 38, 'America/Fort_Nelson', '-07:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:01'),
(93, 38, 'America/Glace_Bay', '-04:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:01'),
(94, 38, 'America/Goose_Bay', '-04:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:01'),
(95, 38, 'America/Halifax', '-04:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:01'),
(96, 38, 'America/Inuvik', '-07:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:01'),
(97, 38, 'America/Iqaluit', '-05:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:01'),
(98, 38, 'America/Moncton', '-04:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:01'),
(99, 38, 'America/Nipigon', '-05:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:01'),
(100, 38, 'America/Pangnirtung', '-05:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:02'),
(101, 38, 'America/Rainy_River', '-06:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:02'),
(102, 38, 'America/Rankin_Inlet', '-06:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:02'),
(103, 38, 'America/Regina', '-06:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:02'),
(104, 38, 'America/Resolute', '-06:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:02'),
(105, 38, 'America/St_Johns', '-03:30', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:02'),
(106, 38, 'America/Swift_Current', '-06:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:02'),
(107, 38, 'America/Thunder_Bay', '-05:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:02'),
(108, 38, 'America/Toronto', '-05:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:02'),
(109, 38, 'America/Vancouver', '-08:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:02'),
(110, 38, 'America/Whitehorse', '-08:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:02'),
(111, 38, 'America/Winnipeg', '-06:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:02'),
(112, 38, 'America/Yellowknife', '-07:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:02'),
(113, 39, 'Atlantic/Cape_Verde', '-01:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:02'),
(114, 40, 'America/Cayman', '-05:00', 'A', 1, '2019-12-27 12:18:13', '2019-12-27 11:48:02'),
(115, 41, 'Africa/Bangui', '+01:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:02'),
(116, 42, 'Africa/Ndjamena', '+01:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:02'),
(117, 43, 'America/Punta_Arenas', '-03:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:02'),
(118, 43, 'America/Santiago', '-03:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:03'),
(119, 43, 'Pacific/Easter', '-05:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:03'),
(120, 44, 'Asia/Shanghai', '+08:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:03'),
(121, 44, 'Asia/Urumqi', '+06:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:03'),
(122, 45, 'Indian/Christmas', '+07:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:03'),
(123, 46, 'Indian/Cocos', '+06:30', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:03'),
(124, 47, 'America/Bogota', '-05:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:03'),
(125, 48, 'Indian/Comoro', '+03:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:03'),
(126, 49, 'Africa/Brazzaville', '+01:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:03'),
(127, 50, 'Africa/Kinshasa', '+01:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:03'),
(128, 50, 'Africa/Lubumbashi', '+02:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:03'),
(129, 51, 'Pacific/Rarotonga', '-10:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:03'),
(130, 52, 'America/Costa_Rica', '-06:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:03'),
(131, 54, 'Europe/Zagreb', '+01:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:03'),
(132, 55, 'America/Havana', '-05:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:03'),
(134, 56, 'Asia/Famagusta', '+02:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:03'),
(135, 56, 'Asia/Nicosia', '+02:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:03'),
(136, 57, 'Europe/Prague', '+01:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:04'),
(137, 53, 'Africa/Abidjan', 'UTC', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:04'),
(138, 58, 'Europe/Copenhagen', '+01:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:04'),
(139, 59, 'Africa/Djibouti', '+03:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:04'),
(140, 60, 'America/Dominica', '-04:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:04'),
(141, 61, 'America/Santo_Domingo', '-04:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:04'),
(142, 63, 'America/Guayaquil', '-05:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:04'),
(143, 63, 'Pacific/Galapagos', '-06:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:04'),
(144, 64, 'Africa/Cairo', '+02:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:04'),
(145, 65, 'America/El_Salvador', '-06:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:04'),
(146, 66, 'Africa/Malabo', '+01:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:04'),
(147, 67, 'Africa/Asmara', '+03:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:04'),
(148, 68, 'Europe/Tallinn', '+02:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:04'),
(149, 69, 'Africa/Addis_Ababa', '+03:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:04'),
(150, 71, 'Atlantic/Stanley', '-03:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:04'),
(151, 72, 'Atlantic/Faroe', 'UTC', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:04'),
(152, 73, 'Pacific/Fiji', '+13:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:04'),
(153, 74, 'Europe/Helsinki', '+02:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:04'),
(154, 75, 'Europe/Paris', '+01:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:05'),
(155, 76, 'America/Cayenne', '-03:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:05'),
(156, 77, 'Pacific/Gambier', '-09:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:05'),
(157, 77, 'Pacific/Marquesas', '-09:30', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:05'),
(158, 77, 'Pacific/Tahiti', '-10:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:05'),
(159, 78, 'Indian/Kerguelen', '+05:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:05'),
(160, 79, 'Africa/Libreville', '+01:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:05'),
(161, 80, 'Africa/Banjul', 'UTC', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:05'),
(162, 81, 'Asia/Tbilisi', '+04:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:05'),
(163, 82, 'Europe/Berlin', '+01:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:05'),
(164, 82, 'Europe/Busingen', '+01:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:05'),
(165, 83, 'Africa/Accra', 'UTC', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:05'),
(166, 84, 'Europe/Gibraltar', '+01:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:05'),
(167, 85, 'Europe/Athens', '+02:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:05'),
(168, 86, 'America/Danmarkshavn', 'UTC', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:05'),
(169, 86, 'America/Godthab', '-03:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:05'),
(170, 86, 'America/Scoresbysund', '-01:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:05'),
(171, 86, 'America/Thule', '-04:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:05'),
(172, 87, 'America/Grenada', '-04:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:06'),
(173, 88, 'America/Guadeloupe', '-04:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:06'),
(174, 89, 'Pacific/Guam', '+10:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:06'),
(175, 90, 'America/Guatemala', '-06:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:06'),
(177, 92, 'Africa/Conakry', 'UTC', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:06'),
(178, 93, 'Africa/Bissau', 'UTC', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:06'),
(179, 94, 'America/Guyana', '-04:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:06'),
(180, 95, 'America/Port-au-Prince', '-05:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:06'),
(181, 236, 'Europe/Vatican', '+01:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:06'),
(182, 97, 'America/Tegucigalpa', '-06:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:06'),
(183, 98, 'Asia/Hong_Kong', '+08:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:06'),
(184, 99, 'Europe/Budapest', '+01:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:06'),
(185, 100, 'Atlantic/Reykjavik', 'UTC', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:06'),
(186, 101, 'Asia/Kolkata', '+05:30', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:06'),
(187, 102, 'Asia/Jakarta', '+07:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:06'),
(188, 102, 'Asia/Jayapura', '+09:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:06'),
(189, 102, 'Asia/Makassar', '+08:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:06'),
(190, 102, 'Asia/Pontianak', '+07:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:06'),
(191, 103, 'Asia/Tehran', '+03:30', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:07'),
(192, 104, 'Asia/Baghdad', '+03:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:07'),
(193, 105, 'Europe/Dublin', 'UTC', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:07'),
(195, 106, 'Asia/Jerusalem', '+02:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:07'),
(196, 107, 'Europe/Rome', '+01:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:07'),
(197, 108, 'America/Jamaica', '-05:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:07'),
(198, 109, 'Asia/Tokyo', '+09:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:07'),
(200, 111, 'Asia/Amman', '+02:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:07'),
(201, 112, 'Asia/Almaty', '+06:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:07'),
(202, 112, 'Asia/Aqtau', '+05:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:07'),
(203, 112, 'Asia/Aqtobe', '+05:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:07'),
(204, 112, 'Asia/Atyrau', '+05:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:07'),
(205, 112, 'Asia/Oral', '+05:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:07'),
(206, 112, 'Asia/Qostanay', '+06:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:07'),
(207, 112, 'Asia/Qyzylorda', '+05:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:07'),
(208, 113, 'Africa/Nairobi', '+03:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:07'),
(209, 114, 'Pacific/Enderbury', '+13:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:07'),
(210, 114, 'Pacific/Kiritimati', '+14:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:08'),
(211, 114, 'Pacific/Tarawa', '+12:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:08'),
(212, 115, 'Asia/Pyongyang', '+09:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:08'),
(213, 116, 'Asia/Seoul', '+09:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:08'),
(214, 117, 'Asia/Kuwait', '+03:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:08'),
(215, 118, 'Asia/Bishkek', '+06:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:08'),
(216, 119, 'Asia/Vientiane', '+07:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:08'),
(217, 120, 'Europe/Riga', '+02:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:08'),
(218, 121, 'Asia/Beirut', '+02:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:08'),
(219, 122, 'Africa/Maseru', '+02:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:08'),
(220, 123, 'Africa/Monrovia', 'UTC', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:08'),
(221, 124, 'Africa/Tripoli', '+02:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:08'),
(222, 125, 'Europe/Vaduz', '+01:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:08'),
(223, 126, 'Europe/Vilnius', '+02:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:08'),
(224, 127, 'Europe/Luxembourg', '+01:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:08'),
(225, 128, 'Asia/Macau', '+08:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:08'),
(226, 129, 'Europe/Skopje', '+01:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:08'),
(227, 130, 'Indian/Antananarivo', '+03:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:09'),
(228, 131, 'Africa/Blantyre', '+02:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:09'),
(229, 132, 'Asia/Kuala_Lumpur', '+08:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:09'),
(230, 132, 'Asia/Kuching', '+08:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:09'),
(231, 133, 'Indian/Maldives', '+05:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:09'),
(232, 134, 'Africa/Bamako', 'UTC', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:09'),
(233, 135, 'Europe/Malta', '+01:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:09'),
(234, 137, 'Pacific/Kwajalein', '+12:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:09'),
(235, 137, 'Pacific/Majuro', '+12:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:09'),
(236, 138, 'America/Martinique', '-04:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:09'),
(237, 139, 'Africa/Nouakchott', 'UTC', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:09'),
(238, 140, 'Indian/Mauritius', '+04:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:09'),
(239, 141, 'Indian/Mayotte', '+03:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:09'),
(240, 142, 'America/Bahia_Banderas', '-06:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:09'),
(241, 142, 'America/Cancun', '-05:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:09'),
(242, 142, 'America/Chihuahua', '-07:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:09'),
(243, 142, 'America/Hermosillo', '-07:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:09'),
(244, 142, 'America/Matamoros', '-06:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:09'),
(245, 142, 'America/Mazatlan', '-07:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:09'),
(246, 142, 'America/Merida', '-06:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:10'),
(247, 142, 'America/Mexico_City', '-06:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:10'),
(248, 142, 'America/Monterrey', '-06:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:10'),
(249, 142, 'America/Ojinaga', '-07:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:10'),
(250, 142, 'America/Tijuana', '-08:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:10'),
(251, 143, 'Pacific/Chuuk', '+10:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:10'),
(252, 143, 'Pacific/Kosrae', '+11:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:10'),
(253, 143, 'Pacific/Pohnpei', '+11:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:10'),
(254, 144, 'Europe/Chisinau', '+02:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:10'),
(255, 145, 'Europe/Monaco', '+01:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:10'),
(256, 146, 'Asia/Choibalsan', '+08:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:10'),
(257, 146, 'Asia/Hovd', '+07:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:10'),
(258, 146, 'Asia/Ulaanbaatar', '+08:00', 'A', 1, '2019-12-27 14:56:38', '2019-12-27 11:48:10');

-- --------------------------------------------------------

--
-- Table structure for table `ia_users`
--

CREATE TABLE `ia_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `phone` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `userkey` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `usertype` enum('S','SA','FU') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'S=Superadmin SA=SubAdmin FU=Frontend User',
  `profile_pic` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('A','I','D') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'A-active, I-Inactive, D-Delete',
  `setting_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `api_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_from` enum('B','F') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'B= Backend , F = Frontednd',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `updated_by` bigint(20) NOT NULL DEFAULT '0',
  `is_deleted` enum('Y','N') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N' COMMENT 'Y- Yes, N-No',
  `deleted_by` bigint(20) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ia_users`
--

INSERT INTO `ia_users` (`id`, `name`, `email`, `email_verified_at`, `phone`, `role_id`, `userkey`, `usertype`, `profile_pic`, `password`, `status`, `setting_json`, `remember_token`, `api_token`, `created_from`, `created_at`, `updated_at`, `created_by`, `updated_by`, `is_deleted`, `deleted_by`, `deleted_at`) VALUES
(1, 'Super Adminn', 'attendance@yopmail.com', '2019-06-11 17:02:21', '7872503102', 1, NULL, 'S', 'no-jpeg', '$2y$10$oYX60pXiL9p5ZLHyHU3hPOdvbHH29D1OwzD3251MW9IgqIJrIfh36', 'A', '{\"timezone\":\"Asia\\/Kolkata\",\"date_format\":\"d-M-Y\",\"time_format\":\"g:i A\",\"vat_value_for_pr_copywrite\":\"43\",\"vat_value_for_press_release\":\"43\",\"return_request\":\"no\",\"limitation_count\":\"\",\"currency_symbol\":\"$\",\"currency_code\":\"USD\"}', '7cZ3A9XKUXJxygGTn5xxpEAzWdW6jdtiW3iyRfZqLSaA8tRVHRdvw4EcAztj', '7CrzbZQ5KWyXsKb8LvdGSKT5ZRZZWSiZb6Y3JhCPsPHz3rnI77PwotoW5hgf', NULL, '2019-06-11 17:02:21', '2020-09-07 13:16:25', 0, 1, 'N', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ia_modules`
--
ALTER TABLE `ia_modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ia_module_functionalities`
--
ALTER TABLE `ia_module_functionalities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `module_functionalities_module_id_foreign` (`module_id`);

--
-- Indexes for table `ia_roles`
--
ALTER TABLE `ia_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ia_role_permissions`
--
ALTER TABLE `ia_role_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_permissions_role_id_foreign` (`role_id`),
  ADD KEY `role_permissions_module_id_foreign` (`module_id`),
  ADD KEY `role_permissions_module_functionality_id_foreign` (`module_functionality_id`);

--
-- Indexes for table `ia_timezones`
--
ALTER TABLE `ia_timezones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ia_users`
--
ALTER TABLE `ia_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ia_modules`
--
ALTER TABLE `ia_modules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `ia_module_functionalities`
--
ALTER TABLE `ia_module_functionalities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `ia_roles`
--
ALTER TABLE `ia_roles`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `ia_role_permissions`
--
ALTER TABLE `ia_role_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `ia_timezones`
--
ALTER TABLE `ia_timezones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=259;
--
-- AUTO_INCREMENT for table `ia_users`
--
ALTER TABLE `ia_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
