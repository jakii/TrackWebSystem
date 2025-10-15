-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 15, 2025 at 06:07 PM
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
(171, 1, '', 'User logged out.', '2025-09-09 09:30:51'),
(172, 1, '', 'User logged in.', '2025-09-09 19:47:12'),
(173, 1, '', 'Deleted folder: sample', '2025-09-09 19:47:40'),
(174, 1, '', 'User logged out.', '2025-09-09 19:58:25'),
(175, 1, '', 'User logged in.', '2025-09-09 20:29:54'),
(176, 2, '', 'User logged in.', '2025-09-09 20:48:24'),
(177, 2, '', 'Document uploaded: SKRMS Final Manuscript (January 08, 2025)', '2025-09-09 20:49:07'),
(178, 2, '', 'Shared document: SKRMS Final Manuscript (January 08, 2025) (ID: 112) to System Administrator with permission: download', '2025-09-09 20:49:32'),
(179, 1, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-09-09 20:49:40'),
(180, 1, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-09-09 20:49:46'),
(181, 2, '', 'Document uploaded: Progress-Report (TRACK) - Copy', '2025-09-09 20:52:13'),
(182, 2, '', 'Document uploaded: piso_water_bmc', '2025-09-09 20:58:28'),
(183, 1, '', 'Document previewed: piso_water_bmc', '2025-09-09 22:41:56'),
(184, 1, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-09-09 22:42:08'),
(185, 1, '', 'Document previewed: Progress-Report (TRACK) - Copy', '2025-09-09 22:42:21'),
(186, 1, '', 'Document previewed: piso_water_bmc', '2025-09-09 22:45:56'),
(187, 1, '', 'Document previewed: piso_water_bmc', '2025-09-09 22:46:43'),
(188, 1, '', 'Document previewed: piso_water_bmc', '2025-09-09 22:47:04'),
(189, 2, '', 'Document previewed: piso_water_bmc', '2025-09-09 22:53:25'),
(190, 2, '', 'Document previewed: piso_water_bmc', '2025-09-09 22:53:27'),
(191, 2, '', 'Document previewed: piso_water_bmc', '2025-09-09 22:53:29'),
(192, 2, '', 'Document previewed: piso_water_bmc', '2025-09-09 22:53:33'),
(193, 2, '', 'Document previewed: piso_water_bmc', '2025-09-09 22:53:33'),
(194, 2, '', 'Document previewed: piso_water_bmc', '2025-09-09 22:53:34'),
(195, 2, '', 'Document previewed: piso_water_bmc', '2025-09-09 22:53:35'),
(196, 2, '', 'Document previewed: piso_water_bmc', '2025-09-09 22:54:26'),
(197, 2, '', 'Document previewed: piso_water_bmc', '2025-09-09 22:55:18'),
(198, 2, '', 'Document previewed: piso_water_bmc', '2025-09-09 22:55:21'),
(199, 2, '', 'Document previewed: Progress-Report (TRACK) - Copy', '2025-09-09 22:55:29'),
(200, 2, '', 'Document uploaded: Sample', '2025-09-09 22:56:16'),
(201, 2, '', 'Document previewed: Sample', '2025-09-09 22:56:19'),
(202, 2, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-09-09 22:56:50'),
(203, 2, '', 'Deleted document: SKRMS Final Manuscript (January 08, 2025) (ID: 112)', '2025-09-09 22:56:57'),
(204, 2, '', 'Deleted document: piso_water_bmc (ID: 114)', '2025-09-09 22:57:02'),
(205, 2, '', 'Document uploaded: my_BMC', '2025-09-09 22:57:25'),
(206, 2, '', 'Document previewed: my_BMC', '2025-09-09 22:57:30'),
(207, 2, '', 'Document previewed: my_BMC', '2025-09-09 22:57:34'),
(208, 2, '', 'Document previewed: my_BMC', '2025-09-09 22:58:04'),
(209, 2, '', 'Document previewed: my_BMC', '2025-09-09 22:59:38'),
(210, 2, '', 'Document previewed: my_BMC', '2025-09-09 22:59:40'),
(211, 2, '', 'Document previewed: my_BMC', '2025-09-09 22:59:45'),
(212, 2, '', 'Document previewed: my_BMC', '2025-09-09 23:02:38'),
(213, 2, '', 'Deleted document: my_BMC (ID: 116)', '2025-09-09 23:02:46'),
(214, 2, '', 'Document previewed: Sample', '2025-09-09 23:02:50'),
(215, 2, '', 'Document previewed: Progress-Report (TRACK) - Copy', '2025-09-09 23:02:57'),
(216, 2, '', 'Downloaded document: Progress-Report (TRACK) - Copy (ID: 113)', '2025-09-09 23:03:20'),
(217, 2, '', 'Document uploaded: my_BMC', '2025-09-09 23:03:56'),
(218, 2, '', 'Document previewed: my_BMC', '2025-09-09 23:03:59'),
(219, 1, '', 'Document previewed: piso_water_bmc', '2025-09-09 23:05:45'),
(220, 1, '', 'Document uploaded: TabasanRodary_InnovationCanvas(4-A)', '2025-09-09 23:06:16'),
(221, 1, '', 'Document previewed: TabasanRodary_InnovationCanvas(4-A)', '2025-09-09 23:06:21'),
(222, 2, '', 'Document previewed: my_BMC', '2025-09-09 23:10:12'),
(223, 2, '', 'Document previewed: my_BMC', '2025-09-09 23:12:20'),
(224, 2, '', 'Document previewed: TabasanRodary_InnovationCanvas(4-A)', '2025-09-09 23:12:25'),
(225, 2, '', 'Document previewed: TabasanRodary_InnovationCanvas(4-A)', '2025-09-09 23:12:29'),
(226, 2, '', 'Downloaded document: Sample (ID: 115)', '2025-09-09 23:12:42'),
(227, 2, '', 'Document previewed: my_BMC', '2025-09-09 23:12:46'),
(228, 2, '', 'Document previewed: Sample', '2025-09-09 23:12:51'),
(229, 2, '', 'Document previewed: Progress-Report (TRACK) - Copy', '2025-09-09 23:12:59'),
(230, 1, '', 'Document previewed: TabasanRodary_InnovationCanvas(4-A)', '2025-09-10 22:35:56'),
(231, 1, '', 'User logged in.', '2025-09-12 08:25:52'),
(232, 1, '', 'Document previewed: TabasanRodary_InnovationCanvas(4-A)', '2025-09-12 08:27:04'),
(233, 1, '', 'Document previewed: Progress-Report (TRACK) - Copy', '2025-09-12 08:27:25'),
(234, 1, '', 'Deleted document: Progress-Report (TRACK) - Copy (ID: 113)', '2025-09-12 08:28:15'),
(235, 1, '', 'Deleted document: Sample (ID: 115)', '2025-09-12 08:28:19'),
(236, 1, '', 'Deleted document: my_BMC (ID: 117)', '2025-09-12 08:28:25'),
(237, 1, '', 'Deleted document: TabasanRodary_InnovationCanvas(4-A) (ID: 118)', '2025-09-12 08:28:30'),
(238, 1, '', 'Document uploaded: aaa', '2025-09-12 08:34:16'),
(239, 1, '', 'Document previewed: aaa', '2025-09-12 08:34:23'),
(240, 1, '', 'Document previewed: aaa', '2025-09-12 08:34:26'),
(241, 1, '', 'Document previewed: aaa', '2025-09-12 08:34:27'),
(242, 1, '', 'Document previewed: aaa', '2025-09-12 08:34:27'),
(243, 1, '', 'Document previewed: aaa', '2025-09-12 08:34:28'),
(244, 1, '', 'User logged in.', '2025-09-13 21:15:35'),
(245, 1, '', 'Document previewed: aaa', '2025-09-13 21:15:47'),
(246, 1, '', 'Document previewed: aaa', '2025-09-13 21:28:48'),
(247, 1, '', 'Document previewed: aaa', '2025-09-13 21:28:50'),
(248, 1, '', 'Document uploaded: TabasanRodary_InnovationCanvas(4-A)', '2025-09-13 21:29:14'),
(249, 1, '', 'Document previewed: TabasanRodary_InnovationCanvas(4-A)', '2025-09-13 21:29:20'),
(250, 1, '', 'Document previewed: TabasanRodary_InnovationCanvas(4-A)', '2025-09-13 21:31:22'),
(251, 1, '', 'Document previewed: TabasanRodary_InnovationCanvas(4-A)', '2025-09-13 21:35:41'),
(252, 1, '', 'Document previewed: TabasanRodary_InnovationCanvas(4-A)', '2025-09-13 21:36:44'),
(253, 1, '', 'Document previewed: TabasanRodary_InnovationCanvas(4-A)', '2025-09-13 21:36:54'),
(254, 1, '', 'Document previewed: TabasanRodary_InnovationCanvas(4-A)', '2025-09-13 21:37:32'),
(255, 1, '', 'Document previewed: TabasanRodary_InnovationCanvas(4-A)', '2025-09-13 21:44:01'),
(256, 1, '', 'Document previewed: TabasanRodary_InnovationCanvas(4-A)', '2025-09-13 21:44:03'),
(257, 1, '', 'Document previewed: TabasanRodary_InnovationCanvas(4-A)', '2025-09-13 21:44:04'),
(258, 1, '', 'Document previewed: TabasanRodary_InnovationCanvas(4-A)', '2025-09-13 21:44:59'),
(259, 1, '', 'Document previewed: TabasanRodary_InnovationCanvas(4-A)', '2025-09-13 21:47:08'),
(260, 1, '', 'Document previewed: aaa', '2025-09-13 21:47:19'),
(261, 1, '', 'Document previewed: aaa', '2025-09-13 21:47:21'),
(262, 1, '', 'Document uploaded: APIcloudconvert', '2025-09-13 21:47:34'),
(263, 1, '', 'Document previewed: APIcloudconvert', '2025-09-13 21:47:40'),
(264, 1, '', 'Document previewed: APIcloudconvert', '2025-09-13 21:47:56'),
(265, 1, '', 'Document previewed: TabasanRodary_InnovationCanvas(4-A)', '2025-09-13 21:48:16'),
(266, 1, '', 'Document previewed: TabasanRodary_InnovationCanvas(4-A)', '2025-09-13 21:53:36'),
(267, 1, '', 'Document previewed: TabasanRodary_InnovationCanvas(4-A)', '2025-09-14 03:44:05'),
(268, 1, '', 'Document previewed: APIcloudconvert', '2025-09-14 08:58:09'),
(269, 1, '', 'Document previewed: APIcloudconvert', '2025-09-14 08:58:46'),
(270, 1, '', 'Downloaded document: TabasanRodary_InnovationCanvas(4-A) (ID: 120)', '2025-09-14 08:58:54'),
(271, 1, '', 'Document previewed: TabasanRodary_InnovationCanvas(4-A)', '2025-09-14 08:58:58'),
(272, 1, '', 'Document previewed: aaa', '2025-09-14 08:59:09'),
(273, 1, '', 'Document uploaded: admindash7', '2025-09-14 08:59:37'),
(274, 1, '', 'Document previewed: admindash7', '2025-09-14 08:59:44'),
(275, 1, '', 'Document previewed: TabasanRodary_InnovationCanvas(4-A)', '2025-09-14 09:00:07'),
(276, 1, '', 'Document previewed: aaa', '2025-09-14 09:01:24'),
(277, 1, '', 'Document uploaded: sample', '2025-09-14 09:02:02'),
(278, 1, '', 'Document previewed: sample', '2025-09-14 09:02:06'),
(279, 1, '', 'Document previewed: sample', '2025-09-14 09:05:00'),
(280, 1, '', 'Document previewed: aaa', '2025-09-14 10:07:00'),
(281, 1, '', 'Document previewed: sample', '2025-09-14 10:07:56'),
(282, 1, '', 'Document uploaded: sample', '2025-09-14 10:08:20'),
(283, 1, '', 'Document uploaded: Sample', '2025-09-14 10:08:20'),
(284, 1, '', 'Document previewed: sample', '2025-09-14 10:08:36'),
(285, 1, '', 'Document previewed: Sample', '2025-09-14 10:08:45'),
(286, 1, '', 'Document previewed: sample', '2025-09-14 10:09:38'),
(287, 1, '', 'Document uploaded: System Progress Report', '2025-09-14 10:10:21'),
(288, 1, '', 'Document previewed: System Progress Report', '2025-09-14 10:10:26'),
(289, 1, '', 'User logged in.', '2025-09-17 01:37:12'),
(290, 1, '', 'Deleted document: sample (ID: 124)', '2025-09-17 01:37:56'),
(291, 1, '', 'Deleted document: Sample (ID: 125)', '2025-09-17 01:38:23'),
(292, 2, '', 'User logged in.', '2025-09-17 01:39:06'),
(293, 2, '', 'Document uploaded: System Progress Report', '2025-09-17 01:39:59'),
(294, 2, '', 'Document previewed: System Progress Report', '2025-09-17 01:40:05'),
(295, 2, '', 'Document uploaded: my_BMC', '2025-09-17 01:41:13'),
(296, 1, '', 'Document previewed: my_BMC', '2025-09-17 01:42:00'),
(297, 2, '', 'Document previewed: aaa', '2025-09-17 01:43:37'),
(298, 2, '', 'Document previewed: sample', '2025-09-17 01:43:55'),
(299, 1, '', 'Document previewed: sample', '2025-09-17 01:45:24'),
(300, 1, '', 'Document previewed: System Progress Report', '2025-09-17 08:38:33'),
(301, 1, '', 'Document previewed: my_BMC', '2025-09-17 08:41:17'),
(302, 1, '', 'User logged in.', '2025-09-17 19:19:35'),
(303, 2, '', 'User logged in.', '2025-09-17 19:22:55'),
(304, 2, '', 'User logged out.', '2025-09-17 19:25:33'),
(305, 2, '', 'User logged in.', '2025-09-17 19:29:17'),
(306, 2, '', 'User logged out.', '2025-09-17 19:33:35'),
(309, 2, '', 'User logged in.', '2025-09-17 20:05:29'),
(310, 2, '', 'Document previewed: APIcloudconvert', '2025-09-17 20:07:34'),
(311, 2, '', 'Document previewed: sample', '2025-09-17 20:07:45'),
(312, 2, '', 'Downloaded document: sample (ID: 123)', '2025-09-17 20:07:49'),
(313, 1, '', 'Document previewed: my_BMC', '2025-09-17 21:21:35'),
(314, 1, '', 'User logged in.', '2025-09-17 23:23:25'),
(315, 1, '', 'Deleted folder: aaaa', '2025-09-17 23:24:48'),
(316, 1, '', 'Searched for: \'letters\'', '2025-09-18 00:42:21'),
(317, 2, '', 'User logged in.', '2025-09-18 00:44:46'),
(318, 2, '', 'Document previewed: TabasanRodary_InnovationCanvas(4-A)', '2025-09-18 00:45:42'),
(319, 2, '', 'Document previewed: APIcloudconvert', '2025-09-18 00:45:51'),
(320, 2, '', 'User logged out.', '2025-09-18 00:53:11'),
(321, 1, '', 'Deleted folder: sample', '2025-09-18 00:53:35'),
(322, 1, '', 'Document previewed: admindash7', '2025-09-18 00:53:59'),
(323, 1, '', 'Deleted document: my_BMC (ID: 128)', '2025-09-18 00:58:46'),
(324, 1, '', 'Deleted document: System Progress Report (ID: 127)', '2025-09-18 00:58:58'),
(325, 1, '', 'Deleted document: System Progress Report (ID: 126)', '2025-09-18 00:59:02'),
(326, 1, '', 'Deleted document: sample (ID: 123)', '2025-09-18 00:59:06'),
(327, 1, '', 'Deleted document: admindash7 (ID: 122)', '2025-09-18 00:59:11'),
(328, 1, '', 'Deleted document: APIcloudconvert (ID: 121)', '2025-09-18 00:59:16'),
(329, 1, '', 'Deleted document: TabasanRodary_InnovationCanvas(4-A) (ID: 120)', '2025-09-18 00:59:21'),
(330, 1, '', 'Deleted document: aaa (ID: 119)', '2025-09-18 00:59:26'),
(331, 1, '', 'Deleted folder: Reports', '2025-09-18 00:59:48'),
(332, 2, '', 'User logged in.', '2025-09-18 01:16:44'),
(333, 1, '', 'Document uploaded: SKRMS Final Manuscript (January 08, 2025)', '2025-09-18 01:20:24'),
(334, 1, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-09-18 01:20:39'),
(335, 1, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-09-18 01:21:45'),
(336, 1, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-09-18 01:23:14'),
(337, 1, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-09-18 01:23:51'),
(338, 1, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-09-18 01:24:44'),
(339, 1, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-09-18 01:25:32'),
(340, 1, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-09-18 01:25:37'),
(341, 1, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-09-18 01:25:51'),
(342, 1, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-09-18 01:26:08'),
(343, 1, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-09-18 01:26:26'),
(344, 1, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-09-18 01:26:39'),
(345, 1, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-09-18 01:27:13'),
(346, 1, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-09-18 01:27:48'),
(347, 1, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-09-18 01:28:48'),
(348, 1, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-09-18 01:28:56'),
(349, 1, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-09-18 01:29:16'),
(350, 1, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-09-18 01:30:07'),
(351, 1, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-09-18 01:30:09'),
(352, 1, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-09-18 01:30:13'),
(353, 1, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-09-18 01:30:28'),
(354, 1, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-09-18 01:31:20'),
(355, 1, '', 'Document uploaded: Resume', '2025-09-18 01:32:21'),
(356, 1, '', 'Document previewed: Resume', '2025-09-18 01:32:25'),
(357, 1, '', 'Deleted document: SKRMS Final Manuscript (January 08, 2025) (ID: 129)', '2025-09-18 01:33:01'),
(358, 1, '', 'Deleted document: Resume (ID: 130)', '2025-09-18 01:33:12'),
(359, 1, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-09-18 01:40:15'),
(360, 1, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-09-18 08:48:41'),
(361, 1, '', 'Downloaded document: SKRMS Final Manuscript (January 08, 2025) (ID: 129)', '2025-09-18 08:51:25'),
(362, 1, '', 'User logged in.', '2025-09-18 18:49:00'),
(363, 1, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-09-18 18:50:19'),
(364, 1, '', 'User logged in.', '2025-09-20 21:00:41'),
(365, 1, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-09-20 22:27:03'),
(366, 1, '', 'User logged in.', '2025-09-21 08:36:04'),
(367, 1, '', 'Searched for: \'letters\'', '2025-09-21 08:36:20'),
(368, 1, '', 'User logged out.', '2025-09-21 08:37:43'),
(369, 2, '', 'User logged in.', '2025-09-21 08:37:55'),
(370, 2, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-09-21 08:38:02'),
(371, 2, '', 'User logged out.', '2025-09-21 08:39:08'),
(372, 1, '', 'User logged in.', '2025-09-21 08:39:20'),
(373, 1, '', 'User logged out.', '2025-09-21 08:41:36'),
(374, 1, '', 'User logged in.', '2025-09-21 08:41:49'),
(375, 1, '', 'Deleted document: SKRMS Final Manuscript (January 08, 2025) (ID: 129)', '2025-09-21 08:51:56'),
(376, 1, '', 'User logged in.', '2025-09-23 20:32:53'),
(377, 1, '', 'Deleted document: Resume (ID: 130)', '2025-09-23 20:40:06'),
(378, 1, '', 'Deleted document: SKRMS Final Manuscript (January 08, 2025) (ID: 129)', '2025-09-23 20:40:11'),
(379, 1, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-09-23 20:58:55'),
(380, 1, '', 'Deleted folder: aaa', '2025-09-23 21:00:11'),
(381, 2, '', 'User logged in.', '2025-09-23 21:00:36'),
(382, 1, '', 'Deleted folder: aaaa', '2025-09-23 21:01:03'),
(383, 2, '', 'User logged out.', '2025-09-23 21:01:23'),
(384, 1, '', 'Deleted document: Resume (ID: 130)', '2025-09-23 21:41:48'),
(385, 1, '', 'User logged out.', '2025-09-23 21:48:34'),
(386, 1, '', 'User logged in.', '2025-09-23 21:48:47'),
(387, 2, '', 'User logged in.', '2025-09-24 00:25:42'),
(388, 1, '', 'User logged out.', '2025-09-24 00:36:56'),
(389, 2, '', 'User logged out.', '2025-09-24 00:38:19'),
(390, 1, '', 'User logged in.', '2025-09-24 00:39:22'),
(391, 1, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-09-24 00:40:00'),
(392, 1, '', 'User logged out.', '2025-09-24 00:41:28'),
(393, 1, '', 'User logged in.', '2025-09-24 09:19:22'),
(394, 2, '', 'User logged in.', '2025-09-24 09:20:03'),
(395, 2, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-09-24 09:20:18'),
(396, 2, '', 'User logged out.', '2025-09-24 09:23:02'),
(397, 2, '', 'User logged in.', '2025-09-29 00:09:35'),
(398, 2, '', 'Searched for: \'letters\'', '2025-09-29 00:10:17'),
(399, 2, '', 'Searched for: \'report\'', '2025-09-29 00:10:50'),
(400, 2, '', 'Document uploaded: APIcloudconvert', '2025-09-29 00:13:19'),
(401, 2, '', 'Shared document: APIcloudconvert (ID: 131) to System Administrator with permission: download', '2025-09-29 00:13:44'),
(402, 2, '', 'Deleted document: APIcloudconvert (ID: 131)', '2025-09-29 00:14:09'),
(403, 2, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-09-29 00:15:32'),
(404, 2, '', 'Downloaded document: APIcloudconvert (ID: 131)', '2025-09-29 00:16:47'),
(405, 1, '', 'User logged in.', '2025-09-29 00:17:28'),
(406, 1, '', 'Document previewed: APIcloudconvert', '2025-09-29 00:19:35'),
(407, 1, '', 'Deleted folder: sampe', '2025-09-29 00:21:37'),
(408, 1, '', 'User logged out.', '2025-09-29 00:34:46'),
(409, 1, '', 'User logged in.', '2025-09-29 00:35:00'),
(410, 1, '', 'Document previewed: APIcloudconvert', '2025-09-29 00:36:10'),
(411, 1, '', 'User logged out.', '2025-09-29 00:37:28'),
(412, 2, '', 'User logged out.', '2025-09-29 00:38:48'),
(413, 2, '', 'User logged in.', '2025-10-02 17:56:28'),
(414, 1, '', 'User logged in.', '2025-10-03 06:02:24'),
(415, 1, '', 'Document previewed: APIcloudconvert', '2025-10-03 06:55:23'),
(416, 1, '', 'Document previewed: APIcloudconvert', '2025-10-03 06:59:47'),
(417, 1, '', 'Searched for: \'letters\'', '2025-10-03 07:04:20'),
(418, 1, '', 'Document previewed: APIcloudconvert', '2025-10-03 07:10:21'),
(419, 1, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-10-03 07:10:45'),
(420, 1, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-10-03 07:11:15'),
(421, 1, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-10-03 07:11:36'),
(422, 1, '', 'Downloaded document: SKRMS Final Manuscript (January 08, 2025) (ID: 129)', '2025-10-03 07:12:38'),
(423, 1, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-10-03 07:18:47'),
(424, 1, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-10-03 07:19:15'),
(425, 1, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-10-03 07:22:27'),
(426, 1, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-10-03 07:23:25'),
(427, 1, '', 'Document previewed: APIcloudconvert', '2025-10-03 07:23:43'),
(428, 1, '', 'Document previewed: APIcloudconvert', '2025-10-03 07:26:13'),
(429, 1, '', 'Document previewed: APIcloudconvert', '2025-10-03 07:26:23'),
(430, 1, '', 'Document previewed: APIcloudconvert', '2025-10-03 07:26:29'),
(431, 1, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-10-03 07:26:42'),
(432, 1, '', 'Document uploaded: Progress-Report (TRACK) (2)', '2025-10-03 07:46:31'),
(433, 1, '', 'Document previewed: APIcloudconvert', '2025-10-03 08:38:36'),
(434, 1, '', 'User logged in.', '2025-10-03 17:44:45'),
(435, 1, '', 'Document previewed: Progress-Report (TRACK) (2)', '2025-10-03 17:49:03'),
(436, 1, '', 'Document previewed: Progress-Report (TRACK) (2)', '2025-10-03 17:49:22'),
(437, 1, '', 'Document previewed: Progress-Report (TRACK) (2)', '2025-10-03 17:49:52'),
(438, 1, '', 'Document previewed: Progress-Report (TRACK) (2)', '2025-10-03 17:51:50'),
(439, 1, '', 'Document previewed: Progress-Report (TRACK) (2)', '2025-10-03 17:53:48'),
(440, 1, '', 'Document previewed: Progress-Report (TRACK) (2)', '2025-10-03 17:54:58'),
(441, 1, '', 'Document previewed: Progress-Report (TRACK) (2)', '2025-10-03 17:55:29'),
(442, 1, '', 'Document previewed: Progress-Report (TRACK) (2)', '2025-10-03 17:56:17'),
(443, 1, '', 'Document previewed: Progress-Report (TRACK) (2)', '2025-10-03 17:56:53'),
(444, 1, '', 'Document previewed: Progress-Report (TRACK) (2)', '2025-10-03 17:57:41'),
(445, 1, '', 'Document previewed: Progress-Report (TRACK) (2)', '2025-10-03 17:58:43'),
(446, 1, '', 'Document previewed: Progress-Report (TRACK) (2)', '2025-10-03 18:00:59'),
(447, 1, '', 'Document previewed: Progress-Report (TRACK) (2)', '2025-10-03 18:01:08'),
(448, 1, '', 'Document previewed: Progress-Report (TRACK) (2)', '2025-10-03 18:01:35'),
(449, 1, '', 'Document previewed: Progress-Report (TRACK) (2)', '2025-10-03 18:02:47'),
(450, 1, '', 'Document previewed: Progress-Report (TRACK) (2)', '2025-10-03 18:04:30'),
(451, 1, '', 'Document previewed: Progress-Report (TRACK) (2)', '2025-10-03 18:08:40'),
(452, 1, '', 'Document previewed: Progress-Report (TRACK) (2)', '2025-10-03 18:23:24'),
(453, 1, '', 'Document previewed: Progress-Report (TRACK) (2)', '2025-10-03 18:24:43'),
(454, 1, '', 'Document previewed: Progress-Report (TRACK) (2)', '2025-10-03 18:27:11'),
(455, 1, '', 'Document previewed: Progress-Report (TRACK) (2)', '2025-10-03 18:27:31'),
(456, 1, '', 'Document previewed: Progress-Report (TRACK) (2)', '2025-10-03 18:28:27'),
(457, 1, '', 'Document previewed: Progress-Report (TRACK) (2)', '2025-10-03 18:30:18'),
(458, 1, '', 'Document previewed: APIcloudconvert', '2025-10-03 18:30:33'),
(459, 1, '', 'Document previewed: APIcloudconvert', '2025-10-03 19:55:22'),
(460, 1, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-10-03 19:55:28'),
(461, 1, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-10-03 19:55:37'),
(462, 1, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-10-03 19:55:39'),
(463, 1, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-10-03 19:56:01'),
(464, 2, '', 'User logged in.', '2025-10-07 08:20:43'),
(465, 2, '', 'Document previewed: APIcloudconvert', '2025-10-07 08:21:27'),
(466, 2, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-10-07 08:24:14'),
(467, 2, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-10-07 08:24:29'),
(468, 2, '', 'Document previewed: Progress-Report (TRACK) (2)', '2025-10-07 08:25:25'),
(469, 2, '', 'Document previewed: APIcloudconvert', '2025-10-07 08:26:48'),
(470, 2, '', 'Downloaded document: APIcloudconvert (ID: 131)', '2025-10-07 08:26:51'),
(471, 2, '', 'Deleted document: APIcloudconvert (ID: 131)', '2025-10-07 08:27:46'),
(472, 1, '', 'User logged in.', '2025-10-07 08:28:25'),
(473, 1, '', 'User logged in.', '2025-10-08 07:59:02'),
(474, 1, '', 'Document uploaded: 2025-parentsConsentacknowledgementPage', '2025-10-08 08:10:40'),
(475, 1, '', 'Document uploaded: 2025-parentsConsentacknowledgementPage', '2025-10-08 08:10:53'),
(476, 1, '', 'Document previewed: 2025-parentsConsentacknowledgementPage', '2025-10-08 08:11:11'),
(477, 1, '', 'Deleted document: 2025-parentsConsentacknowledgementPage (ID: 134)', '2025-10-08 08:12:40'),
(478, 1, '', 'User logged out.', '2025-10-08 08:29:30'),
(479, 1, '', 'User logged in.', '2025-10-08 08:29:41'),
(480, 1, '', 'User logged out.', '2025-10-08 09:08:17'),
(481, 3, '', 'User logged in.', '2025-10-08 09:08:42'),
(482, 3, '', 'Backup created: ../backups/backup_2025-10-08_13-26-11/', '2025-10-08 09:26:11'),
(483, 3, '', 'Backup deleted: backup_2025-10-08_13-26-11', '2025-10-08 09:26:26'),
(484, 3, '', 'Backup created: ../backups/backup_2025-10-08_13-26-35/', '2025-10-08 09:26:35'),
(485, 3, '', 'Backup restored: backup_2025-10-08_13-26-35', '2025-10-08 09:27:59'),
(486, 3, '', 'Deleted document: 2025-parentsConsentacknowledgementPage (ID: 135)', '2025-10-08 09:28:18'),
(487, 3, '', 'Deleted document: Progress-Report (TRACK) (2) (ID: 132)', '2025-10-08 09:28:22'),
(488, 3, '', 'Deleted document: SKRMS Final Manuscript (January 08, 2025) (ID: 129)', '2025-10-08 09:28:26'),
(489, 3, '', 'Backup restored: backup_2025-10-08_13-26-35', '2025-10-08 09:28:53'),
(490, 3, '', 'Backup deleted: backup_2025-10-08_13-26-35', '2025-10-08 09:32:36'),
(491, 3, '', 'Backup created: ../backups/backup_2025-10-08_13-46-38/', '2025-10-08 09:46:38'),
(492, 3, '', 'Backup deleted: backup_2025-10-08_13-46-38', '2025-10-08 09:46:58'),
(493, 3, '', 'Backup created: ../backups/backup_2025-10-08_14-08-32/', '2025-10-08 10:08:33'),
(494, 3, '', 'Backup created: ../backups/backup_2025-10-08_14-10-05/', '2025-10-08 10:10:05'),
(495, 1, '', 'User logged in.', '2025-10-08 18:36:10'),
(496, 1, '', 'Document uploaded: for-WRITTEN-parents-consent-DMW', '2025-10-08 18:47:01'),
(497, 1, '', 'Document previewed: for-WRITTEN-parents-consent-DMW', '2025-10-08 18:47:06'),
(498, 1, '', 'Document previewed: for-WRITTEN-parents-consent-DMW', '2025-10-08 18:55:57'),
(499, 1, '', 'Document previewed: for-WRITTEN-parents-consent-DMW', '2025-10-08 18:56:03'),
(500, 1, '', 'Document previewed: for-WRITTEN-parents-consent-DMW', '2025-10-08 19:00:19'),
(501, 1, '', 'Document previewed: for-WRITTEN-parents-consent-DMW', '2025-10-08 19:00:26'),
(502, 1, '', 'Document previewed: for-WRITTEN-parents-consent-DMW', '2025-10-08 19:46:34'),
(503, 2, '', 'User logged in.', '2025-10-08 21:33:53'),
(504, 1, '', 'Shared document: for-WRITTEN-parents-consent-DMW (ID: 136) to Rodary Tabasan with permission: view', '2025-10-08 21:34:41'),
(505, 1, '', 'Backup deleted: backup_2025-10-08_14-08-32', '2025-10-08 21:39:35'),
(506, 1, '', 'Backup deleted: backup_2025-10-08_14-10-05', '2025-10-08 21:42:55'),
(507, 1, '', 'Backup created: ../backups/backup_2025-10-09_01-43-57/', '2025-10-08 21:43:57'),
(508, 1, '', 'Backup deleted: backup_2025-10-09_01-43-57', '2025-10-08 21:47:28'),
(509, 1, '', 'Backup created: ../backups/backup_2025-10-09_01-48-36/', '2025-10-08 21:48:36'),
(510, 1, '', 'Backup deleted: backup_2025-10-09_01-48-36', '2025-10-08 21:50:25'),
(511, 1, '', 'Backup created: ../backups/backup_2025-10-09_01-51-24/', '2025-10-08 21:51:24'),
(512, 1, '', 'Backup deleted: backup_2025-10-09_01-51-24', '2025-10-08 21:51:34'),
(513, 1, '', 'Deleted document: for-WRITTEN-parents-consent-DMW (ID: 136)', '2025-10-08 21:52:06'),
(514, 2, '', 'Document uploaded: my_BMC', '2025-10-08 21:52:56'),
(515, 2, '', 'Document uploaded: 1755428545164', '2025-10-08 21:53:08'),
(516, 2, '', 'Document uploaded: APIcloudconvert', '2025-10-08 21:53:20'),
(517, 2, '', 'User logged out.', '2025-10-08 22:21:40'),
(518, 1, '', 'User logged in.', '2025-10-08 23:06:31'),
(519, 1, '', 'User logged in.', '2025-10-08 23:21:44'),
(520, 1, '', 'Deleted document: APIcloudconvert (ID: 139)', '2025-10-08 23:22:17'),
(521, 1, '', 'Deleted document: 1755428545164 (ID: 138)', '2025-10-08 23:22:21'),
(522, 1, '', 'Deleted document: my_BMC (ID: 137)', '2025-10-08 23:22:25'),
(523, 1, '', 'User logged in.', '2025-10-09 07:20:30'),
(524, 1, '', 'User logged out.', '2025-10-09 08:34:48'),
(525, 1, '', 'User logged in.', '2025-10-09 08:35:40'),
(526, 1, '', 'User logged out.', '2025-10-09 09:29:50'),
(527, 2, '', 'User logged in.', '2025-10-09 09:30:03'),
(528, 2, '', 'Document uploaded: TabasanRodary_4A', '2025-10-09 09:31:19'),
(529, 2, '', 'Document previewed: TabasanRodary_4A', '2025-10-09 09:31:34'),
(530, 2, '', 'Document previewed: TabasanRodary_4A', '2025-10-09 09:31:37'),
(531, 2, '', 'Document previewed: TabasanRodary_4A', '2025-10-09 09:31:39'),
(532, 2, '', 'Document previewed: TabasanRodary_4A', '2025-10-09 09:31:40'),
(533, 2, '', 'User logged out.', '2025-10-09 09:32:38'),
(534, 1, '', 'User logged in.', '2025-10-09 09:32:50'),
(535, 1, '', 'User logged in.', '2025-10-09 22:20:36'),
(536, 1, '', 'User logged out.', '2025-10-09 22:21:10'),
(537, 2, '', 'User logged in.', '2025-10-09 22:21:23'),
(538, 2, '', 'User logged out.', '2025-10-09 22:22:24'),
(539, 2, '', 'User logged in.', '2025-10-10 03:13:37'),
(540, 1, '', 'User logged in.', '2025-10-10 03:15:29'),
(541, 1, '', 'Document previewed: TabasanRodary_4A', '2025-10-10 03:35:18'),
(542, 1, '', 'Document previewed: TabasanRodary_4A', '2025-10-10 03:37:09'),
(543, 2, '', 'Searched for: \'letters\'', '2025-10-10 04:38:47'),
(544, 2, '', 'Document uploaded: for-WRITTEN-parents-consent-DMW', '2025-10-10 05:19:07'),
(545, 2, '', 'Document previewed: for-WRITTEN-parents-consent-DMW', '2025-10-10 05:19:11'),
(546, 2, '', 'Document uploaded: 2025-parentsConsentacknowledgementPage', '2025-10-10 05:32:20'),
(547, 1, '', 'Deleted document: TabasanRodary_4A (ID: 140)', '2025-10-10 06:08:36'),
(548, 1, '', 'User logged out.', '2025-10-10 06:27:28'),
(549, 2, '', 'User logged in.', '2025-10-10 06:58:31'),
(550, 2, '', 'Document previewed: 2025-parentsConsentacknowledgementPage', '2025-10-10 07:15:46'),
(551, 2, '', 'Document previewed: for-WRITTEN-parents-consent-DMW', '2025-10-10 07:15:56'),
(552, 2, '', 'Document previewed: 2025-parentsConsentacknowledgementPage', '2025-10-10 07:16:27'),
(553, 1, '', 'User logged in.', '2025-10-10 07:51:40'),
(554, 1, '', 'Deleted folder: Trainings', '2025-10-10 08:43:13'),
(555, 1, '', 'User logged out.', '2025-10-10 09:02:20'),
(556, 2, '', 'Document previewed: 2025-parentsConsentacknowledgementPage', '2025-10-10 09:03:58'),
(557, 1, '', 'User logged in.', '2025-10-10 09:30:04'),
(558, 1, '', 'Document previewed: 2025-parentsConsentacknowledgementPage', '2025-10-10 09:46:36'),
(559, 1, '', 'User logged in.', '2025-10-10 18:00:09'),
(560, 1, '', 'Document previewed: 2025-parentsConsentacknowledgementPage', '2025-10-10 18:02:36'),
(561, 1, '', 'Document previewed: 2025-parentsConsentacknowledgementPage', '2025-10-10 18:02:56'),
(562, 1, '', 'Searched for: \'letters\'', '2025-10-10 18:03:32'),
(563, 2, '', 'User logged in.', '2025-10-10 18:06:37'),
(564, 1, '', 'Document previewed: 2025-parentsConsentacknowledgementPage', '2025-10-10 18:08:42'),
(565, 1, '', 'Document uploaded: banana_chips_bmc', '2025-10-10 18:37:59'),
(566, 1, '', 'Shared document: banana_chips_bmc (ID: 143) to Rodary Tabasan with permission: view', '2025-10-10 18:38:10'),
(567, 2, '', 'Document previewed: banana_chips_bmc', '2025-10-10 18:38:34'),
(568, 1, '', 'Document previewed: banana_chips_bmc', '2025-10-10 18:57:05'),
(569, 1, '', 'User logged in.', '2025-10-11 10:06:42'),
(570, 1, '', 'Document previewed: banana_chips_bmc', '2025-10-11 10:11:26'),
(571, 1, '', 'User logged in.', '2025-10-11 23:50:57'),
(572, 1, '', 'User logged out.', '2025-10-12 00:04:13'),
(573, 1, '', 'User logged in.', '2025-10-12 00:04:35'),
(574, 1, '', 'Document previewed: 2025-parentsConsentacknowledgementPage', '2025-10-12 00:29:55'),
(575, 1, '', 'Document previewed: banana_chips_bmc', '2025-10-12 10:28:00'),
(576, 1, '', 'User logged out.', '2025-10-12 10:41:03'),
(577, 1, '', 'User logged in.', '2025-10-12 10:41:31'),
(578, 1, '', 'Document previewed: for-WRITTEN-parents-consent-DMW', '2025-10-12 11:06:48'),
(579, 1, '', 'User logged out.', '2025-10-12 12:29:21'),
(580, 2, '', 'User logged in.', '2025-10-13 05:56:37'),
(581, 2, '', 'Document previewed: 2025-parentsConsentacknowledgementPage', '2025-10-13 05:57:26'),
(582, 2, '', 'User logged out.', '2025-10-13 06:07:09'),
(583, 1, '', 'User logged in.', '2025-10-13 06:11:30'),
(584, 1, '', 'User logged out.', '2025-10-13 06:14:57'),
(585, 2, '', 'User logged in.', '2025-10-13 06:15:40'),
(586, 2, '', 'User logged out.', '2025-10-13 06:15:47'),
(587, 2, '', 'User logged in.', '2025-10-13 06:16:03'),
(588, 1, '', 'User logged in.', '2025-10-13 06:16:20'),
(589, 2, '', 'User logged out.', '2025-10-13 06:59:12'),
(590, 2, '', 'User logged in.', '2025-10-13 06:59:23'),
(591, 2, '', 'Document previewed: for-WRITTEN-parents-consent-DMW', '2025-10-13 07:13:44'),
(592, 2, '', 'Document previewed: banana_chips_bmc', '2025-10-13 07:19:44'),
(593, 2, '', 'Document previewed: 2025-parentsConsentacknowledgementPage', '2025-10-13 07:55:57'),
(594, 1, '', 'Document previewed: banana_chips_bmc', '2025-10-13 08:31:00'),
(595, 1, '', 'Document previewed: banana_chips_bmc', '2025-10-13 08:31:06'),
(596, 2, '', 'User logged out.', '2025-10-13 09:43:28'),
(597, 1, '', 'User logged in.', '2025-10-13 09:43:38'),
(598, 1, '', 'Document previewed: banana_chips_bmc', '2025-10-13 09:45:20'),
(599, 1, '', 'User logged out.', '2025-10-13 10:51:56'),
(600, 2, '', 'User logged in.', '2025-10-13 10:52:08'),
(601, 2, '', 'Document previewed: banana_chips_bmc', '2025-10-13 11:26:30'),
(602, 1, '', 'Document uploaded: 2025-parentsConsentacknowledgementPage', '2025-10-13 11:33:19'),
(603, 1, '', 'Document uploaded: INNOVATE AND PROJECT', '2025-10-13 11:33:34'),
(604, 1, '', 'Document previewed: INNOVATE AND PROJECT', '2025-10-13 11:33:39'),
(605, 1, '', 'Document uploaded: SKRMS Final Manuscript (January 08, 2025)', '2025-10-13 11:34:18'),
(606, 1, '', 'Document previewed: for-WRITTEN-parents-consent-DMW', '2025-10-13 11:38:52'),
(607, 1, '', 'Document previewed: SKRMS Final Manuscript (January 08, 2025)', '2025-10-13 11:42:02'),
(608, 1, '', 'Deleted document: SKRMS Final Manuscript (January 08, 2025) (ID: 146)', '2025-10-13 12:27:59'),
(609, 1, '', 'Document previewed: INNOVATE AND PROJECT', '2025-10-13 12:45:19'),
(610, 1, '', 'Document previewed: 2025-parentsConsentacknowledgementPage', '2025-10-13 12:45:29'),
(611, 1, '', 'User logged in.', '2025-10-13 18:20:26'),
(612, 1, '', 'Document previewed: INNOVATE AND PROJECT', '2025-10-13 18:23:49'),
(613, 1, '', 'Document previewed: 2025-parentsConsentacknowledgementPage', '2025-10-13 18:30:35'),
(614, 1, '', 'Document previewed: 2025-parentsConsentacknowledgementPage', '2025-10-13 18:39:01'),
(615, 1, '', 'Document previewed: INNOVATE AND PROJECT', '2025-10-13 18:41:25'),
(616, 1, '', 'Document previewed: INNOVATE AND PROJECT', '2025-10-13 18:43:40'),
(617, 1, '', 'Document previewed: INNOVATE AND PROJECT', '2025-10-13 18:45:15'),
(618, 1, '', 'Document previewed: INNOVATE AND PROJECT', '2025-10-13 18:48:13'),
(619, 1, '', 'Document previewed: 2025-parentsConsentacknowledgementPage', '2025-10-13 18:48:28'),
(620, 1, '', 'Document previewed: 2025-parentsConsentacknowledgementPage', '2025-10-13 18:51:15'),
(621, 1, '', 'Document previewed: 2025-parentsConsentacknowledgementPage', '2025-10-13 18:55:07'),
(622, 1, '', 'Document previewed: INNOVATE AND PROJECT', '2025-10-13 18:55:58'),
(623, 1, '', 'Document previewed: INNOVATE AND PROJECT', '2025-10-13 18:58:37'),
(624, 1, '', 'Document previewed: INNOVATE AND PROJECT', '2025-10-13 19:01:23'),
(625, 1, '', 'Document previewed: INNOVATE AND PROJECT', '2025-10-13 19:04:56'),
(626, 1, '', 'Document previewed: INNOVATE AND PROJECT', '2025-10-13 19:07:10'),
(627, 1, '', 'Document previewed: INNOVATE AND PROJECT', '2025-10-13 19:07:30'),
(628, 1, '', 'User logged out.', '2025-10-13 19:30:02'),
(629, 2, '', 'User logged in.', '2025-10-13 19:30:13'),
(630, 2, '', 'Document previewed: 2025-parentsConsentacknowledgementPage', '2025-10-13 19:30:17'),
(631, 2, '', 'Document previewed: 2025-parentsConsentacknowledgementPage', '2025-10-13 19:30:32'),
(632, 2, '', 'Searched for: \'letters\'', '2025-10-13 19:30:45'),
(633, 2, '', 'Searched for: \'aa\'', '2025-10-13 19:30:49'),
(634, 2, '', 'Searched for: \'report\'', '2025-10-13 19:30:52'),
(635, 2, '', 'Searched for: \'audit\'', '2025-10-13 19:30:59'),
(636, 2, '', 'Document previewed: INNOVATE AND PROJECT', '2025-10-13 19:31:12'),
(637, 2, '', 'Document previewed: 2025-parentsConsentacknowledgementPage', '2025-10-13 19:31:55'),
(638, 2, '', 'Document previewed: 2025-parentsConsentacknowledgementPage', '2025-10-13 19:35:45'),
(639, 2, '', 'Document previewed: TabasanRodary_4A', '2025-10-13 19:36:26'),
(640, 2, '', 'Document previewed: for-WRITTEN-parents-consent-DMW', '2025-10-13 19:37:00'),
(641, 2, '', 'Document previewed: for-WRITTEN-parents-consent-DMW', '2025-10-13 19:39:58'),
(642, 2, '', 'Document previewed: for-WRITTEN-parents-consent-DMW', '2025-10-13 19:40:56'),
(643, 2, '', 'Document previewed: for-WRITTEN-parents-consent-DMW', '2025-10-13 19:40:56'),
(644, 2, '', 'Document previewed: for-WRITTEN-parents-consent-DMW', '2025-10-13 19:40:57'),
(645, 2, '', 'Document previewed: for-WRITTEN-parents-consent-DMW', '2025-10-13 19:50:43'),
(646, 2, '', 'Document previewed: for-WRITTEN-parents-consent-DMW', '2025-10-13 19:50:50'),
(647, 2, '', 'Document previewed: for-WRITTEN-parents-consent-DMW', '2025-10-13 19:50:51'),
(648, 2, '', 'Document previewed: for-WRITTEN-parents-consent-DMW', '2025-10-13 19:50:51'),
(649, 2, '', 'Document previewed: for-WRITTEN-parents-consent-DMW', '2025-10-13 19:51:55'),
(650, 2, '', 'Document previewed: for-WRITTEN-parents-consent-DMW', '2025-10-13 19:51:55'),
(651, 2, '', 'Document previewed: for-WRITTEN-parents-consent-DMW', '2025-10-13 19:51:55'),
(652, 2, '', 'Document previewed: for-WRITTEN-parents-consent-DMW', '2025-10-13 19:53:44'),
(653, 2, '', 'Document previewed: for-WRITTEN-parents-consent-DMW', '2025-10-13 19:53:45'),
(654, 2, '', 'Document previewed: for-WRITTEN-parents-consent-DMW', '2025-10-13 19:53:45'),
(655, 2, '', 'Document previewed: for-WRITTEN-parents-consent-DMW', '2025-10-13 19:55:31'),
(656, 2, '', 'Document previewed: for-WRITTEN-parents-consent-DMW', '2025-10-13 19:55:47'),
(657, 2, '', 'Document previewed: for-WRITTEN-parents-consent-DMW', '2025-10-13 19:56:05'),
(658, 2, '', 'Document previewed: for-WRITTEN-parents-consent-DMW', '2025-10-13 19:56:52'),
(659, 2, '', 'Downloaded document: for-WRITTEN-parents-consent-DMW (ID: 141)', '2025-10-13 19:57:04'),
(660, 2, '', 'Document previewed: 2025-parentsConsentacknowledgementPage', '2025-10-13 19:57:19'),
(661, 2, '', 'Document previewed: for-WRITTEN-parents-consent-DMW', '2025-10-13 19:57:28'),
(662, 2, '', 'Document previewed: for-WRITTEN-parents-consent-DMW', '2025-10-13 19:58:22'),
(663, 2, '', 'Document previewed: for-WRITTEN-parents-consent-DMW', '2025-10-13 19:58:22'),
(664, 2, '', 'Document previewed: for-WRITTEN-parents-consent-DMW', '2025-10-13 19:58:22'),
(665, 2, '', 'Document previewed: TabasanRodary_4A', '2025-10-13 19:58:44'),
(666, 2, '', 'Document previewed: for-WRITTEN-parents-consent-DMW', '2025-10-13 20:00:16'),
(667, 2, '', 'Document previewed: for-WRITTEN-parents-consent-DMW', '2025-10-13 20:00:22'),
(668, 2, '', 'Document previewed: for-WRITTEN-parents-consent-DMW', '2025-10-13 20:00:22'),
(669, 1, '', 'User logged in.', '2025-10-13 20:00:50'),
(670, 1, '', 'Document previewed: INNOVATE AND PROJECT', '2025-10-13 20:00:58'),
(671, 1, '', 'Document previewed: INNOVATE AND PROJECT', '2025-10-13 20:01:20'),
(672, 1, '', 'Document previewed: INNOVATE AND PROJECT', '2025-10-13 20:01:20'),
(673, 1, '', 'Document previewed: INNOVATE AND PROJECT', '2025-10-13 20:01:20'),
(674, 1, '', 'Document previewed: INNOVATE AND PROJECT', '2025-10-13 20:01:20'),
(675, 1, '', 'Document previewed: INNOVATE AND PROJECT', '2025-10-13 20:01:21'),
(676, 1, '', 'Document previewed: INNOVATE AND PROJECT', '2025-10-13 20:02:48'),
(677, 1, '', 'Document previewed: INNOVATE AND PROJECT', '2025-10-13 20:04:42'),
(678, 1, '', 'Document previewed: INNOVATE AND PROJECT', '2025-10-13 20:04:43'),
(679, 1, '', 'Document previewed: INNOVATE AND PROJECT', '2025-10-13 20:05:43'),
(680, 1, '', 'Document previewed: INNOVATE AND PROJECT', '2025-10-13 20:44:28'),
(681, 2, '', 'Document previewed: for-WRITTEN-parents-consent-DMW', '2025-10-13 20:50:37'),
(682, 2, '', 'Document previewed: for-WRITTEN-parents-consent-DMW', '2025-10-13 20:50:37'),
(683, 2, '', 'Document previewed: for-WRITTEN-parents-consent-DMW', '2025-10-13 20:50:56'),
(684, 2, '', 'Document previewed: 2025-parentsConsentacknowledgementPage', '2025-10-13 20:53:25'),
(685, 2, '', 'Document previewed: for-WRITTEN-parents-consent-DMW', '2025-10-13 20:53:58'),
(686, 2, '', 'Document previewed: INNOVATE AND PROJECT', '2025-10-13 20:59:37'),
(687, 2, '', 'Document previewed: INNOVATE AND PROJECT', '2025-10-13 21:07:11'),
(688, 2, '', 'Document previewed: 2025-parentsConsentacknowledgementPage', '2025-10-13 21:26:10'),
(689, 1, '', 'User logged in.', '2025-10-13 22:40:37'),
(690, 2, '', 'User logged in.', '2025-10-13 22:44:52'),
(691, 1, '', 'Document previewed: INNOVATE AND PROJECT', '2025-10-13 23:46:01'),
(692, 2, '', 'User logged in.', '2025-10-13 23:49:55'),
(693, 2, '', 'User logged in.', '2025-10-14 17:40:42'),
(694, 2, '', 'Document previewed: 2025-parentsConsentacknowledgementPage', '2025-10-14 17:41:04'),
(695, 2, '', 'Document previewed: INNOVATE AND PROJECT', '2025-10-14 17:41:29'),
(696, 2, '', 'Deleted document: 2025-parentsConsentacknowledgementPage (ID: 142)', '2025-10-14 17:52:58'),
(697, 1, '', 'User logged in.', '2025-10-14 17:53:52'),
(698, 1, '', 'Archived document: banana_chips_bmc (ID: 143)', '2025-10-14 18:04:09'),
(699, 1, '', 'Removed archived document permanently: ID 143', '2025-10-14 18:09:31'),
(700, 1, '', 'Archived document: TabasanRodary_4A (ID: 140)', '2025-10-14 18:09:40'),
(701, 1, '', 'Removed archived document permanently: ID 140', '2025-10-14 18:10:55'),
(702, 1, '', 'User logged in.', '2025-10-15 05:14:53'),
(703, 1, '', 'Removed archived document permanently: ID 144', '2025-10-15 05:15:53'),
(704, 1, '', 'Removed archived document permanently: ID 145', '2025-10-15 05:15:53'),
(705, 1, '', 'Archived document: SKRMS Final Manuscript (January 08, 2025) (ID: 146)', '2025-10-15 06:28:48'),
(706, 1, '', 'Archived document: 2025-parentsConsentacknowledgementPage (ID: 142)', '2025-10-15 07:06:40'),
(707, 1, '', 'Unarchived document ID: 142', '2025-10-15 07:06:49'),
(708, 1, '', 'Unarchived document ID: 146', '2025-10-15 07:06:49'),
(709, 1, '', 'Archived document: SKRMS Final Manuscript (January 08, 2025) (ID: 146)', '2025-10-15 07:08:34'),
(710, 1, '', 'Unarchived document ID: 146', '2025-10-15 07:08:41'),
(711, 1, '', 'Archived document: SKRMS Final Manuscript (January 08, 2025) (ID: 146)', '2025-10-15 07:10:16'),
(712, 1, '', 'Unarchived document ID: 146', '2025-10-15 07:14:12'),
(713, 1, '', 'Archived document: SKRMS Final Manuscript (January 08, 2025) (ID: 146)', '2025-10-15 07:15:49'),
(714, 1, '', 'Unarchived document ID: 146', '2025-10-15 07:17:37'),
(715, 2, '', 'User logged in.', '2025-10-15 07:19:08'),
(716, 2, '', 'Document previewed: for-WRITTEN-parents-consent-DMW', '2025-10-15 07:19:20'),
(717, 2, '', 'User logged out.', '2025-10-15 07:42:00'),
(718, 1, '', 'User logged out.', '2025-10-15 07:42:05'),
(719, 1, '', 'User logged in.', '2025-10-15 07:42:30'),
(720, 2, '', 'User logged in.', '2025-10-15 08:07:20'),
(721, 1, '', 'Shared document: SKRMS Final Manuscript (January 08, 2025) (ID: 146) to Rodary Tabasan with permission: download', '2025-10-15 08:07:51'),
(722, 1, '', 'Archived document: SKRMS Final Manuscript (January 08, 2025) (ID: 146)', '2025-10-15 08:11:00'),
(723, 1, '', 'Removed archived document permanently: ID 146', '2025-10-15 08:16:24'),
(724, 1, '', 'Archived document: 2025-parentsConsentacknowledgementPage (ID: 142)', '2025-10-15 08:16:52'),
(725, 1, '', 'Removed archived document permanently: ID 142', '2025-10-15 08:17:04'),
(726, 1, '', 'Archived document: for-WRITTEN-parents-consent-DMW (ID: 141)', '2025-10-15 08:19:48'),
(727, 1, '', 'Removed archived document permanently: ID 141', '2025-10-15 08:19:56'),
(728, 1, '', 'Document uploaded: MAPE_TABASAN_SALUDARES.N_QUEJADO', '2025-10-15 08:22:09'),
(729, 1, '', 'Archived document: MAPE_TABASAN_SALUDARES.N_QUEJADO (ID: 147)', '2025-10-15 08:22:21'),
(730, 1, '', 'Removed archived document permanently: ID 147', '2025-10-15 08:23:19'),
(731, 1, '', 'Document uploaded: Ubuntu_Server_Setup_Guide', '2025-10-15 08:34:33'),
(732, 1, '', 'Document previewed: Ubuntu_Server_Setup_Guide', '2025-10-15 08:34:39'),
(733, 1, '', 'Document previewed: Ubuntu_Server_Setup_Guide', '2025-10-15 08:34:49'),
(734, 1, '', 'Document previewed: Ubuntu_Server_Setup_Guide', '2025-10-15 08:34:52'),
(735, 1, '', 'Archived document: Ubuntu_Server_Setup_Guide (ID: 148)', '2025-10-15 08:35:12'),
(736, 1, '', 'Unarchived document: ID 148', '2025-10-15 08:35:26'),
(737, 1, '', 'Archived document: Ubuntu_Server_Setup_Guide (ID: 148)', '2025-10-15 08:37:28'),
(738, 1, '', 'Unarchived document: ID 148', '2025-10-15 08:40:48'),
(739, 2, '', 'Document uploaded: 1755428545164', '2025-10-15 08:45:31'),
(740, 2, '', 'Document previewed: 1755428545164', '2025-10-15 08:49:20'),
(741, 2, '', 'Archived document: 1755428545164 (ID: 149)', '2025-10-15 08:49:27'),
(742, 1, '', 'Archived document: Ubuntu_Server_Setup_Guide (ID: 148)', '2025-10-15 08:49:43'),
(743, 2, '', 'Unarchived document: ID 149', '2025-10-15 08:59:59'),
(744, 1, '', 'Unarchived document: ID 148', '2025-10-15 09:00:28'),
(745, 1, '', 'Shared document: 1755428545164 (ID: 149) to Rodary Tabasan with permission: view', '2025-10-15 09:02:48'),
(746, 2, '', 'Document previewed: 1755428545164', '2025-10-15 09:25:28'),
(747, 2, '', 'Document previewed: 1755428545164', '2025-10-15 09:34:11'),
(748, 1, '', 'User logged in.', '2025-10-15 10:15:25'),
(749, 1, '', 'Shared document: Ubuntu_Server_Setup_Guide (ID: 148) to Rodary Tabasan with permission: download', '2025-10-15 10:31:19'),
(750, 2, '', 'Archived document: 1755428545164 (ID: 149)', '2025-10-15 10:44:10'),
(751, 2, '', 'Unarchived document: ID 149', '2025-10-15 10:44:27'),
(752, 2, '', 'Document previewed: Ubuntu_Server_Setup_Guide', '2025-10-15 10:45:26'),
(753, 2, '', 'Document previewed: Ubuntu_Server_Setup_Guide', '2025-10-15 10:45:36'),
(754, 2, '', 'Document previewed: 1755428545164', '2025-10-15 11:33:43'),
(755, 1, '', 'Deleted document: 1755428545164 (ID: 149)', '2025-10-15 12:06:24'),
(756, 1, '', 'Deleted document: Ubuntu_Server_Setup_Guide (ID: 148)', '2025-10-15 12:06:28');

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
(23, 'audit', '', '2025-09-05 07:50:37', '#004f80', 1),
(27, 'letters', '', '2025-09-29 00:25:36', '#004f80', 1);

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
  `deleted_at` datetime DEFAULT NULL,
  `archived` tinyint(1) DEFAULT 0,
  `archived_at` datetime DEFAULT NULL,
  `is_archived` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Table structure for table `file_requests`
--

CREATE TABLE `file_requests` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `reason` text NOT NULL,
  `status` enum('pending','approved','denied') DEFAULT 'pending',
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
(49, 'Assesments', '', '#004f80', NULL, 0, 8, 1, '2025-09-09 08:49:01'),
(52, 'Agricultural Crops Production NC II', '', '#004f80', 39, 1, 1, 1, '2025-09-18 01:00:25'),
(53, 'Agricultural Crops Production NC III', '', '#004f80', 39, 1, 2, 1, '2025-09-18 01:00:53'),
(54, 'Agroentrepreneurship NC II', '', '#004f80', 39, 1, 3, 1, '2025-09-18 01:01:30'),
(55, 'Bookkkeeping NC II', '', '#004f80', 39, 1, 4, 1, '2025-09-18 01:02:04'),
(56, 'Diploma in Agricultural Technology', '', '#004f80', 39, 1, 5, 1, '2025-09-18 01:02:54'),
(57, 'Driving NC II', '', '#004f80', 39, 1, 6, 1, '2025-09-18 01:03:10'),
(58, 'Farmers Field school', '', '#004f80', 39, 1, 7, 1, '2025-09-18 01:03:35'),
(59, 'Rice Machinery Operations NC II', '', '#004f80', 39, 1, 8, 1, '2025-09-18 01:03:59'),
(60, 'Front Office Services NC II', '', '#004f80', 39, 1, 9, 1, '2025-09-18 01:04:29'),
(61, 'Housekeeping NC II', '', '#004f80', 39, 1, 10, 1, '2025-09-18 01:04:58'),
(62, 'Food and Beverage Services NC II', '', '#004f80', 39, 1, 11, 1, '2025-09-18 01:05:41'),
(63, 'Tourism Promotion Services NC II', '', '#004f80', 39, 1, 12, 1, '2025-09-18 01:06:18'),
(64, 'Food and Beverage Services NC III', '', '#004f80', 39, 1, 13, 1, '2025-09-18 01:07:20'),
(65, 'Events Management Services NC II', '', '#004f80', 39, 1, 14, 1, '2025-09-18 01:07:47'),
(66, 'Agricultural Crops Production NC I', '', '#004f80', 49, 1, 1, 1, '2025-09-18 01:09:16'),
(67, 'Agricultural Crops Production NC II', '', '#004f80', 49, 1, 2, 1, '2025-09-18 01:09:22'),
(68, 'Agricultural Crops Production NC III', '', '#004f80', 49, 1, 3, 1, '2025-09-18 01:09:30'),
(69, 'Agricultural Machinery Operation NC II', '', '#004f80', 49, 1, 4, 1, '2025-09-18 01:10:32'),
(70, 'Agroentrepreneurship NC II', '', '#004f80', 49, 1, 5, 1, '2025-09-18 01:11:10'),
(71, 'Agroentrepreneurship NC III', '', '#004f80', 49, 1, 6, 1, '2025-09-18 01:11:17'),
(72, 'Agroentrepreneurship NC IV', '', '#004f80', 49, 1, 7, 1, '2025-09-18 01:11:30'),
(73, 'Bookkkeeping NC III', '', '#004f80', 49, 1, 8, 1, '2025-09-18 01:11:57'),
(74, 'Driving NC II', '', '#004f80', 49, 1, 9, 1, '2025-09-18 01:12:14'),
(75, 'Grains Production NC II', '', '#004f80', 49, 1, 10, 1, '2025-09-18 01:12:46'),
(76, 'Housekeeping NC II', '', '#004f80', 49, 1, 11, 1, '2025-09-18 01:13:07'),
(77, 'Housekeeping NC III', '', '#004f80', 49, 1, 12, 1, '2025-09-18 01:13:13'),
(78, 'Pest Management(Vegetable) NC II', '', '#004f80', 49, 1, 13, 1, '2025-09-18 01:13:59'),
(79, 'Rice Machinery Operations NC II', '', '#004f80', 49, 1, 14, 1, '2025-09-18 01:14:11'),
(80, 'Animal Production(Swine) NC II', '', '#004f80', 49, 1, 15, 1, '2025-09-18 01:14:57'),
(81, 'Animal Health Care and Management NC III', '', '#004f80', 49, 1, 16, 1, '2025-09-18 01:15:45');

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
(18, 'ClientLetter', 'uploads/68bb863fa1c7e_1757120063.docx', '1', '2025-09-06 00:54:23'),
(19, 'piso_water_bmc', 'C:\\xampp\\htdocs\\TrackWeb\\config/../documents/uploads/68c0cd34a28de_1757465908.pdf', '2', '2025-09-10 00:58:28'),
(20, 'Sample', 'C:\\xampp\\htdocs\\TrackWeb\\config/../documents/uploads/68c0e8d074639_1757472976.xlsx', '2', '2025-09-10 02:56:16'),
(21, 'my_BMC', 'C:\\xampp\\htdocs\\TrackWeb\\config/../documents/uploads/68c0e9154ad1f_1757473045.pdf', '2', '2025-09-10 02:57:25'),
(22, 'my_BMC', 'C:\\xampp\\htdocs\\TrackWeb\\config/../documents/uploads/68c0ea9c4e5b4_1757473436.pdf', '2', '2025-09-10 03:03:56'),
(23, 'TabasanRodary_InnovationCanvas(4-A)', 'C:\\xampp\\htdocs\\TrackWeb\\config/../documents/uploads/68c0eb285d6df_1757473576.pdf', '1', '2025-09-10 03:06:16'),
(24, 'aaa', 'C:\\xampp\\htdocs\\TrackWeb\\config/../documents/uploads/68c413483ff05_1757680456.png', '1', '2025-09-12 12:34:16'),
(25, 'TabasanRodary_InnovationCanvas(4-A)', 'C:\\xampp\\htdocs\\TrackWeb\\config/../documents/uploads/68c61a6ae7538_1757813354.pdf', '1', '2025-09-14 01:29:14'),
(26, 'APIcloudconvert', 'C:\\xampp\\htdocs\\TrackWeb\\config/../documents/uploads/68c61eb6b1283_1757814454.docx', '1', '2025-09-14 01:47:34'),
(27, 'admindash7', 'C:\\xampp\\htdocs\\TrackWeb\\config/../documents/uploads/68c6bc39736fd_1757854777.png', '1', '2025-09-14 12:59:37'),
(28, 'sample', 'C:\\xampp\\htdocs\\TrackWeb\\config/../documents/uploads/68c6bcca7d60a_1757854922.png', '1', '2025-09-14 13:02:02'),
(29, 'sample', 'C:\\xampp\\htdocs\\TrackWeb\\config/../documents/uploads/68c6cc5429f82_1757858900.pptx', '1', '2025-09-14 14:08:20'),
(30, 'Sample', 'C:\\xampp\\htdocs\\TrackWeb\\config/../documents/uploads/68c6cc542b8d9_1757858900.xlsx', '1', '2025-09-14 14:08:20'),
(31, 'System Progress Report', 'C:\\xampp\\htdocs\\TrackWeb\\config/../documents/uploads/68c6cccdb254d_1757859021.docx', '1', '2025-09-14 14:10:21'),
(32, 'System Progress Report', 'C:\\xampp\\htdocs\\TrackWeb\\config/../documents/uploads/68ca49afc0eec_1758087599.docx', '2', '2025-09-17 05:39:59'),
(33, 'my_BMC', 'C:\\xampp\\htdocs\\TrackWeb\\config/../documents/uploads/68ca49f910bbe_1758087673.pdf', '2', '2025-09-17 05:41:13'),
(34, 'SKRMS Final Manuscript (January 08, 2025)', 'C:\\xampp\\htdocs\\TrackWeb\\config/../documents/uploads/68cb969888c97_1758172824.pdf', '1', '2025-09-18 05:20:24'),
(35, 'Resume', 'C:\\xampp\\htdocs\\TrackWeb\\config/../documents/uploads/68cb99654c4dc_1758173541.docx', '1', '2025-09-18 05:32:21'),
(36, 'APIcloudconvert', 'C:\\xampp\\htdocs\\TrackWeb\\config/../documents/uploads/68da075fb32e6_1759119199.docx', '2', '2025-09-29 04:13:19'),
(37, 'Progress-Report (TRACK) (2)', 'C:\\xampp\\htdocs\\TrackWeb\\config/../documents/uploads/68dfb7976f354_1759491991.docx', '1', '2025-10-03 11:46:31'),
(38, '2025-parentsConsentacknowledgementPage', 'C:\\xampp\\htdocs\\TrackWeb\\config/../documents/uploads/68e654c0cb503_1759925440.pdf', '1', '2025-10-08 12:10:40'),
(39, '2025-parentsConsentacknowledgementPage', 'C:\\xampp\\htdocs\\TrackWeb\\config/../documents/uploads/68e654cd333c6_1759925453.pdf', '1', '2025-10-08 12:10:53'),
(40, 'for-WRITTEN-parents-consent-DMW', 'C:\\xampp\\htdocs\\TrackWeb\\config/../documents/uploads/68e6e9e5617f3_1759963621.docx', '1', '2025-10-08 22:47:01'),
(41, 'my_BMC', 'C:\\xampp\\htdocs\\TrackWeb\\config/../documents/uploads/68e71578bf930_1759974776.pdf', '2', '2025-10-09 01:52:56'),
(42, '1755428545164', 'C:\\xampp\\htdocs\\TrackWeb\\config/../documents/uploads/68e715843f268_1759974788.jpg', '2', '2025-10-09 01:53:08'),
(43, 'APIcloudconvert', 'C:\\xampp\\htdocs\\TrackWeb\\config/../documents/uploads/68e71590e1b5b_1759974800.docx', '2', '2025-10-09 01:53:20'),
(44, 'TabasanRodary_4A', 'C:\\xampp\\htdocs\\TrackWeb\\config/../documents/uploads/68e7b927e0c18_1760016679.png', '2', '2025-10-09 13:31:19'),
(45, 'for-WRITTEN-parents-consent-DMW', 'C:\\xampp\\htdocs\\TrackWeb\\config/../documents/uploads/68e8cf8b73990_1760087947.docx', '2', '2025-10-10 09:19:07'),
(46, '2025-parentsConsentacknowledgementPage', 'C:\\xampp\\htdocs\\TrackWeb\\config/../documents/uploads/68e8d2a41176d_1760088740.pdf', '2', '2025-10-10 09:32:20'),
(47, 'banana_chips_bmc', 'C:\\xampp\\htdocs\\TrackWeb\\config/../documents/uploads/68e98ac764ef3_1760135879.pdf', '1', '2025-10-10 22:37:59'),
(48, '2025-parentsConsentacknowledgementPage', 'C:\\xampp\\htdocs\\TrackWeb\\config/../documents/uploads/68ed1bbf4a8c7_1760369599.pdf', '1', '2025-10-13 15:33:19'),
(49, 'INNOVATE AND PROJECT', 'C:\\xampp\\htdocs\\TrackWeb\\config/../documents/uploads/68ed1bcea77f9_1760369614.docx', '1', '2025-10-13 15:33:34'),
(50, 'SKRMS Final Manuscript (January 08, 2025)', 'C:\\xampp\\htdocs\\TrackWeb\\config/../documents/uploads/68ed1bfa4c9ac_1760369658.pdf', '1', '2025-10-13 15:34:18'),
(51, 'MAPE_TABASAN_SALUDARES.N_QUEJADO', 'C:\\xampp\\htdocs\\TrackWeb\\config/../documents/uploads/68ef91f1b711c_1760530929.docx', '1', '2025-10-15 12:22:09'),
(52, 'Ubuntu_Server_Setup_Guide', 'C:\\xampp\\htdocs\\TrackWeb\\config/../documents/uploads/68ef94d9e6c81_1760531673.pdf', '1', '2025-10-15 12:34:33'),
(53, '1755428545164', 'C:\\xampp\\htdocs\\TrackWeb\\config/../documents/uploads/68ef976b0c48c_1760532331.jpg', '2', '2025-10-15 12:45:31');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) DEFAULT NULL,
  `setting_value` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`) VALUES
(1, 'storage_limit', '1073741824');

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
(1, 'admin', 'admin@gmail.com', '$2y$10$E2ipx9yhTZ77sdfVqD05Pup6mhKTrvq/w7IadlM8MXzugunpm5VLy', 'System Administrator', 'superadmin', '2025-07-31 20:20:00', '2025-10-15 07:42:30', 1, 'active'),
(2, 'rey', 'rey@gmail.com', '$2y$10$y7qIRZmO1JQ3lXltIsiXGe5C.4M7USTl.EkmtxjAjdDFF3tBqbjDu', 'Rodary Tabasan', 'user', '2025-08-06 04:10:20', '2025-10-15 08:07:20', 1, 'active'),
(3, 'jaki', 'jaki@gmail.com', '$2y$10$2dqs1Kms5AO1083m0VDmSuCRy7j8eAK4VMQN0lwJELWVBU7sYiEya', 'Jacqueline Mape', 'user', '2025-08-09 00:49:11', '2025-10-10 03:16:11', 1, 'active'),
(4, 'nora', 'nora@gmail.com', '$2y$10$iR4gWLag9HOT5IXKEdgP2.MTC2NCPrk7vMquN4Jv7Ouk6C3LjcWhG', 'Noralyn Saludares', 'user', '2025-08-09 01:48:48', '2025-09-17 20:06:43', 0, 'active');

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
-- Indexes for table `file_requests`
--
ALTER TABLE `file_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `recipient_id` (`recipient_id`);

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
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=757;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=150;

--
-- AUTO_INCREMENT for table `document_activity`
--
ALTER TABLE `document_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `document_shares`
--
ALTER TABLE `document_shares`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `file_requests`
--
ALTER TABLE `file_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `folders`
--
ALTER TABLE `folders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
-- Constraints for table `file_requests`
--
ALTER TABLE `file_requests`
  ADD CONSTRAINT `file_requests_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `file_requests_ibfk_2` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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
