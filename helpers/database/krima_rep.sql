-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 04, 2024 at 08:05 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `krima_rep`
--

-- --------------------------------------------------------

--
-- Table structure for table `km_users`
--

CREATE TABLE `km_users` (
  `id` bigint(20) NOT NULL COMMENT 'user id',
  `user` varchar(255) NOT NULL COMMENT 'user name',
  `pass` varchar(255) NOT NULL COMMENT 'user password',
  `alias` varchar(255) NOT NULL COMMENT 'user alias'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `km_users`
--

INSERT INTO `km_users` (`id`, `user`, `pass`, `alias`) VALUES
(1, 'admin', '$2y$10$YDSQjW.RufeTV.mrngqAzeHzb05UeG.QV0fOYAXMcnsBjPGsyPMx2', 'Administrator'),
(2, 'user01', '$2y$10$D/1CKVBoz3WHivMJ9jPLQufhrWxMteXQRp6phCSXu2HkN3l5v7cWS', 'KM');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `km_users`
--
ALTER TABLE `km_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `km_users`
--
ALTER TABLE `km_users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'user id', AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
