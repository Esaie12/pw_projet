-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : db
-- Généré le : ven. 10 jan. 2025 à 15:26
-- Version du serveur : 9.1.0
-- Version de PHP : 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `adopte_dev`
--

-- --------------------------------------------------------

--
-- Structure de la table `developer`
--

CREATE TABLE `developer` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `about_me` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `localisation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `salary` int DEFAULT NULL,
  `niveau_experience` int DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profession` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `developer`
--

INSERT INTO `developer` (`id`, `user_id`, `firstname`, `lastname`, `about_me`, `localisation`, `salary`, `niveau_experience`, `avatar`, `profession`) VALUES
(3, 4, 'SAVI', 'Oumar', 'Je suis un dev fullstack', 'Rennes', 1500, 3, '67775b26a6696.png', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `job_posting`
--

CREATE TABLE `job_posting` (
  `id` int NOT NULL,
  `job_type_id` int NOT NULL,
  `society_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `experience_level` varchar(3000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `salary` int NOT NULL,
  `description` varchar(3000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `published_at` date DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pdf_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `end_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `job_posting`
--

INSERT INTO `job_posting` (`id`, `job_type_id`, `society_id`, `title`, `location`, `experience_level`, `salary`, `description`, `published_at`, `image`, `pdf_file`, `end_at`) VALUES
(3, 1, 2, 'Dev Laravel', 'Rennes', 'Débutant', 1800, 'Description Description Description Description', '2025-01-03', '67775b9512c51.png', '67775b9526917.pdf', NULL),
(4, 2, 2, 'Dev Wordpress', 'Saint Malo', 'Débutant', 500, 'Description Description  Description Description Description Description', '2025-01-05', '677acebba8c11.png', '677acebbe1de0.pdf', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `job_type`
--

CREATE TABLE `job_type` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `visible` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `job_type`
--

INSERT INTO `job_type` (`id`, `name`, `visible`) VALUES
(1, 'Full-time', 1),
(2, 'Part-time', 1),
(3, 'Contract', 1),
(4, 'Internship', 1),
(5, 'Freelance', 1),
(6, 'Hidden', 0);

-- --------------------------------------------------------

--
-- Structure de la table `langage`
--

CREATE TABLE `langage` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `visible` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `langage`
--

INSERT INTO `langage` (`id`, `name`, `visible`) VALUES
(1, 'HTML', 1),
(2, 'CSS', 1),
(3, 'PHP', 1),
(4, 'JS', 1),
(5, 'LARAVEL', 1),
(6, 'SYMFONY', 1);

-- --------------------------------------------------------

--
-- Structure de la table `society`
--

CREATE TABLE `society` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `localisation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `siret` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `galleries` json DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `creation_annee` int DEFAULT NULL,
  `about` longtext COLLATE utf8mb4_unicode_ci,
  `linkedin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `society`
--

INSERT INTO `society` (`id`, `user_id`, `name`, `localisation`, `siret`, `telephone`, `galleries`, `avatar`, `website`, `creation_annee`, `about`, `linkedin`, `facebook`) VALUES
(2, 5, 'AKMTECH', 'Rennes', '87878787', '0099090909', NULL, NULL, 'google.com', 2022, 'Une description de ma société', 'linkedin', 'facebook'),
(3, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `status`
--

CREATE TABLE `status` (
  `id` int NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `status`
--

INSERT INTO `status` (`id`, `name`, `color`) VALUES
(1, 'en attente', 'orange'),
(2, 'accepté', 'green'),
(3, 'rejeté', 'red'),
(4, 'Poste pourvu', 'red');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_user` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_society` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `type_user`, `name_society`, `roles`, `password`, `is_active`) VALUES
(4, 'dev@gmail.com', 'dev', NULL, '[\"ROLE_DEV\"]', '$2y$13$4w9iZkidVlB9Ce.d48Tj2.JKQaVV1WL4jHbN3IVGLRn8NwhSfME8K', 1),
(5, 'akm@gmail.com', 'society', 'AKMTECH', '[\"ROLE_SOCIETY\"]', '$2y$13$XXHEuYm6wnkWsMXNbGXejOJLrXKZ6k2q7PKiVvpRdacnTMiDSe3wm', 1),
(6, 'go@gmail.com', 'society', 'GODIGITAL', '[\"ROLE_SOCIETY\"]', '$2y$13$0GcWi8h2xQ7ZMnfvJ.vZrOMhdkFAXPmQTsLCbDlMgacyjlAHaVHEW', 1),
(7, 'speed@gmail.com', 'society', 'SPEEDTECH', '[\"ROLE_SOCIETY\"]', '$2y$13$rUpS6Ykrel5k4MAPmyAjmOgz/j/q/eiSNnyXd6c6G6M.BDayaAdM.', 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `developer`
--
ALTER TABLE `developer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_65FB8B9AA76ED395` (`user_id`);

--
-- Index pour la table `job_posting`
--
ALTER TABLE `job_posting`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_27C8EAE85FA33B08` (`job_type_id`),
  ADD KEY `IDX_27C8EAE8E6389D24` (`society_id`);

--
-- Index pour la table `job_type`
--
ALTER TABLE `job_type`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `langage`
--
ALTER TABLE `langage`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `society`
--
ALTER TABLE `society`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_D6461F2A76ED395` (`user_id`);

--
-- Index pour la table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_7B00651C5E237E06` (`name`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `developer`
--
ALTER TABLE `developer`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `job_posting`
--
ALTER TABLE `job_posting`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `job_type`
--
ALTER TABLE `job_type`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `langage`
--
ALTER TABLE `langage`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `society`
--
ALTER TABLE `society`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `status`
--
ALTER TABLE `status`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `developer`
--
ALTER TABLE `developer`
  ADD CONSTRAINT `FK_65FB8B9AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `job_posting`
--
ALTER TABLE `job_posting`
  ADD CONSTRAINT `FK_27C8EAE85FA33B08` FOREIGN KEY (`job_type_id`) REFERENCES `job_type` (`id`),
  ADD CONSTRAINT `FK_27C8EAE8E6389D24` FOREIGN KEY (`society_id`) REFERENCES `society` (`id`);

--
-- Contraintes pour la table `society`
--
ALTER TABLE `society`
  ADD CONSTRAINT `FK_D6461F2A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
