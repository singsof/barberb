-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 12, 2022 at 12:48 PM
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
-- Database: `barber`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_ad` int(11) NOT NULL,
  `name_ad` varchar(50) NOT NULL,
  `tel_ad` varchar(11) NOT NULL,
  `pass_ad` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_ad`, `name_ad`, `tel_ad`, `pass_ad`) VALUES
(1, 'admin', '0911111111', 'admin01');

-- --------------------------------------------------------

--
-- Table structure for table `hairdresser`
--

CREATE TABLE `hairdresser` (
  `id_hai` int(11) NOT NULL,
  `name_hai` varchar(255) NOT NULL,
  `tel_hai` varchar(255) NOT NULL,
  `pass_hai` varchar(255) NOT NULL,
  `status_hai` int(2) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `hairdresser`
--


-- --------------------------------------------------------

--
-- Table structure for table `hairstyle`
--

CREATE TABLE `hairstyle` (
  `id_style` int(11) NOT NULL,
  `name_style` varchar(50) NOT NULL,
  `price_style` varchar(4) NOT NULL,
  `time_ok` varchar(5) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `id_hai` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `hairstyle`
--


-- --------------------------------------------------------

--
-- Table structure for table `reserve`
--

CREATE TABLE `reserve` (
  `id_reserve` int(11) NOT NULL,
  `id_style` int(11) NOT NULL,
  `id_user` int(5) NOT NULL,
  `id_hai` int(5) NOT NULL,
  `dateTime_reserve` datetime NOT NULL,
  `dateTime_reserve_end` datetime NOT NULL,
  `status` int(2) NOT NULL DEFAULT 2 COMMENT '0 = ยกเลิก 1 = ยืนยัน',
  `date_create` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `reserve`
--


-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `name_user` varchar(50) NOT NULL,
  `lastname_user` varchar(50) NOT NULL,
  `tel_user` varchar(10) NOT NULL,
  `pass_user` varchar(25) NOT NULL,
  `e_mail` varchar(50) NOT NULL,
  `status_user` int(2) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_ad`);

--
-- Indexes for table `hairdresser`
--
ALTER TABLE `hairdresser`
  ADD PRIMARY KEY (`id_hai`);

--
-- Indexes for table `hairstyle`
--
ALTER TABLE `hairstyle`
  ADD PRIMARY KEY (`id_style`),
  ADD KEY `asd` (`id_hai`);

--
-- Indexes for table `reserve`
--
ALTER TABLE `reserve`
  ADD PRIMARY KEY (`id_reserve`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `e_room` (`id_hai`),
  ADD KEY `server` (`id_style`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_ad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hairdresser`
--
ALTER TABLE `hairdresser`
  MODIFY `id_hai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `hairstyle`
--
ALTER TABLE `hairstyle`
  MODIFY `id_style` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `reserve`
--
ALTER TABLE `reserve`
  MODIFY `id_reserve` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `hairstyle`
--
ALTER TABLE `hairstyle`
  ADD CONSTRAINT `asd` FOREIGN KEY (`id_hai`) REFERENCES `hairdresser` (`id_hai`);

--
-- Constraints for table `reserve`
--
ALTER TABLE `reserve`
  ADD CONSTRAINT `e_room` FOREIGN KEY (`id_hai`) REFERENCES `hairdresser` (`id_hai`),
  ADD CONSTRAINT `reserve_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `server` FOREIGN KEY (`id_style`) REFERENCES `hairstyle` (`id_style`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
