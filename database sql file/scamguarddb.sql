-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 23, 2024 at 10:27 AM
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
-- Database: `scamguarddb`
--

-- --------------------------------------------------------

--
-- Table structure for table `educational_content`
--

CREATE TABLE `educational_content` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `video_url` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `educational_content`
--

INSERT INTO `educational_content` (`id`, `title`, `content`, `type`, `video_url`) VALUES
(16, 'Every SCAM Explained in 15 Minutes', 'Phishing Scams - Lottery and Sweepstakes Scams - Tech Support Scams - Identity Theft - Investment Scams -Employment Scams - Fake Charity Scams - Credit Card Skimming\r\n', 'informative', 'https://www.youtube.com/watch?v=gIOz1dZGllg&amp;ab_channel=TheEvaluator'),
(17, 'Vishing Fraud Awareness-Digital Payment Abhiyan- English', 'how fraudsters call on the pretext of bank representatives and trick you into getting your bank &amp; card details.', 'Digital Payment', 'https://www.youtube.com/watch?v=xe48f2dGzmQ&amp;ab_channel=DSCIIN'),
(18, 'Real-time fraud prevention in a real-time world', 'The nature of payments fraud requires real-time solutions designed to detect and prevent fraud before it happens. Learn what is required to thwart fraud and how UP Payments Risk Management solutions.', 'Real-time fraud prevention', 'https://www.youtube.com/watch?v=sMDg7ld1tZU&amp;ab_channel=ACIWorldwide'),
(19, 'Fighting Financial Crime with Machine Learning', 'Everyone is exposed to financial fraud. If you’re selling or buying something online, providing financial services, or simply processing tons of payments, you face fraud risks every day. ', 'Financial Crime', 'https://www.youtube.com/watch?v=QFyM3w95fXI&amp;ab_channel=AltexSoft');

-- --------------------------------------------------------

--
-- Table structure for table `scam_reports`
--

CREATE TABLE `scam_reports` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `scam_title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `evidence` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `scam_reports`
--

INSERT INTO `scam_reports` (`id`, `user_id`, `scam_title`, `description`, `evidence`, `status`) VALUES
(17, 14, 'Fake Investment Platform Scam', 'A fraudulent investment site promises high returns with no risk. Users deposit money, which then becomes inaccessible.', '../../uploads/Logbook 6.docx', 'Approved'),
(18, 14, 'Phishing Email Scam', 'Emails offering deals or prizes are used to steal personal information through fake websites.', '../../uploads/Logbook 4.docx', 'Approved'),
(19, 14, 'Cryptocurrency Investment Fraud', 'cammers lure users with fake trading algorithms, leading to stolen funds once money is deposited.', '../../uploads/Logbook 2.docx', 'Rejected'),
(20, 15, 'Online Auction Fraud', 'Scammers create fake online auctions with enticing items. Bidders pay for items that never exist, and the scammers disappear with the money.', '../../uploads/ProjectScript.docx', 'Rejected'),
(21, 15, 'Tech Support Scam', 'Fraudsters impersonate tech support representatives, claiming to fix non-existent issues. They gain remote access to devices and steal personal information or install malware.', '../../uploads/Project Reflection 1.docx', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `type` varchar(10) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `mobile`, `address`, `country`, `dob`, `gender`, `type`) VALUES
(8, 'Admin', 'admin@test.com', '$2y$10$TeYjdMGV26YhMAm0TQM.RerW0K6WiXv4gbiD.hoEKiiSpWns131Om', '72649821752', 'flat # 2525, covet apartments, usa.', 'United States', '1993-06-20', 'male', 'admin'),
(14, 'Sikander', 'sikandermushtaq346@gmail.com', '$2y$10$Q8p7VluqYZpi4oaSl6dH0.GK8gG/TgxJub3IeGaXoASHfPcWDx1yW', '923021142646', '212 block-k, evergreen apartments', 'pakistan', '2000-08-22', 'male', 'user'),
(15, 'Saim Rap', 'saimrap7@test.com', '$2y$10$U1ETPaM6lDUjapbX7xNWqe2XhkU00QbEY7FbbGiaqNpRHzn7xPjSi', '17256481235326', '3143 block x , galaxy apartments', 'pakistan', '2001-08-15', 'male', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `educational_content`
--
ALTER TABLE `educational_content`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scam_reports`
--
ALTER TABLE `scam_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `educational_content`
--
ALTER TABLE `educational_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `scam_reports`
--
ALTER TABLE `scam_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `scam_reports`
--
ALTER TABLE `scam_reports`
  ADD CONSTRAINT `scam_reports_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
