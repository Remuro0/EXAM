-- phpMyAdmin SQL Dump
-- version 5.1.3-3.red80
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Апр 16 2026 г., 10:02
-- Версия сервера: 10.11.11-MariaDB
-- Версия PHP: 8.1.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `warehouse_db`
--

-- --------------------------------------------------------

--
-- Структура таблицы `audit_log`
--

CREATE TABLE `audit_log` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(50) NOT NULL,
  `table_name` varchar(50) DEFAULT NULL,
  `record_id` int(11) DEFAULT NULL,
  `old_values` text DEFAULT NULL,
  `new_values` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `batches`
--

CREATE TABLE `batches` (
  `batch_id` int(11) NOT NULL,
  `batch_number` varchar(30) NOT NULL,
  `product_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL CHECK (`quantity` > 0),
  `quantity_remaining` int(11) NOT NULL CHECK (`quantity_remaining` >= 0),
  `receive_date` date NOT NULL DEFAULT curdate(),
  `expiry_date` date DEFAULT NULL,
  `cost_price` decimal(10,2) NOT NULL CHECK (`cost_price` > 0),
  `status` enum('in_stock','reserved','written_off','expired') NOT NULL DEFAULT 'in_stock',
  `quality_check` enum('passed','pending','failed') NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `batches`
--

INSERT INTO `batches` (`batch_id`, `batch_number`, `product_id`, `supplier_id`, `location_id`, `quantity`, `quantity_remaining`, `receive_date`, `expiry_date`, `cost_price`, `status`, `quality_check`, `created_at`) VALUES
(13, 'BATCH-2024-001', 1, 1, 1, 50, 35, '2024-01-15', NULL, '75000.00', 'in_stock', 'passed', '2026-04-16 16:49:01'),
(14, 'BATCH-2024-002', 2, 1, 2, 100, 80, '2024-01-20', NULL, '30000.00', 'in_stock', 'passed', '2026-04-16 16:49:01'),
(15, 'BATCH-2024-003', 3, 2, 3, 30, 25, '2024-02-10', NULL, '12000.00', 'in_stock', 'passed', '2026-04-16 16:49:01'),
(16, 'BATCH-2024-004', 4, 2, 4, 500, 400, '2024-02-15', NULL, '400.00', 'in_stock', 'passed', '2026-04-16 16:49:01');

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`category_id`, `name`, `description`, `parent_id`) VALUES
(17, 'Электроника', 'Электронные компоненты и устройства', NULL),
(18, 'Комплектующие', 'Комплектующие для электроники', 1),
(19, 'Инструменты', 'Строительные и ремонтные инструменты', NULL),
(20, 'Расходные материалы', 'Расходники для склада', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `inventory_movements`
--

CREATE TABLE `inventory_movements` (
  `movement_id` int(11) NOT NULL,
  `batch_id` int(11) NOT NULL,
  `movement_type` enum('receipt','shipment','transfer','adjustment','return') NOT NULL,
  `quantity` int(11) NOT NULL,
  `from_location_id` int(11) DEFAULT NULL,
  `to_location_id` int(11) DEFAULT NULL,
  `reference_number` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `movement_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `sku` varchar(30) NOT NULL,
  `category_id` int(11) NOT NULL,
  `unit` enum('pcs','kg','l','box','pallet') NOT NULL DEFAULT 'pcs',
  `base_price` decimal(10,2) NOT NULL CHECK (`base_price` > 0),
  `min_stock` int(11) NOT NULL DEFAULT 0 CHECK (`min_stock` >= 0),
  `description` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`product_id`, `name`, `sku`, `category_id`, `unit`, `base_price`, `min_stock`, `description`, `created_at`, `updated_at`) VALUES
(13, 'Ноутбук Dell Latitude 5520', 'DELL-LAT5520-I7', 1, 'pcs', '85000.00', 10, 'Бизнес-ноутбук, Intel Core i7', '2026-04-16 16:49:50', '2026-04-16 16:49:50'),
(14, 'Монитор Samsung 27\"', 'SAM-MON27-4K', 1, 'pcs', '35000.00', 15, '4K UHD монитор, 27 дюймов', '2026-04-16 16:49:50', '2026-04-16 16:49:50'),
(15, 'Дрель Makita 18V', 'MAK-DRILL-18V', 3, 'pcs', '15000.00', 8, 'Аккумуляторная дрель', '2026-04-16 16:49:50', '2026-04-16 16:49:50'),
(16, 'Перчатки рабочие', 'GLOVES-WORK-L', 4, 'box', '500.00', 100, 'Защитные перчатки, размер L', '2026-04-16 16:49:50', '2026-04-16 16:49:50');

-- --------------------------------------------------------

--
-- Структура таблицы `storage_locations`
--

CREATE TABLE `storage_locations` (
  `location_id` int(11) NOT NULL,
  `cell_code` varchar(20) NOT NULL,
  `zone` enum('receiving','storage','shipping','quarantine') NOT NULL DEFAULT 'storage',
  `aisle` varchar(10) DEFAULT NULL,
  `rack` varchar(10) DEFAULT NULL,
  `shelf` varchar(10) DEFAULT NULL,
  `max_capacity` int(11) NOT NULL CHECK (`max_capacity` > 0),
  `current_load` int(11) NOT NULL DEFAULT 0 CHECK (`current_load` >= 0),
  `temperature_controlled` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('available','occupied','maintenance') NOT NULL DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `storage_locations`
--

INSERT INTO `storage_locations` (`location_id`, `cell_code`, `zone`, `aisle`, `rack`, `shelf`, `max_capacity`, `current_load`, `temperature_controlled`, `status`) VALUES
(9, 'A-01-01', 'storage', 'A', '01', '01', 100, 45, 0, 'occupied'),
(10, 'A-01-02', 'storage', 'A', '01', '02', 100, 30, 0, 'occupied'),
(11, 'B-01-01', 'storage', 'B', '01', '01', 80, 0, 0, 'available'),
(12, 'C-01-01', 'receiving', 'C', '01', '01', 200, 50, 0, 'occupied');

-- --------------------------------------------------------

--
-- Структура таблицы `suppliers`
--

CREATE TABLE `suppliers` (
  `supplier_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `inn` varchar(12) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `contact_person` varchar(120) DEFAULT NULL,
  `status` enum('active','inactive','blocked') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `suppliers`
--

INSERT INTO `suppliers` (`supplier_id`, `name`, `inn`, `phone`, `email`, `address`, `contact_person`, `status`, `created_at`) VALUES
(7, 'ООО \"ТехноСнаб\"', '7701234567', '+74951112233', 'info@technosnab.ru', 'г. Москва, ул. Промышленная, 10', 'Иванов Пётр', 'active', '2026-04-16 16:47:48'),
(8, 'ИП Смирнов А.В.', '7709876543', '+74954445566', 'smirnov@mail.ru', 'г. Москва, ул. Складская, 5', 'Смирнов Алексей', 'active', '2026-04-16 16:47:48'),
(9, 'ООО \"Логистик Плюс\"', '7705555666', '+74957778899', 'info@logisticplus.ru', 'г. Подольск, ул. Транспортная, 20', 'Козлова Мария', 'active', '2026-04-16 16:47:48');

-- --------------------------------------------------------

--
-- Структура таблицы `theme_settings`
--

CREATE TABLE `theme_settings` (
  `id` int(11) NOT NULL,
  `primary_r` int(11) NOT NULL DEFAULT 76,
  `primary_g` int(11) NOT NULL DEFAULT 175,
  `primary_b` int(11) NOT NULL DEFAULT 80,
  `secondary_r` int(11) NOT NULL DEFAULT 56,
  `secondary_g` int(11) NOT NULL DEFAULT 142,
  `secondary_b` int(11) NOT NULL DEFAULT 60,
  `accent_r` int(11) NOT NULL DEFAULT 255,
  `accent_g` int(11) NOT NULL DEFAULT 152,
  `accent_b` int(11) NOT NULL DEFAULT 0,
  `background_r` int(11) NOT NULL DEFAULT 245,
  `background_g` int(11) NOT NULL DEFAULT 245,
  `background_b` int(11) NOT NULL DEFAULT 245,
  `text_r` int(11) NOT NULL DEFAULT 33,
  `text_g` int(11) NOT NULL DEFAULT 33,
  `text_b` int(11) NOT NULL DEFAULT 33
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `theme_settings`
--

INSERT INTO `theme_settings` (`id`, `primary_r`, `primary_g`, `primary_b`, `secondary_r`, `secondary_g`, `secondary_b`, `accent_r`, `accent_g`, `accent_b`, `background_r`, `background_g`, `background_b`, `text_r`, `text_g`, `text_b`) VALUES
(1, 76, 175, 80, 56, 142, 60, 255, 152, 0, 245, 245, 245, 33, 33, 33);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(120) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` enum('admin','warehouse_manager','clerk','viewer') NOT NULL DEFAULT 'viewer',
  `status` enum('active','inactive','blocked') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `full_name`, `email`, `role`, `status`, `created_at`, `last_login`) VALUES
(7, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Администратор Системы', 'admin@warehouse.com', 'admin', 'active', '2026-04-16 16:49:01', NULL),
(8, 'manager', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Менеджер склада', 'manager@warehouse.com', 'warehouse_manager', 'active', '2026-04-16 16:49:01', NULL),
(9, 'clerk1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Кладовщик Иванов', 'ivanov@warehouse.com', 'clerk', 'active', '2026-04-16 16:49:01', NULL);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `audit_log`
--
ALTER TABLE `audit_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_action` (`action`);

--
-- Индексы таблицы `batches`
--
ALTER TABLE `batches`
  ADD PRIMARY KEY (`batch_id`),
  ADD UNIQUE KEY `batch_number` (`batch_number`),
  ADD KEY `idx_product` (`product_id`),
  ADD KEY `idx_supplier` (`supplier_id`),
  ADD KEY `idx_location` (`location_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_expiry` (`expiry_date`);

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `idx_parent` (`parent_id`);

--
-- Индексы таблицы `inventory_movements`
--
ALTER TABLE `inventory_movements`
  ADD PRIMARY KEY (`movement_id`),
  ADD KEY `from_location_id` (`from_location_id`),
  ADD KEY `to_location_id` (`to_location_id`),
  ADD KEY `idx_batch` (`batch_id`),
  ADD KEY `idx_type` (`movement_type`),
  ADD KEY `idx_date` (`movement_date`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `idx_category` (`category_id`),
  ADD KEY `idx_sku` (`sku`);

--
-- Индексы таблицы `storage_locations`
--
ALTER TABLE `storage_locations`
  ADD PRIMARY KEY (`location_id`),
  ADD UNIQUE KEY `cell_code` (`cell_code`),
  ADD KEY `idx_zone` (`zone`),
  ADD KEY `idx_status` (`status`);

--
-- Индексы таблицы `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`supplier_id`),
  ADD UNIQUE KEY `inn` (`inn`),
  ADD KEY `idx_inn` (`inn`),
  ADD KEY `idx_status` (`status`);

--
-- Индексы таблицы `theme_settings`
--
ALTER TABLE `theme_settings`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_role` (`role`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `audit_log`
--
ALTER TABLE `audit_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `batches`
--
ALTER TABLE `batches`
  MODIFY `batch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT для таблицы `inventory_movements`
--
ALTER TABLE `inventory_movements`
  MODIFY `movement_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT для таблицы `storage_locations`
--
ALTER TABLE `storage_locations`
  MODIFY `location_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT для таблицы `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `theme_settings`
--
ALTER TABLE `theme_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `audit_log`
--
ALTER TABLE `audit_log`
  ADD CONSTRAINT `audit_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `batches`
--
ALTER TABLE `batches`
  ADD CONSTRAINT `batches_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `batches_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`supplier_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `batches_ibfk_3` FOREIGN KEY (`location_id`) REFERENCES `storage_locations` (`location_id`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `inventory_movements`
--
ALTER TABLE `inventory_movements`
  ADD CONSTRAINT `inventory_movements_ibfk_1` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`batch_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inventory_movements_ibfk_2` FOREIGN KEY (`from_location_id`) REFERENCES `storage_locations` (`location_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `inventory_movements_ibfk_3` FOREIGN KEY (`to_location_id`) REFERENCES `storage_locations` (`location_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
