-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 02, 2025 at 12:22 PM
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
-- Database: `waktuctfdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `ctf_events`
--

CREATE TABLE `ctf_events` (
  `ctf_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `format` varchar(50) NOT NULL,
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ctf_events`
--

INSERT INTO `ctf_events` (`ctf_id`, `title`, `date`, `format`) VALUES
(1, 'HackThePlanet', '2025-12-01', 'Jeopardy'),
(2, 'WebWarriors', '2025-12-10', 'Attack/Defense');

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `team_id` int(11) UNSIGNED NOT NULL,
  `team_name` varchar(100) NOT NULL,
  `created_by` int(11) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`team_id`, `team_name`, `created_by`, `created_at`) VALUES
(1, 'pisang', 7, '2025-11-02 18:46:48');

-- --------------------------------------------------------

--
-- Table structure for table `team_members`
--

CREATE TABLE `team_members` (
  `id` int(11) UNSIGNED NOT NULL,
  `team_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `joined_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `team_members`
--

INSERT INTO `team_members` (`id`, `team_id`, `user_id`, `joined_at`) VALUES
(1, 1, 7, '2025-11-02 18:46:48');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `ctf_joined` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `created_at`, `ctf_joined`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$m9mabPuvmf.1Wrrw0Vp0ue1UZt8HG9OEHVcJfzvhzwW03MpWckJ3y', '2025-10-21 15:49:22', 0),
(3, 'test1', 'test1@gmail.com', '$2y$10$t2Kc3RKFdubhuF20td3.puXhj3SsxRISYML2SA542He9vi5CxF2Ee', '2025-10-27 13:03:09', 0),
(4, 'test2', 'test2@gmail.com', '$2y$10$Y5sf/bS2vVeS/UC82vZr1ugjSWaQWf8Tmjl0dmNI/C9nsEOFIuMEu', '2025-10-27 13:22:23', 0),
(5, 'test3', 'test3@gmail.com', '$2y$10$L9DMPZFtHE/tJ53U4B9dAemnszmpZwgtGFdQDxhuVHsZL9XDaFI/.', '2025-10-27 13:22:46', 0),
(6, 'test4', 'test4@gmail.com', '$2y$10$wVhL1xFGLVdKZHAMjjfBMed57YouLknSda3/nzKvBQpLIvgcw3Blm', '2025-10-27 13:32:38', 0),
(7, 'test5', 'test5@gmail.com', '$2y$10$ceZLy9Tv3chEYpZChiqm5uCXFny8TcEe1TDvWI16GIg6lv4gkN.rC', '2025-11-02 10:08:36', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_ctf`
--

CREATE TABLE `user_ctf` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `ctf_id` int(11) NOT NULL,
  `team_name` varchar(100) DEFAULT NULL,
  `experience` enum('beginner','intermediate','advanced') DEFAULT 'beginner',
  `notify` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `joined_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_ctf`
--

INSERT INTO `user_ctf` (`id`, `user_id`, `ctf_id`, `team_name`, `experience`, `notify`, `notes`, `joined_at`) VALUES
(1, 7, 1, 'pisang', 'beginner', 'Email', 'aa', '2025-11-02 19:19:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ctf_events`
--
ALTER TABLE `ctf_events`
  ADD PRIMARY KEY (`ctf_id`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`team_id`),
  ADD UNIQUE KEY `team_name` (`team_name`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `team_members`
--
ALTER TABLE `team_members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_team_user` (`team_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_ctf`
--
ALTER TABLE `user_ctf`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_ctf` (`user_id`,`ctf_id`),
  ADD KEY `ctf_id` (`ctf_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ctf_events`
--
ALTER TABLE `ctf_events`
  MODIFY `ctf_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `team_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `team_members`
--
ALTER TABLE `team_members`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_ctf`
--
ALTER TABLE `user_ctf`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `teams`
--
ALTER TABLE `teams`
  ADD CONSTRAINT `teams_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `team_members`
--
ALTER TABLE `team_members`
  ADD CONSTRAINT `team_members_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `teams` (`team_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `team_members_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_ctf`
--
ALTER TABLE `user_ctf`
  ADD CONSTRAINT `user_ctf_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_ctf_ibfk_2` FOREIGN KEY (`ctf_id`) REFERENCES `ctf_events` (`ctf_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
