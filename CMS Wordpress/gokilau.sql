-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 28, 2025 at 04:09 AM
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
-- Database: `gokilau`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `nama`, `email`, `password`) VALUES
(1, 'Admin Gokilau', 'gokilaua@gmail.com', '$2y$10$SqkyTr8rTJLArmlgeOQ8nempC1UoWWLHR0sePLHV/iosrDEQtSeTe');

-- --------------------------------------------------------

--
-- Table structure for table `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id` int(11) NOT NULL,
  `role` enum('admin','pelanggan','pekerja','pemilik') NOT NULL,
  `id_user` int(11) NOT NULL,
  `aktivitas` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id` int(11) NOT NULL,
  `id_pelanggan` int(11) DEFAULT NULL,
  `pesan` text DEFAULT NULL,
  `status` enum('Belum Dibaca','Dibaca') DEFAULT 'Belum Dibaca',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `paket_layanan`
--

CREATE TABLE `paket_layanan` (
  `id` int(11) NOT NULL,
  `nama_layanan` varchar(150) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `harga` decimal(10,2) DEFAULT NULL,
  `estimasi` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `paket_layanan`
--

INSERT INTO `paket_layanan` (`id`, `nama_layanan`, `deskripsi`, `harga`, `estimasi`) VALUES
(2, 'Cuci Mobil Express', 'Layanan cuci mobil cepat yang difokuskan pada kebersihan bagian luar kendaraan. Menggunakan shampo khusus mobil untuk mengangkat debu, lumpur, dan kotoran ringan tanpa merusak cat. Cocok untuk pelanggan yang ingin mobil bersih dan rapi dalam waktu singkat.', 30000.00, '20–30 menit'),
(3, 'Cuci Mobil Premium', 'Layanan cuci mobil menyeluruh yang mencakup pembersihan bagian luar dan dalam kendaraan. Termasuk vakum interior, pembersihan dashboard, kaca, serta sela-sela pintu. Memberikan hasil lebih bersih, wangi, dan nyaman digunakan.', 50000.00, '45–60 menit'),
(4, 'Salon Mobil Interior', 'Perawatan interior mobil secara mendalam meliputi pembersihan jok, karpet, plafon, dashboard, dan interior lainnya. Menghilangkan debu, noda, serta bau tidak sedap agar kabin terasa lebih bersih dan segar.', 120000.00, '1,5 – 2 jam'),
(5, 'Salon Mobil Full', 'Layanan perawatan mobil paling lengkap yang mencakup salon interior dan perawatan eksterior. Termasuk pencucian menyeluruh serta poles ringan untuk mengembalikan kilap bodi mobil agar terlihat seperti baru.', 180000.00, '2 – 3 jam'),
(6, 'Ganti Oli Mesin', 'Layanan penggantian oli mesin untuk menjaga performa dan umur mesin kendaraan. Dikerjakan oleh teknisi berpengalaman dengan prosedur standar bengkel. Harga belum termasuk oli.', 90000.00, '30 menit'),
(7, 'Tune Up Mesin', 'Pemeriksaan dan penyetelan komponen mesin untuk memastikan sistem pembakaran bekerja optimal. Meliputi pengecekan busi, filter udara, dan komponen pendukung lainnya agar mesin lebih bertenaga dan efisien.', 150000.00, '1 – 1,5 jam'),
(8, 'Cek & Tambah Air Radiator', 'Pemeriksaan sistem pendingin mesin termasuk kondisi radiator dan penambahan air radiator jika diperlukan. Membantu mencegah mesin overheat dan menjaga suhu tetap stabil.', 25000.00, '15 menit'),
(9, 'Cek Rem & Kampas Rem', 'Pemeriksaan sistem pengereman kendaraan meliputi kondisi kampas rem, minyak rem, dan cakram. Bertujuan untuk memastikan keamanan dan kenyamanan saat berkendara.', 40000.00, '30 menit');

-- --------------------------------------------------------

--
-- Table structure for table `pekerja`
--

CREATE TABLE `pekerja` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pekerja`
--

INSERT INTO `pekerja` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'Iksan', '$2y$10$mkM.csQTxQy3B8l6FIQI0eA6pR.9W/AROdtXcYjAtKwxzuyoMjihW', '2025-12-28 07:00:08');

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `no_telp` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id`, `username`, `email`, `password`, `no_telp`) VALUES
(4, 'Diva', 'girlcancer898@gmail.com', '$2y$10$4O0q7UKIGt0t1KaeOD6sI.L6qgfLz9ohuusidZ5DHe3AUxYb8rlXW', '$082314761194');

-- --------------------------------------------------------

--
-- Table structure for table `pemilik`
--

CREATE TABLE `pemilik` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pemilik`
--

INSERT INTO `pemilik` (`id`, `username`, `password`, `nama`) VALUES
(1, 'pemilik', '$2y$10$fAngmwpf5gLLOrxvb1TIYOQd7WKNn4Z4UPRogeDWcCkNt9CFj4TL6', 'Pemilik GOKILAU');

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id` int(11) NOT NULL,
  `id_pelanggan` int(11) DEFAULT NULL,
  `id_layanan` int(11) DEFAULT NULL,
  `nama_layanan` varchar(150) DEFAULT NULL,
  `harga` decimal(10,2) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `status` varchar(30) DEFAULT 'Menunggu',
  `alasan_batal` text DEFAULT NULL,
  `tanggal` datetime NOT NULL,
  `batas_bayar` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `testimoni`
--

CREATE TABLE `testimoni` (
  `id` int(11) NOT NULL,
  `id_pesanan` int(11) NOT NULL,
  `id_pelanggan` int(11) NOT NULL,
  `isi` text NOT NULL,
  `rating` int(11) DEFAULT 5,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `paket_layanan`
--
ALTER TABLE `paket_layanan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pekerja`
--
ALTER TABLE `pekerja`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pemilik`
--
ALTER TABLE `pemilik`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testimoni`
--
ALTER TABLE `testimoni`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `paket_layanan`
--
ALTER TABLE `paket_layanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `pekerja`
--
ALTER TABLE `pekerja`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pemilik`
--
ALTER TABLE `pemilik`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `testimoni`
--
ALTER TABLE `testimoni`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
