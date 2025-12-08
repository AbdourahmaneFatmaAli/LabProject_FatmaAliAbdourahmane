-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : dim. 07 déc. 2025 à 16:44
-- Version du serveur : 8.0.44-0ubuntu0.24.04.1
-- Version de PHP : 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `webtech_2025A_fatma_abdourahmane`
--

-- --------------------------------------------------------

--
-- Structure de la table `attendance`
--

CREATE TABLE `attendance` (
  `attendance_id` int NOT NULL,
  `session_id` int NOT NULL,
  `student_id` int NOT NULL,
  `status` enum('present','absent','late') NOT NULL,
  `check_in_time` time DEFAULT NULL,
  `remarks` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `experience_id` int DEFAULT NULL,
  `content` text COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `destination` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `author` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `comments`
--

INSERT INTO `comments` (`comment_id`, `user_id`, `experience_id`, `content`, `created_at`, `destination`, `author`, `email`) VALUES
(1, 5, NULL, 'Nice place', '2025-12-06 11:15:19', 'morondava', 'fatma', 'fatmaaliabdourahmane@gmail.com'),
(2, 5, NULL, 'This is actually my country :)', '2025-12-06 11:16:22', 'Agadez', 'fatma', 'fatmaaliabdourahmane@gmail.com'),
(4, 5, NULL, 'This needs to be on my list', '2025-12-06 11:21:42', 'Algiers', 'fatma', 'fatmaaliabdourahmane@gmail.com'),
(5, 12, NULL, 'I am excited to visit Morondava', '2025-12-06 18:31:07', 'morondava', 'Nana', 'nana@gmail.com'),
(6, 12, NULL, 'it is a nice place, i visited this mosque 3 years ago.', '2025-12-06 18:34:39', 'Agadez', 'Sani', 'sani@gmail.con');

-- --------------------------------------------------------

--
-- Structure de la table `courses`
--

CREATE TABLE `courses` (
  `course_id` int NOT NULL,
  `course_code` varchar(20) DEFAULT NULL,
  `course_name` varchar(150) NOT NULL,
  `description` text,
  `credit_hours` int DEFAULT NULL,
  `faculty_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `course_student_list`
--

CREATE TABLE `course_student_list` (
  `course_id` int NOT NULL,
  `student_id` int NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `requested_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `destinations`
--

CREATE TABLE `destinations` (
  `destination_id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `main_image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `experiences`
--

CREATE TABLE `experiences` (
  `experience_id` int NOT NULL,
  `first_name` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `destination` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `experiences`
--

INSERT INTO `experiences` (`experience_id`, `first_name`, `email`, `destination`, `description`, `created_at`, `user_id`) VALUES
(5, 'Fourera', 'Fourera@gmail.com', 'Kenya', 'Last summer, I went to Kenya. I visited the Maasai Mara and saw lions, giraffes, and elephants. I went to Lake Naivasha and took a boat ride to see hippos. I ate local food called nyama choma, and it was very tasty. Kenya was beautiful, and I hope to go there again.', '2025-12-06 15:09:28', 11),
(7, 'Rafiatou', 'Rafiatoumalam@gmail.com', 'Malanville', 'I want to Malanville this year and i explore the different traditions and culture it was so exiting.', '2025-12-06 18:46:43', 12);

-- --------------------------------------------------------

--
-- Structure de la table `experience_images`
--

CREATE TABLE `experience_images` (
  `image_id` int NOT NULL,
  `experience_id` int DEFAULT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `experience_images`
--

INSERT INTO `experience_images` (`image_id`, `experience_id`, `image_url`) VALUES
(4, 5, 'upload/1765033768_GettyImages-1048375948-1a6601078303494193d61913f16c8c11.jpg'),
(6, 7, 'upload/1765046803_ethiopia.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `faculty`
--

CREATE TABLE `faculty` (
  `faculty_id` int NOT NULL,
  `is_intern` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `members`
--

CREATE TABLE `members` (
  `member_id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone_num` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bio` text COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

CREATE TABLE `sessions` (
  `session_id` int NOT NULL,
  `course_id` int NOT NULL,
  `session_code` varchar(10) DEFAULT NULL,
  `topic` varchar(150) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `students`
--

CREATE TABLE `students` (
  `student_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `first_name` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `last_name` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `role` enum('visitor','admin','contributor') COLLATE utf8mb4_general_ci DEFAULT 'visitor',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `dob` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `first_name`, `last_name`, `role`, `created_at`, `dob`) VALUES
(5, 'fatma', 'fatmaaliabdourahmane@gmail.com', '$2y$10$KTjZk2t7zSMDDrDPZvnLlOiYQ12FzjMD2q.AzZc/IP47OaDCTckC2', 'Fatma', 'Ali', 'visitor', '2025-12-06 10:28:43', NULL),
(6, 'fatou.abdourahmane', 'fatma.abdourahmane@ashesi.edu.gh', '$2y$10$LLreUx/T8aXBZz9Q3WTLEOujPG4RR45lgwzc6ShReFNTT8grpMG.y', 'Fatma', 'Ali', 'visitor', '2025-12-06 11:28:19', NULL),
(7, 'rahiatou', 'rahia@gmail.com', '$2y$10$Ey.uncjVvheN4gPOn2UVjOPLOZgIfcdt.yPlpbcQfj7.ldyZAATke', 'Rahia', 'Malam', 'visitor', '2025-12-06 13:56:02', NULL),
(8, 'Mari', 'mari@gmail.com', '$2y$10$gkGiyAsCp2KKB7UPDPCZnev2fOC./rfk5ujz0ZBemy0X4BYPKHC1e', 'Mari', 'Kessa', 'visitor', '2025-12-06 14:08:30', NULL),
(9, 'Na', 'na@gmail.com', '$2y$10$Vh1T//3iGj3PrOEHsCyc5eI.rIZWLsJKwVxyVxqeuNC3zuk6EYTca', 'Na', 'Ni', 'visitor', '2025-12-06 14:14:04', NULL),
(10, 'Aisha', 'Asha@gmail.com', '$2y$10$vrQDA39FTT3aqR6QFfE4w.hWAscRQWaVhh7NgPg5s2q4fnsNvVUj.', NULL, NULL, 'visitor', '2025-12-06 14:27:24', NULL),
(11, 'Fourera', 'Fourera@gmail.com', '$2y$10$rH5LEiqCu97yghL/ANXRT.eh18OvfPgiN8aYXIwuEprgCjhHHNVVi', 'Fourera', 'Idi', 'visitor', '2025-12-06 14:29:10', NULL),
(12, 'rafifi', 'Rafiatoumalam@gmail.com', '$2y$10$SKvMNP8R5pH.Y.QSwBLqoOOIzSX18L3u8.X5T10IOWGpkNRLRXvgm', 'Rafiatou', 'Malam Ali', 'visitor', '2025-12-06 15:22:15', NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendance_id`),
  ADD KEY `session_id` (`session_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Index pour la table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `experience_id` (`experience_id`);

--
-- Index pour la table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_id`),
  ADD UNIQUE KEY `course_code` (`course_code`),
  ADD KEY `faculty_id` (`faculty_id`);

--
-- Index pour la table `course_student_list`
--
ALTER TABLE `course_student_list`
  ADD PRIMARY KEY (`course_id`,`student_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Index pour la table `destinations`
--
ALTER TABLE `destinations`
  ADD PRIMARY KEY (`destination_id`);

--
-- Index pour la table `experiences`
--
ALTER TABLE `experiences`
  ADD PRIMARY KEY (`experience_id`);

--
-- Index pour la table `experience_images`
--
ALTER TABLE `experience_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `experience_id` (`experience_id`);

--
-- Index pour la table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`faculty_id`);

--
-- Index pour la table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`member_id`);

--
-- Index pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Index pour la table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `courses`
--
ALTER TABLE `courses`
  MODIFY `course_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `destinations`
--
ALTER TABLE `destinations`
  MODIFY `destination_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `experiences`
--
ALTER TABLE `experiences`
  MODIFY `experience_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `experience_images`
--
ALTER TABLE `experience_images`
  MODIFY `image_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `members`
--
ALTER TABLE `members`
  MODIFY `member_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `session_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`session_id`),
  ADD CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`);

--
-- Contraintes pour la table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`experience_id`) REFERENCES `experiences` (`experience_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `course_student_list`
--
ALTER TABLE `course_student_list`
  ADD CONSTRAINT `course_student_list_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`),
  ADD CONSTRAINT `course_student_list_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`);

--
-- Contraintes pour la table `experience_images`
--
ALTER TABLE `experience_images`
  ADD CONSTRAINT `experience_images_ibfk_1` FOREIGN KEY (`experience_id`) REFERENCES `experiences` (`experience_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `faculty`
--
ALTER TABLE `faculty`
  ADD CONSTRAINT `faculty_ibfk_1` FOREIGN KEY (`faculty_id`) REFERENCES `users` (`user_id`);

--
-- Contraintes pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
