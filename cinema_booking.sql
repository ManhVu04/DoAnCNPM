-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 07, 2025 at 04:24 PM
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
(1, 1, 1, '2025-04-06 13:27:26', 300000.00, 'cancelled', 'credit_card'),
(2, 2, 1, '2025-04-06 13:27:59', 200000.00, 'cancelled', 'at_counter'),
(3, 1, 1, '2025-04-06 16:18:22', 300000.00, 'paid', 'credit_card');

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
(1, 1),
(1, 2),
(1, 3),
(2, 11),
(2, 21),
(3, 1),
(3, 2),
(3, 3);

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
(1, 'VŨ ', 'Mạnh', 'manhsd2002@gmail.com', '$2y$10$2XIvGb0Ea9QYALegxHj4ZeoXTaTFiCg4jAFiHlOQvDpCnEUr7quma', '0869727137', '', 'admin'),
(2, 'VŨ ', 'Hạnh', 'manhsd2004@gmail.com', '$2y$10$ZVbw.TsyuDhyJGUiWpGzber6t6G3xGMbPunVNsGQHSWAMKld0Utm6', '00009999', '', 'user'),
(3, 'VŨ ', 'Hạnh', 'manhsd2001@gmail.com', '$2y$10$QUHnZgz4kbnndz6wohDut.cHJNtKdadu8mmMty3lwxuUuhfKn1.sO', '0869727137', '', 'user');

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
(1, 'Mufasa: The Lion King', 'Barry Jenkins', 'Aaron Pierre, Kelvin Harrison Jr., Seth Rogen, Billy Eichner, Tiffany Boone, Donald Glover, Mads Mikkelsen, Thandiwe Newton, Lennie James, Anika Noni Rose, Blue Ivy Carter, Beyoncé Knowles-Carter.', 'Hoạt hình', 117, '2025-04-06', 'Phim kể về cuộc đời Mufasa – người cha đáng kính của Simba. Dù là tiền truyện, Mufasa: The Lion King vẫn lồng ghép nhân vật quen thuộc từ The Lion King.\r\nNhiều năm trước câu chuyện chính, chú sư tử con Mufasa bơ vơ trơ trọi giữa đồng cỏ châu Phi. Dù không mang dòng máu hoàng gia nhưng Mufasa trở thành vua sư tử vĩ đại sau một hành trình đầy hấp dẫn và kịch tính.', 'uploads/posters/67f25c49b21ff_lurEK87kukWNaHd0zYnsi3yzJrs.webp', 'https://youtu.be/obqiE9Rgs-k'),
(3, 'Your Name (Kimi no Na wa)', 'Makoto Shinkai', 'Kamiki Ryunosuke,Kamishiraishi Mone.', 'Hoạt hình', 116, '2025-04-06', 'Bộ phim là câu chuyện hoán đổi cơ thể của 2 cô cậu Mitsuha - nữ sinh trung học sống ở một thị trấn nhỏ của vùng Itomori và Taki – nam sinh tại thủ đô Tokyo đầy sôi động.\r\n\r\nMitsuha luôn chán chường với cuộc sống tẻ nhạt của mình và mơ ước được làm anh chàng đẹp trai sống tại thủ đô. Trong khi đó, Taki hằng đêm lại nhìn thấy mình trong hình hài cô gái vùng miền quê. Ước mơ của cả 2 đã thành sự thật khi sao chổi nghìn năm xuất hiện trên trái đất và rồi cứ cách ngày lại được hoán đổi cơ thể cho nhau.\r\n\r\nDiễn viên', 'uploads/posters/67f25d1317ac0_q719jXXEzOoYaps6babgKnONONX.webp', 'https://youtu.be/xU47nhruN-Q'),
(4, 'John Wick', 'David LeitchChad Stahelski', 'Keanu Reeves, Michael Nyqvist, Alfie Allen, Willem Dafoe, Dean Winters, Adrianne Palicki, Bridget Moynahan, John Leguizamo, Ian McShane, Lance Reddick, Daniel Bernhardt, Omer Barnea, Toby Leonard Moore, David Patrick Kelly.', 'Hành động', 101, '2025-04-06', 'Sau cái chết bất ngờ của người vợ, John Wick (Reeves) nhận được món quà cuối cùng từ cô ấy, một chú chó nhỏ giống beagle tên Daisy, và một lời nhắn \"Xin anh đừng quên cách yêu thương\". Nhưng cuộc sống của John lại bị quấy rối khi chiếc Boss Mustang 1969 lọt vào tầm ngắm của tên mafia Nga Iosef Tarasov (Alfie Allen).\r\n\r\nKhi John từ chối bán chiếc xe, Iosef cùng với tay sai đột nhập vào nhà John và đánh cắp nó, làm anh bất tỉnh và giết chết Daisy. Một cách vô tình, chúng đã đánh thức một trong những sát thủ tàn bạo nhất của thế giới ngầm.', 'uploads/posters/67f25e52cb17c_TxbvYS8wsgYSpYZtQLZXnoVOIQ.webp', 'https://youtu.be/2AUmvWm5ZDQ'),
(5, 'Weathering with You', 'Makoto Shinkai', 'Kotaro Daigo (Morishima Hodaka), Nana Mori (Hina Amano), Shun Oguri (Keisuke Suga), Tsubasa Honda (Natsumi Suga), Sakura Kiryu (Nagisa Amano), Sei Hiraizumi (Yasui), Yûki Kaji (Takai), Chieko Baishô (Fumi Tachibana).', 'Hoạt hình', 112, '2025-04-06', 'Đứa Con Của Thời Tiết xoay quanh hai nhân vật: Hodaka và Hina. Hodaka là cậu thiếu niên sống trên một hòn đảo nhỏ, đã rời khỏi quê hương để đến Tokyo sầm uất. Tại đây, cậu quen biết với Hina - một cô gái kì lạ có năng lực thanh lọc bầu trời mỗi khi \"cầu nguyện\". Cô có khả năng chặn đứng cơn mưa và xua tan mây đen theo ý muốn.', 'uploads/posters/67f25ec090301_qgrk7r1fV4IjuoeiGS5HOhXNdLJ.webp', 'https://youtu.be/Q6iK6DjV_iE'),
(6, 'Suzume no Tojimari', 'Makoto Shinkai', 'Nanoka Hara, Hokuto Matsumura, Eri Fukatsu, Shota Sometani, Sairi Ito, Kotone Hanase, Kana Hanazawa, Matsumoto Hakuō II, Ryūnosuke Kamiki, Ann Yamane, Aimi.', 'Hoạt hình', 121, '2025-04-06', 'Câu chuyện bắt đầu khi Suzume vô tình gặp chàng trai Souta đến thị trấn nơi cô sinh sống với mục đích tìm kiếm \"một cánh cửa\". Để bảo vệ Nhật Bản khỏi thảm họa, những cánh cửa rải rác khắp nơi phải được đóng lại, và bất ngờ thay Suzume cũng có khả năng đóng cửa đặc biệt này. Từ đó cả hai cùng nhau thực hiện sứ mệnh \"khóa chặt cửa\".', 'uploads/posters/67f25f87ac1d0_x5CrIflmv8WZJeLv7V611aRGbPs.webp', 'https://youtu.be/RdYs29wQZq4'),
(7, 'A Silent Voice', 'Naoko Yamada', 'Miyu Irino,Saori Hayami ,Aoi Yûki ,Kenshô Ono ', 'Hoạt hình', 129, '2025-04-06', 'Dáng Hình Thanh Âm xoay quanh cuộc sống học đường của cô bé khiếm thính bẩm sinh Shoko Nishimiya và cậu bé Shoya Ishida, vốn là người bắt nạt Shoko khi cả hai học chung lớp thời tiểu học. Bỗng một ngày, Shoko đột ngột chuyển trường. Cô bỏ lại cậu bé Shoya bị bạn bè xa lánh và chỉ trích, vì giờ đây, Shoya là “kẻ xấu tính chuyên bắt nạt người khác”. Lên trung học, Shoya một lần nữa gặp lại Shoko, cậu quyết định bù đắp lại những tổn thương đã gây ra cho cô bạn thuở xưa. Tuy vậy, liệu rằng mọi chuyện đã quá muộn màng?', 'uploads/posters/67f26146998eb_tuFaWiqX0TXoWu7DGNcmX3UW7sT.webp', 'https://youtu.be/twUSlecQpGQ'),
(8, 'Hitman 2', 'Choi Won-sub', 'Kwon Sang-woo, Jung Joon-ho, Lee Yi-kyung, Hwang Woo-seul-hye, Kim Sung-oh và Lee Ji-won', 'Hành động', 118, '2025-01-22', 'Câu chuyện tiếp nối về cuộc đời làm hoạ sĩ webtoon Jun, người nổi tiếng trong thời gian ngắn với tư cách là tác giả của webtoon Đặc vụ ám sát Jun, nhanh chóng mang danh là \"nhà văn thiếu não\" sau khi Phần 2 bị chỉ trích thảm hại, nhưng mọi thứ thay đổi khi một cuộc tấn công khủng bố ngoài đời thực giống hệt với phần 2 anh vừa xuất bản, khiến Jun bị NIS buộc tội sai là kẻ chủ mưu đằng sau tội ác.\r\nDiễn viên', 'uploads/posters/67f261d9d12fd_oMocciufHx1x1yVfZQFnbCYd8FI.webp', 'https://youtu.be/a3Df7ruzq8Q'),
(9, 'Venom: The Last Dance', 'Kelly Marcel', ' Tom Hardy thủ vai chính Eddie Brock/Venom, Chiwetel Ejiofor, Juno Temple, Rhys Ifans, Stephen Graham, Peggy Lu, Clark Backo, ...', 'Hành động', 108, '2024-10-22', 'Đây là phần phim cuối cùng và hoành tráng nhất về cặp đôi Venom và Eddie Brock (Tom Hardy). Sau khi dịch chuyển từ Vũ trụ Marvel trong ‘Spider-man: No way home’ (2021) trở về thực tại, Eddie Brock giờ đây cùng Venom sẽ phải đối mặt với ác thần Knull hùng mạnh - kẻ tạo ra cả chủng tộc Symbiote và những thế lực đang rình rập khác. Cặp đôi Eddie và Venom sẽ phải đưa ra lựa quyết định khốc liệt để hạ màn kèo cuối này.', 'uploads/posters/67f26272c8d9a_vGXptEdgZIhPg3cGlc7e8sNPC2e.jpg', 'https://youtu.be/sZgZL6Yn2fw');

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
(1, 2, 1, 80, 'Standard', 8, 10);

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
(1, 7, 1, '2025-04-10', '20:20:00', 100000.00);

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
(1, 'Cinestar Quốc Thanh ', '271 Nguyễn Trãi, P. Nguyễn Cư Trinh, Q.1, Tp. Hồ Chí Minh', 'HOCHIMINH', 'HoChiMinh', '', ''),
(2, 'Cinestar Hai Bà Trưng ', '135 Hai Bà Trưng, P. Bến Nghé, Q.1, Tp. Hồ Chí Minh', 'HOCHIMINH', 'HoChiMinh', '', ''),
(3, 'Mega GS Cao Thắng', 'Lầu 6 - 7, 19 Cao Thắng, P.2, Q.3, Tp. Hồ Chí Minh', 'HOCHIMINH', 'HoChiMinh', '', ''),
(4, 'DCINE Bến Thành', 'Số 6, Mạc Đĩnh Chi, Q.1, Tp. Hồ Chí Minh', 'HOCHIMINH', 'HoChiMinh', '', '');

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
(1, 1, 'A01', 100000.00, 'booked'),
(2, 1, 'A02', 100000.00, 'booked'),
(3, 1, 'A03', 100000.00, 'booked'),
(4, 1, 'A04', 100000.00, 'available'),
(5, 1, 'A05', 100000.00, 'available'),
(6, 1, 'A06', 100000.00, 'available'),
(7, 1, 'A07', 100000.00, 'available'),
(8, 1, 'A08', 100000.00, 'available'),
(9, 1, 'A09', 100000.00, 'available'),
(10, 1, 'A10', 100000.00, 'available'),
(11, 1, 'B01', 100000.00, 'available'),
(12, 1, 'B02', 100000.00, 'available'),
(13, 1, 'B03', 100000.00, 'available'),
(14, 1, 'B04', 100000.00, 'available'),
(15, 1, 'B05', 100000.00, 'available'),
(16, 1, 'B06', 100000.00, 'available'),
(17, 1, 'B07', 100000.00, 'available'),
(18, 1, 'B08', 100000.00, 'available'),
(19, 1, 'B09', 100000.00, 'available'),
(20, 1, 'B10', 100000.00, 'available'),
(21, 1, 'C01', 100000.00, 'available'),
(22, 1, 'C02', 100000.00, 'available'),
(23, 1, 'C03', 100000.00, 'available'),
(24, 1, 'C04', 100000.00, 'available'),
(25, 1, 'C05', 100000.00, 'available'),
(26, 1, 'C06', 100000.00, 'available'),
(27, 1, 'C07', 100000.00, 'available'),
(28, 1, 'C08', 100000.00, 'available'),
(29, 1, 'C09', 100000.00, 'available'),
(30, 1, 'C10', 100000.00, 'available'),
(31, 1, 'D01', 100000.00, 'available'),
(32, 1, 'D02', 100000.00, 'available'),
(33, 1, 'D03', 100000.00, 'available'),
(34, 1, 'D04', 100000.00, 'available'),
(35, 1, 'D05', 100000.00, 'available'),
(36, 1, 'D06', 100000.00, 'available'),
(37, 1, 'D07', 100000.00, 'available'),
(38, 1, 'D08', 100000.00, 'available'),
(39, 1, 'D09', 100000.00, 'available'),
(40, 1, 'D10', 100000.00, 'available'),
(41, 1, 'E01', 100000.00, 'available'),
(42, 1, 'E02', 100000.00, 'available'),
(43, 1, 'E03', 100000.00, 'available'),
(44, 1, 'E04', 100000.00, 'available'),
(45, 1, 'E05', 100000.00, 'available'),
(46, 1, 'E06', 100000.00, 'available'),
(47, 1, 'E07', 100000.00, 'available'),
(48, 1, 'E08', 100000.00, 'available'),
(49, 1, 'E09', 100000.00, 'available'),
(50, 1, 'E10', 100000.00, 'available'),
(51, 1, 'F01', 100000.00, 'available'),
(52, 1, 'F02', 100000.00, 'available'),
(53, 1, 'F03', 100000.00, 'available'),
(54, 1, 'F04', 100000.00, 'available'),
(55, 1, 'F05', 100000.00, 'available'),
(56, 1, 'F06', 100000.00, 'available'),
(57, 1, 'F07', 100000.00, 'available'),
(58, 1, 'F08', 100000.00, 'available'),
(59, 1, 'F09', 100000.00, 'available'),
(60, 1, 'F10', 100000.00, 'available'),
(61, 1, 'G01', 100000.00, 'available'),
(62, 1, 'G02', 100000.00, 'available'),
(63, 1, 'G03', 100000.00, 'available'),
(64, 1, 'G04', 100000.00, 'available'),
(65, 1, 'G05', 100000.00, 'available'),
(66, 1, 'G06', 100000.00, 'available'),
(67, 1, 'G07', 100000.00, 'available'),
(68, 1, 'G08', 100000.00, 'available'),
(69, 1, 'G09', 100000.00, 'available'),
(70, 1, 'G10', 100000.00, 'available'),
(71, 1, 'H01', 100000.00, 'available'),
(72, 1, 'H02', 100000.00, 'available'),
(73, 1, 'H03', 100000.00, 'available'),
(74, 1, 'H04', 100000.00, 'available'),
(75, 1, 'H05', 100000.00, 'available'),
(76, 1, 'H06', 100000.00, 'available'),
(77, 1, 'H07', 100000.00, 'available'),
(78, 1, 'H08', 100000.00, 'available'),
(79, 1, 'H09', 100000.00, 'available'),
(80, 1, 'H10', 100000.00, 'available');

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
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `movie_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `screens`
--
ALTER TABLE `screens`
  MODIFY `screen_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `showtimes`
--
ALTER TABLE `showtimes`
  MODIFY `showtime_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `theaters`
--
ALTER TABLE `theaters`
  MODIFY `theater_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `ticket_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

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
