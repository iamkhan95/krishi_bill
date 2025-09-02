-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 02, 2025 at 11:59 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `billing_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(10) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(10) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `email`, `phone`) VALUES
(13, 'Aman Khan', 'iamkhan9510@gmail.com', '09511749510'),
(14, 'Sangam Park', 'iamkhan9510@gmail.com', '08625999510');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `invoice_number` varchar(50) DEFAULT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `customer_email` varchar(100) NOT NULL,
  `customer_phone` varchar(20) DEFAULT 'CURRENT_DATE',
  `invoice_date` date NOT NULL,
  `total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `invoice_number`, `customer_name`, `customer_email`, `customer_phone`, `invoice_date`, `total`) VALUES
(14, 'INV-1756806318', 'aman khan', 'iamkhan9510@gmail.com', '08625999510', '0000-00-00', 2832.00),
(15, 'INV-1756806347', 'aman khan', 'iamkhan9510@gmail.com', '08625999510', '0000-00-00', 2832.00);

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoice_items`
--

INSERT INTO `invoice_items` (`id`, `invoice_id`, `product_id`, `quantity`, `price`, `subtotal`) VALUES
(19, 14, 5, 1, 1200.00, 1200.00),
(20, 14, 5, 0, 1200.00, 1200.00),
(21, 15, 5, 1, 1200.00, 1200.00),
(22, 15, 5, 0, 1200.00, 1200.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_id` varchar(20) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT 0.00,
  `stock` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_id`, `name`, `description`, `price`, `stock`, `created_at`) VALUES
(5, NULL, 'aman', NULL, 1200.00, 12, '2025-09-02 09:00:30'),
(6, NULL, 'pipe', NULL, 200.00, 198, '2025-09-02 09:49:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
