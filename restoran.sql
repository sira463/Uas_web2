-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20241219.8e721911f0
-- https://www.phpmyadmin.net/
--
-- Host: 192.168.30.23
-- Generation Time: Dec 25, 2024 at 09:17 PM
-- Server version: 8.0.18
-- PHP Version: 8.2.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `restoran`
--

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `nama_menu` varchar(255) NOT NULL,
  `tanggal` date NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `deskripsi` text,
  `kategori` varchar(100) DEFAULT NULL,
  `lama_penyajian` int(11) DEFAULT NULL COMMENT 'Lama penyajian dalam menit',
  `bahan_menu` text,
  `petunjuk_penyajian` text,
  `url_gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `nama_menu`, `tanggal`, `harga`, `deskripsi`, `kategori`, `lama_penyajian`, `bahan_menu`, `petunjuk_penyajian`, `url_gambar`) VALUES
(1, 'Espresso', '2024-12-25', 20000.00, 'Kopi hitam pekat tanpa gula', 'Minuman', 5, 'Biji kopi robusta', 'Seduh dengan air panas, sajikan hangat', 'https://www.shutterstock.com/image-photo/espresso-coffee-cup-on-wooden-table-168414984'),
(2, 'Cappuccino', '2024-12-25', 25000.00, 'Kopi dengan susu berbusa', 'Minuman', 7, 'Biji kopi arabika, susu segar', 'Campurkan susu panas dengan kopi, sajikan dengan topping busa', 'https://www.shutterstock.com/image-photo/cappuccino-coffee-cup-on-wooden-table-168414985'),
(3, 'Americano', '2024-12-25', 22000.00, 'Espresso dengan tambahan air panas', 'Minuman', 5, 'Biji kopi arabika', 'Campur espresso dengan air panas, sajikan hangat', 'https://www.shutterstock.com/image-photo/americano-coffee-cup-on-wooden-table-168414986'),
(4, 'Latte', '2024-12-25', 27000.00, 'Espresso dengan susu hangat', 'Minuman', 6, 'Biji kopi arabika, susu segar', 'Campurkan susu panas dengan kopi, sajikan dalam cangkir', 'https://www.shutterstock.com/image-photo/latte-coffee-cup-on-wooden-table-168414987');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `nama_menu` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `id_user`, `id_menu`, `harga`, `jumlah`, `nama_menu`) VALUES
(1, 2, 1, 20000.00, 2, 'Espresso'),
(2, 2, 2, 25000.00, 1, 'Cappuccino'),
(3, 2, 3, 22000.00, 3, 'Americano'),
(4, 2, 4, 27000.00, 1, 'Latte');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','kasir','pelanggan') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', '25e4ee4e9229397b6b17776bfceaf8e7', 'admin'),
(2, 'user', '63e780c3f321d13109c71bf81805476e', 'kasir'),
(3, 'leader_user', '482c811da5d5b4bc6d497ffa98491e38', 'pelanggan');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_menu` (`id_menu`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
