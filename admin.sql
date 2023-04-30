-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 25, 2023 at 11:22 AM
-- Server version: 8.0.32-0ubuntu0.20.04.2
-- PHP Version: 7.4.3-4ubuntu2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `admin`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint UNSIGNED NOT NULL,
  `user_role` varchar(255) DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `business_phone_number` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `default_language` varchar(50) NOT NULL DEFAULT 'en',
  `is_active` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `device_id` varchar(255) DEFAULT NULL,
  `device_type` varchar(255) DEFAULT NULL,
  `forgot_password_validate_string` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `is_deleted` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `is_verified` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `is_mobile_verified` tinyint NOT NULL DEFAULT '0',
  `otp` int DEFAULT '0',
  `remember_token` text,
  `validate_string` text,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `mobile_validate_string` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='This table has all user records';

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `user_role`, `name`, `first_name`, `last_name`, `email`, `profile_picture`, `business_phone_number`, `password`, `last_login`, `default_language`, `is_active`, `device_id`, `device_type`, `forgot_password_validate_string`, `is_deleted`, `is_verified`, `is_mobile_verified`, `otp`, `remember_token`, `validate_string`, `created_at`, `updated_at`, `deleted_at`, `mobile_validate_string`) VALUES
(1, 'super_admin', 'Admin Nipun', NULL, NULL, 'nipun@gmail.com', NULL, NULL, '$2y$10$MZevF/iyII43NPYb35mKe.YhHAS0HIoJwwSGOocqO5GPo5Vu.S4kW', NULL, 'en', 1, NULL, NULL, NULL, 0, 1, 0, 0, 'CUdv2NsauYDZL3S77H67BbtKy07a0Nnne8lu9ntHkfHh0GJxdBN8VylgF9S3', NULL, '2021-08-19 08:43:17', '2023-03-18 10:46:36', '2021-08-19 08:43:17', '');

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` bigint NOT NULL,
  `image` text,
  `title` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `description` longtext,
  `link` varchar(255) DEFAULT NULL,
  `is_active` tinyint NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `image`, `title`, `subtitle`, `description`, `link`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'banner-bg.jpg', 'No Surprises3', 'Just Surprisingly Smooth Solutions3', 'our industry expertise & great work ethic act as a catalyst for superior professional competence.3', 'https://www.google.com/', 1, '2021-04-26 07:18:34', '2021-05-10 09:32:03'),
(2, '2022-04-17-1650194425-banner.jpg', 'No Surprises 4', 'Just Surprisingly Smooth Solutions 3', 'our industry expertise & great work ethic act as a catalyst for superior professional competence. 3', 'https://www.google.com/', 1, '2021-04-26 07:19:40', '2022-04-17 11:37:07');

-- --------------------------------------------------------

--
-- Table structure for table `blocks`
--

CREATE TABLE `blocks` (
  `id` int UNSIGNED NOT NULL,
  `section_name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `description` longtext,
  `image` varchar(255) DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Table for store dropdown values';

--
-- Dumping data for table `blocks`
--

INSERT INTO `blocks` (`id`, `section_name`, `slug`, `title`, `subtitle`, `description`, `image`, `video_url`, `link`, `created_at`, `updated_at`) VALUES
(1, 'Wedding', 'wedding', 'Wedding', '', 'A wedding and Birthday planner is a who assists with the design, planning and management of a client’s. wedding', '', NULL, NULL, '2022-04-17 14:23:19', '2022-04-17 14:23:19'),
(2, 'Private Party', 'private-party', 'Private Party', '', 'A wedding and Birthday planner is a who assists with the design, planning and management of a client’s. wedding', '', NULL, NULL, '2022-04-17 14:23:19', '2022-04-17 14:23:19'),
(3, 'Birthday Party', 'birthday-party', 'Birthday Party', '', 'A wedding and Birthday planner is a who assists with the design, planning and management of a client’s. wedding', '', 'https://youtu.be/6jHOJWFevrk', NULL, '2022-04-17 14:23:19', '2022-04-17 14:23:19'),
(4, 'Corporate Party', 'corporate-party', 'Corporate Party', '', 'A wedding and Birthday planner is a who assists with the design, planning and management of a client’s. wedding', '', NULL, NULL, '2022-04-17 14:23:19', '2022-04-17 14:23:19'),
(5, 'Our New Event', 'our-new-event', 'Our New Event', '', 'At NJS, we operate as your internal finance team ensuring you have the right skills when you need them. We understand growing businesses have growing needs and often you need a wide range of skill sets one individual will struggle to provide.', '', NULL, NULL, '2022-04-17 14:23:19', '2022-04-17 14:23:19'),
(6, 'The Best Event Planner', 'the-best-event-planner', 'The Best Event Planner', NULL, 'With our passion, knowledge, creative flair and inspiration, we are dedicated in helping you to achieve.\r\n\r\n', 'appointment-project.png', NULL, 'https://www.google.com/', '2022-04-17 14:23:19', '2022-04-17 18:22:57'),
(7, 'Our Next Event', 'our-next-event', 'Our Next Event', '', 'With our passion, knowledge, creative flair and inspiration, we are dedicated in helping you to achieve your dream wedding. Our wedding planning and events coordination services are designed for Any Sized budget, meaning anyone. Our wedding planning and events coordination services are designed.', '', NULL, '', '2022-04-17 14:23:19', '2022-04-17 14:23:19');

-- --------------------------------------------------------

--
-- Table structure for table `colors`
--

CREATE TABLE `colors` (
  `id` bigint NOT NULL,
  `type` varchar(100) DEFAULT NULL,
  `value` varchar(200) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `colors`
--

INSERT INTO `colors` (`id`, `type`, `value`, `name`, `created_at`, `updated_at`) VALUES
(1, 'main', '#f30000', 'Main Color', '2022-04-10 11:44:40', '2022-04-10 09:53:10');

-- --------------------------------------------------------

--
-- Table structure for table `faq`
--

CREATE TABLE `faq` (
  `id` bigint NOT NULL,
  `question` text,
  `answer` text,
  `is_deleted` tinyint DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `faq`
--

INSERT INTO `faq` (`id`, `question`, `answer`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 'How Can I Set An Event?', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 0, '2023-02-23 18:09:16', '2023-02-26 15:22:20'),
(2, 'What Venues Do You Use?', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 0, '2023-02-26 14:31:56', '2023-02-26 14:31:56'),
(3, 'Event catalogue', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 0, '2023-02-26 14:33:37', '2023-02-26 14:33:37'),
(4, 'Shipping & Delivery', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 0, '2023-02-26 14:34:14', '2023-02-26 14:34:14'),
(5, 'What\'s your dream job?', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 0, '2023-02-26 14:41:53', '2023-02-26 14:41:53');

-- --------------------------------------------------------

--
-- Table structure for table `links`
--

CREATE TABLE `links` (
  `id` bigint NOT NULL,
  `type` varchar(100) DEFAULT NULL,
  `value` varchar(200) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `links`
--

INSERT INTO `links` (`id`, `type`, `value`, `name`, `created_at`, `updated_at`) VALUES
(1, 'facebook', 'https://www.facebook.com/', 'Facebook', '2022-03-11 19:06:11', '2022-03-12 13:54:28'),
(2, 'linkedin', 'https://www.linkedin.com/', 'Linkedin', '2022-03-11 19:06:11', '2022-03-12 13:54:28'),
(3, 'twitter', 'https://www.twitter.com/', 'Twitter', '2022-03-11 19:06:11', '2022-03-12 13:54:28'),
(4, 'instagram', 'https://www.instagram.com/?hl=en', 'Instagram', '2022-03-11 19:06:11', '2022-03-12 13:54:28');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint NOT NULL,
  `comment` text,
  `image` varchar(200) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `location` varchar(200) DEFAULT NULL,
  `is_deleted` tinyint DEFAULT '0',
  `rating` int NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `comment`, `image`, `name`, `location`, `is_deleted`, `rating`, `created_at`, `updated_at`) VALUES
(1, 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloret quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit.', 'FEB2023/1677420838-reviewwe.png', 'Richa', 'Perths', 0, 5, '2022-04-15 14:07:39', '2023-02-26 14:13:58'),
(2, 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloret quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit.', 'FEB2023/1677420784-reviewwe.png', 'First slide label', 'Newyork City', 1, 4, '2022-04-17 09:54:26', '2023-03-18 11:06:52');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `value` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `input_type` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'text',
  `editable` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `weight` bigint DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci COMMENT='Table store all website settings';

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `title`, `description`, `input_type`, `editable`, `weight`, `updated_at`, `created_at`) VALUES
(9, 'Reading.records_per_page', '10', 'Records Per Page', '', 'text', 0, 1, '2022-03-11 13:13:22', '0000-00-00 00:00:00'),
(3, 'Reading.date_format', 'm/d/Y', 'Date Format', '', 'text', 1, 3, '2022-03-11 13:13:22', '0000-00-00 00:00:00'),
(7, 'Site.email', 'admin@gmail.com', 'From Email', '', 'text', 1, 2, '2023-03-02 04:54:12', '0000-00-00 00:00:00'),
(1, 'Site.title', 'Wonder Home Finance Limited', 'Site Title', '', 'text', 1, 1, '2023-03-02 04:54:12', '0000-00-00 00:00:00'),
(2, 'Site.address', '620, 6th Floor, North Block, World Trade Park, Malviya Nagar, JLN Road, Jaipur - 302017', 'Site Address', '', 'text', 1, 1, '2023-03-02 04:54:12', '0000-00-00 00:00:00'),
(4, 'Site.mobile1', '+91 7300-238-888', 'Site Mobile 1', '', 'text', 1, 1, '2023-03-02 04:54:12', '0000-00-00 00:00:00'),
(5, 'Site.mobile2', '+91 7300-238-888', 'Site Mobile 2', '', 'text', 1, 1, '2023-03-02 04:54:12', '0000-00-00 00:00:00'),
(8, 'Site.email2', 'nipun.agarwal@wonderhfl.com', 'Site Email 2', '', 'text', 1, 1, '2023-03-02 04:54:12', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `system_documents`
--

CREATE TABLE `system_documents` (
  `id` bigint NOT NULL,
  `image` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `is_active` tinyint NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `system_documents`
--

INSERT INTO `system_documents` (`id`, `image`, `name`, `slug`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'FEB2021/1614319335-image.png', 'Site Logo', 'site-logo', 1, '2021-02-02 09:16:20', '2021-02-26 06:02:15'),
(2, 'FEB2021/1613562263-image.png', 'Fav Icon', 'fav-icon', 1, '2021-02-17 11:44:23', '2021-02-17 11:44:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blocks`
--
ALTER TABLE `blocks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `colors`
--
ALTER TABLE `colors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faq`
--
ALTER TABLE `faq`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `links`
--
ALTER TABLE `links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key` (`key`);

--
-- Indexes for table `system_documents`
--
ALTER TABLE `system_documents`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=161;

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `blocks`
--
ALTER TABLE `blocks`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `colors`
--
ALTER TABLE `colors`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `faq`
--
ALTER TABLE `faq`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `links`
--
ALTER TABLE `links`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
