-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 19, 2025 at 08:49 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `speed_pos_js_v1`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `c_name` varchar(250) NOT NULL,
  `p_id` int(11) NOT NULL DEFAULT 0,
  `sts` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `c_name`, `p_id`, `sts`) VALUES
(1, 'Un Category', 0, 1),
(2, 'Gold', 0, 1),
(3, 'Ring', 2, 1),
(4, 'Baby Ring', 3, 1),
(6, 'Shine', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `synced` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `name`, `slug`, `code`, `synced`) VALUES
(1, 'English', 'english', 'en', 0);

-- --------------------------------------------------------

--
-- Table structure for table `language_translations`
--

CREATE TABLE `language_translations` (
  `id` int(10) NOT NULL,
  `lang_id` int(10) NOT NULL,
  `lang_key` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `key_type` enum('specific','default') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'specific',
  `lang_value` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `synced` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `language_translations`
--

INSERT INTO `language_translations` (`id`, `lang_id`, `lang_key`, `key_type`, `lang_value`, `synced`) VALUES
(1, 1, 'text_login', 'specific', NULL, 0),
(2, 1, 'button_sign_in', 'specific', NULL, 0),
(3, 1, 'title_dashboard', 'specific', NULL, 0),
(4, 1, 'menu_dashboard', 'specific', NULL, 0),
(5, 1, 'menu_pos', 'specific', NULL, 0),
(6, 1, 'menu_customer_display', 'specific', NULL, 0),
(7, 1, 'menu_sele', 'specific', NULL, 0),
(8, 1, 'menu_seles_list', 'specific', NULL, 0),
(9, 1, 'menu_return_list', 'specific', NULL, 0),
(10, 1, 'menu_seles_log', 'specific', NULL, 0),
(11, 1, 'menu_seles_request', 'specific', NULL, 0),
(12, 1, 'menu_sales_summary', 'specific', NULL, 0),
(13, 1, 'menu_customer', 'specific', NULL, 0),
(14, 1, 'menu_users', 'specific', NULL, 0),
(15, 1, 'menu_department', 'specific', NULL, 0),
(16, 1, 'menu_roles', 'specific', NULL, 0),
(17, 1, 'menu_user', 'specific', NULL, 0),
(18, 1, 'menu_change_password', 'specific', NULL, 0),
(19, 1, 'button_today', 'specific', NULL, 0),
(20, 1, 'button_last_7_days', 'specific', NULL, 0),
(21, 1, 'button_last_30_days', 'specific', NULL, 0),
(22, 1, 'button_last_365_days', 'specific', NULL, 0),
(23, 1, 'button_filter', 'specific', NULL, 0),
(24, 1, 'text_powered_by', 'specific', NULL, 0),
(25, 1, 'text_version', 'specific', NULL, 0),
(26, 1, 'menu_user_group', 'specific', NULL, 0),
(27, 1, 'title_users', 'specific', NULL, 0),
(28, 1, 'button_add_user', 'specific', NULL, 0),
(29, 1, 'text_name', 'specific', NULL, 0),
(30, 1, 'text_user_group', 'specific', NULL, 0),
(31, 1, 'text_email', 'specific', NULL, 0),
(32, 1, 'text_mobile', 'specific', NULL, 0),
(33, 1, 'text_view', 'specific', NULL, 0),
(34, 1, 'text_edit', 'specific', NULL, 0),
(35, 1, 'text_delete', 'specific', NULL, 0),
(36, 1, 'title_home', 'specific', NULL, 0),
(37, 1, 'label_name', 'specific', NULL, 0),
(38, 1, 'label_email', 'specific', NULL, 0),
(39, 1, 'label_mobile', 'specific', NULL, 0),
(40, 1, 'label_user_group', 'specific', NULL, 0),
(41, 1, 'label_select_one', 'specific', NULL, 0),
(42, 1, 'title_user_groups', 'specific', NULL, 0),
(43, 1, 'button_add_user_group', 'specific', NULL, 0),
(44, 1, 'text_user_count', 'specific', NULL, 0),
(45, 1, 'button_create', 'specific', NULL, 0),
(46, 1, 'label_reset', 'specific', NULL, 0),
(47, 1, 'text_successful_created', 'specific', NULL, 0),
(48, 1, 'text_delete_from_message', 'specific', NULL, 0),
(49, 1, 'label_insert_content_to', 'specific', NULL, 0),
(50, 1, 'text_select', 'specific', NULL, 0),
(51, 1, 'button_delete', 'specific', NULL, 0),
(52, 1, 'text_delete_success', 'specific', NULL, 0),
(53, 1, 'label_company', 'specific', NULL, 0),
(54, 1, 'button_update', 'specific', NULL, 0),
(55, 1, 'text_password_change', 'specific', NULL, 0),
(56, 1, 'title_password', 'specific', NULL, 0),
(57, 1, 'label_user_name', 'specific', NULL, 0),
(58, 1, 'label_password_new', 'specific', NULL, 0),
(59, 1, 'label_password_confirm', 'specific', NULL, 0),
(60, 1, 'text_permission', 'specific', NULL, 0),
(61, 1, 'text_update_success', 'specific', NULL, 0),
(62, 1, 'button_reset', 'specific', NULL, 0),
(63, 1, 'text_success', 'specific', NULL, 0),
(64, 1, 'error_mobile_or_email_exist', 'specific', NULL, 0),
(65, 1, 'menu_inventory', 'specific', NULL, 0),
(66, 1, 'menu_category', 'specific', NULL, 0),
(67, 1, 'menu_unit', 'specific', NULL, 0),
(68, 1, 'menu_product', 'specific', NULL, 0),
(69, 1, 'menu_supplier', 'specific', NULL, 0),
(70, 1, 'title_categorys', 'specific', NULL, 0),
(71, 1, 'button_add_category', 'specific', NULL, 0),
(72, 1, 'text_category_group', 'specific', NULL, 0),
(73, 1, 'label_category_name', 'specific', NULL, 0),
(74, 1, 'label_category_group', 'specific', NULL, 0),
(75, 1, 'label_parent_category', 'specific', NULL, 0),
(76, 1, 'error_category_name_exist', 'specific', NULL, 0),
(77, 1, 'title_suppliers', 'specific', NULL, 0),
(78, 1, 'button_add_supplier', 'specific', NULL, 0),
(79, 1, 'error_supplier_name', 'specific', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `login_logs`
--

CREATE TABLE `login_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `username` varchar(150) NOT NULL,
  `ip` varchar(150) NOT NULL,
  `status` enum('success','error') NOT NULL DEFAULT 'success',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `store_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `code_name` varchar(150) NOT NULL,
  `mobile` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `zip_code` varchar(20) NOT NULL,
  `currency` varchar(10) NOT NULL,
  `address` text NOT NULL,
  `logo` varchar(150) NOT NULL,
  `favicon` varchar(150) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`store_id`, `name`, `code_name`, `mobile`, `email`, `country`, `zip_code`, `currency`, `address`, `logo`, `favicon`, `created_at`) VALUES
(1, 'Kalaimakal Jewelry ', 'dni_pos', '+94 77 354 3644', 'nit@gmail.com', 'Sri Lanka', '4300', 'Rs', 'vavuniya', '', '', '2025-07-30 00:55:31');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id` int(11) NOT NULL,
  `s_name` varchar(250) NOT NULL,
  `s_mobile` varchar(20) NOT NULL,
  `due` decimal(25,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id`, `s_name`, `s_mobile`, `due`) VALUES
(1, 'No Supplier', '0760000000', '0.00'),
(2, 'Kamal', '0769104866', '0.00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `username` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `mobile` varchar(30) NOT NULL,
  `password` varchar(250) NOT NULL,
  `raw_password` varchar(250) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `preference` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `group_id`, `username`, `email`, `mobile`, `password`, `raw_password`, `status`, `last_login`, `ip`, `preference`, `created_at`, `updated_at`) VALUES
(1, 1, 'I.Rajeevan', 'admin@gmail.com', '+94773543644', '58db9cfebc4cc28c2f628d1b06a30405', '@nit', 1, '2025-08-16 04:27:32', '127.0.0.1', NULL, '2025-07-30 01:15:45', '2025-08-16 10:45:39');

-- --------------------------------------------------------

--
-- Table structure for table `user_group`
--

CREATE TABLE `user_group` (
  `group_id` int(11) NOT NULL,
  `g_name` varchar(150) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 1,
  `permission` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_group`
--

INSERT INTO `user_group` (`group_id`, `g_name`, `status`, `permission`) VALUES
(1, 'Admin', 1, ''),
(2, 'Seles Man', 1, 'a:1:{s:6:\"access\";a:1:{s:15:\"change_password\";s:4:\"true\";}}');

-- --------------------------------------------------------

--
-- Table structure for table `user_to_store`
--

CREATE TABLE `user_to_store` (
  `u2s_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_to_store`
--

INSERT INTO `user_to_store` (`u2s_id`, `user_id`, `store_id`, `status`, `sort_order`) VALUES
(29, 1, 1, 1, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `language_translations`
--
ALTER TABLE `language_translations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_logs`
--
ALTER TABLE `login_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`store_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_group`
--
ALTER TABLE `user_group`
  ADD PRIMARY KEY (`group_id`);

--
-- Indexes for table `user_to_store`
--
ALTER TABLE `user_to_store`
  ADD PRIMARY KEY (`u2s_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `language_translations`
--
ALTER TABLE `language_translations`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `login_logs`
--
ALTER TABLE `login_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `store_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_group`
--
ALTER TABLE `user_group`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_to_store`
--
ALTER TABLE `user_to_store`
  MODIFY `u2s_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
