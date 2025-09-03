-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 11, 2025 at 09:23 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `database`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `message`, `timestamp`) VALUES
(1, 2, '', 'User logged in.', '2025-08-07 04:42:17'),
(2, 1, '', 'User logged in.', '2025-08-07 04:45:38'),
(3, 2, '', 'User logged in.', '2025-08-07 04:55:22'),
(4, 1, '', 'User logged in.', '2025-08-07 07:56:48'),
(5, 1, '', 'User logged in.', '2025-08-08 09:33:22'),
(6, 1, '', 'User logged in.', '2025-08-08 10:33:38'),
(7, 1, '', 'User logged in.', '2025-08-08 11:05:56'),
(8, 1, '', 'User logged in.', '2025-08-08 11:06:22'),
(9, 1, '', 'User logged in.', '2025-08-08 19:58:22'),
(10, 1, '', 'User logged in.', '2025-08-08 20:08:04'),
(11, 1, '', 'User logged in.', '2025-08-08 20:40:45'),
(12, 2, '', 'User logged in.', '2025-08-08 21:12:20'),
(13, 1, '', 'User logged in.', '2025-08-08 21:29:28'),
(14, 2, '', 'User logged in.', '2025-08-08 21:32:48'),
(15, 1, '', 'User logged in.', '2025-08-08 21:33:29'),
(16, 1, '', 'User logged in.', '2025-08-09 00:00:50'),
(17, 3, '', 'User logged in.', '2025-08-09 00:49:19'),
(18, 1, '', 'User logged in.', '2025-08-09 00:49:33'),
(19, 2, '', 'User logged in.', '2025-08-09 01:01:29'),
(20, 1, '', 'User logged in.', '2025-08-09 01:02:06'),
(21, 1, '', 'User logged in.', '2025-08-09 01:33:05'),
(22, 1, '', 'User logged in.', '2025-08-09 01:46:39'),
(23, 1, '', 'User logged in.', '2025-08-09 01:49:15'),
(24, 3, '', 'User logged in.', '2025-08-09 02:00:40'),
(25, 1, '', 'User logged in.', '2025-08-09 02:00:52'),
(26, 1, '', 'User logged in.', '2025-08-09 03:30:49'),
(27, 1, '', 'User logged in.', '2025-08-09 03:37:58'),
(28, 2, '', 'User logged in.', '2025-08-09 06:11:29'),
(29, 1, '', 'User logged in.', '2025-08-09 07:49:28'),
(30, 1, '', 'User logged in.', '2025-08-09 08:24:35'),
(31, 2, '', 'User logged in.', '2025-08-09 08:38:14'),
(32, 1, '', 'User logged in.', '2025-08-09 08:39:53'),
(33, 2, '', 'User logged in.', '2025-08-09 08:57:17'),
(34, 1, '', 'User logged in.', '2025-08-09 09:04:26'),
(35, 1, '', 'User logged in.', '2025-08-09 09:41:54'),
(36, 2, '', 'User logged in.', '2025-08-09 10:22:15'),
(37, 1, '', 'User logged in.', '2025-08-09 18:35:13'),
(38, 1, '', 'User logged in.', '2025-08-09 20:15:58'),
(39, 1, '', 'User logged in.', '2025-08-09 20:22:38'),
(40, 1, '', 'User logged in.', '2025-08-09 22:53:42'),
(41, 2, '', 'User logged in.', '2025-08-09 23:22:29'),
(42, 2, '', 'User logged in.', '2025-08-09 23:34:32'),
(43, 1, '', 'User logged in.', '2025-08-09 23:35:15'),
(44, 2, '', 'User logged in.', '2025-08-09 23:35:41'),
(45, 1, '', 'User logged in.', '2025-08-10 00:08:51'),
(46, 1, '', 'User logged out.', '2025-08-10 10:26:55'),
(47, 2, '', 'User logged in.', '2025-08-10 10:27:05'),
(48, 2, '', 'User logged out.', '2025-08-10 10:30:08'),
(49, 1, '', 'User logged in.', '2025-08-10 10:30:18'),
(50, 1, '', 'User logged out.', '2025-08-10 10:51:01'),
(51, 2, '', 'User logged in.', '2025-08-10 10:51:10'),
(52, 2, '', 'Document previewed: aaa', '2025-08-10 10:53:40'),
(53, 2, '', 'Document previewed: sss', '2025-08-10 10:55:31'),
(54, 2, '', 'Document previewed: sss', '2025-08-10 11:00:18'),
(55, 2, '', 'Document previewed: aaaa', '2025-08-10 11:01:44'),
(56, 2, '', 'Document previewed: sss', '2025-08-10 11:01:56'),
(57, 2, '', 'Document previewed: sss', '2025-08-10 11:02:19'),
(58, 2, '', 'Document previewed: sss', '2025-08-10 11:02:22'),
(59, 2, '', 'Document previewed: pic', '2025-08-10 11:05:01'),
(60, 2, '', 'Document previewed: sss', '2025-08-10 11:05:06'),
(61, 2, '', 'Document previewed: sss', '2025-08-10 11:07:27'),
(62, 2, '', 'Document previewed: sss', '2025-08-10 11:07:41'),
(63, 2, '', 'Document previewed: test', '2025-08-10 11:07:48'),
(64, 2, '', 'User logged out.', '2025-08-10 11:20:36'),
(65, 1, '', 'User logged in.', '2025-08-10 11:20:44'),
(66, 1, '', 'Searched for: \'reports\'', '2025-08-10 11:23:09'),
(67, 1, '', 'User logged out.', '2025-08-10 11:28:45'),
(68, 2, '', 'User logged in.', '2025-08-10 11:28:54'),
(69, 2, '', 'Searched for: \'reports\'', '2025-08-10 11:34:53'),
(70, 2, '', 'Searched for: \'reports\'', '2025-08-10 11:35:54'),
(71, 2, '', 'Searched for: \'reports\'', '2025-08-10 11:35:59'),
(72, 2, '', 'Searched for: \'reports\'', '2025-08-10 11:36:16'),
(73, 2, '', 'Searched for: \'reports\'', '2025-08-10 11:36:23'),
(74, 2, '', 'Searched for: \'reports\'', '2025-08-10 11:36:42'),
(75, 2, '', 'Searched for: \'reports\'', '2025-08-10 11:37:19'),
(76, 2, '', 'Searched for: \'reports\'', '2025-08-10 11:37:28'),
(77, 2, '', 'User logged out.', '2025-08-10 12:33:42'),
(78, 1, '', 'User logged in.', '2025-08-10 19:25:31'),
(79, 1, '', 'Permanently deleted document: Unknown (ID: 27)', '2025-08-10 19:40:25'),
(80, 1, '', 'Document previewed: aaa', '2025-08-10 20:38:48'),
(81, 1, '', 'Document uploaded: Reports', '2025-08-10 20:41:09'),
(82, 1, '', 'Document previewed: Reports', '2025-08-10 20:42:37'),
(83, 1, '', 'Searched for: \'reports-2025\'', '2025-08-10 20:43:14'),
(84, 1, '', 'Searched for: \'reports-2025\'', '2025-08-10 20:43:58'),
(85, 1, '', 'Document previewed: Reports', '2025-08-10 20:44:03'),
(86, 1, '', 'Searched for: \'reports-2025\'', '2025-08-10 20:44:05'),
(87, 1, '', 'Shared document: Reports (ID: 32) to Rodary Tabasan with permission: view', '2025-08-10 20:44:21'),
(88, 1, '', 'User logged out.', '2025-08-10 20:45:34'),
(89, 2, '', 'User logged in.', '2025-08-10 20:45:44'),
(90, 2, '', 'Document previewed: Reports', '2025-08-10 20:45:53'),
(91, 2, '', 'Shared document: pic (ID: 31) to System Administrator with permission: download', '2025-08-10 20:51:00'),
(92, 2, '', 'Unshared document: pic (ID: 31)', '2025-08-10 20:54:55'),
(93, 2, '', 'Shared document: pic (ID: 31) to System Administrator with permission: view', '2025-08-10 20:55:03'),
(94, 2, '', 'Unshared document: pic (ID: 31)', '2025-08-10 20:55:35'),
(95, 2, '', 'Shared document: pic (ID: 31) to System Administrator with permission: download', '2025-08-10 20:55:40'),
(96, 2, '', 'Unshared document: pic (ID: 31)', '2025-08-10 20:57:46'),
(97, 2, '', 'Shared document: pic (ID: 31) to System Administrator with permission: download', '2025-08-10 20:57:54'),
(98, 2, '', 'User logged out.', '2025-08-10 20:58:20'),
(99, 1, '', 'User logged in.', '2025-08-10 20:58:31'),
(100, 1, '', 'Document previewed: sample', '2025-08-10 20:58:38'),
(101, 1, '', 'User logged out.', '2025-08-10 20:59:35'),
(102, 1, '', 'User logged in.', '2025-08-11 02:48:21'),
(103, 1, '', 'Document previewed: sample', '2025-08-11 02:49:00'),
(104, 1, '', 'Document previewed: aaa', '2025-08-11 02:49:05'),
(105, 1, '', 'Document previewed: pic', '2025-08-11 02:50:47'),
(106, 1, '', 'Document previewed: tesias', '2025-08-11 02:50:56'),
(107, 1, '', 'Document previewed: sample', '2025-08-11 02:51:05'),
(108, 1, '', 'Searched for: \'reports-2025\'', '2025-08-11 02:51:16'),
(109, 1, '', 'Searched for: \'reports-2025\'', '2025-08-11 02:51:33');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `color` varchar(20) DEFAULT '#6c757d',
  `created_by` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`, `color`, `created_by`) VALUES
(11, 'reports', '', '2025-08-06 07:54:09', '#004f80', 1),
(12, 'Letters', '', '2025-08-06 07:54:17', '#004f80', 1),
(13, 'audit', '', '2025-08-06 07:54:28', '#004f80', 1),
(14, 'financial reports', '', '2025-08-06 07:54:39', '#004f80', 1),
(15, 'logs', '', '2025-08-06 07:55:04', '#004f80', 1);

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `filename` varchar(255) NOT NULL,
  `original_filename` varchar(255) NOT NULL,
  `file_size` bigint(20) NOT NULL,
  `file_type` varchar(100) NOT NULL,
  `file_path` text NOT NULL,
  `folder_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `uploaded_by` int(11) NOT NULL,
  `is_public` tinyint(1) DEFAULT 0,
  `tags` text DEFAULT NULL,
  `download_count` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_deleted` tinyint(1) DEFAULT 0,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `title`, `description`, `filename`, `original_filename`, `file_size`, `file_type`, `file_path`, `folder_id`, `category_id`, `uploaded_by`, `is_public`, `tags`, `download_count`, `created_at`, `updated_at`, `is_deleted`, `deleted_at`) VALUES
(12, 'Sample Documents', 'Student info', '68940f54cd740_1754533716.docx', 'Dedication.docx', 14156, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'uploads/68940f54cd740_1754533716.docx', 6, 14, 1, 1, '', 0, '2025-08-06 22:28:36', '2025-08-06 22:28:36', 0, NULL),
(13, 'ddddd', 'ddddd', '6894116043cc8_1754534240.xlsx', 'Sample.xlsx', 9038, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'uploads/6894116043cc8_1754534240.xlsx', 7, 13, 1, 1, '', 0, '2025-08-06 22:37:20', '2025-08-06 22:37:20', 0, NULL),
(14, 'ddddd', 'dddd', '689411ea19daf_1754534378.docx', 'Tech Stack.docx', 16641, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'uploads/689411ea19daf_1754534378.docx', 6, 12, 1, 1, '2024', 0, '2025-08-06 22:39:38', '2025-08-06 22:39:38', 0, NULL),
(15, 'aaaa', 'aaaa', '6894121f6b004_1754534431.pdf', 'TRACK- TVET RECORD ARCHIVAL AND CONTROL KIOSK.pdf', 187917, 'application/pdf', 'uploads/6894121f6b004_1754534431.pdf', 6, 11, 1, 1, '', 0, '2025-08-06 22:40:31', '2025-08-09 09:16:49', 0, NULL),
(17, 'pic', 'picture', '68942f2227fdf_1754541858.png', 'Screenshot 2025-08-04 225319.png', 222244, 'image/png', 'uploads/68942f2227fdf_1754541858.png', NULL, 15, 1, 1, '', 0, '2025-08-07 00:44:18', '2025-08-07 00:44:18', 0, NULL),
(18, 'aaa', 'aaaa', '689433e5ccf20_1754543077.docx', 'APIcloudconvert.docx', 14234, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'uploads/689433e5ccf20_1754543077.docx', 6, 14, 1, 1, '', 1, '2025-08-07 01:04:37', '2025-08-07 01:16:46', 0, NULL),
(20, 'sample', 'smple', '6894a36fe5fbe_1754571631.xlsx', 'Sample.xlsx', 9038, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'uploads/6894a36fe5fbe_1754571631.xlsx', 6, 13, 1, 1, '', 0, '2025-08-07 09:00:31', '2025-08-07 09:00:31', 0, NULL),
(23, 'aaa', 'aaa', '68969fcfa1357_1754701775.docx', 'Dedication.docx', 14156, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'uploads/68969fcfa1357_1754701775.docx', 7, 11, 1, 1, '', 0, '2025-08-08 21:09:35', '2025-08-08 21:09:35', 0, NULL),
(24, 'aaa', 'aaaa', '68969fdd13c04_1754701789.docx', 'ClientLetter.docx', 240356, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'uploads/68969fdd13c04_1754701789.docx', NULL, 13, 1, 1, '', 0, '2025-08-08 21:09:49', '2025-08-08 21:09:49', 0, NULL),
(25, 'sample', '', '6896a0a56669b_1754701989.png', 'Screenshot 2025-08-04 225319.png', 222244, 'image/png', 'uploads/6896a0a56669b_1754701989.png', 7, 12, 2, 1, '', 0, '2025-08-08 21:13:09', '2025-08-08 21:13:09', 0, NULL),
(26, 'test', '', '6896a0b9dbd84_1754702009.docx', 'APIcloudconvert.docx', 14234, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'uploads/6896a0b9dbd84_1754702009.docx', NULL, 11, 2, 1, '', 0, '2025-08-08 21:13:29', '2025-08-09 10:22:42', 0, NULL),
(28, 'sss', 'sss', '689746b222800_1754744498.pptx', 'MAAM-JAM.pptx', 1474702, 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'uploads/689746b222800_1754744498.pptx', 7, 14, 2, 1, '', 0, '2025-08-09 09:01:38', '2025-08-09 09:01:38', 0, NULL),
(29, 'sss', 'sss', '689746c3d9e0d_1754744515.docx', 'TRACK(FINAL NA FINAL).docx', 642932, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'uploads/689746c3d9e0d_1754744515.docx', NULL, 14, 2, 1, '', 0, '2025-08-09 09:01:55', '2025-08-09 09:01:55', 0, NULL),
(30, 'tesias', 'aaa', '6898169d6c460_1754797725.png', 'LandingPage.png', 131677, 'image/png', 'uploads/6898169d6c460_1754797725.png', NULL, 13, 2, 1, '', 0, '2025-08-09 23:48:45', '2025-08-09 23:48:45', 0, NULL),
(31, 'pic', 'sss', '689816b45029b_1754797748.png', 'Annotation 2025-07-09 031234.png', 74815, 'image/png', 'uploads/689816b45029b_1754797748.png', NULL, 12, 2, 1, '', 1, '2025-08-09 23:49:08', '2025-08-10 12:16:49', 0, NULL),
(32, 'Reports', 'reports in audit', '68993c2547586_1754872869.docx', 'ClientLetter.docx', 240356, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'uploads/68993c2547586_1754872869.docx', 8, 11, 1, 1, 'reports-2025', 0, '2025-08-10 20:41:09', '2025-08-10 20:41:09', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `document_shares`
--

CREATE TABLE `document_shares` (
  `id` int(11) NOT NULL,
  `document_id` int(11) NOT NULL,
  `shared_with` int(11) NOT NULL,
  `shared_by` int(11) NOT NULL,
  `permission` enum('view','edit') DEFAULT 'view',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `document_shares`
--

INSERT INTO `document_shares` (`id`, `document_id`, `shared_with`, `shared_by`, `permission`, `created_at`) VALUES
(1, 18, 2, 1, 'view', '2025-08-07 04:54:58'),
(2, 26, 1, 2, '', '2025-08-08 21:13:58'),
(3, 25, 1, 2, 'view', '2025-08-08 21:14:26'),
(4, 32, 2, 1, 'view', '2025-08-10 20:44:21'),
(8, 31, 1, 2, '', '2025-08-10 20:57:54');

-- --------------------------------------------------------

--
-- Table structure for table `folders`
--

CREATE TABLE `folders` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `color` varchar(20) DEFAULT '#007bff',
  `parent_id` int(11) DEFAULT NULL,
  `level` int(11) DEFAULT 0,
  `sort_order` int(11) DEFAULT 0,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `folders`
--

INSERT INTO `folders` (`id`, `name`, `description`, `color`, `parent_id`, `level`, `sort_order`, `created_by`, `created_at`) VALUES
(6, 'Trainings', '', '#b53b76', NULL, 0, 0, NULL, '2025-08-01 10:32:50'),
(7, 'Assesments', '', '#007bff', NULL, 0, 1, 1, '2025-08-06 04:06:52'),
(8, 'Reports folder', '', '#004f80', NULL, 0, 2, 1, '2025-08-10 20:39:50');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `key` varchar(50) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`key`, `value`) VALUES
('items_per_page', '20'),
('site_email', 'rey@gmail.com'),
('site_name', 'Rey Tabasan');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(200) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `role` enum('user','admin','superadmin') DEFAULT 'user',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_logged_in` tinyint(1) DEFAULT 0,
  `status` enum('pending','active','disabled') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `full_name`, `role`, `created_at`, `updated_at`, `is_logged_in`, `status`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$E2ipx9yhTZ77sdfVqD05Pup6mhKTrvq/w7IadlM8MXzugunpm5VLy', 'System Administrator', 'superadmin', '2025-07-31 20:20:00', '2025-08-11 02:48:21', 1, 'active'),
(2, 'rey', 'rey@gmail.com', '$2y$10$y7qIRZmO1JQ3lXltIsiXGe5C.4M7USTl.EkmtxjAjdDFF3tBqbjDu', 'Rodary Tabasan', 'user', '2025-08-06 04:10:20', '2025-08-10 20:58:20', 0, 'active'),
(3, 'jaki', 'jaki@gmail.com', '$2y$10$2dqs1Kms5AO1083m0VDmSuCRy7j8eAK4VMQN0lwJELWVBU7sYiEya', 'Jacqueline Mape', 'user', '2025-08-09 00:49:11', '2025-08-09 06:19:01', 0, 'active'),
(4, 'nora', 'nora@gmail.com', '$2y$10$iR4gWLag9HOT5IXKEdgP2.MTC2NCPrk7vMquN4Jv7Ouk6C3LjcWhG', 'Noralyn Saludares', 'user', '2025-08-09 01:48:48', '2025-08-09 18:45:24', 0, 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `folder_id` (`folder_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `uploaded_by` (`uploaded_by`);

--
-- Indexes for table `document_shares`
--
ALTER TABLE `document_shares`
  ADD PRIMARY KEY (`id`),
  ADD KEY `document_id` (`document_id`),
  ADD KEY `shared_with` (`shared_with`),
  ADD KEY `shared_by` (`shared_by`);

--
-- Indexes for table `folders`
--
ALTER TABLE `folders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `document_shares`
--
ALTER TABLE `document_shares`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `folders`
--
ALTER TABLE `folders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`folder_id`) REFERENCES `folders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `documents_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `documents_ibfk_3` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `document_shares`
--
ALTER TABLE `document_shares`
  ADD CONSTRAINT `document_shares_ibfk_1` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `document_shares_ibfk_2` FOREIGN KEY (`shared_with`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `document_shares_ibfk_3` FOREIGN KEY (`shared_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `folders`
--
ALTER TABLE `folders`
  ADD CONSTRAINT `folders_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `folders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `folders_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
