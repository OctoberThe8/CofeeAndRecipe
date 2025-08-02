-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 01, 2025 at 03:41 PM
-- Server version: 10.4.34-MariaDB-log
-- PHP Version: 8.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `senah_schema`
--

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `recipe_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`id`, `user_id`, `product_id`, `recipe_id`, `created_at`) VALUES
(5, 4, NULL, 11, '2025-07-31 20:19:01');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `shipping_address` text DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total`, `status`, `shipping_address`, `payment_method`, `created_at`, `updated_at`, `name`, `email`) VALUES
(1, NULL, 539.91, 'pending', NULL, NULL, '2025-07-27 02:42:16', '2025-07-27 02:42:16', 'hanan', 'user1@gmail.com'),
(2, NULL, 199.99, 'pending', NULL, NULL, '2025-07-27 02:44:30', '2025-07-27 02:44:30', 'hanan', 'senah@uwindsor.ca'),
(3, 4, 199.99, 'Pending', '1234', NULL, '2025-07-30 00:03:46', '2025-07-30 00:03:46', 'hanan', 'senah@uwindsor.ca'),
(4, 4, 59.43, 'Pending', 'test1@gmail.com', NULL, '2025-07-30 19:05:18', '2025-07-30 19:05:18', 'test1', 'test1@gmail.com'),
(5, 4, 509.40, 'Pending', 'jjjjjj', NULL, '2025-07-30 19:15:38', '2025-07-30 19:15:38', 'hh', 'senah@uwindsor.ca'),
(6, 4, 9.99, 'Pending', '1222', NULL, '2025-07-30 19:23:42', '2025-07-30 19:23:42', 'hanan', 'senah@uwindsor.ca'),
(7, 4, 199.99, 'Pending', 'jjjjjj', NULL, '2025-07-30 19:31:55', '2025-07-30 19:31:55', 'hanan', 'senah@uwindsor.ca'),
(8, 4, 8.99, 'Pending', 'jjjjjj', NULL, '2025-07-30 19:36:30', '2025-07-30 19:36:30', 'hanan', 'senah@uwindsor.ca'),
(9, 4, 71.97, 'Pending', '1234', NULL, '2025-07-31 14:44:52', '2025-07-31 14:44:52', 'hanan', 'senah@uwindsor.ca'),
(10, 4, 211.98, 'Pending', 'jjjjjj', NULL, '2025-07-31 15:04:45', '2025-07-31 15:04:45', 'hanan', 'senah@uwindsor.ca'),
(11, 4, 211.98, 'Pending', 'jjjjjj', NULL, '2025-07-31 15:06:54', '2025-07-31 15:06:54', 'hanan', 'senah@uwindsor.ca'),
(12, 4, 199.99, 'Pending', '1234', NULL, '2025-07-31 20:49:53', '2025-07-31 20:49:53', 'hanan', 'senah@uwindsor.ca'),
(13, 4, 199.99, 'Pending', '1234', NULL, '2025-07-31 20:55:28', '2025-07-31 20:55:28', 'hanan', 'senah@uwindsor.ca'),
(14, 4, 15.99, 'Pending', 'jjjjjj', NULL, '2025-07-31 21:31:48', '2025-07-31 21:31:48', 'saad', 'user1@gmail.com'),
(15, 4, 31.98, 'Pending', '1234', NULL, '2025-07-31 21:56:09', '2025-07-31 21:56:09', 'hanan', 'senah@uwindsor.ca'),
(16, 4, 47.97, 'Pending', '1234', NULL, '2025-07-31 21:57:56', '2025-07-31 21:57:56', 'hanan', 'user1@gmail.com'),
(17, 4, 8.99, 'Pending', '1234', NULL, '2025-07-31 22:01:07', '2025-07-31 22:01:07', 'hanan', 'senah@uwindsor.ca'),
(18, 4, 8.99, 'Pending', '1234', NULL, '2025-07-31 22:06:52', '2025-07-31 22:06:52', 'hanan', 'senah@uwindsor.ca'),
(19, 4, 17.98, 'processing', '1234', NULL, '2025-07-31 22:11:30', '2025-07-31 23:04:33', 'saad', 'senah@uwindsor.ca'),
(20, 4, 12.99, 'Pending', 'jjjjjj', NULL, '2025-08-01 16:38:48', '2025-08-01 16:38:48', 'hanan', 'user1@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `options` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`, `options`) VALUES
(1, 1, 4, 2, 199.99, NULL),
(2, 1, 6, 3, 9.99, NULL),
(3, 1, 10, 1, 19.99, NULL),
(4, 1, 12, 3, 29.99, NULL),
(5, 2, 4, 1, 199.99, NULL),
(6, 3, 4, 1, 199.99, NULL),
(7, 4, 14, 7, 8.49, NULL),
(8, 5, 14, 60, 8.49, NULL),
(9, 6, 6, 1, 9.99, NULL),
(10, 7, 4, 1, 199.99, NULL),
(11, 8, 8, 1, 8.99, NULL),
(12, 9, 9, 1, 11.99, NULL),
(13, 9, 12, 2, 29.99, NULL),
(14, 11, 9, 1, 11.99, NULL),
(15, 11, 4, 1, 199.99, NULL),
(16, 12, 4, 1, 199.99, NULL),
(17, 13, 4, 0, 199.99, NULL),
(18, 14, 5, 0, 15.99, NULL),
(19, 15, 5, 0, 15.99, NULL),
(20, 16, 5, 0, 15.99, NULL),
(21, 16, 5, 0, 15.99, NULL),
(22, 17, 8, 0, 8.99, NULL),
(23, 18, 8, 1, 8.99, NULL),
(24, 19, 8, 2, 8.99, NULL),
(25, 20, 1, 1, 12.99, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(50) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `image_path` varchar(255) DEFAULT NULL,
  `options` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `category`, `stock`, `image_path`, `options`, `created_at`, `updated_at`) VALUES
(1, 'Colombian Coffee Beans', 'Colombian beans brought from Colombia', 12.99, 'beans', 49, 'Colombian-beans.jpg', '[\"Medium roast\", \"full roast\"]', '2025-07-25 22:14:35', '2025-08-01 16:38:48'),
(2, 'Ethiopian Coffee Beans', 'Light roast with fruity notes', 14.99, 'beans', 40, 'ethiopian-beans.jpg', '[\"Ground\", \"Whole Bean\"]', '2025-07-25 22:18:36', '2025-07-30 22:45:47'),
(3, 'Pour Over Kit\r\n', 'Beginner-friendly pour over set', 24.99, 'equipment', 20, 'Pour Over Kit.jpg\r\n', '[\"Plastic Dripper\", \"Ceramic Dripper\"]', '2025-07-25 22:22:53', '2025-07-30 22:45:26'),
(4, 'Espresso Machine - Basic', 'Compact espresso maker for home', 199.99, 'equipment', 10, 'Espresso-machine.jpg', '[\"110V\", \"220V\"]', '2025-07-25 22:27:16', '2025-07-30 22:31:47'),
(5, 'Sumatra Organic Beans', 'Earthy and herbal profile', 15.99, 'beans', 30, 'Sumatra-beans.jpg', '[\"Medium Roast\", \"Dark Roast\"]', '2025-07-25 22:27:16', '2025-07-30 22:45:08'),
(6, 'Matcha Powder (100g)', 'Premium ceremonial grade', 9.99, 'tea', 25, 'Matcha-powder.jpg', '[\"Sweetened\", \"Unsweetened\"]', '2025-07-25 22:27:16', '2025-07-30 22:44:52'),
(7, 'Cold Brew Bottle', 'Brew coffee cold overnight', 17.99, 'equipment', 30, 'Cold-brew-kit.jpg\r\n\r\n', '[\"Glass\", \"Plastic\"]', '2025-07-25 22:27:16', '2025-07-30 22:44:37'),
(8, 'Reusable Coffee Filter', 'Eco-friendly metal mesh filter', 8.99, 'equipment', 32, 'Reusable-filter.jpg\r\n\r\n', '[\"Single Layer\", \"Double Layer\"]', '2025-07-25 22:27:16', '2025-07-31 22:11:30'),
(9, 'Iced Coffee Cups (Set of 4)', 'Durable, reusable cups for cold drinks', 11.99, 'accessory', 40, 'Iced-coffee-glass.jpg\r\n\r\n', '[\"Tall\", \"Short\"]', '2025-07-25 22:27:16', '2025-07-30 22:44:03'),
(10, 'Coffee Travel Mug', 'Keeps coffee hot for hours', 19.99, 'accessory', 50, 'Travel-mug.jpg\r\n\r\n', '[\"Black\", \"Silver\"]', '2025-07-25 22:27:16', '2025-07-30 22:43:44'),
(11, 'Organic Chai Mix', 'Spicy, aromatic tea blend', 13.49, 'tea', 20, 'Organic Chai mix.jpg', '[\"Loose Leaf\", \"Powder\"]', '2025-07-25 22:27:16', '2025-07-30 22:38:02'),
(12, 'French Press (1L)', 'Brew coffee easily at home', 29.99, 'equipment', 15, 'French Press.jpg', '[\"Glass\", \"Stainless Steel\"]', '2025-07-25 22:27:16', '2025-07-30 22:37:46'),
(13, 'Mocha Syrup (250ml)', 'Rich chocolate syrup for coffee drinks', 7.99, 'flavoring', 60, 'Mocha Syrup.jpg', '[\"Dark Chocolate\", \"White Chocolate\"]', '2025-07-25 22:27:16', '2025-07-30 22:37:29'),
(14, 'Vanilla Syrup (500ml)', 'Sweet vanilla notes for lattes', 8.49, 'flavoring', 60, 'Vanilla-syrup.jpg', '[\"Regular\", \"Sugar-Free\"]', '2025-07-25 22:27:16', '2025-07-30 22:37:12'),
(15, 'Bamboo Stirring Spoon', 'Natural, eco-friendly', 2.99, 'accessory', 80, 'Bamboo Stirring Spoon.jpg', '[\"Small\", \"Large\"]', '2025-07-25 22:27:16', '2025-07-30 22:36:51'),
(16, 'Coffee Storage Canister', 'Keeps beans fresh and dry', 14.99, 'accessory', 45, 'Coffee Storage Canister.jpg', '[\"Black\", \"White\"]', '2025-07-25 22:27:16', '2025-07-30 22:34:02'),
(17, 'Espresso Glass Set', 'Two small espresso cups', 10.99, 'accessory', 35, 'Espresso Glass Set.jpg', '[\"Clear\", \"Tinted\"]', '2025-07-25 22:27:16', '2025-07-30 22:33:43'),
(18, 'Coffee Grinder', 'Manual burr grinder for beans', 34.99, 'equipment', 25, 'Coffee Grinder.jpg', '[\"Metal Handle\", \"Wooden Handle\"]', '2025-07-25 22:27:16', '2025-07-30 22:33:26'),
(19, 'Organic Cocoa Powder (200g)', 'Great for mochas and baking', 6.49, 'ingredient', 50, 'Organic Cocoa Powder.jpg', '[\"Dutch Process\", \"Natural\"]', '2025-07-25 22:27:16', '2025-07-30 22:33:03'),
(20, 'Coffee Recipe Book.jpg', '50 delicious drink ideas', 21.99, 'book', 20, 'Coffee Recipe Book.jpg', '[\"Paperback\", \"Hardcover\"]', '2025-07-25 22:27:16', '2025-07-31 23:02:43');

-- --------------------------------------------------------

--
-- Table structure for table `recipes`
--

CREATE TABLE `recipes` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `ingredients` text NOT NULL,
  `instructions` text NOT NULL,
  `prep_time` int(11) DEFAULT NULL,
  `cook_time` int(11) DEFAULT NULL,
  `servings` int(11) DEFAULT NULL,
  `difficulty` varchar(20) DEFAULT NULL,
  `category` varchar(50) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `video_path` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recipes`
--

INSERT INTO `recipes` (`id`, `title`, `description`, `ingredients`, `instructions`, `prep_time`, `cook_time`, `servings`, `difficulty`, `category`, `image_path`, `video_path`, `user_id`, `created_at`, `updated_at`) VALUES
(11, 'Classic Tiramisu', 'Classic Italian dessert with espresso and mascarpone', '1 cup strong brewed espresso\n24 ladyfinger cookies\n16 oz mascarpone cheese\n3 eggs, separated\n½ cup sugar\n2 tbsp coffee liqueur (optional)\nCocoa powder for dusting', 'Whisk egg yolks and sugar until creamy. Add mascarpone and mix. Beat egg whites until stiff peaks form, then fold into mascarpone mixture. Dip ladyfingers briefly in espresso and layer. Spread half the mascarpone mixture, then repeat layers. Chill for 4+ hours. Dust with cocoa before serving.', 15, 0, 6, 'Medium', 'Dessert', 'tiramisu.jpg', NULL, NULL, '2025-07-26 01:25:01', '2025-07-26 01:25:01'),
(22, 'Coffee-Rubbed Steak', 'Bold steak infused with coffee-based dry rub', '2 ribeye steaks\r\n2 tbsp finely ground coffee\r\n1 tbsp brown sugar\r\n1 tsp smoked paprika\r\n1 tsp garlic powder\r\nSalt & pepper\r\n1 tbsp olive oil', 'Mix dry rub ingredients. Rub on steaks. Let rest 30 minutes. Sear each side 3–4 minutes. Rest 5 minutes before serving.', 10, 11, 2, 'Medium', 'Main Course', 'coffee_steak.jpg', NULL, NULL, '2025-07-26 01:25:01', '2025-08-01 15:14:55'),
(33, 'Iced Dalgona Coffee', 'Viral whipped coffee drink for hot days', '2 tbsp instant coffee\n2 tbsp sugar\n2 tbsp hot water\n1 cup milk\nIce cubes', 'Whip coffee, sugar, water until frothy. Pour milk over ice. Spoon coffee foam on top.', 5, 0, 1, 'Easy', 'Drink', 'dalgona.jpg', NULL, NULL, '2025-07-26 01:25:01', '2025-07-26 01:25:01'),
(44, 'Mocha Overnight Oats', 'Healthy, energizing oats infused with coffee and cocoa', '½ cup rolled oats\n½ cup brewed coffee\n½ cup milk\n1 tbsp cocoa powder\n1 tbsp maple syrup\n1 tsp chia seeds\nBanana slices, almonds (topping)', 'Mix ingredients in a jar. Refrigerate overnight. Stir and top before eating.', 10, 0, 1, 'Easy', 'Breakfast', 'mocha_oats.jpg', NULL, NULL, '2025-07-26 01:25:01', '2025-07-26 01:25:01'),
(55, 'Espresso Brownies', 'Rich chocolate brownies with espresso twist', '½ cup butter\n1 cup sugar\n2 eggs\n1 tsp vanilla\n⅓ cup cocoa powder\n½ cup flour\n1 tbsp instant espresso powder\nPinch of salt', 'Melt butter. Mix with sugar, eggs, vanilla. Add dry ingredients. Bake at 350°F for 20–25 min.', 10, 25, 9, 'Easy', 'Dessert', 'espresso_brownies.jpg', NULL, NULL, '2025-07-26 01:25:01', '2025-07-26 01:25:01');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `recipe_id` int(11) DEFAULT NULL,
  `item_type` text NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `product_id`, `recipe_id`, `item_type`, `rating`, `comment`, `created_at`) VALUES
(2, 4, NULL, 11, 'recipe', 4, 'hi, it\'s me again, I tried it yesterday, it was goooooooooooooooooooooooooooooood', '2025-07-30 18:04:03'),
(3, 4, 4, NULL, 'product', 5, 'i love it', '2025-07-30 18:21:35'),
(4, 4, 4, NULL, 'product', 5, 'love it', '2025-07-30 18:49:40'),
(6, 4, 4, NULL, 'product', 5, 'i love it', '2025-07-30 18:55:14'),
(7, 4, 4, NULL, 'product', 4, 'love it', '2025-07-30 18:55:31'),
(8, 4, NULL, 11, 'recipe', 1, 'Terrible, I hate Tiramisu!!!!!!! Hanan, DO BETTER!!', '2025-07-30 19:01:03'),
(9, 4, 14, NULL, 'product', 2, 'I recommend mixing it with ketchup, very delicious.', '2025-07-30 19:02:27'),
(10, 6, NULL, 11, 'recipe', 5, 'hi i am the admin', '2025-07-30 21:19:20'),
(11, 4, NULL, 33, 'recipe', 4, 'it is good', '2025-07-31 19:14:31');

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL,
  `setting_name` varchar(50) NOT NULL,
  `setting_value` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `setting_name`, `setting_value`, `created_at`, `updated_at`) VALUES
(1, 'current_theme', 'easter', '2025-07-18 14:48:48', '2025-08-01 19:05:56'),
(2, 'maintenance_mode', '0', '2025-07-18 14:48:48', '2025-07-18 14:48:48');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `first_name`, `last_name`, `address`, `phone`, `is_admin`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 'Lynn', 'Lynn@coffee.ca', '1357', NULL, NULL, NULL, NULL, 0, 1, '2025-07-18 14:48:48', '2025-08-01 15:14:01'),
(3, 'saad', 'saad@uwindsor.com', '$2y$10$O0KAzfQ0fBuND5f/jfe06O5TExBl/b0/fsaD/YuhjZQZ2lh/8gDY.', NULL, NULL, NULL, NULL, 1, 1, '2025-07-20 23:56:35', '2025-07-30 21:02:25'),
(4, 'user1', 'user1@gmail.com', '$2y$10$INx2YSfOm5ZkmeSB3gmM7eQyB/jUQpvvefc1KJ.bwXGhMy/6A2czC', 'hanan', 'ss', '', '', 0, 1, '2025-07-23 15:22:02', '2025-07-31 16:24:50'),
(5, 'user999', 'user999@gmail.com', '$2y$10$0eGusxk0cn02zyb12VA60ONSTSaGhxO6mHB8N2RlR5G96kcW/MJNe', NULL, NULL, NULL, NULL, 0, 1, '2025-07-30 19:52:10', '2025-07-30 19:52:10'),
(6, 'hanan', 'marco@uwindsor.ca', '$2y$10$E6LnVZrYVt0w3171IHR93OBF950noebprNf7QSYaLoN6l5igYlpGi', 'hanan', '', '', '', 1, 1, '2025-07-30 21:03:20', '2025-08-01 15:16:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `recipe_id` (`recipe_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recipes`
--
ALTER TABLE `recipes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `recipe_id` (`recipe_id`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_name` (`setting_name`);

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
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `recipes`
--
ALTER TABLE `recipes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `fk_favorites_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_favorites_recipe` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_favorites_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_order_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `recipes`
--
ALTER TABLE `recipes`
  ADD CONSTRAINT `fk_recipes_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_reviews_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reviews_recipe` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reviews_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
