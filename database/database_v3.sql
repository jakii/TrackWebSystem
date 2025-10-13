-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 09, 2025 at 03:32 PM
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
(1, 1, '', 'User logged out.', '2025-09-04 19:53:29'),
(2, 2, '', 'User logged in.', '2025-09-04 19:53:39'),
(3, 2, '', 'User logged out.', '2025-09-04 19:54:25'),
(4, 1, '', 'User logged in.', '2025-09-04 19:54:43'),
(5, 2, '', 'User logged in.', '2025-09-04 19:54:54'),
(6, 1, '', 'User logged in.', '2025-09-05 02:53:19'),
(7, 2, '', 'User logged in.', '2025-09-05 02:56:12'),
(8, 1, '', 'Document uploaded: Ever Green Tea', '2025-09-05 04:30:10'),
(9, 1, '', 'Document uploaded: Progress-Report (TRACK) (2)', '2025-09-05 04:30:10'),
(10, 1, '', 'Document uploaded: Ever Green Tea', '2025-09-05 04:31:50'),
(11, 1, '', 'Document uploaded: Progress-Report (TRACK) (2)', '2025-09-05 04:31:50'),
(12, 1, '', 'Downloaded document: Ever Green Tea (ID: 56)', '2025-09-05 04:32:13'),
(13, 1, '', 'Document previewed: Ever Green Tea', '2025-09-05 04:32:17'),
(14, 1, '', 'Document uploaded: Progress-Report (TRACK) (1)', '2025-09-05 04:35:26'),
(15, 1, '', 'Document uploaded: APIcloudconvert', '2025-09-05 04:35:51'),
(16, 1, '', 'Document uploaded: APIcloudconvert', '2025-09-05 04:46:08'),
(17, 1, '', 'Document uploaded: Sample', '2025-09-05 04:47:14'),
(18, 1, '', 'Document uploaded: Sample', '2025-09-05 04:48:50'),
(19, 1, '', 'Document uploaded: ClientLetter', '2025-09-05 04:49:19'),
(20, 2, '', 'Document uploaded: ClientLetter', '2025-09-05 07:59:29'),
(21, 2, '', 'Document uploaded: Dedication', '2025-09-05 07:59:29'),
(22, 2, '', 'Document uploaded: Resume', '2025-09-05 07:59:29'),
(23, 2, '', 'Document previewed: ClientLetter', '2025-09-05 08:00:03'),
(24, 2, '', 'Document previewed: ClientLetter', '2025-09-05 08:01:00'),
(25, 2, '', 'Document previewed: ClientLetter', '2025-09-05 08:01:27'),
(26, 2, '', 'Document uploaded: Tech Stack', '2025-09-05 08:02:15'),
(27, 2, '', 'Document previewed: Tech Stack', '2025-09-05 08:04:54'),
(28, 2, '', 'Document uploaded: Green simple business model canvas poster', '2025-09-05 08:14:35'),
(29, 2, '', 'Deleted document: Green simple business model canvas poster (ID: 68)', '2025-09-05 08:14:57'),
(30, 2, '', 'Document previewed: Tech Stack', '2025-09-05 08:48:12'),
(31, 2, '', 'Downloaded document: Tech Stack (ID: 67)', '2025-09-05 08:48:52'),
(32, 2, '', 'Downloaded document: ClientLetter (ID: 63)', '2025-09-05 08:49:22'),
(33, 1, '', 'Downloaded document: Tech Stack (ID: 67)', '2025-09-05 08:49:45'),
(34, 2, '', 'Document previewed: Green simple business model canvas poster', '2025-09-05 09:14:49'),
(35, 1, '', 'Document uploaded: ClientLetter', '2025-09-05 09:18:47'),
(36, 1, '', 'Document uploaded: ClientLetter', '2025-09-05 09:19:17'),
(37, 1, '', 'Document previewed: ClientLetter', '2025-09-05 09:19:27'),
(38, 1, '', 'Document uploaded: Dedication', '2025-09-05 09:22:03'),
(39, 1, '', 'Document uploaded: Dedication', '2025-09-05 09:25:07'),
(40, 1, '', 'Document uploaded: Dedication', '2025-09-05 09:25:10'),
(41, 1, '', 'Document uploaded: Dedication', '2025-09-05 09:26:40'),
(42, 1, '', 'Document uploaded: Screenshot 2025-09-04 082609', '2025-09-05 09:26:55'),
(43, 1, '', 'Deleted document: Progress-Report (TRACK) (2) (ID: 55)', '2025-09-05 09:27:48'),
(44, 1, '', 'Deleted document: Dedication (ID: 71)', '2025-09-05 09:27:53'),
(45, 1, '', 'Deleted document: ClientLetter (ID: 70)', '2025-09-05 09:27:58'),
(46, 1, '', 'Deleted document: ClientLetter (ID: 69)', '2025-09-05 09:28:03'),
(47, 1, '', 'Deleted document: Dedication (ID: 72)', '2025-09-05 09:28:08'),
(48, 1, '', 'Deleted document: Dedication (ID: 73)', '2025-09-05 09:28:14'),
(49, 1, '', 'Deleted document: Dedication (ID: 74)', '2025-09-05 09:28:18'),
(50, 1, '', 'User logged out.', '2025-09-05 09:34:02'),
(52, 1, '', 'User logged in.', '2025-09-05 09:36:10'),
(53, 2, '', 'Deleted document: Green simple bmc poster (ID: 68)', '2025-09-05 09:37:02'),
(54, 1, '', 'Searched for: \'letters\'', '2025-09-05 09:39:26'),
(55, 1, '', 'Document previewed: ClientLetter', '2025-09-05 09:39:33'),
(56, 2, '', 'User logged out.', '2025-09-05 09:48:28'),
(57, 1, '', 'User logged in.', '2025-09-05 09:50:06'),
(58, 1, '', 'User logged out.', '2025-09-05 09:50:30'),
(59, 1, '', 'User logged in.', '2025-09-05 18:49:41'),
(60, 2, '', 'User logged in.', '2025-09-05 18:50:25'),
(61, 2, '', 'Document previewed: Screenshot 2025-09-04 082609', '2025-09-05 18:51:17'),
(62, 2, '', 'User logged out.', '2025-09-05 18:53:07'),
(63, 2, '', 'User logged in.', '2025-09-05 18:53:20'),
(64, 1, '', 'Deleted document: Resume (ID: 66)', '2025-09-05 19:22:04'),
(65, 1, '', 'Deleted document: ClientLetter (ID: 63)', '2025-09-05 19:22:08'),
(66, 1, '', 'Deleted document: Sample (ID: 62)', '2025-09-05 19:22:12'),
(67, 1, '', 'Deleted document: Sample (ID: 61)', '2025-09-05 19:22:17'),
(68, 1, '', 'Deleted document: APIcloudconvert (ID: 60)', '2025-09-05 19:22:23'),
(69, 1, '', 'Deleted document: APIcloudconvert (ID: 59)', '2025-09-05 19:22:28'),
(70, 1, '', 'Deleted document: Progress-Report (TRACK) (1) (ID: 58)', '2025-09-05 19:22:32'),
(71, 1, '', 'Deleted document: Ever Green Tea (ID: 54)', '2025-09-05 19:22:41'),
(72, 1, '', 'Deleted document: Ever Green Tea (ID: 56)', '2025-09-05 19:22:46'),
(73, 1, '', 'Deleted document: Progress-Report (TRACK) (2) (ID: 57)', '2025-09-05 19:22:51'),
(74, 1, '', 'Deleted document: Dedication (ID: 65)', '2025-09-05 19:22:55'),
(75, 1, '', 'Deleted document: ClientLetter (ID: 64)', '2025-09-05 19:23:00'),
(76, 1, '', 'Deleted document: Tech Stack (ID: 67)', '2025-09-05 19:23:04'),
(77, 1, '', 'Deleted document: Screenshot 2025-09-04 082609 (ID: 75)', '2025-09-05 19:23:09'),
(78, 2, '', 'Document uploaded: Progress-Report (TRACK) (2)', '2025-09-05 20:03:12'),
(79, 2, '', 'Document uploaded: banana_chips_bmc', '2025-09-05 20:03:40'),
(80, 2, '', 'Deleted document: banana_chips_bmc (ID: 77)', '2025-09-05 20:04:06'),
(81, 2, '', 'Deleted document: Progress-Report (TRACK) (2) (ID: 76)', '2025-09-05 20:04:36'),
(82, 2, '', 'Document uploaded: Ever Green Tea', '2025-09-05 20:08:31'),
(83, 1, '', 'Deleted document: piso_water_bmc.pdf (ID: 79)', '2025-09-05 20:48:19'),
(84, 1, '', 'Deleted document: APIcloudconvert.docx (ID: 80)', '2025-09-05 20:52:19'),
(85, 1, '', 'Document uploaded: ClientLetter', '2025-09-05 20:54:23'),
(86, 1, '', 'Deleted document: APIcloudconvert.docx (ID: 83)', '2025-09-05 21:03:37'),
(87, 1, '', 'Deleted document: Dedication.docx (ID: 84)', '2025-09-05 21:03:52'),
(88, 1, '', 'Deleted document: sample.pptx (ID: 85)', '2025-09-05 21:04:03'),
(89, 1, '', 'Deleted document: 1755428545164.jpg (ID: 88)', '2025-09-05 21:08:37'),
(90, 1, '', 'Deleted document: Dedication.docx (ID: 89)', '2025-09-05 21:08:44'),
(91, 1, '', 'Deleted document: ClientLetter.docx (ID: 90)', '2025-09-05 21:17:44'),
(92, 2, '', 'Document uploaded: piso_water_bmc', '2025-09-05 21:30:55'),
(93, 2, '', 'Deleted document: piso_water_bmc (ID: 91)', '2025-09-05 21:31:11'),
(94, 2, '', 'Document uploaded: piso_water_bmc', '2025-09-05 21:31:26'),
(95, 2, '', 'Document uploaded: BUSINESS MODEL CANVAS', '2025-09-05 21:32:49'),
(96, 2, '', 'Deleted document: BUSINESS MODEL CANVAS (ID: 93)', '2025-09-05 21:34:26'),
(97, 2, '', 'Deleted document: piso_water_bmc (ID: 92)', '2025-09-05 21:34:37'),
(98, 2, '', 'Document uploaded: Ever Green Tea', '2025-09-05 21:34:58'),
(99, 2, '', 'Document uploaded: piso_water_bmc', '2025-09-05 21:38:20'),
(100, 2, '', 'Deleted document: Ever Green Tea (ID: 94)', '2025-09-05 21:38:48'),
(101, 2, '', 'Document uploaded: BUSINESS MODEL CANVAS', '2025-09-05 21:39:01'),
(102, 2, '', 'Deleted document: BUSINESS MODEL CANVAS (ID: 96)', '2025-09-05 21:40:51'),
(103, 2, '', 'Document uploaded: Ever Green Tea', '2025-09-05 21:51:17'),
(104, 2, '', 'Document uploaded: Progress-Report (TRACK) - Copy', '2025-09-05 22:01:58'),
(105, 2, '', 'Document previewed: Progress-Report (TRACK) - Copy', '2025-09-05 22:02:18'),
(106, 2, '', 'Deleted document: Ever Green Tea (ID: 97)', '2025-09-05 22:02:49'),
(107, 1, '', 'Deleted document: ClientLetter.docx (ID: 81)', '2025-09-05 22:03:24'),
(108, 1, '', 'Downloaded document: Progress-Report (TRACK) - Copy (ID: 98)', '2025-09-05 22:03:45'),
(109, 1, '', 'Deleted document: Ever Green Tea (ID: 78)', '2025-09-05 22:04:03'),
(110, 1, '', 'Deleted document: Progress-Report (TRACK) - Copy (ID: 98)', '2025-09-05 22:04:17'),
(111, 1, '', 'Deleted document: piso_water_bmc (ID: 95)', '2025-09-05 22:04:21'),
(112, 1, '', 'Deleted document: ClientLetter (ID: 82)', '2025-09-05 22:04:28'),
(113, 1, '', 'Document uploaded: aaa', '2025-09-06 01:37:03'),
(114, 1, '', 'Deleted document: aaa (ID: 99)', '2025-09-06 01:37:27'),
(115, 2, '', 'User logged out.', '2025-09-06 01:39:42'),
(116, 1, '', 'User logged in.', '2025-09-06 17:55:09'),
(117, 1, '', 'Document uploaded: APIcloudconvert', '2025-09-07 00:05:04'),
(118, 1, '', 'Deleted document: APIcloudconvert (ID: 100)', '2025-09-07 00:05:50'),
(119, 1, '', 'Document uploaded: aaa', '2025-09-07 03:18:57'),
(120, 1, '', 'Deleted document: aaa (ID: 101)', '2025-09-07 03:19:25'),
(121, 1, '', 'Deleted folder: test', '2025-09-07 03:32:36'),
(122, 1, '', 'Deleted folder: aaa', '2025-09-07 03:32:41'),
(123, 1, '', 'Deleted folder: NCII', '2025-09-07 03:32:50'),
(124, 2, '', 'User logged in.', '2025-09-07 03:34:09'),
(125, 2, '', 'Document uploaded: aaa', '2025-09-07 03:34:38'),
(126, 2, '', 'Document uploaded: Progress-Report (TRACK) - Copy', '2025-09-07 03:34:38'),
(127, 2, '', 'Document uploaded: Ever Green Tea', '2025-09-07 03:34:38'),
(128, 2, '', 'Document uploaded: Green simple business model canvas poster', '2025-09-07 03:34:38'),
(129, 2, '', 'Document uploaded: piso_water_bmc', '2025-09-07 03:34:38'),
(130, 2, '', 'Document uploaded: banana_chips_bmc', '2025-09-07 03:34:38'),
(131, 2, '', 'Document uploaded: BUSINESS MODEL CANVAS', '2025-09-07 03:34:38'),
(132, 1, '', 'Document previewed: aaa', '2025-09-07 03:35:12'),
(133, 1, '', 'Document previewed: aaa', '2025-09-07 03:35:17'),
(134, 1, '', 'Document previewed: Progress-Report (TRACK) - Copy', '2025-09-07 03:35:24'),
(135, 1, '', 'Document previewed: Ever Green Tea', '2025-09-07 03:35:42'),
(136, 2, '', 'Deleted document: BUSINESS MODEL CANVAS (ID: 108)', '2025-09-07 03:37:16'),
(137, 2, '', 'Deleted document: banana_chips_bmc (ID: 107)', '2025-09-07 03:37:25'),
(138, 2, '', 'Deleted document: piso_water_bmc (ID: 106)', '2025-09-07 03:37:33'),
(139, 2, '', 'Deleted document: Green simple business model canvas poster (ID: 105)', '2025-09-07 03:37:40'),
(140, 2, '', 'Deleted document: Ever Green Tea (ID: 104)', '2025-09-07 03:37:44'),
(141, 2, '', 'Deleted document: Progress-Report (TRACK) - Copy (ID: 103)', '2025-09-07 03:37:47'),
(142, 2, '', 'Deleted document: aaa (ID: 102)', '2025-09-07 03:37:52'),
(143, 1, '', 'User logged in.', '2025-09-09 08:35:23'),
(144, 1, '', 'Document uploaded: aaa', '2025-09-09 08:36:09'),
(145, 2, '', 'User logged in.', '2025-09-09 08:36:50'),
(146, 2, '', 'Document previewed: aaa', '2025-09-09 08:36:57'),
(147, 2, '', 'Document previewed: aaa', '2025-09-09 08:37:01'),
(148, 2, '', 'Document previewed: aaa', '2025-09-09 08:37:19'),
(149, 2, '', 'Document previewed: aaa', '2025-09-09 08:37:47'),
(150, 2, '', 'Downloaded document: aaa (ID: 109)', '2025-09-09 08:37:50'),
(151, 1, '', 'Deleted document: aaa (ID: 109)', '2025-09-09 08:38:19'),
(152, 1, '', 'Document uploaded: MySQL Dump', '2025-09-09 08:40:05'),
(153, 1, '', 'Document previewed: MySQL Dump', '2025-09-09 08:40:09'),
(154, 1, '', 'Downloaded document: MySQL Dump (ID: 110)', '2025-09-09 08:40:37'),
(155, 1, '', 'Deleted folder: Trainings', '2025-09-09 08:43:07'),
(156, 1, '', 'Deleted folder: Assesments', '2025-09-09 08:44:33'),
(157, 1, '', 'Deleted folder: Assesments', '2025-09-09 08:45:22'),
(158, 1, '', 'Deleted folder: Assesments', '2025-09-09 08:48:33'),
(159, 1, '', 'Deleted folder: sss', '2025-09-09 08:48:51'),
(160, 1, '', 'Deleted document: MySQL Dump (ID: 110)', '2025-09-09 08:51:55'),
(161, 2, '', 'Document uploaded: banana_chips_bmc', '2025-09-09 08:53:08'),
(162, 2, '', 'Document previewed: banana_chips_bmc', '2025-09-09 08:53:12'),
(163, 2, '', 'Document previewed: banana_chips_bmc', '2025-09-09 08:53:21'),
(164, 2, '', 'Document previewed: banana_chips_bmc', '2025-09-09 08:53:32'),
(165, 2, '', 'Document previewed: banana_chips_bmc', '2025-09-09 08:53:34'),
(166, 2, '', 'Document previewed: banana_chips_bmc', '2025-09-09 08:53:39'),
(167, 1, '', 'Document previewed: banana_chips_bmc', '2025-09-09 08:53:50'),
(168, 1, '', 'Document previewed: banana_chips_bmc', '2025-09-09 09:27:12'),
(169, 2, '', 'Document previewed: banana_chips_bmc', '2025-09-09 09:28:35'),
(170, 2, '', 'Deleted document: banana_chips_bmc (ID: 111)', '2025-09-09 09:28:54'),
(171, 1, '', 'User logged out.', '2025-09-09 09:30:51');

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
(21, 'Letters', '', '2025-09-05 07:47:50', '#004f80', 1),
(22, 'Reports', '', '2025-09-05 07:48:12', '#004f80', 1),
(23, 'audit', '', '2025-09-05 07:50:37', '#004f80', 1);

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
(102, 'aaa', '', '68bd358e05ec2_1757230478.png', 'aaa.png', 60399, 'image/png', 'C:\\xampp\\htdocs\\TrackWeb\\config/../documents/uploads/68bd358e05ec2_1757230478.png', 41, 23, 2, 1, '', 0, '2025-09-07 03:34:38', '2025-09-07 03:37:52', 1, '2025-09-07 03:37:52'),
(103, 'Progress-Report (TRACK) - Copy', '', '68bd358e07700_1757230478.docx', 'Progress-Report (TRACK) - Copy.docx', 1285669, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'C:\\xampp\\htdocs\\TrackWeb\\config/../documents/uploads/68bd358e07700_1757230478.docx', 41, 23, 2, 1, '', 0, '2025-09-07 03:34:38', '2025-09-07 03:37:47', 1, '2025-09-07 03:37:47'),
(104, 'Ever Green Tea', '', '68bd358e08d8e_1757230478.docx', 'Ever Green Tea.docx', 18013, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'C:\\xampp\\htdocs\\TrackWeb\\config/../documents/uploads/68bd358e08d8e_1757230478.docx', 41, 23, 2, 1, '', 0, '2025-09-07 03:34:38', '2025-09-07 03:37:44', 1, '2025-09-07 03:37:44'),
(105, 'Green simple business model canvas poster', '', '68bd358e0a119_1757230478.png', 'Green simple business model canvas poster.png', 284066, 'image/png', 'C:\\xampp\\htdocs\\TrackWeb\\config/../documents/uploads/68bd358e0a119_1757230478.png', 41, 23, 2, 1, '', 0, '2025-09-07 03:34:38', '2025-09-07 03:37:40', 1, '2025-09-07 03:37:40'),
(106, 'piso_water_bmc', '', '68bd358e0bff2_1757230478.pdf', 'piso_water_bmc.pdf', 2862, 'application/pdf', 'C:\\xampp\\htdocs\\TrackWeb\\config/../documents/uploads/68bd358e0bff2_1757230478.pdf', 41, 23, 2, 1, '', 0, '2025-09-07 03:34:38', '2025-09-07 03:37:33', 1, '2025-09-07 03:37:33'),
(107, 'banana_chips_bmc', '', '68bd358e0cd51_1757230478.pdf', 'banana_chips_bmc.pdf', 2871, 'application/pdf', 'C:\\xampp\\htdocs\\TrackWeb\\config/../documents/uploads/68bd358e0cd51_1757230478.pdf', 41, 23, 2, 1, '', 0, '2025-09-07 03:34:38', '2025-09-07 03:37:25', 1, '2025-09-07 03:37:25'),
(108, 'BUSINESS MODEL CANVAS', '', '68bd358e0dc34_1757230478.pdf', 'BUSINESS MODEL CANVAS.pdf', 131942, 'application/pdf', 'C:\\xampp\\htdocs\\TrackWeb\\config/../documents/uploads/68bd358e0dc34_1757230478.pdf', 41, 23, 2, 1, '', 0, '2025-09-07 03:34:38', '2025-09-07 03:37:16', 1, '2025-09-07 03:37:16'),
(109, 'aaa', 'AA', '68c01f391e1d7_1757421369.png', 'aaa.png', 60399, 'image/png', 'C:\\xampp\\htdocs\\TrackWeb\\config/../documents/uploads/68c01f391e1d7_1757421369.png', 41, 21, 1, 1, 'letter-2025', 1, '2025-09-09 08:36:09', '2025-09-09 08:38:19', 1, '2025-09-09 08:38:19'),
(110, 'MySQL Dump', '', '68c020255aeb8_1757421605.docx', 'MySQL Dump.docx', 15208, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'C:\\xampp\\htdocs\\TrackWeb\\config/../documents/uploads/68c020255aeb8_1757421605.docx', 41, 21, 1, 1, '', 1, '2025-09-09 08:40:05', '2025-09-09 08:51:55', 1, '2025-09-09 08:51:55'),
(111, 'banana_chips_bmc', '', '68c02334bb003_1757422388.pdf', 'banana_chips_bmc.pdf', 2871, 'application/pdf', 'C:\\xampp\\htdocs\\TrackWeb\\config/../documents/uploads/68c02334bb003_1757422388.pdf', 49, 22, 2, 1, '', 0, '2025-09-09 08:53:08', '2025-09-09 09:28:54', 1, '2025-09-09 09:28:54');

-- --------------------------------------------------------

--
-- Table structure for table `document_activity`
--

CREATE TABLE `document_activity` (
  `id` int(11) NOT NULL,
  `document_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `activity_type` enum('view','download') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `document_activity`
--

INSERT INTO `document_activity` (`id`, `document_id`, `user_id`, `activity_type`, `created_at`) VALUES
(15, 104, 1, 'view', '2025-09-07 07:36:24'),
(16, 109, 2, 'view', '2025-09-09 12:37:22'),
(17, 109, 2, 'view', '2025-09-09 12:37:49'),
(18, 109, 2, 'download', '2025-09-09 12:37:50'),
(19, 110, 1, 'download', '2025-09-09 12:40:37'),
(20, 110, 1, 'view', '2025-09-09 12:40:43');

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
(39, 'Trainings', 'aa', '#004f80', NULL, 0, 6, 1, '2025-09-07 03:17:31'),
(40, 'Reports', '', '#004f80', NULL, 0, 7, 1, '2025-09-07 03:17:37'),
(41, 'sample', '', '#004f80', 39, 1, 1, 1, '2025-09-07 03:17:43'),
(49, 'Assesments', '', '#004f80', NULL, 0, 8, 1, '2025-09-09 08:49:01');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_by` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `title`, `file_path`, `uploaded_by`, `created_at`) VALUES
(1, 'Sample', 'uploads/68baa3f25fe14_1757062130.xlsx', '1', '2025-09-05 08:48:50'),
(2, 'ClientLetter', 'uploads/68baa40f477c8_1757062159.docx', '1', '2025-09-05 08:49:19'),
(3, 'ClientLetter', 'uploads/68bad0a189f43_1757073569.docx', '2', '2025-09-05 11:59:29'),
(4, 'Dedication', 'uploads/68bad0a18b672_1757073569.docx', '2', '2025-09-05 11:59:29'),
(5, 'Resume', 'uploads/68bad0a18d44c_1757073569.docx', '2', '2025-09-05 11:59:29'),
(6, 'Tech Stack', 'uploads/68bad147e4413_1757073735.docx', '2', '2025-09-05 12:02:15'),
(7, 'Green simple business model canvas poster', 'uploads/68bad42b4520f_1757074475.png', '2', '2025-09-05 12:14:35'),
(8, 'ClientLetter', 'uploads/68bae337b30ca_1757078327.docx', '1', '2025-09-05 13:18:47'),
(9, 'ClientLetter', 'uploads/68bae35569a71_1757078357.docx', '1', '2025-09-05 13:19:17'),
(10, 'Dedication', 'uploads/68bae3fbf10fc_1757078523.docx', '1', '2025-09-05 13:22:03'),
(11, 'Dedication', 'uploads/68bae4b3a98c2_1757078707.docx', '1', '2025-09-05 13:25:07'),
(12, 'Dedication', 'uploads/68bae4b67fc3b_1757078710.docx', '1', '2025-09-05 13:25:10'),
(13, 'Dedication', 'uploads/68bae510266fd_1757078800.docx', '1', '2025-09-05 13:26:40'),
(14, 'Screenshot 2025-09-04 082609', 'uploads/68bae51f1594f_1757078815.png', '1', '2025-09-05 13:26:55'),
(15, 'Progress-Report (TRACK) (2)', 'uploads/68bb7a407adb3_1757116992.docx', '2', '2025-09-06 00:03:12'),
(16, 'banana_chips_bmc', 'uploads/68bb7a5c9e370_1757117020.pdf', '2', '2025-09-06 00:03:40'),
(17, 'Ever Green Tea', 'uploads/68bb7b7f882bb_1757117311.docx', '2', '2025-09-06 00:08:31'),
(18, 'ClientLetter', 'uploads/68bb863fa1c7e_1757120063.docx', '1', '2025-09-06 00:54:23');

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
(1, 'admin', 'admin@gmail.com', '$2y$10$E2ipx9yhTZ77sdfVqD05Pup6mhKTrvq/w7IadlM8MXzugunpm5VLy', 'System Administrator', 'superadmin', '2025-07-31 20:20:00', '2025-09-09 09:30:51', 0, 'active'),
(2, 'rey', 'rey@gmail.com', '$2y$10$y7qIRZmO1JQ3lXltIsiXGe5C.4M7USTl.EkmtxjAjdDFF3tBqbjDu', 'Rodary Tabasan', 'user', '2025-08-06 04:10:20', '2025-09-07 03:34:09', 1, 'active'),
(3, 'jaki', 'jaki@gmail.com', '$2y$10$2dqs1Kms5AO1083m0VDmSuCRy7j8eAK4VMQN0lwJELWVBU7sYiEya', 'Jacqueline Mape', 'user', '2025-08-09 00:49:11', '2025-09-03 08:04:32', 0, 'active'),
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
-- Indexes for table `document_activity`
--
ALTER TABLE `document_activity`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `document_activity_ibfk_1` (`document_id`);

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
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=172;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `document_activity`
--
ALTER TABLE `document_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `document_shares`
--
ALTER TABLE `document_shares`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `folders`
--
ALTER TABLE `folders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
-- Constraints for table `document_activity`
--
ALTER TABLE `document_activity`
  ADD CONSTRAINT `document_activity_ibfk_1` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `document_activity_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

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
