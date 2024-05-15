-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 04, 2023 at 10:07 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hyderabad_feed`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `grand_parent_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `opening_balance` double(8,2) NOT NULL,
  `opening_date` date NOT NULL,
  `account_nature` enum('credit','debit') COLLATE utf8mb4_unicode_ci NOT NULL,
  `ageing` int(11) NOT NULL,
  `commission` tinyint(4) NOT NULL,
  `discount` tinyint(4) NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1 means active and 0 means deactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `grand_parent_id`, `parent_id`, `name`, `opening_balance`, `opening_date`, `account_nature`, `ageing`, `commission`, `discount`, `address`, `status`, `created_at`, `updated_at`) VALUES
(3, 7, 8, 'testing', 65.00, '2018-04-27', 'debit', 4, 39, 80, 'Sed earum nulla cons', 1, '2022-11-16 08:55:23', '2022-11-16 08:55:23'),
(4, 7, 8, 'testing11\r\n', 65.00, '2018-04-27', 'debit', 4, 39, 80, 'Sed earum nulla cons', 1, '2022-11-16 08:55:23', '2022-11-16 08:55:23'),
(5, 3, 8, 'tewste', 43.00, '2023-01-05', 'credit', 434, 43, 4, 'fsdgssfdgfds', 1, '2023-01-05 13:53:37', '2023-01-05 13:53:37'),
(6, 3, 8, 'tewste', 89.00, '2023-01-05', 'credit', 6, 6, 5, 'sdfdgdfgfdsg', 1, '2023-01-05 14:02:42', '2023-01-05 14:02:42'),
(7, 3, 8, 'tewste', 89.00, '2023-01-05', 'credit', 6, 6, 5, 'sdfdgdfgfdsg', 1, '2023-01-05 14:09:26', '2023-01-05 14:09:26'),
(8, 3, 8, 'tewste', 89.00, '2023-01-05', 'credit', 6, 6, 5, 'sdfdgdfgfdsg', 1, '2023-01-05 14:09:41', '2023-01-05 14:09:41'),
(9, 3, 8, 'tewste', 89.00, '2023-01-05', 'credit', 61, 6, 5, 'sdfdgdfgfdsg', 1, '2023-01-05 14:10:02', '2023-01-05 14:10:02'),
(10, 3, 8, 'tewste', 89.00, '2023-01-05', 'credit', 61, 6, 5, 'sdfdgdfgfdsg', 1, '2023-01-05 14:10:14', '2023-01-05 14:10:14'),
(11, 3, 8, 'tewste', 4.00, '2023-01-05', 'credit', 43, 5, 6, 'ggfsg', 1, '2023-01-05 14:11:28', '2023-01-05 14:11:28'),
(12, 3, 8, 'test555', 89.00, '2023-01-14', 'credit', 9, 89, 77, 'jhgjhgjhhj', 1, '2023-01-14 17:09:19', '2023-01-14 17:09:19'),
(13, 3, 8, 'feed debtor', 1000.00, '2023-02-01', 'credit', 12, 7, 8, 'testing', 1, '2023-02-01 07:00:32', '2023-02-01 07:00:32');

-- --------------------------------------------------------

--
-- Table structure for table `account_ledger`
--

CREATE TABLE `account_ledger` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `account_id` int(11) NOT NULL,
  `sale_id` int(11) DEFAULT NULL,
  `purchase_id` int(11) DEFAULT NULL,
  `cash_id` int(11) DEFAULT NULL,
  `debit` int(11) DEFAULT NULL,
  `credit` int(11) DEFAULT NULL,
  `description` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `account_ledger`
--

INSERT INTO `account_ledger` (`id`, `account_id`, `sale_id`, `purchase_id`, `cash_id`, `debit`, `credit`, `description`, `deleted_at`, `created_at`, `updated_at`) VALUES
(32, 12, 0, 20, 0, 0, 5435, 'Vehicle #brt-655, Bilty # 2423,  Item:test555,  Weight:40kg,  Posted Weight:54kg,  Account #[12]test555', NULL, '2023-02-01 12:54:54', '2023-02-01 12:54:54'),
(33, 12, 0, 34, 0, 0, NULL, 'Vehicle #brt-655, Bilty # 2423,  Item:test555,  Weight:40kg,  Posted Weight:kg,  Account #[12]test555', NULL, '2023-02-01 13:41:14', '2023-02-01 13:41:14'),
(34, 12, 0, 35, 0, 0, NULL, 'Vehicle #brt-655, Bilty # 2423,  Item:test555,  Weight:40kg,  Posted Weight:kg,  Account #[12]test555', NULL, '2023-02-01 13:41:45', '2023-02-01 13:41:45'),
(35, 12, 0, 36, 0, 2240, 0, 'Vehicle #brt-655, Bilty # 2423,  Item:test555,  Weight:40kg,  Posted Weight:kg,  Account #[12]test555', NULL, '2023-02-01 13:59:35', '2023-02-01 13:59:35');

-- --------------------------------------------------------

--
-- Table structure for table `account_types`
--

CREATE TABLE `account_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `account_types`
--

INSERT INTO `account_types` (`id`, `parent_id`, `name`, `created_at`, `updated_at`) VALUES
(3, NULL, 'Assets', NULL, NULL),
(4, NULL, 'Expenses', NULL, NULL),
(5, NULL, 'Liabilities', NULL, NULL),
(6, NULL, 'Revenue', NULL, NULL),
(7, NULL, 'Proprietorship', NULL, NULL),
(8, 3, 'Feed Debtors', NULL, NULL),
(9, 3, 'Other Debtors', NULL, NULL),
(10, 3, 'Stock In Trade', NULL, NULL),
(11, 3, 'Cash In Hand', NULL, NULL),
(12, 3, 'Bank Accounts', NULL, NULL),
(13, 4, 'Machinery Maintanance', NULL, NULL),
(14, 4, 'Electric Maintanance', NULL, NULL),
(15, 4, 'Wages', NULL, NULL),
(16, 4, 'Admin Salaries', NULL, NULL),
(17, 4, 'Freight out', NULL, NULL),
(18, 4, 'Social Security', NULL, NULL),
(19, 5, 'Meal Creditors', NULL, NULL),
(20, 5, 'Grain Creditors', NULL, NULL),
(21, 5, 'Other Creditors', NULL, NULL),
(22, 5, 'Medicine Creditors', NULL, NULL),
(23, 6, 'Income Others', NULL, NULL),
(24, 6, 'Scrape Sales', NULL, NULL),
(25, 6, 'Sales', NULL, NULL),
(26, 7, 'Partners Capital', NULL, NULL),
(27, 7, 'Partners Drawing', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'normal',
  `user_permissions` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `first_name`, `last_name`, `username`, `email`, `password`, `image`, `user_type`, `user_permissions`, `is_active`, `remember_token`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, NULL, 'admin', 'admin', 'admin@admin.com', '$2y$10$Yx46pkJYYjLzJwL95DmwXOKu4TShIjNPCib2Tv8HcU/hjmj1uOmu.', NULL, 'admin', NULL, 1, 'K6co9gF5KGHwL4RDiCfjtPNqijEMwWYmHmCwtIuazv8IBNGopukMwN5smbWA', NULL, NULL, NULL),
(2, 'Jescie', 'Knapp', 'fonugyhur', 'coso@mailinator.com', '$2y$10$5rtfQqsEe9UjWNctVbOM2uDCPu7nudHfaPMAJAB27VqyPzSUMXTm.', NULL, 'normal', '[{\"name\":\"asdasd1\"}]', 1, NULL, NULL, '2022-11-14 08:19:46', '2022-12-27 10:55:10'),
(3, 'test', NULL, 'manger', 'user1@example.com', '$2y$10$XpHYZW/l/JuVXoNcGCh2deAr2xo6rS5HzDTOYGnrqdYcwDDBY6H26', NULL, 'normal', '[{\"name\":\"edit-item\"}]', 1, NULL, NULL, '2023-02-04 06:43:03', '2023-02-04 06:43:03'),
(4, 'Bilal', NULL, 'manger.bilal@gmail.com', 'manger.bilal@gmail.com', '$2y$10$wXqoKn4kq1581/T5OKHBTeDewPSdYyzs8BPipVAeawg9SiFOV73ja', NULL, 'normal', '[{\"name\":\"add-account\"},{\"name\":\"edit-account\"},{\"name\":\"delete-account\"},{\"name\":\"add-item\"},{\"name\":\"edit-item\"},{\"name\":\"delete-item\"}]', 1, NULL, NULL, '2023-02-04 06:44:22', '2023-02-04 06:55:31');

-- --------------------------------------------------------

--
-- Table structure for table `cash_book`
--

CREATE TABLE `cash_book` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `account_id` int(11) NOT NULL,
  `narration` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `bil_no` int(11) NOT NULL,
  `payment_ammount` int(11) DEFAULT NULL,
  `receipt_ammount` int(11) DEFAULT NULL,
  `status` enum('payment','receipt') COLLATE utf8mb4_unicode_ci NOT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cash_book`
--

INSERT INTO `cash_book` (`id`, `date`, `account_id`, `narration`, `bil_no`, `payment_ammount`, `receipt_ammount`, `status`, `remarks`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '2022-12-01', 10, '2fdgsdgfdfd 343', 23344, 0, 34456, 'receipt', NULL, NULL, '2022-12-20 13:07:21', '2023-01-20 11:16:33'),
(2, '2022-12-08', 3, '2fdgsdg', 233, 0, 888, 'receipt', NULL, NULL, '2022-12-21 19:18:10', '2022-12-21 19:18:10'),
(3, '2023-01-20', 11, 'tewste', 23354, 0, 89, 'receipt', NULL, NULL, '2022-12-21 19:43:59', '2023-01-28 21:34:29'),
(4, '2022-12-01', 3, 'kjkkjljl', 233, 7899, 0, 'payment', NULL, NULL, '2022-12-23 10:57:53', '2023-01-27 14:01:32'),
(6, '2023-01-11', 12, 'tewste', 23344, NULL, NULL, 'receipt', NULL, NULL, '2023-01-28 21:38:58', '2023-01-28 21:38:58'),
(9, '2023-01-07', 12, 'tewste sdsd', 233, 0, 1000, 'receipt', NULL, NULL, '2023-01-31 07:22:44', '2023-01-31 07:22:44'),
(10, '2023-01-06', 12, 'tewste fsfsf', 233, 1000, 0, 'payment', NULL, NULL, '2023-01-31 07:23:15', '2023-01-31 07:23:15'),
(11, '2023-01-05', 11, 'tewste fdfdg', 23354, 0, 400, 'receipt', NULL, NULL, '2023-01-31 07:23:38', '2023-01-31 07:23:38'),
(12, '2023-01-06', 9, 'tewste', 23354, 1000, 0, 'payment', NULL, NULL, '2023-01-31 07:23:57', '2023-01-31 07:23:57');

-- --------------------------------------------------------

--
-- Table structure for table `cash_in_hand_detail`
--

CREATE TABLE `cash_in_hand_detail` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `cash_in_hand` int(11) NOT NULL,
  `total_debit` int(11) NOT NULL,
  `total_credit` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cash_in_hand_detail`
--

INSERT INTO `cash_in_hand_detail` (`id`, `date`, `cash_in_hand`, `total_debit`, `total_credit`, `created_at`, `updated_at`) VALUES
(1, '2023-01-26', 1000, 32434, 0, '2023-01-27 15:06:00', NULL),
(2, '2023-01-26', 2000, 32434, 0, '2023-01-27 15:07:00', NULL),
(3, '2023-01-26', 3000, 1689, 1889, '2023-01-29 15:07:00', '2023-01-28 21:38:58'),
(6, '2023-01-31', 3600, 2000, 1400, '2023-01-31 07:22:44', '2023-01-31 07:23:57');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1 means active 0 means deactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(2, 'test2', 1, '2022-11-14 20:34:20', '2022-11-14 20:34:38');

-- --------------------------------------------------------

--
-- Table structure for table `consumptions`
--

CREATE TABLE `consumptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_id` int(11) NOT NULL,
  `qunantity` int(11) NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `consumptions`
--

INSERT INTO `consumptions` (`id`, `item_id`, `qunantity`, `date`, `created_at`, `updated_at`) VALUES
(1, 2, 1200, '2022-12-25', '2022-12-25 17:47:40', '2022-12-25 17:47:40'),
(2, 2, 1200, '2022-12-25', '2022-12-25 17:49:12', '2022-12-25 17:49:12'),
(3, 2, 1200, '2022-12-25', '2022-12-25 17:49:43', '2022-12-25 17:49:43'),
(4, 2, 1200, '2022-12-25', '2022-12-25 17:50:54', '2022-12-25 17:50:54'),
(5, 2, 1209, '2022-12-25', '2022-12-25 17:51:22', '2022-12-25 18:15:08');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `formulations`
--

CREATE TABLE `formulations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_item_id` int(11) NOT NULL,
  `sale_weight` int(11) NOT NULL,
  `total_purchase_weight` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `formulations`
--

INSERT INTO `formulations` (`id`, `sale_item_id`, `sale_weight`, `total_purchase_weight`, `created_at`, `updated_at`) VALUES
(2, 2, 500, 500, '2022-12-11 15:31:43', '2022-12-11 15:31:43');

-- --------------------------------------------------------

--
-- Table structure for table `formulation_details`
--

CREATE TABLE `formulation_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `formulation_id` int(11) NOT NULL,
  `purhcase_item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `formulation_details`
--

INSERT INTO `formulation_details` (`id`, `formulation_id`, `purhcase_item_id`, `quantity`, `created_at`, `updated_at`) VALUES
(11, 2, 2, 499, '2022-12-11 15:32:15', '2022-12-11 15:32:15'),
(12, 2, 2, 1, '2022-12-11 15:32:15', '2022-12-11 15:32:15');

-- --------------------------------------------------------

--
-- Table structure for table `inward`
--

CREATE TABLE `inward` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `item_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `vehicle_no` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vehicle_status` enum('pending','loading','completed') COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_of_bags` int(11) DEFAULT NULL,
  `fare` int(11) DEFAULT NULL,
  `bilty_no` int(11) DEFAULT NULL,
  `gp_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_weight` int(11) DEFAULT NULL,
  `party_weight` int(11) DEFAULT NULL,
  `first_weight` int(11) DEFAULT NULL,
  `second_weight` int(11) DEFAULT NULL,
  `weight_difference` int(11) DEFAULT NULL,
  `party_weight_difference` int(11) DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inward`
--

INSERT INTO `inward` (`id`, `date`, `item_id`, `account_id`, `vehicle_no`, `vehicle_status`, `no_of_bags`, `fare`, `bilty_no`, `gp_no`, `company_weight`, `party_weight`, `first_weight`, `second_weight`, `weight_difference`, `party_weight_difference`, `remarks`, `deleted_at`, `created_at`, `updated_at`) VALUES
(6, '2023-01-12', 6, 12, 'brt-655', 'completed', 200, 900, 2423, NULL, 40, 100, 90, 50, 40, 100, 'testing', NULL, '2023-01-31 14:05:00', '2023-01-31 14:26:37');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` double(10,2) NOT NULL,
  `type` enum('purchase','sale') COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock_status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1 means enabled 0 means disabled',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1 means active 0 means deactive',
  `stock_qty` int(11) NOT NULL DEFAULT 0,
  `remarks` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `category_id`, `name`, `price`, `type`, `stock_status`, `status`, `stock_qty`, `remarks`, `created_at`, `updated_at`) VALUES
(2, 2, 'Aimee Noble', 391.00, 'purchase', 1, 1, 12762, NULL, '2022-11-18 12:40:00', '2023-01-25 07:54:30'),
(3, 2, 'Aimee Noble', 391.00, 'sale', 1, 1, 12582, NULL, '2022-11-18 12:40:00', '2022-12-23 19:16:46'),
(4, 2, 'Aimee Noble11', 392.00, 'sale', 1, 1, 12582, NULL, '2022-11-18 12:40:00', '2022-12-23 19:16:46'),
(6, 2, 'test555', 56.00, 'purchase', 1, 1, 240, 'testing the item', '2023-01-10 14:27:47', '2023-02-01 13:59:35'),
(8, 2, 'edit item', 11.00, 'purchase', 1, 1, 0, NULL, '2023-02-03 14:11:09', '2023-02-03 14:11:09');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(7, '2022_07_14_223044_create_user_logins_table', 3),
(8, '2022_07_15_121516_create_activities_table', 4),
(11, '2022_07_15_133608_create_districts_table', 5),
(12, '2022_07_15_133620_create_facilities_table', 5),
(16, '2022_07_15_135723_create_questions_table', 6),
(17, '2022_07_15_160403_add_added_by_id_in_facilities_table', 7),
(18, '2022_07_18_115806_create_sync_data_table', 8),
(20, '2022_07_18_121422_create_form_data_table', 9),
(21, '2022_07_28_132750_update_questions_table', 10),
(22, '2022_08_02_155639_add_checksum_table_in_orm_responses', 11),
(24, '2022_08_03_234630_add_sync_type_in_form_responses', 12),
(25, '2022_08_17_212213_add_geo_fields_in_form_reponses_table', 13),
(26, '2022_08_30_142514_add_columns_in_form_responses', 14),
(27, '2022_09_03_211432_add_order_column_in_questions', 15),
(28, '2022_09_03_224229_add_is_required_in_questions', 16),
(29, '2022_09_05_152231_add_closed_reason_column_in_form_responses', 17),
(31, '2022_09_13_192455_add_city_in_admins', 18),
(32, '2022_09_14_161049_create_table_uclist', 19),
(33, '2022_09_14_185845_add_type_column_in_question_categories', 20),
(34, '2022_09_14_195058_add_type_column_in_questions', 21),
(35, '2022_09_15_191535_add_colums_form_responses', 22),
(36, '2022_09_05_195744_add_order_in_question_categories', 23),
(37, '2022_10_22_203854_add_colums_question', 24),
(38, '2022_11_07_181114_add_colums_in_form_responses', 25),
(39, '2022_11_07_182358_create_table_functional_calculations', 25),
(40, '2022_11_07_190445_add_colums_in_questions', 25),
(56, '2014_10_12_000000_create_users_table', 26),
(57, '2014_10_12_100000_create_password_resets_table', 26),
(58, '2019_08_19_000000_create_failed_jobs_table', 26),
(59, '2019_12_14_000001_create_personal_access_tokens_table', 26),
(60, '2022_07_14_081707_create_admins_table', 26),
(61, '2022_07_14_162435_create_permissions_table', 26),
(62, '2022_11_12_192714_create_disciplines_table', 26),
(63, '2022_11_12_200515_create_subjects_table', 26),
(64, '2022_11_12_204228_create_topics_table', 26),
(66, '2022_11_14_201659_create_account_types_table', 27),
(67, '2022_11_14_210756_create_accounts_table', 28),
(70, '2022_11_15_011900_create_categories_table', 29),
(71, '2022_11_15_193523_create_items_table', 30),
(72, '2022_11_16_160356_sales_book', 31),
(73, '2022_11_16_161538_purchase_book', 31),
(74, '2022_11_16_161609_cash_book', 31),
(75, '2022_11_17_191057_outward', 31),
(76, '2022_11_17_192917_inward', 31),
(77, '2022_12_11_170540_create_formulations_table', 32),
(78, '2022_12_11_170614_create_formulation_details_table', 32),
(79, '2022_12_20_081648_account_ledger', 33),
(80, '2022_12_25_003941_create_consumptions_table', 34),
(81, '2022_12_27_203124_inward_detail', 35),
(82, '2023_01_27_191325_cashinhand_detail', 36),
(83, '2023_01_27_191529_cashinhand_detail', 37);

-- --------------------------------------------------------

--
-- Table structure for table `outward`
--

CREATE TABLE `outward` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `account_id` int(11) NOT NULL,
  `sub_dealer_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vehicle_no` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vehicle_status` enum('pending','loading','completed') COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_of_begs` int(11) DEFAULT NULL,
  `fare` int(11) DEFAULT NULL,
  `bilty_no` int(11) DEFAULT NULL,
  `gp_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_weight` int(11) DEFAULT NULL,
  `first_weight` int(11) DEFAULT NULL,
  `second_weight` int(11) DEFAULT NULL,
  `weight_difference` int(11) DEFAULT NULL,
  `driver_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `driver_phone_no` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `driver_status` enum('with_driver','without_driver') COLLATE utf8mb4_unicode_ci NOT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `outward`
--

INSERT INTO `outward` (`id`, `date`, `account_id`, `sub_dealer_name`, `vehicle_no`, `vehicle_status`, `no_of_begs`, `fare`, `bilty_no`, `gp_no`, `company_weight`, `first_weight`, `second_weight`, `weight_difference`, `driver_name`, `driver_phone_no`, `driver_status`, `remarks`, `deleted_at`, `created_at`, `updated_at`) VALUES
(19, '2023-01-05', 12, 'ghy69', 'brt-655', 'completed', 200, 900, 890, '7/1-23', -100, 100, NULL, -100, 'saleem bhai', '03034598678', 'with_driver', 'testing', NULL, '2023-01-31 14:31:35', '2023-01-31 17:16:48');

-- --------------------------------------------------------

--
-- Table structure for table `outward_detail`
--

CREATE TABLE `outward_detail` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `outward_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `outward_detail`
--

INSERT INTO `outward_detail` (`id`, `outward_id`, `item_id`, `quantity`, `created_at`, `updated_at`) VALUES
(1, 17, 3, 232, '2022-12-30 13:18:01', '2022-12-30 13:18:01'),
(2, 17, 4, 344, '2022-12-30 13:18:01', '2022-12-30 13:18:01'),
(3, 18, 3, 232, '2022-12-30 13:23:26', '2022-12-30 13:23:26'),
(4, 18, 4, 344, '2022-12-30 13:23:26', '2022-12-30 13:23:26'),
(5, 19, 3, 60, '2023-01-31 14:31:35', '2023-01-31 14:31:35'),
(6, 19, 4, 40, '2023-01-31 14:31:35', '2023-01-31 14:31:35');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `slug`) VALUES
(6, 'add-account', 'add-account'),
(7, 'edit-account', 'edit-account'),
(8, 'delete-account', 'delete-account'),
(9, 'add-item', 'add-item'),
(10, 'edit-item', 'edit-item'),
(11, 'delete-item', 'delete-item'),
(12, 'add-cashbook', 'add-cashbook'),
(13, 'edit-cashbook', 'edit-cashbook'),
(14, 'delete-cashbook', 'delete-cashbook'),
(15, 'add-sale', 'add-sale'),
(16, 'all-sale', 'all-sale'),
(17, 'edit-sale', 'edit-sale'),
(18, 'delete-sale', 'delete-sale'),
(19, 'add-purchase', 'add-purchase'),
(20, 'all-purchase', 'all-purchase'),
(21, 'edit-purchase', 'edit-purchase'),
(22, 'delete-purchase', 'delete-purchase'),
(23, 'add-staff', 'add-staff'),
(24, 'edit-staff', 'edit-staff'),
(25, 'delete-staff', 'delete-staff'),
(26, 'add-permission', 'add-permission'),
(27, 'add-consumption', 'add-consumption'),
(28, 'edit-consumption', 'edit-consumption'),
(29, 'delete-consumption', 'delete-consumption'),
(30, 'add-formulation', 'add-formulation'),
(35, 'edit-formulation', 'edit-formulation'),
(36, 'delete-formulation', 'delete-formulation'),
(37, 'all-formulation', 'all-formulation'),
(38, 'view-formulation', 'view-formulation');

-- --------------------------------------------------------

--
-- Table structure for table `permission_types`
--

CREATE TABLE `permission_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_book`
--

CREATE TABLE `purchase_book` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `pro_inv_no` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `vehicle_no` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vehicle_status` enum('pending','completed') COLLATE utf8mb4_unicode_ci NOT NULL,
  `bag_rate` int(11) DEFAULT NULL,
  `no_of_bags` int(11) DEFAULT NULL,
  `commission` int(11) DEFAULT NULL,
  `discount` int(11) DEFAULT NULL,
  `fare` int(11) DEFAULT NULL,
  `bilty_no` int(11) DEFAULT NULL,
  `other_charges` int(11) DEFAULT NULL,
  `company_weight` int(11) DEFAULT NULL,
  `party_weight` int(11) DEFAULT NULL,
  `weight_difference` int(11) DEFAULT NULL,
  `posted_weight` int(11) DEFAULT NULL,
  `net_weight` int(11) DEFAULT NULL,
  `net_ammount` int(11) DEFAULT NULL,
  `purchase_status` enum('pending','rejected','completed') COLLATE utf8mb4_unicode_ci NOT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_book`
--

CREATE TABLE `sales_book` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `gp_no` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `inward_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `sub_dealer_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vehicle_no` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vehicle_status` enum('pending','completed') COLLATE utf8mb4_unicode_ci NOT NULL,
  `bag_rate` int(11) DEFAULT NULL,
  `no_of_bags` int(11) DEFAULT NULL,
  `commission` int(11) DEFAULT NULL,
  `discount` int(11) DEFAULT NULL,
  `fare` int(11) DEFAULT NULL,
  `fare_status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1 means paid and 0 mens not paid',
  `bilty_no` int(11) DEFAULT NULL,
  `net_ammount` int(11) DEFAULT NULL,
  `sale_status` enum('pending','rejected','completed') COLLATE utf8mb4_unicode_ci NOT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales_book`
--

INSERT INTO `sales_book` (`id`, `date`, `gp_no`, `inward_id`, `account_id`, `sub_dealer_name`, `vehicle_no`, `vehicle_status`, `bag_rate`, `no_of_bags`, `commission`, `discount`, `fare`, `fare_status`, `bilty_no`, `net_ammount`, `sale_status`, `remarks`, `deleted_at`, `created_at`, `updated_at`) VALUES
(2, '2022-12-09', 'ghy789', 0, 3, 'gdfgdg', 'brt-655', 'pending', 899, 67, 39, 80, 678, 0, 0, 89899, 'pending', 'hgkhjdhfgjk', NULL, '2022-12-21 19:53:43', '2022-12-21 19:53:43'),
(3, '2022-12-09', 'ghy789', 0, 3, 'gdfgdg', 'brt-655', 'pending', 899, 67, 39, 80, 678, 1, 0, 89899, 'pending', 'hgkhjdhfgjk', NULL, '2022-12-21 19:54:23', '2022-12-26 18:30:25'),
(6, '2022-12-01', '3/12-22', 17, 3, 'gdfgdg', 'vrt-899', 'pending', NULL, 120, 39, 80, 7800, 0, 890, -50656, 'pending', 'testing', NULL, '2023-01-28 19:40:48', '2023-01-28 19:40:48'),
(7, '2022-12-01', '3/12-22', 18, 3, 'gdfgdg', 'vrt-899', 'pending', 225560, 576, 39, 80, 7800, 1, 0, -50656, 'pending', 'testing', NULL, '2023-01-30 17:56:25', '2023-01-30 17:56:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `account_ledger`
--
ALTER TABLE `account_ledger`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `account_types`
--
ALTER TABLE `account_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_username_unique` (`username`),
  ADD UNIQUE KEY `admins_email_unique` (`email`);

--
-- Indexes for table `cash_book`
--
ALTER TABLE `cash_book`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cash_in_hand_detail`
--
ALTER TABLE `cash_in_hand_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `consumptions`
--
ALTER TABLE `consumptions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `formulations`
--
ALTER TABLE `formulations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `formulation_details`
--
ALTER TABLE `formulation_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inward`
--
ALTER TABLE `inward`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `outward`
--
ALTER TABLE `outward`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `outward_detail`
--
ALTER TABLE `outward_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permission_types`
--
ALTER TABLE `permission_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `purchase_book`
--
ALTER TABLE `purchase_book`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_book`
--
ALTER TABLE `sales_book`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `account_ledger`
--
ALTER TABLE `account_ledger`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `account_types`
--
ALTER TABLE `account_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cash_book`
--
ALTER TABLE `cash_book`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `cash_in_hand_detail`
--
ALTER TABLE `cash_in_hand_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `consumptions`
--
ALTER TABLE `consumptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `formulations`
--
ALTER TABLE `formulations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `formulation_details`
--
ALTER TABLE `formulation_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `inward`
--
ALTER TABLE `inward`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `outward`
--
ALTER TABLE `outward`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `outward_detail`
--
ALTER TABLE `outward_detail`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `permission_types`
--
ALTER TABLE `permission_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_book`
--
ALTER TABLE `purchase_book`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `sales_book`
--
ALTER TABLE `sales_book`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
