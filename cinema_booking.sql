-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 24, 2025 at 02:56 PM
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
-- Database: `cinema_booking`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `showtime_id` int(11) DEFAULT NULL,
  `booking_date` datetime DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `payment_status` varchar(20) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `customer_id`, `showtime_id`, `booking_date`, `total_amount`, `payment_status`, `payment_method`) VALUES
(1, 1, 20, '2025-03-23 12:53:37', 225000.00, 'paid', 'credit_card'),
(2, 1, 20, '2025-03-23 12:54:02', 375000.00, 'paid', 'credit_card'),
(3, 2, 20, '2025-03-23 13:23:30', 225000.00, 'pending', 'at_counter');

-- --------------------------------------------------------

--
-- Table structure for table `booking_tickets`
--

CREATE TABLE `booking_tickets` (
  `booking_id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_tickets`
--

INSERT INTO `booking_tickets` (`booking_id`, `ticket_id`) VALUES
(1, 147),
(1, 148),
(1, 149),
(2, 150),
(2, 151),
(2, 152),
(2, 153),
(2, 154),
(3, 177),
(3, 187),
(3, 197);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `role` enum('admin','user') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `first_name`, `last_name`, `email`, `password`, `phone_number`, `address`, `role`) VALUES
(1, 'VŨ ', 'Mạnh', 'manhsd2004@gmail.com', '$2y$10$7xiYmXZ16yMtuWjcQEtHJuyQW8fHqE7.6DzE0qb7kwFsyUUMVX1uG', '00999999a', 'khóm 8', 'user'),
(2, 'VŨ ', 'Hạnh', 'manhsd2002@gmail.com', '$2y$10$zVd81OQKG7GUSsdLfvKZr.DvKKlIzJ/1NqVvWLX43tXwjJrFyJfAa', '009999996566', 'khóm 9', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `movie_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `director` varchar(100) DEFAULT NULL,
  `actors` text DEFAULT NULL,
  `genre` varchar(50) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `release_date` date DEFAULT NULL,
  `description` text DEFAULT NULL,
  `poster_url` varchar(255) DEFAULT NULL,
  `trailer_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`movie_id`, `title`, `director`, `actors`, `genre`, `duration`, `release_date`, `description`, `poster_url`, `trailer_url`) VALUES
(1, 'sdadsad', 'ly hai', 'dddddđ', 'Phiêu lưu', 60, '2025-03-19', 'dâdadadad', 'uploads/posters/67dedd0626943.jpg', NULL),
(2, 'adsssssssss', '', '', 'Nhạc', 6, '2025-03-22', 'adsdaaaaaaaaa', 'uploads/posters/67dee987e0011_download (2).jpg', 'https://www.youtube.com/watch?v=lUdvLnP6KXQ'),
(4, 'ădad', 'Hoài Linh', 'dddddddasdasdasdasdasdasdasdasdasdasdasdasdasdsasdsasdasd', 'Bí ẩn', 60, '2025-03-22', 'Là phim bluray (reencoded), có độ phân giải thấp nhất là Full HD (1080p), trong khi hầu hết các trang phim khác chỉ có tới độ phân giải HD (720p) là cao nhất\r\nChất lượng cao, lượng dữ liệu trên giây (bitrate) gấp từ 5 - 10 lần phim online thông thường - đây là yếu tố quyết định độ nét của phim (thậm chí còn quan trọng hơn độ phân giải)\r\nÂm thanh 5.1 (6 channel) thay vì stereo (2 channel) như các trang phim khác (kể cả Youtube)\r\nPhù hợp để xem trên màn hình TV, máy tính, laptop có độ phân giải cao\r\nNếu không hài lòng với phụ đề có sẵn, bạn có thể tự upload phụ đề của riêng mình để xem online\r\nCó lựa chọn hiện phụ đề song ngữ (tức hiện đồng thời cả tiếng Anh & tiếng Việt), phù hợp với những người muốn học tiếng Anh qua phụ đề phim', 'uploads/posters/67dee9dc7302f_Screenshot 2025-03-15 184224.png', 'https://www.youtube.com/watch?v=5MZmSJtP7fE'),
(5, 'Responsive Login and Registration Form in HTML CSS & Javascript', 'sssssss', 'nd me your github repository link i need to big projects so i cant waste time on authentication pages????', 'Lãng mạn', 60, '2025-03-23', 'Responsive Login and Registration Form in HTML CSS and Javascript, Responsive Login and Signup Form HTML CSS, Responsive Login and Registration Form using HTML CSS and Javascript, Responsive Login and Signup Page in HTML and CSS, Responsive Sign In and Sign Up Form using HTML CSS and Javascript\r\n', 'uploads/posters/67dffa9cce6f3_imager_50_21454_700.jpg', 'https://www.youtube.com/watch?v=Z_AbWH-Vyl8');

-- --------------------------------------------------------

--
-- Table structure for table `screens`
--

CREATE TABLE `screens` (
  `screen_id` int(11) NOT NULL,
  `theater_id` int(11) DEFAULT NULL,
  `screen_number` int(11) NOT NULL,
  `capacity` int(11) NOT NULL,
  `screen_type` varchar(20) DEFAULT NULL,
  `rows` int(11) NOT NULL DEFAULT 8,
  `seats_per_row` int(11) NOT NULL DEFAULT 10
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `screens`
--

INSERT INTO `screens` (`screen_id`, `theater_id`, `screen_number`, `capacity`, `screen_type`, `rows`, `seats_per_row`) VALUES
(1, 1, 1, 80, '3D', 8, 10),
(2, 1, 3, 50, '3D', 5, 10);

-- --------------------------------------------------------

--
-- Table structure for table `showtimes`
--

CREATE TABLE `showtimes` (
  `showtime_id` int(11) NOT NULL,
  `movie_id` int(11) DEFAULT NULL,
  `screen_id` int(11) DEFAULT NULL,
  `show_date` date DEFAULT NULL,
  `show_time` time DEFAULT NULL,
  `ticket_price` decimal(10,2) NOT NULL DEFAULT 50000.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `showtimes`
--

INSERT INTO `showtimes` (`showtime_id`, `movie_id`, `screen_id`, `show_date`, `show_time`, `ticket_price`) VALUES
(20, 2, 1, '2025-03-25', '21:06:00', 75000.00),
(21, 2, 2, '2025-03-29', '22:07:00', 80000.00);

-- --------------------------------------------------------

--
-- Table structure for table `theaters`
--

CREATE TABLE `theaters` (
  `theater_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `zip_code` varchar(20) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `theaters`
--

INSERT INTO `theaters` (`theater_id`, `name`, `address`, `city`, `state`, `zip_code`, `phone_number`) VALUES
(1, 'Smaticon', 'Khóm 5', 'HOCHIMINH', 'Hà lội', '554', '00999999a');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `ticket_id` int(11) NOT NULL,
  `showtime_id` int(11) DEFAULT NULL,
  `seat_number` varchar(10) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `status` enum('booked','available') DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`ticket_id`, `showtime_id`, `seat_number`, `price`, `status`) VALUES
(147, 20, 'A01', 75000.00, 'booked'),
(148, 20, 'A02', 75000.00, 'booked'),
(149, 20, 'A03', 75000.00, 'booked'),
(150, 20, 'A04', 75000.00, 'booked'),
(151, 20, 'A05', 75000.00, 'booked'),
(152, 20, 'A06', 75000.00, 'booked'),
(153, 20, 'A07', 75000.00, 'booked'),
(154, 20, 'A08', 75000.00, 'booked'),
(155, 20, 'A09', 75000.00, 'available'),
(156, 20, 'A10', 75000.00, 'available'),
(157, 20, 'B01', 75000.00, 'available'),
(158, 20, 'B02', 75000.00, 'available'),
(159, 20, 'B03', 75000.00, 'available'),
(160, 20, 'B04', 75000.00, 'available'),
(161, 20, 'B05', 75000.00, 'available'),
(162, 20, 'B06', 75000.00, 'available'),
(163, 20, 'B07', 75000.00, 'available'),
(164, 20, 'B08', 75000.00, 'available'),
(165, 20, 'B09', 75000.00, 'available'),
(166, 20, 'B10', 75000.00, 'available'),
(167, 20, 'C01', 75000.00, 'available'),
(168, 20, 'C02', 75000.00, 'available'),
(169, 20, 'C03', 75000.00, 'available'),
(170, 20, 'C04', 75000.00, 'available'),
(171, 20, 'C05', 75000.00, 'available'),
(172, 20, 'C06', 75000.00, 'available'),
(173, 20, 'C07', 75000.00, 'available'),
(174, 20, 'C08', 75000.00, 'available'),
(175, 20, 'C09', 75000.00, 'available'),
(176, 20, 'C10', 75000.00, 'available'),
(177, 20, 'D01', 75000.00, 'booked'),
(178, 20, 'D02', 75000.00, 'available'),
(179, 20, 'D03', 75000.00, 'available'),
(180, 20, 'D04', 75000.00, 'available'),
(181, 20, 'D05', 75000.00, 'available'),
(182, 20, 'D06', 75000.00, 'available'),
(183, 20, 'D07', 75000.00, 'available'),
(184, 20, 'D08', 75000.00, 'available'),
(185, 20, 'D09', 75000.00, 'available'),
(186, 20, 'D10', 75000.00, 'available'),
(187, 20, 'E01', 75000.00, 'booked'),
(188, 20, 'E02', 75000.00, 'available'),
(189, 20, 'E03', 75000.00, 'available'),
(190, 20, 'E04', 75000.00, 'available'),
(191, 20, 'E05', 75000.00, 'available'),
(192, 20, 'E06', 75000.00, 'available'),
(193, 20, 'E07', 75000.00, 'available'),
(194, 20, 'E08', 75000.00, 'available'),
(195, 20, 'E09', 75000.00, 'available'),
(196, 20, 'E10', 75000.00, 'available'),
(197, 20, 'F01', 75000.00, 'booked'),
(198, 20, 'F02', 75000.00, 'available'),
(199, 20, 'F03', 75000.00, 'available'),
(200, 20, 'F04', 75000.00, 'available'),
(201, 20, 'F05', 75000.00, 'available'),
(202, 20, 'F06', 75000.00, 'available'),
(203, 20, 'F07', 75000.00, 'available'),
(204, 20, 'F08', 75000.00, 'available'),
(205, 20, 'F09', 75000.00, 'available'),
(206, 20, 'F10', 75000.00, 'available'),
(207, 20, 'G01', 75000.00, 'available'),
(208, 20, 'G02', 75000.00, 'available'),
(209, 20, 'G03', 75000.00, 'available'),
(210, 20, 'G04', 75000.00, 'available'),
(211, 20, 'G05', 75000.00, 'available'),
(212, 20, 'G06', 75000.00, 'available'),
(213, 20, 'G07', 75000.00, 'available'),
(214, 20, 'G08', 75000.00, 'available'),
(215, 20, 'G09', 75000.00, 'available'),
(216, 20, 'G10', 75000.00, 'available'),
(217, 20, 'H01', 75000.00, 'available'),
(218, 20, 'H02', 75000.00, 'available'),
(219, 20, 'H03', 75000.00, 'available'),
(220, 20, 'H04', 75000.00, 'available'),
(221, 20, 'H05', 75000.00, 'available'),
(222, 20, 'H06', 75000.00, 'available'),
(223, 20, 'H07', 75000.00, 'available'),
(224, 20, 'H08', 75000.00, 'available'),
(225, 20, 'H09', 75000.00, 'available'),
(226, 20, 'H10', 75000.00, 'available'),
(227, 21, 'A01', 80000.00, 'available'),
(228, 21, 'A02', 80000.00, 'available'),
(229, 21, 'A03', 80000.00, 'available'),
(230, 21, 'A04', 80000.00, 'available'),
(231, 21, 'A05', 80000.00, 'available'),
(232, 21, 'A06', 80000.00, 'available'),
(233, 21, 'A07', 80000.00, 'available'),
(234, 21, 'A08', 80000.00, 'available'),
(235, 21, 'A09', 80000.00, 'available'),
(236, 21, 'A10', 80000.00, 'available'),
(237, 21, 'B01', 80000.00, 'available'),
(238, 21, 'B02', 80000.00, 'available'),
(239, 21, 'B03', 80000.00, 'available'),
(240, 21, 'B04', 80000.00, 'available'),
(241, 21, 'B05', 80000.00, 'available'),
(242, 21, 'B06', 80000.00, 'available'),
(243, 21, 'B07', 80000.00, 'available'),
(244, 21, 'B08', 80000.00, 'available'),
(245, 21, 'B09', 80000.00, 'available'),
(246, 21, 'B10', 80000.00, 'available'),
(247, 21, 'C01', 80000.00, 'available'),
(248, 21, 'C02', 80000.00, 'available'),
(249, 21, 'C03', 80000.00, 'available'),
(250, 21, 'C04', 80000.00, 'available'),
(251, 21, 'C05', 80000.00, 'available'),
(252, 21, 'C06', 80000.00, 'available'),
(253, 21, 'C07', 80000.00, 'available'),
(254, 21, 'C08', 80000.00, 'available'),
(255, 21, 'C09', 80000.00, 'available'),
(256, 21, 'C10', 80000.00, 'available'),
(257, 21, 'D01', 80000.00, 'available'),
(258, 21, 'D02', 80000.00, 'available'),
(259, 21, 'D03', 80000.00, 'available'),
(260, 21, 'D04', 80000.00, 'available'),
(261, 21, 'D05', 80000.00, 'available'),
(262, 21, 'D06', 80000.00, 'available'),
(263, 21, 'D07', 80000.00, 'available'),
(264, 21, 'D08', 80000.00, 'available'),
(265, 21, 'D09', 80000.00, 'available'),
(266, 21, 'D10', 80000.00, 'available'),
(267, 21, 'E01', 80000.00, 'available'),
(268, 21, 'E02', 80000.00, 'available'),
(269, 21, 'E03', 80000.00, 'available'),
(270, 21, 'E04', 80000.00, 'available'),
(271, 21, 'E05', 80000.00, 'available'),
(272, 21, 'E06', 80000.00, 'available'),
(273, 21, 'E07', 80000.00, 'available'),
(274, 21, 'E08', 80000.00, 'available'),
(275, 21, 'E09', 80000.00, 'available'),
(276, 21, 'E10', 80000.00, 'available');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `idx_booking_customer` (`customer_id`),
  ADD KEY `idx_booking_showtime` (`showtime_id`);

--
-- Indexes for table `booking_tickets`
--
ALTER TABLE `booking_tickets`
  ADD PRIMARY KEY (`booking_id`,`ticket_id`),
  ADD KEY `fk_ticket` (`ticket_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`movie_id`);

--
-- Indexes for table `screens`
--
ALTER TABLE `screens`
  ADD PRIMARY KEY (`screen_id`),
  ADD KEY `theater_id` (`theater_id`);

--
-- Indexes for table `showtimes`
--
ALTER TABLE `showtimes`
  ADD PRIMARY KEY (`showtime_id`),
  ADD KEY `idx_showtime_movie` (`movie_id`),
  ADD KEY `idx_showtime_screen` (`screen_id`);

--
-- Indexes for table `theaters`
--
ALTER TABLE `theaters`
  ADD PRIMARY KEY (`theater_id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`ticket_id`),
  ADD KEY `idx_ticket_showtime` (`showtime_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `movie_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `screens`
--
ALTER TABLE `screens`
  MODIFY `screen_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `showtimes`
--
ALTER TABLE `showtimes`
  MODIFY `showtime_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `theaters`
--
ALTER TABLE `theaters`
  MODIFY `theater_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `ticket_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=277;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`showtime_id`) REFERENCES `showtimes` (`showtime_id`);

--
-- Constraints for table `booking_tickets`
--
ALTER TABLE `booking_tickets`
  ADD CONSTRAINT `fk_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`),
  ADD CONSTRAINT `fk_ticket` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`ticket_id`);

--
-- Constraints for table `screens`
--
ALTER TABLE `screens`
  ADD CONSTRAINT `screens_ibfk_1` FOREIGN KEY (`theater_id`) REFERENCES `theaters` (`theater_id`);

--
-- Constraints for table `showtimes`
--
ALTER TABLE `showtimes`
  ADD CONSTRAINT `showtimes_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`),
  ADD CONSTRAINT `showtimes_ibfk_2` FOREIGN KEY (`screen_id`) REFERENCES `screens` (`screen_id`);

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`showtime_id`) REFERENCES `showtimes` (`showtime_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
