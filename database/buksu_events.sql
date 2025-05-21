-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 21, 2025 at 05:26 PM
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
-- Database: `buksu_events`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `role` varchar(255) DEFAULT 'Admin',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `email`, `password`, `name`, `role`, `created_at`) VALUES
(4, 'admin@buksu.edu.ph', '$2y$10$HAZ4Nh8xWTu.0X4XpkHMj.71tnVXTzVtugLl9/AYPftQ4n9IM2igy', 'BukSU Events Admin', 'admin', '2025-05-21 10:27:00');

-- --------------------------------------------------------

--
-- Table structure for table `attendees`
--

CREATE TABLE `attendees` (
  `attendee_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `roles` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attendance_date` datetime DEFAULT current_timestamp(),
  `attendance_status` enum('present','absent') DEFAULT 'absent'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendees`
--

INSERT INTO `attendees` (`attendee_id`, `event_id`, `user_id`, `roles`, `attendance_date`, `attendance_status`) VALUES
(9, 53, 11, 'student', '2025-05-18 07:54:22', 'present'),
(10, 52, 11, 'student', '2025-05-18 12:53:25', 'present'),
(11, 53, 18, 'student', '2025-05-19 10:04:39', 'present'),
(36, 54, 19, 'student', '2025-05-21 11:33:06', 'present'),
(38, 52, 18, 'student', '2025-05-21 17:18:29', 'present'),
(39, 57, 19, 'student', '2025-05-21 18:09:23', 'absent'),
(43, 90, 9, 'faculty', '2025-05-21 22:25:47', 'absent'),
(44, 90, 10, 'faculty', '2025-05-21 22:33:32', 'absent'),
(45, 52, 10, 'faculty', '2025-05-21 22:33:35', 'absent'),
(46, 58, 11, 'student', '2025-05-21 22:34:02', 'absent'),
(47, 57, 11, 'student', '2025-05-21 22:34:05', 'absent'),
(48, 56, 11, 'student', '2025-05-21 22:34:07', 'absent'),
(49, 90, 11, 'student', '2025-05-21 22:34:10', 'present'),
(50, 54, 11, 'student', '2025-05-21 22:34:13', 'absent'),
(51, 57, 18, 'student', '2025-05-21 22:34:58', 'present'),
(52, 58, 18, 'student', '2025-05-21 22:35:00', 'present'),
(53, 56, 18, 'student', '2025-05-21 22:35:02', 'present'),
(54, 54, 18, 'student', '2025-05-21 22:35:05', 'present'),
(55, 90, 18, 'student', '2025-05-21 22:35:08', 'absent'),
(56, 90, 20, 'student', '2025-05-21 22:36:07', 'absent'),
(57, 52, 20, 'student', '2025-05-21 22:36:10', 'absent'),
(58, 53, 20, 'student', '2025-05-21 22:36:12', 'absent'),
(59, 54, 20, 'student', '2025-05-21 22:36:14', 'absent'),
(60, 56, 20, 'student', '2025-05-21 22:36:17', 'absent'),
(61, 57, 20, 'student', '2025-05-21 22:36:19', 'absent'),
(62, 58, 20, 'student', '2025-05-21 22:36:21', 'absent'),
(63, 56, 21, 'student', '2025-05-21 22:36:47', 'present'),
(64, 57, 21, 'student', '2025-05-21 22:36:49', 'present'),
(65, 58, 21, 'student', '2025-05-21 22:36:51', 'present'),
(66, 90, 21, 'student', '2025-05-21 22:36:54', 'present'),
(67, 52, 21, 'student', '2025-05-21 22:36:57', 'absent'),
(68, 53, 21, 'student', '2025-05-21 22:36:59', 'absent'),
(69, 54, 21, 'student', '2025-05-21 22:37:01', 'absent');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_name` varchar(255) DEFAULT NULL,
  `event_type` varchar(255) NOT NULL,
  `target_audience` varchar(255) NOT NULL,
  `venue` varchar(255) NOT NULL,
  `capacity` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `event_date_time` datetime DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `image_path` varchar(255) DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `user_id`, `event_name`, `event_type`, `target_audience`, `venue`, `capacity`, `description`, `event_date_time`, `status`, `image_path`, `rejection_reason`) VALUES
(52, 9, 'BukSU Intramurals Opening Program & Parade', 'Sports', 'All Faculty and Students', 'Main Campus', 5000, 'Speed. Strength, Spirit.  Who will rise as this year\'s champion? Witness electrifying battles, awe-inspiring performances, and relentless action as Bukidnon State University sets the stage for its most intense competition yet! 🚀\r\n\r\n', '2025-05-27 07:30:00', 'approved', 'uploads/event_68282865d69391.35429776.jpg', NULL),
(53, 9, 'The Stage is Set: BukSUONE is Here!', 'Research', 'Students Only', 'Auditorium', 400, 'Prepare to connect, inspire, and lead as ONE Smart BukSU. Let’s continue to make history together!', '2025-05-28 13:30:00', 'approved', 'uploads/6827342e62f98_buksuone.jpg', NULL),
(54, 9, 'Level Up: BukSU Unite 2nd General Assembly', 'Assembly', 'Students Only', 'Gymnasium', 2500, 'An event that promises to inspire, challenge, and unite us as we embark on a journey toward achievements.', '2025-05-29 08:00:00', 'approved', 'uploads/682736cbaa90c_2nd.jpg', NULL),
(56, 10, 'DevThon for Aspiring Designers and Storytellers!', 'Competition', 'Students Only', 'Comlab 7', 30, 'Unleash your creativity at DevThon Animate, the ultimate animation design competition that challenges students to bring ideas to life through motion and storytelling! Whether you’re passionate about digital art, motion graphics, or animation, this event is your chance to showcase your talent, gain industry insights, and compete with the best. Join us in shaping the future of animated design—step up, create, and let your vision move the world! 🚀\r\n', '2025-05-30 09:00:00', 'approved', 'uploads/68282e386c689_Animationcompetition.jpg', NULL),
(57, 10, 'SLIOT: Sparking Innovation in Electronics and IoT', 'Competition', 'Students Only', '4th Floor COT Building', 50, 'Step into the future with SLIOT, the ultimate innovation competition designed for electronics and IoT enthusiasts! This event challenges students to develop groundbreaking solutions that integrate smart technology, connectivity, and creative engineering. Whether you\'re designing cutting-edge circuits, developing IoT applications, or pushing the limits of innovation, SLIOT is your chance to showcase your skills, collaborate with like-minded innovators, and make an impact in the tech world. 🚀🔬\n\n', '2025-05-31 15:30:00', 'approved', 'uploads/682830539be4f_InnovationCompetition.jpg', NULL),
(58, 10, 'CSE 40: The Ultimate Coding Showdown!', 'Competition', 'Students Only', 'COMLAB 1-3', 100, 'Gear up for CSE 40, the premier coding competition where IT students put their problem-solving skills to the test! Whether you\'re a seasoned programmer or just starting your journey, this challenge pushes you to innovate, optimize, and code your way to victory. Compete against fellow students, sharpen your technical expertise, and prove you have what it takes to rise to the top in the world of programming! 💻🚀\r\n\r\n', '2025-06-02 14:00:00', 'approved', 'uploads/682830fb1a8b3_CSEcompetition.jpg', NULL),
(84, 9, 'Test event 1', 'Test Type', 'Faculty Only', 'Test Venue', 100, 'Test event 11111111111', '2025-05-19 01:39:00', 'pending', 'uploads/682a1baf16f05_IMG1184.JPG', NULL),
(85, 9, 'Test event 2', 'Test event 2', 'All Faculty and Students', 'Test event 2', 200, 'Test event 2222222', '2025-05-19 01:41:00', 'rejected', 'uploads/682a1c0fc0432_43220712511452222798422034865119171169543401n.jpg', 'Test Rejection 123'),
(88, 9, 'InnovateU: The University Tech & Design Expo', 'Workshop', 'All Faculty and Students', 'Mini Theatre', 150, 'Join us for InnovateU, the premier university expo celebrating creativity, technology, and design! This event brings together students, faculty, and industry professionals to showcase cutting-edge projects, engage in hands-on workshops, and compete in exciting innovation challenges. Whether you\'re a tech enthusiast, a budding designer, or someone looking for inspiration, this is your chance to network, learn, and push the boundaries of creativity.\n', '2025-05-19 09:11:00', 'approved', 'uploads/682a859605409_images.jpeg', NULL),
(90, 9, 'Campus Connect: Uniting Minds, Celebrating Diversi', 'Festival', 'All Faculty and Students', 'Oval & Auditorium', 2000, 'Experience the power of connection at Campus Connect, the annual university-wide festival that brings together faculty, students, and staff for a day of celebration, collaboration, and inclusivity! Enjoy cultural performances, interactive exhibits, academic showcases, and team-building activities that strengthen the spirit of unity. Campus Connect is your gateway to forging new friendships, honoring achievements, and embracing the diversity that makes our university extraordinary.\r\n', '2025-05-22 10:00:00', 'approved', 'uploads/682dd3e5531a9_Screenshot20250521212247.png', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `contact_no` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `reset_code` int(11) DEFAULT NULL,
  `roles` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `firstname`, `lastname`, `contact_no`, `email`, `password`, `reset_code`, `roles`) VALUES
(9, 'Faculty', 'User', '09913715938', 'faculty@buksu.edu.ph', '$2y$10$pvNtXsl1sp4w7VqA/dZc0OXS.QufPe0UiEn5EKcMDwcvmVV4gLApS', 183228, 'faculty'),
(10, 'Faculty', 'User 2', '09368611525', 'faculty2@buksu.edu.ph', '$2y$10$KJBV0dNS4772UvJfVpv81.vMK9aCz0xS1ugPKu86C5n4zMepeDVTO', 599911, 'faculty'),
(11, 'Kent Ian', 'Seridon', '09790462673', 'kent@student.buksu.edu.ph', '$2y$10$prdGWrc5gzms/eKauGQow.mM8uoxZctZO/hI4XBUHREaLyWaOpqOm', NULL, 'student'),
(18, 'Steven', 'Bernabe', '09123456789', 'steven@student.buksu.edu.ph', '$2y$10$KLArWDyJXHhHytn1xt5LNeoG6Z60sujFBiRf5cP6vcSErHWeGlrb2', NULL, 'student'),
(19, 'Student', 'User', '09913715938', 'student@student.buksu.edu.ph', '$2y$10$BDLfkS5ngv7tVQj/OAv2ne5uLKVK6itA0QrhOnu8pwNCSIEfwOCiC', 199715, 'student'),
(20, 'Philip Lee', 'Artianza', '099137159381', '2301106197@student.buksu.edu.ph', '$2y$10$so7EnhATuvPL9cMi9benhO0KqaVcLUs7IHDZw7AcB0yIMp.kZyLYK', 280045, 'student'),
(21, 'Hani', 'Pham', '123456111111', 'hani@student.buksu.edu.ph', '$2y$10$HxIfQrrxapiE7NH/5pGS9utSeLnjGp0Gs6VmqJwhkGxKXvW4/VcTm', NULL, 'student');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `attendees`
--
ALTER TABLE `attendees`
  ADD PRIMARY KEY (`attendee_id`),
  ADD UNIQUE KEY `unique_user_role_per_event` (`event_id`,`user_id`,`roles`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `attendees`
--
ALTER TABLE `attendees`
  MODIFY `attendee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendees`
--
ALTER TABLE `attendees`
  ADD CONSTRAINT `attendees_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`),
  ADD CONSTRAINT `attendees_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
