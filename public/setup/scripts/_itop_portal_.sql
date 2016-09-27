-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u2
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Mar 27 Septembre 2016 à 14:37
-- Version du serveur :  5.5.50-0+deb8u1
-- Version de PHP :  5.6.24-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `itop_portal_dev`
--

-- --------------------------------------------------------

--
-- Structure de la table `auth_belong`
--

CREATE TABLE IF NOT EXISTS `auth_belong` (
  `user_id` int(11) unsigned NOT NULL,
  `group_id` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `auth_belong`
--

INSERT INTO `auth_belong` (`user_id`, `group_id`) VALUES
(1, 1),
(16, 1),
(14, 3),
(15, 3),
(2, 5);

-- --------------------------------------------------------

--
-- Structure de la table `auth_group`
--

CREATE TABLE IF NOT EXISTS `auth_group` (
`id` int(11) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `group_parent_id` int(11) unsigned DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `auth_group`
--

INSERT INTO `auth_group` (`id`, `name`, `description`, `group_parent_id`) VALUES
(1, 'Administrator', 'Administrator', 4),
(2, 'Webmaster', 'Webmaster', 3),
(3, 'User', '<p>User : Les clients.</p>', NULL),
(4, 'Moderator', 'Moderator', 2),
(5, 'Anonymous', 'Anonymous', NULL),
(6, 'Syleps', 'Ensemble des utilisateurs salariés Syleps.', NULL),
(7, 'Basic', 'Profil utilisateur basique Syleps.\r\nDestiné au personne ne travaillant peu ou pas sur l''information, les menus accessibles seront limités au strict minimum', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `auth_group_permission`
--

CREATE TABLE IF NOT EXISTS `auth_group_permission` (
  `group_id` int(11) unsigned NOT NULL,
  `permission_id` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `auth_group_permission`
--

INSERT INTO `auth_group_permission` (`group_id`, `permission_id`) VALUES
(1, 1),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(2, 9),
(3, 9),
(4, 9),
(5, 9),
(6, 9),
(7, 9),
(3, 10),
(6, 10),
(7, 10),
(3, 11),
(6, 11),
(3, 12),
(6, 12),
(3, 13),
(6, 13),
(3, 14),
(6, 14),
(7, 14),
(3, 15),
(6, 15),
(3, 16),
(6, 16),
(7, 16),
(3, 17),
(6, 17),
(7, 17),
(3, 18),
(6, 18),
(7, 18),
(3, 19),
(6, 19),
(7, 19),
(3, 20),
(6, 20),
(7, 20),
(3, 21),
(6, 21),
(7, 21),
(1, 41),
(3, 43),
(3, 61),
(6, 61),
(7, 61),
(3, 62),
(6, 62),
(7, 62),
(3, 66),
(6, 66),
(7, 66),
(3, 67),
(6, 67),
(3, 68),
(6, 68),
(7, 68),
(3, 69),
(6, 69),
(7, 69),
(3, 70),
(6, 70),
(7, 70),
(3, 71),
(6, 71),
(7, 71),
(3, 74),
(6, 74),
(7, 74),
(3, 75),
(3, 76),
(1, 77),
(1, 78),
(1, 79),
(3, 80),
(6, 80),
(7, 80),
(3, 81),
(6, 81),
(7, 81);

-- --------------------------------------------------------

--
-- Structure de la table `auth_permission`
--

CREATE TABLE IF NOT EXISTS `auth_permission` (
`id` int(11) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `auth_permission`
--

INSERT INTO `auth_permission` (`id`, `name`, `description`) VALUES
(0, 'admin_index_dashboard', 'View admin dashboard'),
(1, 'auth_admin-user_get', 'View an user'),
(3, 'auth_admin-user_post', 'Create an user'),
(4, 'auth_admin-user_put', 'Update user information'),
(5, 'auth_admin-user_index', 'View user index'),
(6, 'auth_admin-user_list', 'View user list'),
(7, 'auth_admin-group-permission_index', 'View permission per group'),
(8, 'auth_admin-group-permission_switch', 'Switch permission'),
(9, 'all', 'All'),
(10, 'home_index_index', 'Accueil du site'),
(11, 'request_dashboard_index', 'Tableau de bord global'),
(12, 'home_catalogue_index', 'Catalogue des services'),
(13, 'home_contact_index', 'Contact Services'),
(14, 'request_index_index', 'Accueil request (rediriger)'),
(15, 'request_dashboard_index', 'Tableau de bord Atelys'),
(16, 'request_newrequest_index', 'Création d''un ticket'),
(17, 'request_openedrequest_index', 'Liste des tickets en cours'),
(18, 'request_closedrequest_index', 'Liste des tickets fermés'),
(19, 'request_openedrequest_getdata', 'Action pour charger les données (json) tickets ouverts'),
(20, 'request_closedrequest_getdata', 'Action pour charger les données (json) tickets clos'),
(21, 'home_catalogue_getdata', 'Action pour charger les données (json) du catalogue '),
(41, 'chat_index_viewallchats', 'Listes des chats'),
(43, 'chat_index_index', 'Nouveau Chat'),
(61, 'user_language_change', 'User can change the language'),
(62, 'request_openedrequest_download', 'Download Attachment'),
(66, 'home_index_savepref', 'Action pour sauvegarder la position des services sur la page d''acceuil'),
(67, 'request_dashboard_recherche', 'Action pour recharger les données des diagramme dans le tableau de bords général'),
(68, 'user_preference_index', 'Préférence'),
(69, 'user_preference_changepref', 'validation en background des préférences'),
(70, 'request_openedrequest_changefilter', 'Modification des filtres sur consultation des tickets'),
(71, 'request_pdf_imprimer', 'Impression des requêtes utilisateurs'),
(74, 'request_closedrequest_changefilter', 'Modification des filtres sur consultation des tickets fermés'),
(75, 'home_contract_index', 'Liste des Contrats Fournisseurs'),
(76, 'home_contract_getdata', 'Liste Json des contrats fournisseur'),
(77, 'user_admin-services_index', 'Liste des services pour les clients autre que Syleps'),
(78, 'user_admin-services_get', 'Formulaire des services pour les clients autre que Syleps'),
(79, 'user_admin-user_get', 'View an user'),
(80, 'home_index_index', 'Accueil du site'),
(81, 'request_openedrequest_update', 'Modification d''un ticket puis redirection vers la vue du ticket');

-- --------------------------------------------------------

--
-- Structure de la table `auth_user`
--

CREATE TABLE IF NOT EXISTS `auth_user` (
`id` int(11) unsigned NOT NULL,
  `username` varchar(255) NOT NULL,
  `first_name` varchar(30) DEFAULT NULL,
  `last_name` varchar(30) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(128) NOT NULL,
  `salt` varchar(128) DEFAULT NULL,
  `algorithm` varchar(128) NOT NULL,
  `can_be_deleted` int(1) unsigned NOT NULL DEFAULT '1',
  `is_active` int(1) unsigned NOT NULL DEFAULT '0',
  `is_super_admin` int(1) unsigned NOT NULL DEFAULT '0',
  `is_staff` int(1) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_login` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_parent_id` int(11) unsigned DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `auth_user`
--

INSERT INTO `auth_user` (`id`, `username`, `first_name`, `last_name`, `email`, `password`, `salt`, `algorithm`, `can_be_deleted`, `is_active`, `is_super_admin`, `is_staff`, `created_at`, `last_login`, `updated_at`, `user_parent_id`) VALUES
(1, 'manu', 'Manu (admin)', 'Lozachmeur', 'emmanuel.lozachmeur@syleps.fr', 'c138ef1cde75dcf83c5f99ddeac292678543cdeb', 'a73056c2bbdca2d4148049493e296e70', 'sha1', 0, 1, 1, 0, '2009-11-23 17:36:31', '2015-04-07 01:06:01', '2015-04-07 13:06:01', NULL),
(2, 'anonymous', NULL, NULL, NULL, '', NULL, '', 0, 0, 0, 0, '0000-00-00 00:00:00', '2015-03-26 08:56:52', '2015-04-07 13:02:01', NULL),
(14, 'dali@demo.com', 'Salvador', 'Dali', 'dali@demo.com', 'c2a2543bdd9f0f5fe3c7887d7c455fa620b30aee', '39a9606ae41122d8f05896e8419c505d', 'sha1', 1, 1, 0, 0, '2015-03-26 08:47:01', '2016-09-23 00:05:39', '2016-09-23 12:05:39', NULL),
(15, 'pablo@demo.com', 'Pablo', 'Picasso', 'pablo@demo.com', '443941840ecd783d156423d760ef281bda0dd0b7', '8bc9749e695faaf83ecf99aba96c6d05', 'sha1', 1, 1, 0, 1, '2015-03-30 08:51:25', '0000-00-00 00:00:00', '2015-03-30 08:58:03', NULL),
(16, 'admin', 'My First Name', 'My Last Name', 'my.email@foo.org', 'eaad908b009ee6ea086bde96e6fcb1b61d720cfc', '8928d295449baeeae36ec22d4c833f6e', 'sha1', 0, 1, 1, 0, '2015-04-07 13:02:58', '2016-09-26 23:45:28', '2016-09-27 11:45:28', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `auth_user_permission`
--

CREATE TABLE IF NOT EXISTS `auth_user_permission` (
  `user_id` int(11) unsigned NOT NULL,
  `permission_id` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `auth_user_permission`
--

INSERT INTO `auth_user_permission` (`user_id`, `permission_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `centurion_content_type`
--

CREATE TABLE IF NOT EXISTS `centurion_content_type` (
`id` int(11) unsigned NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `centurion_content_type`
--

INSERT INTO `centurion_content_type` (`id`, `name`) VALUES
(2, 'Cms_Model_DbTable_Flatpage'),
(3, 'Cms_Model_DbTable_Row_Flatpage'),
(8, 'Core_Model_DbTable_Navigation');

-- --------------------------------------------------------

--
-- Structure de la table `centurion_navigation`
--

CREATE TABLE IF NOT EXISTS `centurion_navigation` (
`id` int(11) unsigned NOT NULL,
  `label` varchar(150) DEFAULT NULL,
  `module` varchar(100) DEFAULT NULL,
  `controller` varchar(100) DEFAULT NULL,
  `action` varchar(100) DEFAULT NULL,
  `params` text,
  `permission` varchar(255) DEFAULT NULL,
  `route` varchar(100) DEFAULT NULL,
  `uri` varchar(255) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `is_visible` int(1) NOT NULL DEFAULT '1',
  `is_in_menu` int(1) NOT NULL DEFAULT '1',
  `class` varchar(50) DEFAULT NULL,
  `mptt_lft` int(11) unsigned NOT NULL,
  `mptt_rgt` int(11) unsigned NOT NULL,
  `mptt_level` int(11) unsigned NOT NULL,
  `mptt_tree_id` int(11) unsigned DEFAULT NULL,
  `mptt_parent_id` int(11) unsigned DEFAULT NULL,
  `proxy_model` int(11) unsigned DEFAULT NULL,
  `proxy_pk` int(11) unsigned DEFAULT NULL,
  `can_be_deleted` int(11) unsigned DEFAULT '1',
  `original_id` int(11) unsigned DEFAULT NULL,
  `language_id` int(11) unsigned DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=200 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `centurion_navigation`
--

INSERT INTO `centurion_navigation` (`id`, `label`, `module`, `controller`, `action`, `params`, `permission`, `route`, `uri`, `order`, `is_visible`, `is_in_menu`, `class`, `mptt_lft`, `mptt_rgt`, `mptt_level`, `mptt_tree_id`, `mptt_parent_id`, `proxy_model`, `proxy_pk`, `can_be_deleted`, `original_id`, `language_id`) VALUES
(1, 'Users Profiles', 'user', 'admin-profile', NULL, NULL, NULL, 'default', NULL, 1, 1, 1, 'sqdsdqsdqsd', 20, 21, 3, 5, 122, NULL, NULL, 1, NULL, 1),
(2, 'Manage group permissions', 'auth', 'admin-group-permission', NULL, NULL, NULL, 'default', NULL, 3, 0, 1, NULL, 37, 38, 2, 5, 192, NULL, NULL, 1, NULL, 2),
(3, 'Pages', 'admin', 'admin-navigation', NULL, NULL, NULL, NULL, NULL, 2, 1, 1, NULL, 52, 63, 1, 5, 12, NULL, NULL, 1, NULL, NULL),
(4, 'Settings', 'cron', 'admin-cron', NULL, NULL, NULL, 'default', NULL, 3, 1, 1, NULL, 64, 81, 1, 5, 12, NULL, NULL, 1, NULL, 1),
(5, 'Template', 'cms', 'admin-flatpage-template', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, 55, 56, 2, 5, 3, NULL, NULL, 1, NULL, NULL),
(7, 'Cache', 'admin', 'index', 'cache', NULL, NULL, NULL, NULL, 2, 1, 1, NULL, 67, 70, 2, 5, 4, NULL, NULL, 1, NULL, NULL),
(8, 'Clear cache', 'admin', 'index', 'clear-cache', NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, 68, 69, 3, 5, 7, NULL, NULL, 1, NULL, NULL),
(11, 'Translation', 'translation', 'admin', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, 71, 72, 2, 5, 4, NULL, NULL, 1, NULL, NULL),
(12, 'Backoffice', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, 1, 84, 0, 5, NULL, NULL, NULL, 0, NULL, 1),
(13, 'Error', 'admin', 'index', 'log', NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, 73, 74, 2, 5, 4, NULL, NULL, 1, NULL, NULL),
(14, 'Pages unactivated', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 'unactived', 1, 26, 0, 20, NULL, NULL, NULL, 0, NULL, NULL),
(16, 'Frontoffice', NULL, NULL, NULL, NULL, 'all', NULL, NULL, NULL, 1, 1, NULL, 1, 122, 0, 18, NULL, NULL, NULL, 0, NULL, 1),
(20, 'Contents', 'admin', 'admin-dashboard', 'list-admin', NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, 2, 17, 1, 5, 12, NULL, NULL, 1, NULL, 1),
(105, 'Pages', 'admin', 'admin-navigation', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, 53, 54, 2, 5, 3, NULL, NULL, 1, NULL, NULL),
(118, 'Permission', 'auth', 'admin-permission', NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, NULL, 39, 40, 2, 5, 192, NULL, NULL, 1, NULL, 2),
(119, 'Script permissions', 'auth', 'admin-script-permission', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, 30, 31, 3, 5, 122, NULL, NULL, 1, NULL, NULL),
(121, 'Group permission', 'auth', 'admin-group-permission', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, 34, 35, 3, 5, 123, NULL, NULL, 1, NULL, 2),
(122, 'Users', 'auth', 'admin-user', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, 19, 32, 2, 5, 192, NULL, NULL, 1, NULL, 2),
(123, 'Groups', 'auth', 'admin-group', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, 33, 36, 2, 5, 192, NULL, NULL, 1, NULL, 2),
(124, 'User permission', 'auth', 'admin-permission', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, 28, 29, 3, 5, 122, NULL, NULL, 1, NULL, 2),
(125, 'Bienvenue', 'home', 'index', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, 86, 105, 1, 18, 16, NULL, NULL, 1, NULL, 2),
(126, 'Tableau de bord', 'home', 'index', 'index', NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, 87, 88, 2, 18, 125, NULL, NULL, 1, NULL, 2),
(127, 'Catalogue des services', 'home', 'catalogue', 'index', NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, 89, 96, 2, 18, 125, NULL, NULL, 1, NULL, 2),
(128, 'Contact', 'home', 'contact', 'index', NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, 97, 102, 2, 18, 125, NULL, NULL, 1, NULL, 2),
(129, 'Gestion des Incidents', 'request', 'index', 'index', NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, 106, 115, 1, 18, 16, NULL, NULL, 1, NULL, 2),
(131, 'Tableau de bord', 'request', 'dashboard', 'index', NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, 107, 108, 2, 18, 129, NULL, NULL, 1, NULL, 2),
(132, 'Nouveau ticket', 'request', 'newrequest', 'index', NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, 109, 110, 2, 18, 129, NULL, NULL, 1, NULL, 2),
(133, 'Tickets en cours', 'request', 'openedrequest', 'index', NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, 111, 112, 2, 18, 129, NULL, NULL, 1, NULL, 2),
(134, 'Tickets fermés', 'request', 'closedrequest', 'index', NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, 113, 114, 2, 18, 129, NULL, NULL, 1, NULL, 2),
(149, 'Store', 'store', 'product', 'index', NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, 116, 121, 1, 18, 16, NULL, NULL, 1, NULL, 2),
(167, 'Produits', 'store', 'product', 'index', NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, 117, 118, 2, 18, 149, NULL, NULL, 1, NULL, 2),
(168, 'Panier', 'store', 'panier', 'index', NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, 119, 120, 2, 18, 149, NULL, NULL, 1, NULL, 2),
(169, 'Liste des Chat', 'chat', 'index', 'viewallchats', NULL, NULL, NULL, NULL, NULL, 0, 1, NULL, 98, 99, 3, 18, 128, NULL, NULL, 1, NULL, 1),
(170, 'Nouveau Chat', 'chat', 'index', 'index', NULL, NULL, NULL, NULL, NULL, 0, 1, NULL, 100, 101, 3, 18, 128, NULL, NULL, 1, NULL, 1),
(172, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, NULL, 103, 104, 2, 18, 125, 2, 1, 1, NULL, 1),
(183, 'Préférences', 'user', 'preference', 'index', NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, 82, 83, 1, 5, 12, NULL, NULL, 1, NULL, 1),
(184, 'Manage Import Ldap', 'user', 'admin-ldap-user', 'index', NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, 24, 25, 3, 5, 122, NULL, NULL, 1, NULL, 1),
(185, 'Manage Local Users', 'user', 'admin-user', NULL, NULL, NULL, 'default', NULL, NULL, 1, 1, NULL, 22, 23, 3, 5, 122, NULL, NULL, 1, NULL, 1),
(186, 'Manage Import iTop', 'user', 'admin-itop-user', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, 26, 27, 3, 5, 122, NULL, NULL, 1, NULL, 1),
(187, 'Cron Task', 'cron', 'admin-cron', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, 65, 66, 2, 5, 4, NULL, NULL, 1, NULL, 1),
(188, 'Contrats Fournisseur', 'home', 'contract', 'index', NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, 92, 93, 3, 18, 127, NULL, NULL, 1, NULL, 1),
(189, 'Admin Services Clients', 'config', 'admin-services', NULL, NULL, NULL, 'default', NULL, NULL, 1, 1, NULL, 94, 95, 3, 18, 127, NULL, NULL, 1, NULL, 1),
(190, 'Home Services Page', 'config', 'admin-style-services', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, 57, 60, 2, 5, 3, NULL, NULL, 1, NULL, 1),
(191, 'Manage Media', 'media', 'admin-media', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, 58, 59, 3, 5, 190, NULL, NULL, 1, NULL, 1),
(192, 'Configuration', 'config', 'admin-home', 'dashboard', NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, 18, 45, 1, 5, 12, NULL, NULL, 1, NULL, 1),
(193, 'Organizations', 'config', 'admin-organizations', 'index', NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, 41, 42, 2, 5, 192, NULL, NULL, 1, NULL, 1),
(194, 'Alerts', 'config', 'admin-alerts', 'index', NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, 43, 44, 2, 5, 192, NULL, NULL, 1, NULL, 1),
(195, 'Widgets', 'config', 'admin-widget', 'index', NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, 61, 62, 2, 5, 3, NULL, NULL, 1, NULL, 1),
(196, 'Listes des Services', 'home', 'catalogue', 'list', NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, 90, 91, 3, 18, 127, NULL, NULL, 1, NULL, 1),
(197, 'Configuration Files', 'config', 'admin-ini-file', 'index', NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, 75, 80, 2, 5, 4, NULL, NULL, 1, NULL, 1),
(198, 'Application', 'config', 'admin-ini-file', 'application', NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, 76, 77, 3, 5, 197, NULL, NULL, 1, NULL, 1),
(199, 'Database', 'config', 'admin-ini-file', 'db', NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, 78, 79, 3, 5, 197, NULL, NULL, 1, NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `centurion_site`
--

CREATE TABLE IF NOT EXISTS `centurion_site` (
`id` int(11) unsigned NOT NULL,
  `domain` varchar(100) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `centurion_site`
--

INSERT INTO `centurion_site` (`id`, `domain`, `name`) VALUES
(1, 'example.com', 'example.com');

-- --------------------------------------------------------

--
-- Structure de la table `cms_flatpage`
--

CREATE TABLE IF NOT EXISTS `cms_flatpage` (
`id` int(11) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `body` text,
  `url` varchar(100) DEFAULT NULL,
  `flatpage_template_id` int(11) unsigned NOT NULL,
  `published_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_published` int(1) unsigned DEFAULT '0',
  `mptt_lft` int(11) unsigned NOT NULL,
  `mptt_rgt` int(11) unsigned NOT NULL,
  `mptt_level` int(11) unsigned NOT NULL,
  `mptt_tree_id` int(11) unsigned DEFAULT NULL,
  `mptt_parent_id` int(11) unsigned DEFAULT NULL,
  `original_id` int(11) unsigned DEFAULT NULL,
  `language_id` int(11) unsigned DEFAULT NULL,
  `forward_url` varchar(255) DEFAULT NULL,
  `flatpage_type` int(1) NOT NULL DEFAULT '1',
  `route` varchar(50) DEFAULT NULL,
  `class` varchar(255) DEFAULT NULL,
  `cover_id` varchar(100) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `cms_flatpage`
--

INSERT INTO `cms_flatpage` (`id`, `title`, `slug`, `description`, `keywords`, `body`, `url`, `flatpage_template_id`, `published_at`, `created_at`, `updated_at`, `is_published`, `mptt_lft`, `mptt_rgt`, `mptt_level`, `mptt_tree_id`, `mptt_parent_id`, `original_id`, `language_id`, `forward_url`, `flatpage_type`, `route`, `class`, `cover_id`) VALUES
(1, 'Contact', 'contact', NULL, NULL, '<h1>Commerciaux Services</h1>\r\n<p>Nos commerciaux Service, St&eacute;phane Ren&eacute; et Yannick Loison, sont &agrave; votre &eacute;coute.</p>', '/contact', 1, '2013-07-24 19:09:00', '2013-07-24 19:09:54', '2014-07-30 16:00:16', 0, 1, 2, 0, 1, NULL, NULL, 1, NULL, 1, NULL, NULL, NULL),
(2, 'Contact', NULL, NULL, NULL, '<h1>Commercials Services</h1>\r\n<p>our commercials services, St&eacute;phane Ren&eacute;e and Yannick Loison, are listen to you.</p>', '/contact', 2, '2013-07-24 19:11:00', '2013-07-24 19:11:12', '2014-07-30 16:00:17', 0, 0, 0, 0, NULL, NULL, 1, 2, NULL, 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `cms_flatpage_template`
--

CREATE TABLE IF NOT EXISTS `cms_flatpage_template` (
`id` int(11) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `view_script` varchar(50) NOT NULL,
  `class` varchar(50) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `cms_flatpage_template`
--

INSERT INTO `cms_flatpage_template` (`id`, `name`, `view_script`, `class`) VALUES
(1, 'Basic', '_generic/basic.phtml', NULL),
(2, 'Basic no title', '_generic/basic-notitle.phtml', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `cron_task`
--

CREATE TABLE IF NOT EXISTS `cron_task` (
`id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `class_name` varchar(255) NOT NULL,
  `function_name` varchar(255) NOT NULL,
  `is_active` varchar(1) NOT NULL,
  `frequency` int(11) NOT NULL,
  `last_execution` datetime DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COMMENT='Table listant les tâches exécutées par le cron';

--
-- Contenu de la table `cron_task`
--

INSERT INTO `cron_task` (`id`, `name`, `class_name`, `function_name`, `is_active`, `frequency`, `last_execution`) VALUES
(2, 'Synchronisation des utilisateurs avec iTop', 'Portal_Itop_UserLocal', 'synchronize', '0', 3600, '2015-03-09 11:36:24'),
(3, 'Synchronisation des utilisateurs avec Ldap', 'Portal_Ldap_ldap ?', 'synchronize', '0', 3600, '2015-03-06 12:02:00');

-- --------------------------------------------------------

--
-- Structure de la table `ecom_products`
--

CREATE TABLE IF NOT EXISTS `ecom_products` (
`produitId` int(20) NOT NULL,
  `nom` varchar(20) NOT NULL,
  `description` text NOT NULL,
  `prix` decimal(9,2) NOT NULL,
  `image` varchar(255) NOT NULL COMMENT 'nom de l''image'
) ENGINE=MyISAM AUTO_INCREMENT=57 DEFAULT CHARSET=latin1;

--
-- Contenu de la table `ecom_products`
--

INSERT INTO `ecom_products` (`produitId`, `nom`, `description`, `prix`, `image`) VALUES
(56, 'Chromecast Audio', 'Chromecast is a media streaming device that plugs into the HDMI port on your TV. Simply use your mobile device and the TV you already own to cast your favourite TV shows, films, music, sport, games and more.', 40.00, 'google_chromecast_audio.png'),
(52, 'Google Glass', 'Google Glass is a headset, or optical head-mounted display, that is worn like a pair of eyeglasses.', 1500.00, 'google_glass.png'),
(53, 'Nexus 9', 'Designed for work and play, the Nexus 9 features a just-right 8.9â€ screen with front-facing speakers for rich music and audio. 64-bit processor drives productivity and play to new levels, with intuitive voice commands and automatic Android updates that keep you on the cutting edge.', 450.00, 'nexus9.png'),
(54, 'Nexus 5', 'Nexus 5 has a 8 MP OIS camera that incorporates advanced technology to shoot vivid imagery with a wide range of color and light intake. And while youâ€™re holding the camera, Optical Image Stabilization will steady the shot even with shaky hands, so your photos and videos will come out sharp and clear. ', 350.00, 'nexus5.png'),
(55, 'Chromecast', 'Chromecast is a media streaming device that plugs into the HDMI port on your TV. Simply use your mobile device and the TV you already own to cast your favourite TV shows, films, music, sport, games and more.', 30.00, 'google_chromecast.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `media_duplicate`
--

CREATE TABLE IF NOT EXISTS `media_duplicate` (
`id` int(11) unsigned NOT NULL,
  `file_id` varchar(100) NOT NULL,
  `adapter` varchar(50) NOT NULL,
  `params` text NOT NULL,
  `dest` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `media_duplicate`
--

INSERT INTO `media_duplicate` (`id`, `file_id`, `adapter`, `params`, `dest`) VALUES
(52, 'b270d78a83e5d5c7584ba6e4147e37ec', 'local', 'a:3:{s:4:"path";s:51:"/var/www/itopPortalDev/application/../public/files/";s:3:"url";s:8:"/static/";s:16:"use_urlrewriting";s:1:"1";}', '34PniNU-tO8gzPBIMJ7Ncw/_adc75e75.centurion'),
(53, '538844570c7ede460b53639f04a56a73', 'local', 'a:3:{s:4:"path";s:51:"/var/www/itopPortalDev/application/../public/files/";s:3:"url";s:8:"/static/";s:16:"use_urlrewriting";s:1:"1";}', 'iLJx2-I51UrYWoQpFsro7w/_adc75e75.centurion'),
(54, '3f6e0df4c354ad27d829b7af7fb516c9', 'local', 'a:3:{s:4:"path";s:51:"/var/www/itopPortalDev/application/../public/files/";s:3:"url";s:8:"/static/";s:16:"use_urlrewriting";s:1:"1";}', 'RVWWZMI04wjSvnLOGQE8rQ/_adc75e75.centurion'),
(55, '1311d969b803f360433a4252e501eb8c', 'local', 'a:3:{s:4:"path";s:51:"/var/www/itopPortalDev/application/../public/files/";s:3:"url";s:8:"/static/";s:16:"use_urlrewriting";s:1:"1";}', '__6cV6myViOsIZJgwbUVUA/_adc75e75.centurion'),
(56, '7ead9b87613378ab41c733ff85f7c6bf', 'local', 'a:3:{s:4:"path";s:51:"/var/www/itopPortalDev/application/../public/files/";s:3:"url";s:8:"/static/";s:16:"use_urlrewriting";s:1:"1";}', 'ylcJDJmiXLSYSJdIgXQ4nw/_adc75e75.centurion'),
(57, '412f3013dca7184c8876c8e7092dcd86', 'local', 'a:3:{s:4:"path";s:51:"/var/www/itopPortalDev/application/../public/files/";s:3:"url";s:8:"/static/";s:16:"use_urlrewriting";s:1:"1";}', 'IFwsAfVifdaM9UakMgWLQA/_adc75e75.centurion'),
(58, 'b6d2ed8d5a22e584dee51034edb75901', 'local', 'a:3:{s:4:"path";s:51:"/var/www/itopPortalDev/application/../public/files/";s:3:"url";s:8:"/static/";s:16:"use_urlrewriting";s:1:"1";}', 'OC2dyZ6n9a3W-md-wJZdCQ/_adc75e75.centurion'),
(59, '2abfd121b2b3074ae4ba914769ee8fbf', 'local', 'a:3:{s:4:"path";s:51:"/var/www/itopPortalDev/application/../public/files/";s:3:"url";s:8:"/static/";s:16:"use_urlrewriting";s:1:"1";}', 'Ni-cMpO9tLQRZjlMAQrQHg/_adc75e75.centurion'),
(60, 'a7ee0fc801aae5fafbe678e47afe0420', 'local', 'a:3:{s:4:"path";s:51:"/var/www/itopPortalDev/application/../public/files/";s:3:"url";s:8:"/static/";s:16:"use_urlrewriting";s:1:"1";}', 'TgaYbutJJ8m3GkMTbCxYkg/_.centurion'),
(61, '412f3013dca7184c8876c8e7092dcd86', 'local', 'a:3:{s:4:"path";s:51:"/var/www/itopPortalDev/application/../public/files/";s:3:"url";s:8:"/static/";s:16:"use_urlrewriting";s:1:"1";}', 'IFwsAfVifdaM9UakMgWLQA/_.centurion'),
(62, 'b270d78a83e5d5c7584ba6e4147e37ec', 'local', 'a:3:{s:4:"path";s:51:"/var/www/itopPortalDev/application/../public/files/";s:3:"url";s:8:"/static/";s:16:"use_urlrewriting";s:1:"1";}', '34PniNU-tO8gzPBIMJ7Ncw/_.centurion'),
(63, '538844570c7ede460b53639f04a56a73', 'local', 'a:3:{s:4:"path";s:51:"/var/www/itopPortalDev/application/../public/files/";s:3:"url";s:8:"/static/";s:16:"use_urlrewriting";s:1:"1";}', 'iLJx2-I51UrYWoQpFsro7w/_.centurion'),
(64, '13ed5810386e20dcf0a02bfb197564ba', 'local', 'a:3:{s:4:"path";s:51:"/var/www/itopPortalDev/application/../public/files/";s:3:"url";s:8:"/static/";s:16:"use_urlrewriting";s:1:"1";}', 'ueUVCRe7IP7Thlcczsfs9g/_.centurion'),
(65, '7ead9b87613378ab41c733ff85f7c6bf', 'local', 'a:3:{s:4:"path";s:51:"/var/www/itopPortalDev/application/../public/files/";s:3:"url";s:8:"/static/";s:16:"use_urlrewriting";s:1:"1";}', 'ylcJDJmiXLSYSJdIgXQ4nw/_.centurion'),
(66, 'b6d2ed8d5a22e584dee51034edb75901', 'local', 'a:3:{s:4:"path";s:51:"/var/www/itopPortalDev/application/../public/files/";s:3:"url";s:8:"/static/";s:16:"use_urlrewriting";s:1:"1";}', 'OC2dyZ6n9a3W-md-wJZdCQ/_.centurion'),
(67, '1669ea92abfd2b1229c809cc4a528f6d', 'local', 'a:3:{s:4:"path";s:51:"/var/www/itopPortalDev/application/../public/files/";s:3:"url";s:8:"/static/";s:16:"use_urlrewriting";s:1:"1";}', 'av4yJ73C6VdhvgtVaPcuyg/_.centurion'),
(68, '8a7eb9d5e669e5433b3b299145f14eac', 'local', 'a:3:{s:4:"path";s:51:"/var/www/itopPortalDev/application/../public/files/";s:3:"url";s:8:"/static/";s:16:"use_urlrewriting";s:1:"1";}', 'nG7MNMk4iaDk28iiZItdEQ/_.centurion'),
(69, '6eb147aea2654478b297469098434798', 'local', 'a:3:{s:4:"path";s:51:"/var/www/itopPortalDev/application/../public/files/";s:3:"url";s:8:"/static/";s:16:"use_urlrewriting";s:1:"1";}', 'seOnsSB0S2de8fa9nR4mAA/_.centurion'),
(70, '5077346224f7c73bf562c0420951b5ab', 'local', 'a:3:{s:4:"path";s:51:"/var/www/itopPortalDev/application/../public/files/";s:3:"url";s:8:"/static/";s:16:"use_urlrewriting";s:1:"1";}', '4PysWemJ4b1wr_U-A5Lj4w/_.centurion'),
(71, '55140aa540ebe30a95dbd0d8da8a9a90', 'local', 'a:3:{s:4:"path";s:51:"/var/www/itopPortalDev/application/../public/files/";s:3:"url";s:8:"/static/";s:16:"use_urlrewriting";s:1:"1";}', 'OvhsY0Zyd6-d8y6vtsaPvA/_abf174g94.centurion'),
(72, '55140aa540ebe30a95dbd0d8da8a9a90', 'local', 'a:3:{s:4:"path";s:51:"/var/www/itopPortalDev/application/../public/files/";s:3:"url";s:8:"/static/";s:16:"use_urlrewriting";s:1:"1";}', 'OvhsY0Zyd6-d8y6vtsaPvA/_abf174g94.centurion');

-- --------------------------------------------------------

--
-- Structure de la table `media_file`
--

CREATE TABLE IF NOT EXISTS `media_file` (
  `id` varchar(100) NOT NULL,
  `file_id` varchar(32) DEFAULT NULL,
  `local_filename` varchar(255) DEFAULT NULL,
  `mime` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `filesize` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `use_count` int(11) unsigned NOT NULL DEFAULT '0',
  `user_id` int(11) unsigned DEFAULT NULL,
  `proxy_model` varchar(150) DEFAULT NULL,
  `proxy_pk` int(11) unsigned DEFAULT NULL,
  `belong_model` varchar(150) DEFAULT NULL,
  `belong_pk` int(11) unsigned DEFAULT NULL,
  `description` text,
  `sha1` varchar(40) DEFAULT NULL,
  `delete_original` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `media_file`
--

INSERT INTO `media_file` (`id`, `file_id`, `local_filename`, `mime`, `filename`, `name`, `filesize`, `created_at`, `use_count`, `user_id`, `proxy_model`, `proxy_pk`, `belong_model`, `belong_pk`, `description`, `sha1`, `delete_original`) VALUES
('0dd9cf3561a896e3b59533756f31bc30', '25b74e8b3b5dd29db586ba89f7d0a1f1', '2c\\b794de0977a2027b464dfebc5a821a.png', 'image/png', 'si[64].png', '', 4827, '2015-04-07 13:09:34', 0, NULL, 'Media_Model_DbTable_Image', 36, NULL, NULL, NULL, '873393359eec3dc931719af7c221812b594a30e6', 1),
('11366388bdf366a971af63d013f639b1', '36ae7c7cc8bed7a2c7264cabb08f1ef5', 'ab\\ec0b11e10cd3463b30bab090b5be42.png', 'image/png', 'backup[64].png', '', 5658, '2015-04-07 13:48:32', 0, NULL, 'Media_Model_DbTable_Image', 48, NULL, NULL, NULL, '5b10d23e93c0760be3409d6d08348ccfba5ec3fc', 1),
('1311d969b803f360433a4252e501eb8c', 'fffe9c57a9b25623ac219260c1b51550', 'c3\\44328a194463471dce6dacba8001f7.png', 'image/png', 'security[64].png', '', 5084, '2015-04-07 13:11:44', 0, NULL, 'Media_Model_DbTable_Image', 40, NULL, NULL, NULL, 'd8d642774a2b9a308808744185c2536acb8643a5', 1),
('13ed5810386e20dcf0a02bfb197564ba', 'b9e5150917bb20fed386571ccec7ecf6', '3c\\3454a3fc0d4436a15b3adeb390ca6d.png', 'image/png', 'amelioration[64].png', '', 9020, '2015-04-07 13:09:15', 0, NULL, 'Media_Model_DbTable_Image', 35, NULL, NULL, NULL, '65f6fe7ac7d7d39798ace450bc124926aa879a6a', 1),
('1669ea92abfd2b1229c809cc4a528f6d', '6afe3227bdc2e95761be0b5568f72eca', 'e9\\d0a278e51cb1d83e5406be35b9775d.png', 'image/png', 'depannage2[64].png', '', 9332, '2015-04-07 13:47:01', 0, NULL, 'Media_Model_DbTable_Image', 46, NULL, NULL, NULL, 'ba4aef57e7d901da5a2a4fcb61c351e2325068db', 1),
('1dea3864a66ffcaa15af5cb2c4c482b0', 'c5350ecc830d88e2a7f4934547c002d7', 'dd\\33a527c20c13bed4eacf87d8383108.png', 'image/png', 'user[64].png', '', 6996, '2015-04-07 13:47:58', 0, NULL, 'Media_Model_DbTable_Image', 47, NULL, NULL, NULL, '87984ca022074ee8cb4f70e13239397ef12e2b1e', 1),
('2abfd121b2b3074ae4ba914769ee8fbf', '362f9c3293bdb4b41166394c010ad01e', 'd9\\2ce6231d0c0f5f6a4f4823a91f348a.png', 'image/png', 'virtual_machine[64].png', '', 8140, '2015-04-07 13:46:43', 0, NULL, 'Media_Model_DbTable_Image', 45, NULL, NULL, NULL, 'bb584368f65f483052d702639ec8501fe7bbb88a', 1),
('3f6e0df4c354ad27d829b7af7fb516c9', '45559664c234e308d2be72ce19013cad', '7e\\93e72401d314dee88649dd51f60199.png', 'image/png', 'reporting[64].png', '', 8748, '2015-04-07 13:11:27', 0, NULL, 'Media_Model_DbTable_Image', 39, NULL, NULL, NULL, '7b74c928fd7b6f3d9bb4834e8fc2fd85f3169a48', 1),
('412f3013dca7184c8876c8e7092dcd86', '205c2c01f5627dd68cf546a432058b40', '86\\9e11266344dd7030847f96c965e7ca.png', 'image/png', 'admin[64].png', '', 7584, '2015-04-07 13:12:49', 0, NULL, 'Media_Model_DbTable_Image', 43, NULL, NULL, NULL, 'bcbef6d2b31803286ca89dc15c41be4dabb38d46', 1),
('5077346224f7c73bf562c0420951b5ab', 'e0fcac59e989e1bd70aff53e0392e3e3', '53\\3d319fe42419714646abfe31a0e484.png', 'image/png', 'remote_access[64].png', '', 4677, '2015-04-07 13:50:16', 0, NULL, 'Media_Model_DbTable_Image', 50, NULL, NULL, NULL, '87ec3ea0b656a9de7ead03f527c7690dda34b355', 1),
('538844570c7ede460b53639f04a56a73', '88b271dbe239d54ad85a842916cae8ef', 'd8\\7b20732c2ead4cdb19c548f840c81d.png', 'image/png', 'collaboratif[64].png', '', 10083, '2015-04-07 13:10:38', 0, NULL, 'Media_Model_DbTable_Image', 38, NULL, NULL, NULL, 'f8a158f2b836b794094f5ac5741015340fe5538c', 1),
('55140aa540ebe30a95dbd0d8da8a9a90', '3af86c63467277af9df32eafb6c68fbc', '73/b3aca17cab54741d3559f34ff3b257.png', 'image/png', 'logo.png', '', 5891, '2016-09-09 15:20:52', 0, NULL, 'Media_Model_DbTable_Image', 53, NULL, NULL, NULL, 'cc3439dd38ed9f04574a11b6dcc3ad383fd7f5cc', 1),
('6eb147aea2654478b297469098434798', 'b1e3a7b120744b675ef1f6bd9d1e2600', '4d\\e253332057b1f1939a97c28c071170.png', 'image/png', 'reinstall_workstation[64].png', '', 8720, '2015-04-07 13:49:49', 0, NULL, 'Media_Model_DbTable_Image', 49, NULL, NULL, NULL, '9330115e18a0359933bd3c331d89c95289b700b1', 1),
('7ead9b87613378ab41c733ff85f7c6bf', 'ca57090c99a25cb4984897488174389f', '5d\\f61e6a390fb3b49cabcdfa70c39a0d.png', 'image/png', 'bureautique[64].png', '', 5999, '2015-04-07 13:12:27', 0, NULL, 'Media_Model_DbTable_Image', 42, NULL, NULL, NULL, '08780704eddad53d23fe33d8aa869b9cb823cea9', 1),
('8a7eb9d5e669e5433b3b299145f14eac', '9c6ecc34c93889a0e4dbc8a2648b5d11', '86\\6db1ee6f435a41e87d6b29cf16a6b3.png', 'image/png', 'acquisition2[64].png', '', 8058, '2015-04-07 13:51:04', 0, NULL, 'Media_Model_DbTable_Image', 52, NULL, NULL, NULL, 'fa41dbd83eeeddc9acddfc1d91c6a6aecf97fac8', 1),
('a7ee0fc801aae5fafbe678e47afe0420', '4e06986eeb4927c9b71a43136c2c5892', '26\\02d2d82caab097281b5615f866ddd6.png', 'image/png', 'remote_access2[64].png', '', 9298, '2015-04-07 13:12:06', 0, NULL, 'Media_Model_DbTable_Image', 41, NULL, NULL, NULL, '9b610452feceea9fdf3d19d597e01f2abb06a521', 1),
('b270d78a83e5d5c7584ba6e4147e37ec', 'df83e788d53eb4ef20ccf048309ecd73', '7b\\4f0f10b4ce322da4522ec02ea8b982.png', 'image/png', 'help2[64].png', '', 4325, '2015-04-07 13:10:13', 0, NULL, 'Media_Model_DbTable_Image', 37, NULL, NULL, NULL, 'c229222ef2141ab864074b5e3a55f6afad106185', 1),
('b6d2ed8d5a22e584dee51034edb75901', '382d9dc99ea7f5add6fa677ec0965d09', '5a\\d6853c6857aaf8fcf83b1881f3cad3.png', 'image/png', 'demonstration[64].png', '', 5859, '2015-04-07 13:46:13', 0, NULL, 'Media_Model_DbTable_Image', 44, NULL, NULL, NULL, 'd35a5677dd44b20406022b9b908de51c9c4a4172', 1),
('f5b47885b6190b487182d3767702c0a9', '1370472955d83c392925fea4cb50351d', 'b1\\961d7b66c6c21413dbc867eb5bfc33.png', 'image/png', 'new_workstation[64].png', '', 8124, '2015-04-07 13:50:26', 0, NULL, 'Media_Model_DbTable_Image', 51, NULL, NULL, NULL, 'fd471c94e2f7cdd46186dd92f613c2fe4ab6a42d', 1);

-- --------------------------------------------------------

--
-- Structure de la table `media_image`
--

CREATE TABLE IF NOT EXISTS `media_image` (
`id` int(11) unsigned NOT NULL,
  `width` int(11) unsigned NOT NULL,
  `height` int(11) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `media_image`
--

INSERT INTO `media_image` (`id`, `width`, `height`) VALUES
(17, 64, 64),
(18, 64, 64),
(19, 64, 64),
(20, 64, 64),
(21, 64, 54),
(22, 64, 64),
(23, 128, 128),
(24, 64, 64),
(25, 64, 64),
(26, 64, 64),
(27, 64, 54),
(28, 64, 64),
(29, 64, 64),
(30, 64, 64),
(31, 64, 64),
(32, 64, 64),
(33, 64, 64),
(34, 64, 64),
(35, 64, 64),
(36, 64, 64),
(37, 64, 64),
(38, 64, 64),
(39, 64, 54),
(40, 64, 64),
(41, 64, 64),
(42, 64, 64),
(43, 64, 64),
(44, 64, 64),
(45, 64, 64),
(46, 64, 64),
(47, 64, 64),
(48, 64, 64),
(49, 64, 64),
(50, 64, 64),
(51, 64, 64),
(52, 64, 64),
(53, 156, 60);

-- --------------------------------------------------------

--
-- Structure de la table `media_multiupload_ticket`
--

CREATE TABLE IF NOT EXISTS `media_multiupload_ticket` (
`id` int(11) unsigned NOT NULL,
  `ticket` varchar(32) NOT NULL,
  `expire` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `proxy_model_id` int(11) unsigned DEFAULT NULL,
  `proxy_pk` int(11) unsigned DEFAULT NULL,
  `form_class_model_id` int(11) unsigned NOT NULL,
  `element_name` varchar(255) NOT NULL,
  `values` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `media_video`
--

CREATE TABLE IF NOT EXISTS `media_video` (
`id` int(11) unsigned NOT NULL,
  `width` int(11) unsigned NOT NULL,
  `height` int(11) unsigned NOT NULL,
  `duration` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `portal_alert`
--

CREATE TABLE IF NOT EXISTS `portal_alert` (
`id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '0',
  `text` text NOT NULL,
  `is_active` varchar(1) NOT NULL,
  `start` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `stop` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `type` enum('Alert','Information','Notification') NOT NULL DEFAULT 'Information',
  `priority` enum('1','2','3','4','5','6','7','8','9') DEFAULT '5' COMMENT '1 : very high, 9 : very low'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `portal_alert`
--

INSERT INTO `portal_alert` (`id`, `name`, `text`, `is_active`, `start`, `stop`, `type`, `priority`) VALUES
(1, 'Welcome', '<p>Hello, this message is viewed by <span style="text-decoration: underline;">everyone</span>. It is an information.</p>', '1', '2016-06-22 07:25:00', '2016-06-27 14:25:00', 'Information', '5'),
(2, 'Patch R-012345', '<p>A<span style="color: #ff0000;"> new version</span>&nbsp;is available. Please give us your feedback !</p>', '1', '2016-06-22 15:57:00', '2016-08-21 22:00:00', 'Alert', '3'),
(3, 'Test', '<p>This is a single notification. This is the lower priority in the alerts.<strong> Be careful</strong>, only one line is readable.</p>', '1', '2016-06-24 12:31:00', '2016-07-24 12:31:00', 'Notification', '5');

-- --------------------------------------------------------

--
-- Structure de la table `portal_alert_organization`
--

CREATE TABLE IF NOT EXISTS `portal_alert_organization` (
  `alert_id` int(11) NOT NULL,
  `organization_id` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `portal_alert_organization`
--

INSERT INTO `portal_alert_organization` (`alert_id`, `organization_id`) VALUES
(1, 1),
(2, 1),
(3, 1);

-- --------------------------------------------------------

--
-- Structure de la table `portal_chat_questions`
--

CREATE TABLE IF NOT EXISTS `portal_chat_questions` (
`id` int(10) unsigned NOT NULL,
  `message` varchar(255) NOT NULL,
  `sessId` varchar(100) NOT NULL DEFAULT '',
  `user` varchar(20) NOT NULL,
  `org_id` int(11) NOT NULL,
  `organization` varchar(40) NOT NULL,
  `role` varchar(6) NOT NULL DEFAULT ''
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Contenu de la table `portal_chat_questions`
--

INSERT INTO `portal_chat_questions` (`id`, `message`, `sessId`, `user`, `org_id`, `organization`, `role`) VALUES
(3, 'Ceci est un test', 'k9iit62vnoreaaeu715am42126', 'Manu (admin)', 1, 'My Company/Department', 'client');

-- --------------------------------------------------------

--
-- Structure de la table `portal_itop_user`
--

CREATE TABLE IF NOT EXISTS `portal_itop_user` (
`id` int(11) unsigned NOT NULL,
  `login` varchar(255) NOT NULL,
  `itop_id` int(11) NOT NULL,
  `first_name` varchar(30) DEFAULT NULL,
  `last_name` varchar(30) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `is_local` int(1) unsigned NOT NULL DEFAULT '0',
  `group_id` int(11) DEFAULT NULL,
  `org_id` varchar(255) NOT NULL,
  `org_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table permettant l''import des User de iTop ';

-- --------------------------------------------------------

--
-- Structure de la table `portal_ldap_user`
--

CREATE TABLE IF NOT EXISTS `portal_ldap_user` (
`id` int(11) unsigned NOT NULL,
  `sn` varchar(255) NOT NULL,
  `first_name` varchar(30) DEFAULT NULL,
  `last_name` varchar(30) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `email2` varchar(255) DEFAULT NULL,
  `is_local` int(1) unsigned NOT NULL DEFAULT '0',
  `group_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table permettant l''import des User de Ldap';

-- --------------------------------------------------------

--
-- Structure de la table `portal_organization`
--

CREATE TABLE IF NOT EXISTS `portal_organization` (
  `id` int(11) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `phonecode` int(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Liste des organisations';

--
-- Contenu de la table `portal_organization`
--

INSERT INTO `portal_organization` (`id`, `name`, `created_at`, `phonecode`) VALUES
(1, 'My Company/Department', '2016-08-11 07:40:08', NULL),
(2, 'IT Department', '2016-09-27 08:11:48', NULL),
(3, 'Demo', '2016-09-27 08:11:48', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `portal_service_config`
--

CREATE TABLE IF NOT EXISTS `portal_service_config` (
`id` int(11) NOT NULL COMMENT 'identifiant',
  `name` varchar(50) NOT NULL COMMENT 'identifiant utilisateur',
  `title` varchar(80) NOT NULL COMMENT 'Titre',
  `subtitle` varchar(160) NOT NULL COMMENT 'Sous Titre',
  `description` varchar(250) NOT NULL COMMENT 'Description',
  `link_module` varchar(50) NOT NULL COMMENT 'module destination',
  `link_controller` varchar(50) NOT NULL COMMENT 'controleur destination',
  `link_action` varchar(50) NOT NULL COMMENT 'Action destination',
  `created_at` date NOT NULL COMMENT 'Date de création',
  `updated_at` date NOT NULL COMMENT 'Date de modification',
  `is_active` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'Actif (0 False, 1 True)'
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='Configuration Homepage';

--
-- Contenu de la table `portal_service_config`
--

INSERT INTO `portal_service_config` (`id`, `name`, `title`, `subtitle`, `description`, `link_module`, `link_controller`, `link_action`, `created_at`, `updated_at`, `is_active`) VALUES
(1, 'atelys', 'Atelys', 'Assister', 'L''assistance technique est à votre écoute 24h/24.', 'atelys', 'index', 'index', '2014-07-08', '2014-07-08', 1),
(2, 'admys', 'Admys', 'Détecter', 'Administration active et pro-active de vos systèmes.', 'admys', 'index', 'index', '2014-07-08', '2014-07-08', 1),
(3, 'movys', 'Movys', 'Évoluer', 'Votre solution informatique évolue à votre demande.', 'movys', 'index', 'index', '2014-07-08', '2014-07-08', 1),
(4, 'skolys', 'Skolys', 'Former', 'Des formations standards et personnalisées vous sont proposées.', 'skolys', 'index', 'index', '2014-07-08', '2014-07-08', 1),
(5, 'prevys', 'Prevys', 'Dépanner', 'Lot de maintenance, réparation, remplacement ...', 'prevys', 'index', 'index', '2014-07-08', '2014-07-08', 1),
(6, 'migsys', 'Migsys', 'Migrer', 'Migration des données de votre ancien système verts votre nouvelle solution', 'migsys', 'index', 'index', '2014-07-08', '2014-07-08', 0),
(7, 'ulys', 'Ulys', 'Syleps', 'Catalogue de corrections et évolutions.', 'ulys', 'index', 'index', '2014-07-08', '2014-07-08', 1),
(8, 'store', 'Magasin en ligne', 'Syleps', 'Commander vos pièces et fournitures en ligne.', 'store', 'index', 'product', '2014-07-08', '2014-07-08', 1);

-- --------------------------------------------------------

--
-- Structure de la table `portal_service_style`
--

CREATE TABLE IF NOT EXISTS `portal_service_style` (
  `id` int(11) unsigned NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` varchar(250) DEFAULT NULL,
  `code` varchar(50) DEFAULT NULL,
  `color` varchar(100) DEFAULT '#1C94C4',
  `type` enum('Service','ServiceSubcategory') NOT NULL DEFAULT 'Service',
  `parent_id` int(11) DEFAULT NULL,
  `parent_name` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `avatar_id` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `portal_service_style`
--

INSERT INTO `portal_service_style` (`id`, `name`, `description`, `code`, `color`, `type`, `parent_id`, `parent_name`, `created_at`, `updated_at`, `avatar_id`) VALUES
(1, 'Computers and peripherals', 'Ordering of new hardware (Desktop computer, laptop computer, monitor, mouse, keyboard...) and support in case of hardware failure.', 'Service1', '#1C94C4', 'Service', 9, 'INFRA', '2015-04-07 13:08:45', '2016-09-09 15:50:35', '6eb147aea2654478b297469098434798'),
(2, 'AMÉLIORATIONS FONCTIONNELLES', 'Améliorations des logiciels Métiers', 'Service2', '#1C94C4', 'Service', 5, 'METIER', '2015-04-07 13:08:45', '2015-04-07 13:09:16', '13ed5810386e20dcf0a02bfb197564ba'),
(3, 'Telecom and connectivity', 'Ordering and configuration of new mobile phones, computer connectivity requests, cabling, etc...', 'Service3', '#1C94C4', 'Service', 9, 'INFRA', '2015-04-07 13:08:45', '2015-04-07 13:50:16', '5077346224f7c73bf562c0420951b5ab'),
(4, 'Network Troubleshooting', 'Ask for help troubleshooting a network related issue.', 'ServiceSubcategory4', '#1C94C4', 'ServiceSubcategory', NULL, NULL, '2015-04-07 13:08:49', '2015-04-07 13:08:49', NULL),
(5, 'New desktop ordering', 'Order a new desktop computer, for a new employee or for replacing an old system.', 'ServiceSubcategory5', '#1C94C4', 'ServiceSubcategory', NULL, NULL, '2015-04-07 13:08:49', '2015-04-07 13:50:26', 'f5b47885b6190b487182d3767702c0a9'),
(6, 'New DNS name', 'Request a new DNS name for a fixed system (Desktop computer or server).', 'ServiceSubcategory6', '#1C94C4', 'ServiceSubcategory', NULL, NULL, '2015-04-07 13:08:49', '2015-04-07 13:08:49', NULL),
(7, 'New IP address', 'Request a new IP address for a fixed system (Desktop computer or server)', 'ServiceSubcategory7', '#1C94C4', 'ServiceSubcategory', NULL, NULL, '2015-04-07 13:08:49', '2015-04-07 13:08:49', NULL),
(8, 'SI', 'Opération sur l''infrastructure SI', 'Service8', '#1C94C4', 'Service', 9, 'INFRA', '2015-04-07 13:08:45', '2015-04-07 13:09:34', '0dd9cf3561a896e3b59533756f31bc30'),
(10, 'GARANTIE', 'Maintenance en période de garantie', 'Service10', '#1C94C4', 'Service', 4, 'Maintenance', '2015-04-07 13:08:45', '2015-04-07 13:08:45', NULL),
(11, 'HORS CONTRAT', 'Maintenance hors contrat', 'Service11', '#1C94C4', 'Service', 4, 'Maintenance', '2015-04-07 13:08:45', '2015-04-07 13:08:45', NULL),
(12, 'MAINTENANCE HARDWARE', '', 'Service12', '#1C94C4', 'Service', 4, 'Maintenance', '2015-04-07 13:08:45', '2015-04-07 13:08:45', NULL),
(13, 'ADMINISTRATION', 'Administration des logiciels Métiers', 'Service13', '#1C94C4', 'Service', 5, 'METIER', '2015-04-07 13:08:45', '2015-04-07 13:12:50', '412f3013dca7184c8876c8e7092dcd86'),
(14, 'ASSISTANCE AUX UTILISATEURS', 'Nouvel incident', 'Service14', '#1C94C4', 'Service', 5, 'METIER', '2015-04-07 13:08:45', '2015-04-07 13:10:13', 'b270d78a83e5d5c7584ba6e4147e37ec'),
(15, 'REPORTING', 'Reporting', 'Service15', '#1C94C4', 'Service', 5, 'METIER', '2015-04-07 13:08:45', '2015-04-07 13:11:27', '3f6e0df4c354ad27d829b7af7fb516c9'),
(16, 'Troubleshooting', 'Ask for help troubleshooting a hardware issue.', 'ServiceSubcategory16', '#1C94C4', 'ServiceSubcategory', NULL, NULL, '2015-04-07 13:08:49', '2015-04-07 13:08:49', NULL),
(17, 'Maintenance Technocentre', 'Service relatif à la bonne marche du Technocentre.', 'Service17', '#1C94C4', 'Service', 7, 'Technocentre', '2015-04-07 13:08:45', '2015-04-07 13:08:45', NULL),
(18, 'ACCES DISTANT', 'Connexions à distance', 'Service18', '#1C94C4', 'Service', 8, 'THEMA', '2015-04-07 13:08:44', '2015-04-07 13:12:06', 'a7ee0fc801aae5fafbe678e47afe0420'),
(19, 'ACQUISITION', 'Demander des Matériels/Logiciels', 'Service19', '#1C94C4', 'Service', 8, 'THEMA', '2015-04-07 13:08:45', '2015-04-07 13:51:04', '8a7eb9d5e669e5433b3b299145f14eac'),
(20, 'BUREAUTIQUE', 'Poste de travail', 'Service20', '#1C94C4', 'Service', 8, 'THEMA', '2015-04-07 13:08:45', '2015-04-07 13:12:27', '7ead9b87613378ab41c733ff85f7c6bf'),
(21, 'COLLABORATIF', 'Outils collaboratifs', 'Service21', '#1C94C4', 'Service', 8, 'THEMA', '2015-04-07 13:08:45', '2015-04-07 13:10:46', '538844570c7ede460b53639f04a56a73'),
(22, 'DEMONSTRATION CLIENT', 'Démonstrations clients', 'Service22', '#1C94C4', 'Service', 8, 'THEMA', '2015-04-07 13:08:45', '2015-04-07 13:46:13', 'b6d2ed8d5a22e584dee51034edb75901'),
(23, 'DEPANNAGE MATERIEL', 'Dépannage matériel, PC portable & Desktop', 'Service23', '#1C94C4', 'Service', 8, 'THEMA', '2015-04-07 13:08:45', '2015-04-07 13:47:01', '1669ea92abfd2b1229c809cc4a528f6d'),
(24, 'MACHINE VIRTUELLE', 'Machine Virtuelle (VM)', 'Service24', '#1C94C4', 'Service', 8, 'THEMA', '2015-04-07 13:08:45', '2015-04-07 13:46:43', '2abfd121b2b3074ae4ba914769ee8fbf'),
(25, 'SAUVEGARDE-RESTITUTION', 'Sauvegardes/restitutions de vos données', 'Service25', '#1C94C4', 'Service', 8, 'THEMA', '2015-04-07 13:08:45', '2015-04-07 13:48:32', '11366388bdf366a971af63d013f639b1'),
(26, 'SECURITE', 'Sécurité du SI', 'Service26', '#1C94C4', 'Service', 8, 'THEMA', '2015-04-07 13:08:45', '2015-04-07 13:11:45', '1311d969b803f360433a4252e501eb8c'),
(27, 'UTILISATEUR', 'Comptes d''utilisateurs', 'Service27', '#1C94C4', 'Service', 8, 'THEMA', '2015-04-07 13:08:45', '2015-04-07 13:47:58', '1dea3864a66ffcaa15af5cb2c4c482b0'),
(28, 'Demande création Machine Virtuelle (VM)', 'Création VM à partir d''un hyperviseur VMWARE, PowerVM, ORACLE VM', 'ServiceSubcategory28', '#1C94C4', 'ServiceSubcategory', NULL, NULL, '2015-04-07 13:08:49', '2015-04-07 13:08:49', NULL),
(29, 'Demande création service supplémentaire distant', 'Mise en oeuvre d''un service par IPDIVA', 'ServiceSubcategory29', '#1C94C4', 'ServiceSubcategory', NULL, NULL, '2015-04-07 13:08:49', '2015-04-07 13:08:49', NULL),
(30, 'Demande d''acquisition matériel/logiciel informatiq', 'Demande d''acquisition de matériels ou de logiciels à l''unité', 'ServiceSubcategory30', '#1C94C4', 'ServiceSubcategory', NULL, NULL, '2015-04-07 13:08:49', '2015-04-07 13:08:49', NULL),
(31, 'Demande d''assistance', 'Le service d''assistance, ou support aux utilisateurs, consiste à garantir que les utilisateurs d''un système puissent continuer à profiter de sa disponibilité pour l''accomplissement de leurs tâches. L''assistance peut porter sur les applications ou sur', 'ServiceSubcategory31', '#1C94C4', 'ServiceSubcategory', NULL, NULL, '2015-04-07 13:08:49', '2015-04-07 13:08:49', NULL),
(32, 'Demande dépannage matériel', 'Dépannage des matériels sous garantie et hors garantie', 'ServiceSubcategory32', '#1C94C4', 'ServiceSubcategory', NULL, NULL, '2015-04-07 13:08:49', '2015-04-07 13:08:49', NULL),
(33, 'Demande espace de création et partage de fichiers ', 'Ouverture d''un espace de partage SAAS ACROBAT ou Visioconnect', 'ServiceSubcategory33', '#1C94C4', 'ServiceSubcategory', NULL, NULL, '2015-04-07 13:08:49', '2015-04-07 13:08:49', NULL),
(34, 'Demande fourniture accès Internet à domicile', 'Installation d''une ORANGE LIVEBOX au domicile', 'ServiceSubcategory34', '#1C94C4', 'ServiceSubcategory', NULL, NULL, '2015-04-07 13:08:49', '2015-04-07 13:08:49', NULL),
(35, 'Demande installation complète poste de travail', 'Installation OS poste de travail et outils suivant profil de l''utilisateur', 'ServiceSubcategory35', '#1C94C4', 'ServiceSubcategory', NULL, NULL, '2015-04-07 13:08:49', '2015-04-07 13:08:49', NULL),
(36, 'Demande installation ou mise à jour outil sur post', 'Installation ou mise à jour d''un logiciels, pilote de périphériques, etc.', 'ServiceSubcategory36', '#1C94C4', 'ServiceSubcategory', NULL, NULL, '2015-04-07 13:08:49', '2015-04-07 13:08:49', NULL),
(37, 'Demande installation PC DEMO PHENYX', 'Installation PC portable pour DEMO PHENYX à un client', 'ServiceSubcategory37', '#1C94C4', 'ServiceSubcategory', NULL, NULL, '2015-04-07 13:08:49', '2015-04-07 13:08:49', NULL),
(38, 'Demande installation VPN fourni par un client', 'Demande installation VPN fourni par un client', 'ServiceSubcategory38', '#1C94C4', 'ServiceSubcategory', NULL, NULL, '2015-04-07 13:08:49', '2015-04-07 13:08:49', NULL),
(39, 'Demande lecteur réseau supplémentaire', 'Demande lecteur réseau supplémentaire par un utilisateur', 'ServiceSubcategory39', '#1C94C4', 'ServiceSubcategory', NULL, NULL, '2015-04-07 13:08:49', '2015-04-07 13:08:49', NULL),
(40, 'Demande mise à jour Keypass', 'Demande de modification BdD KEYPASS SYLEPS', 'ServiceSubcategory40', '#1C94C4', 'ServiceSubcategory', NULL, NULL, '2015-04-07 13:08:49', '2015-04-07 13:08:49', NULL),
(41, 'Demande opération administration', 'Installation, mises à jour, paramétrages, restitutions, gestion des problèmes, conseil', 'ServiceSubcategory41', '#1C94C4', 'ServiceSubcategory', NULL, NULL, '2015-04-07 13:08:49', '2015-04-07 13:08:49', NULL),
(42, 'Demande opération de maintenance Technocentre', 'Service permettant la disponibilité fonctionnelle du Technocentre Syleps', 'ServiceSubcategory42', '#1C94C4', 'ServiceSubcategory', NULL, NULL, '2015-04-07 13:08:49', '2015-04-07 13:08:49', NULL),
(43, 'Demande reconditionnement poste de travail', 'Installation système d''exploitation, logiciels, pilotes de périphériques, etc. sur un ancien poste de travail', 'ServiceSubcategory43', '#1C94C4', 'ServiceSubcategory', NULL, NULL, '2015-04-07 13:08:49', '2015-04-07 13:08:49', NULL),
(44, 'Demande reporting supplémentaire', 'Construction de rapport à la demande des utilisateurs', 'ServiceSubcategory44', '#1C94C4', 'ServiceSubcategory', NULL, NULL, '2015-04-07 13:08:49', '2015-04-07 13:08:49', NULL),
(45, 'Demande restitution répertoire, fichier', 'Restitution fichiers, répertoires à la demande des utilisateurs', 'ServiceSubcategory45', '#1C94C4', 'ServiceSubcategory', NULL, NULL, '2015-04-07 13:08:49', '2015-04-07 13:08:49', NULL),
(46, 'Demande sauvegarde supplémentaire', 'Définition de la politique de sauvegarde générale, sauvegardes à la demande', 'ServiceSubcategory46', '#1C94C4', 'ServiceSubcategory', NULL, NULL, '2015-04-07 13:08:49', '2015-04-07 13:08:49', NULL),
(47, 'Demande test validation Visioconférence', 'Demande de tests sur la visioconférence pour valider une connexion avec une autre visioconférence (client, fournisseur, etc.)', 'ServiceSubcategory47', '#1C94C4', 'ServiceSubcategory', NULL, NULL, '2015-04-07 13:08:49', '2015-04-07 13:08:49', NULL),
(49, 'Prêt de matériel', 'Prêt à la demande sur une période déterminée d''un matériel (ex:Poste de travail).', 'ServiceSubcategory49', '#1C94C4', 'ServiceSubcategory', NULL, NULL, '2015-04-07 13:08:49', '2015-04-07 13:08:49', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `portal_user_preference`
--

CREATE TABLE IF NOT EXISTS `portal_user_preference` (
`id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `preference_name` varchar(50) NOT NULL,
  `preference_value` varchar(250) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `portal_user_preference`
--

INSERT INTO `portal_user_preference` (`id`, `user_id`, `preference_name`, `preference_value`, `created_at`, `updated_at`) VALUES
(4, 1, 'USER_LANGUAGE', 'en', '2016-08-09 15:28:12', '2016-08-11 09:54:10'),
(1, 12, 'USER_FILTER', 'false', '2015-03-26 11:12:25', '2015-03-26 11:12:37'),
(5, 12, 'USER_LANGUAGE', 'en', '2016-08-11 11:46:31', '2016-08-11 11:46:31'),
(3, 18, 'HOME_SERVICES', '14,18,20,13,2|19,21,22,23', '2015-03-28 14:35:27', '2015-04-02 12:38:26'),
(2, 18, 'USER_FILTER', 'false', '2015-03-28 14:35:15', '2015-04-01 15:56:46');

-- --------------------------------------------------------

--
-- Structure de la table `portal_widget`
--

CREATE TABLE IF NOT EXISTS `portal_widget` (
  `id` varchar(50) NOT NULL,
  `dashboard` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text,
  `source` varchar(500) DEFAULT NULL,
  `parameter` text,
  `type` enum('Text','Pie','Table','Flatpage','PhoneCode','StackedBar') NOT NULL DEFAULT 'Text',
  `is_active` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'Actif (0 False, 1 True)',
  `order` int(11) DEFAULT NULL,
  `size` enum('simple','double') NOT NULL DEFAULT 'simple'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `portal_widget`
--

INSERT INTO `portal_widget` (`id`, `dashboard`, `name`, `description`, `source`, `parameter`, `type`, `is_active`, `order`, `size`) VALUES
('1', 'HOME_DASHBOARD', 'Tickets per status', 'On cumule les tickets par statut en excluant les tickets clos.', 'getCountRequest', '{"count_attribute":"status","where":"status !=''closed''"}', 'Pie', 1, 1, 'simple'),
('2', 'HOME_DASHBOARD', 'Opened request', 'Liste des tickets ouverts', 'getListOpenedRequest', '[{"field":"ref","label":"User Request"},{"field":"title","label":"Title"},{"field":"start_date","label":"Started"},{"field":"status","label":"Status"}]', 'Table', 1, 0, 'double'),
('3', 'HOME_DASHBOARD', 'Informations', 'Simple texte d''informations.', NULL, 'Ceci est un exemple de texte. On peut mettre le texte en valeur, les balises Html fonctionnent ! \r\nUne alternative au Flatpage interne au site, cf. partie Admisnistration !\r\n', 'Text', 1, 4, 'simple'),
('4', 'HOME_DASHBOARD', 'Other informations', 'Texte informatif issu d''une page élaborée à partir de la partie administrative du site.', NULL, '5', 'Flatpage', 1, 3, 'simple'),
('6', 'HOME_DASHBOARD', 'Tickets par mois', 'Nombre de tickets sur les 12 mois écoulés', NULL, 'new,assigned,qualified,pending,resolved,closed', 'StackedBar', 0, 6, 'simple');

-- --------------------------------------------------------

--
-- Structure de la table `translation_language`
--

CREATE TABLE IF NOT EXISTS `translation_language` (
`id` int(10) unsigned NOT NULL,
  `locale` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `flag` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `translation_language`
--

INSERT INTO `translation_language` (`id`, `locale`, `name`, `flag`) VALUES
(1, 'fr', 'Français', '/layouts/backoffice/images/flags/fr.png'),
(2, 'en', 'English', '/layouts/backoffice/images/flags/uk.png');

-- --------------------------------------------------------

--
-- Structure de la table `translation_tag`
--

CREATE TABLE IF NOT EXISTS `translation_tag` (
`id` int(11) unsigned NOT NULL,
  `tag` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `translation_tag`
--

INSERT INTO `translation_tag` (`id`, `tag`) VALUES
(1, 'backoffice'),
(2, 'cms'),
(3, 'vinsoc'),
(4, 'hostname');

-- --------------------------------------------------------

--
-- Structure de la table `translation_tag_uid`
--

CREATE TABLE IF NOT EXISTS `translation_tag_uid` (
  `uid_id` int(11) unsigned NOT NULL,
  `tag_id` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `translation_tag_uid`
--

INSERT INTO `translation_tag_uid` (`uid_id`, `tag_id`) VALUES
(2, 1),
(3, 1),
(4, 1),
(6, 1),
(7, 1),
(18, 1),
(33, 1),
(34, 1),
(39, 1),
(52, 1),
(53, 1),
(101, 1),
(102, 1),
(103, 1),
(104, 1),
(120, 1),
(121, 1),
(39, 2),
(52, 2),
(53, 2),
(103, 2),
(104, 2),
(120, 2),
(121, 2),
(18, 3),
(101, 3),
(102, 3),
(186, 4);

-- --------------------------------------------------------

--
-- Structure de la table `translation_translation`
--

CREATE TABLE IF NOT EXISTS `translation_translation` (
  `translation` text COLLATE utf8_bin NOT NULL,
  `uid_id` int(11) unsigned NOT NULL,
  `language_id` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Contenu de la table `translation_translation`
--

INSERT INTO `translation_translation` (`translation`, `uid_id`, `language_id`) VALUES
('Retour', 1, 1),
('Back', 1, 2),
('Nom d''utilisateur', 2, 1),
('Username', 2, 2),
('Mot de passe', 3, 1),
('Password', 3, 2),
('Se souvenir de moi', 4, 1),
('Remember me', 4, 2),
('Connexion', 5, 1),
('Log in', 5, 2),
('Déconnexion', 6, 1),
('Log out', 6, 2),
('Gestion de la navigation', 8, 1),
('Manage navigation', 8, 2),
('Langue', 10, 1),
('Language', 10, 2),
('ajouter un nouvel %s', 11, 1),
('Add a new %s', 11, 2),
('Titre', 12, 1),
('Title', 12, 2),
('publié', 13, 1),
('Is published', 13, 2),
('A', 14, 1),
('At', 14, 2),
('Éditer', 18, 1),
('Edit', 18, 2),
('Éditer les propriétés', 19, 1),
('Edit properties', 19, 2),
('Êtes vous sûr ? L''opération ne pourra pas être annulée', 20, 1),
('Are you sure? This operation can not be undone', 20, 2),
('Effacer', 21, 1),
('Delete', 21, 2),
('Nom du module', 23, 1),
('Module name', 23, 2),
('Nom du contrôleur', 24, 1),
('Controller name', 24, 2),
('Nom de l''action', 25, 1),
('Action name', 25, 2),
('Enregistrer', 33, 1),
('Save', 33, 2),
('Enregistrer et ajouter un autre', 34, 1),
('Save and add another', 34, 2),
('Enregistrer et continuer', 35, 1),
('Save and continue', 35, 2),
('Gérer les traductions', 36, 1),
('Manage translation', 36, 2),
('Gérer les permissions', 37, 1),
('Manage permissions', 37, 2),
('Nom', 39, 1),
('Name', 39, 2),
('Utilisateurs', 41, 1),
('Users', 41, 2),
('Groupes', 42, 1),
('Groups', 42, 2),
('Filtres', 43, 1),
('Filters', 43, 2),
('Soumettre', 44, 1),
('Submit', 44, 2),
('Suivant', 49, 1),
('Next', 49, 2),
('Dernier', 50, 1),
('Last', 50, 2),
('Enregistrement effectué', 51, 1),
('Saving has been done.', 51, 2),
('Contenu', 57, 1),
('Content', 57, 2),
('Couverture', 58, 1),
('Cover', 58, 2),
('Date de publication', 61, 1),
('Date to publish', 61, 2),
('Ordre', 62, 1),
('Order', 62, 2),
('Dernière connexion', 66, 1),
('Last login', 66, 2),
('Est actif', 67, 1),
('Is active', 67, 2),
('Statut', 70, 1),
('Status', 70, 2),
('Oui', 71, 1),
('Yes', 71, 2),
('Non', 72, 1),
('No', 72, 2),
('Activer', 73, 1),
('Activate', 73, 2),
('Désactiver', 74, 1),
('Desactivate', 74, 2),
('Gestion des utilisateurs', 75, 1),
('Manage users', 75, 2),
('Peut être effacer', 78, 1),
('Can be deleted', 78, 2),
('Membre de l''équipe', 79, 1),
('Is staff', 79, 2),
('En tant qu''utilisateur %s, vous n''êtes pas autorisé à effectuer cette action.', 87, 1),
('As a %s user, you don''t have the permission to accomplish this action.', 87, 2),
('Retour', 88, 1),
('Go back', 88, 2),
('Surnom', 89, 1),
('Nickname', 89, 2),
('Utilisateur', 91, 1),
('user', 91, 2),
('Ticket invalide', 93, 1),
('Invalid Ticket', 93, 2),
('Premier', 117, 1),
('First', 117, 2),
('Précédent', 118, 1),
('Previous', 118, 2),
('Gestion des groupes', 124, 1),
('Manage groups', 124, 2),
('Détails de la requête', 130, 1),
('Details for Request', 130, 2),
('Tableau de bord', 147, 1),
('Dashboard', 147, 2),
('Accueil', 150, 1),
('Home', 150, 2),
('Catalogue des services', 151, 1),
('Service Management', 151, 2),
('Calendrier', 154, 1),
('Calendar', 154, 2),
('Nouveau ticket', 159, 1),
('New Request', 159, 2),
('Accueil', 160, 1),
('Home', 160, 2),
('Ticket en cours', 161, 1),
('Current Request', 161, 2),
('CompteurTemps', 167, 1),
('Timeline', 167, 2),
('Tickets Fermés', 169, 1),
('Closed Request', 169, 2),
('Produits', 172, 1),
('Products', 172, 2),
('Panier', 176, 1),
('Cart', 176, 2),
('Ticket Incidents', 177, 1),
('Incident Request', 177, 2),
('Liste des Chat', 182, 1),
('Chat list', 182, 2),
('Nouveau Chat', 183, 1),
('New chat', 183, 2),
('Nouvelle version S.U.', 197, 1),
('New S.U. version', 197, 2),
('Bienvenue', 201, 1),
('Welcome', 201, 2),
('de', 202, 1),
('from', 202, 2),
('Bienvenue', 208, 1),
('Welcome', 208, 2),
('priorité', 263, 1),
('priority', 263, 2),
('Priorité', 267, 1),
('Priority', 267, 2),
('Type de Requête', 268, 1),
('Request Type', 268, 2),
('Demande Utilisateur', 278, 1),
('User Request', 278, 2),
('Gestion des incidents', 280, 1),
('Incident Management', 280, 2),
('Tableau de bord global', 379, 1),
('Main Dashboard', 379, 2),
('Ne visualiser que vos tickets', 380, 1),
('Display only your own requests', 380, 2),
('nouveau', 424, 1),
('new', 424, 2),
('indéfini', 425, 1),
('undefined', 425, 2),
('Demande de service', 426, 1),
('Service Request', 426, 2),
('Début', 427, 1),
('Started', 427, 2),
('Nom du fichier', 458, 1),
('File name', 458, 2),
('Gérer les Médias', 459, 1),
('Manage Media', 459, 2),
('Nom', 461, 1),
('First Name', 461, 2),
('Prénom', 462, 1),
('Last Name', 462, 2),
('Actif', 463, 1),
('Activ', 463, 2),
('Générer', 471, 1),
('Generate', 471, 2),
('Importer', 472, 1),
('Import', 472, 2),
('Importer', 478, 1),
('Import', 478, 2),
('utilisateurs', 482, 1),
('users', 482, 2),
('Oui', 483, 1),
('Yes', 483, 2),
('Non', 484, 1),
('No', 484, 2),
('Date de dernière exécution', 494, 1),
('Last execution Date', 494, 2),
('Tâche', 496, 1),
('Task', 496, 2),
('Fournisseur', 498, 1),
('Provider', 498, 2),
('Contrats Fournisseur', 499, 1),
('Provider Contract', 499, 2),
('Sous-titre', 500, 1),
('Subtitle', 500, 2),
('Configurations', 505, 1),
('Settings', 505, 2),
('Traduction', 511, 1),
('Translation', 511, 2),
('Votre espace Services', 519, 1),
('Your Syleps Area', 519, 2),
('Tableaux de bord', 523, 1),
('Dashboard', 523, 2),
('Nouvelle requête utilisateur.', 524, 1),
('New user request', 524, 2),
('iTop - Déclarer un incident', 525, 1),
('iTop - Declare an incident', 525, 2),
('Requêtes Utilisateur', 526, 1),
('User''s Requests', 526, 2),
('fermé', 546, 1),
('closed', 546, 2),
('Catalogue de Services.', 548, 1),
('Services Catalog', 548, 2),
('Consulter la liste des incidents', 550, 1),
('View the Incidents List', 550, 2),
('Nouvelle déclaration d''incident', 551, 1),
('New incident', 551, 2),
('Créer', 555, 1),
('Create', 555, 2),
('Organisations', 558, 1),
('Organizations', 558, 2),
('Votre site en un coup d''oeil', 559, 1),
('Your site at a glance', 559, 2),
('Salut %s, quoi de neuf ?', 560, 1),
('Hey %s, what’s up?', 560, 2),
('Gérer les alertes', 565, 1),
('Manage Alerts', 565, 2),
('Alerte', 566, 1),
('Alert', 566, 2),
('Modifier Alerte', 567, 1),
('Edit Alert', 567, 2),
('Alertes', 568, 1),
('Alerts', 568, 2),
('Tableau de bord', 570, 1),
('Dashboard', 570, 2),
('Compte iTop déclaré', 574, 1),
('iTop''s account available', 574, 2),
('Pas de compte iTop déclaré', 575, 1),
('No iTop account available', 575, 2),
('interaction sur les tickets possible', 576, 1),
('Interaction with request possible', 576, 2),
('interaction sur les tickets impossible', 577, 1),
('Interaction on request not possible', 577, 2),
('Réglages de vos préférences', 578, 1),
('Manage your settings', 578, 2),
('Régler ici vos préférences comme la langue ou les sites auquels vous souhaitez vous rattacher.', 579, 1),
('Configure here your settings like the language or the sites which you want to consult.', 579, 2),
('Langue', 580, 1),
('Language', 580, 2),
('Changer de langue pour :', 581, 1),
('Change the language for:', 581, 2),
('Mot de passe valide.', 582, 1),
('Password is correct.', 582, 2),
('Mot de passe invalide.', 583, 1),
('Password is not correct.', 583, 2),
('Le mot de passe doit faire au moins 5 caractères.', 584, 1),
('The password must have 5 caracters minimum.', 584, 2),
('Le mot de passe a été modifié.', 585, 1),
('The password has been changed.', 585, 2),
('Environnement :', 587, 1),
('Environment :', 587, 2),
('Version du Portail', 588, 1),
('Portal''s Version', 588, 2),
('Requête ouverte', 590, 1),
('Opened request', 590, 2),
('Tickets par statut', 591, 1),
('Tickets per status', 591, 2),
('Autre informations', 592, 1),
('Other informations', 592, 2),
('Listes des Services', 593, 1),
('Services Lists', 593, 2),
('Veuillez renseigner les informations ci-dessous :', 594, 1),
('Please fill the informations below:', 594, 2),
('Contrats Fournisseurs', 595, 1),
('Provider Contracts', 595, 2),
('Gestion des incidents', 598, 1),
('Requests Managment', 598, 2),
('Utilisateur', 599, 1),
('User', 599, 2),
('Organisation', 600, 1),
('Organization', 600, 2),
('Ticket par code résolution', 601, 1),
('Ticket per resolution code', 601, 2),
('Code résolution', 602, 1),
('Resolution code', 602, 2),
('Nbre de tickets', 603, 1),
('Nb Requests', 603, 2),
('Ticket par Service', 604, 1),
('Ticket per Service', 604, 2),
('Répartition des tickets selon leur code de résolution', 605, 1),
('Distribution of tickets according to their way of resolution', 605, 2),
('Les incidents ouverts sur l''année écoulée', 606, 1),
('The open incidents over the past year', 606, 2),
('<h1>Visualisation en mode édition</h1><p>Utiliser les fonction d\\''édition du navigateur. Presser \\''Echappe\\'' pour revenir à la page</p>', 632, 1),
('<h1>Display in Print Mode</h1><p>Use your browser\\''s printing function. Press \\''Escape\\'' to go back</p>', 632, 2);

-- --------------------------------------------------------

--
-- Structure de la table `translation_uid`
--

CREATE TABLE IF NOT EXISTS `translation_uid` (
`id` int(11) unsigned NOT NULL,
  `uid` text COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=667 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Contenu de la table `translation_uid`
--

INSERT INTO `translation_uid` (`id`, `uid`) VALUES
(1, 'back'),
(2, 'Username'),
(3, 'Password'),
(4, 'Remember me'),
(5, 'Log in'),
(6, 'Log out'),
(7, 'View website'),
(8, 'Manage navigation'),
(9, 'navigation'),
(10, 'Language'),
(11, 'Add a new %s'),
(12, 'Title'),
(13, 'Is published'),
(14, 'At'),
(15, 'Actions'),
(16, 'Offline'),
(17, 'Online'),
(18, 'Edit'),
(19, 'Edit properties'),
(20, 'Are you sure? This operation can not be undone'),
(21, 'Delete'),
(22, 'Label'),
(23, 'Module name'),
(24, 'Controller name'),
(25, 'Action name'),
(26, 'Params (json)'),
(27, 'Route name'),
(28, 'URI'),
(29, 'Visible?'),
(30, 'Stylesheet'),
(31, 'Proxy'),
(32, 'Translated from'),
(33, 'Save'),
(34, 'Save and add another'),
(35, 'Save and continue'),
(36, 'Manage translation'),
(37, 'Manage permissions'),
(38, 'Permissions'),
(39, 'Name'),
(40, 'Description'),
(41, 'Users'),
(42, 'Groups'),
(43, 'Filters'),
(44, 'Submit'),
(45, '<strong>%d-%d</strong> of <strong>%d</strong>'),
(46, 'Module:'),
(47, 'Controller:'),
(48, 'Ressource:'),
(49, 'Next'),
(50, 'Last'),
(51, 'Saving has been done.'),
(52, 'Manage flatpage'),
(53, 'flatpage'),
(54, 'Missing translation'),
(55, 'Keywords'),
(56, 'URL'),
(57, 'Content'),
(58, 'Cover'),
(59, 'Template'),
(60, 'Parent'),
(61, 'Date to publish'),
(62, 'Order'),
(63, 'Route'),
(64, 'Edit flatpage'),
(65, 'Created at'),
(66, 'Last login'),
(67, 'Is active'),
(68, 'Active'),
(69, 'Not active'),
(70, 'Status'),
(71, 'Yes'),
(72, 'No'),
(73, 'Activate'),
(74, 'Desactivate'),
(75, 'Manage users'),
(76, 'Email'),
(77, 'User parent'),
(78, 'Can be deleted'),
(79, 'Is staff'),
(80, 'Is super admin'),
(81, 'Special characters are not allowed'),
(82, 'Password confirmation'),
(83, 'Email confirmation'),
(84, 'Form hasn''t been save. Maybe you have waiting to much time. Try again.'),
(85, 'Unauthorized access - '),
(86, 'Unauthorized action'),
(87, 'As a %s user, you don''t have the permission to accomplish this action.'),
(88, 'Go back'),
(89, 'Nickname'),
(90, 'Login'),
(91, 'user'),
(92, 'An error occur when validating the form. See below.'),
(93, 'Invalid ticket'),
(94, 'Médiathèque'),
(97, 'Search for content'),
(98, 'Add a highlight'),
(99, 'Manage highlights of: %s'),
(100, 'Add'),
(101, 'Empty'),
(102, 'Custom'),
(103, 'Manage Highlight'),
(104, 'highlight'),
(105, 'Link'),
(106, 'Link label'),
(107, 'Image'),
(108, 'Body'),
(109, 'Edit Highlight info'),
(110, 'override info'),
(111, 'Reference text'),
(112, 'Translations'),
(113, 'Id'),
(114, 'Reference language'),
(115, 'Language to translate'),
(116, 'Tags'),
(117, 'First'),
(118, 'Previous'),
(119, 'Backoffice'),
(120, 'View script'),
(121, 'Manage flatpage templates'),
(122, 'Edit flatpage template'),
(123, 'Parent group'),
(124, 'Manage groups'),
(125, 'Group parent'),
(126, 'Reference (%s)'),
(127, 'Translate'),
(128, 'Search for a Request'),
(129, 'View Request'),
(130, 'Details for Request'),
(131, 'My closed Requests'),
(132, 'Price: '),
(133, 'to'),
(134, 'Unauthorized action\nAs a %s user, you don''t have the permission to accomplish this action.'),
(135, 'My opened Requests'),
(136, 'Déclarer un incident'),
(137, 'Veuillez renseigner les informations suivantes'),
(138, 'Veuillez renseigner les informations suivantes :'),
(140, 'name'),
(141, 'Fichier'),
(142, 'Valider'),
(143, 'Pièce jointe à ajouter'),
(144, 'Nom'),
(145, 'X'),
(146, 'File'),
(147, 'Tableau de bord'),
(149, 'Frontoffice'),
(150, 'Accueil'),
(151, 'Catalogue des services'),
(152, 'Contact'),
(154, 'Calendrier'),
(158, 'Liste des alarmes'),
(159, 'Nouveau ticket'),
(160, 'Home'),
(161, 'Tickets en cours'),
(162, 'Demande de formation'),
(164, 'Demande de service'),
(166, 'Comptes rendus'),
(167, 'Compteur Temps'),
(168, 'Documentation S.U.'),
(169, 'Tickets fermés'),
(170, 'QCM Sydel Univers'),
(171, 'Catalogue de Formations'),
(172, 'Produits'),
(175, 'Liste des modifications'),
(176, 'Panier'),
(177, 'Ticket incident'),
(178, 'La sonde'),
(179, 'Lot de maintenance'),
(180, 'Demande de matériel'),
(181, 'Chat'),
(182, 'Liste des Chat'),
(183, 'Nouveau Chat'),
(184, 'Demande de migration'),
(185, 'Matrice'),
(186, '''%value%'' is no valid email address in the basic format local-part'),
(197, 'Nouvelle version S.U.'),
(201, 'Welcome'),
(202, 'from'),
(208, 'Bienvenue'),
(213, 'Détails de la requête'),
(243, 'Contacter un Agent'),
(244, 'Titre'),
(246, 'Ajouter des pièces jointes'),
(247, 'Ajouter des pièces jointes :'),
(248, 'Pièce jointe:'),
(249, 'Voir que vos tickets'),
(250, 'Options'),
(251, 'Quel site-vous consulter ?'),
(252, 'Tous'),
(253, 'Quel(s) site(s)-vous consulter ?'),
(254, 'Quelle(s) site(s)-vous consulter ?'),
(255, 'Quelle(s) années(s)-vous consulter ?'),
(256, 'Sites'),
(257, 'Années'),
(258, 'Ne voir que vos tickets'),
(259, 'Toutes'),
(260, 'Filtres'),
(261, 'Mes requêtes ouvertes'),
(262, 'ref'),
(263, 'priority'),
(264, 'caller'),
(265, 'Référence'),
(266, 'Caller'),
(267, 'Priority'),
(268, 'Request Type'),
(269, 'Service'),
(270, 'Resolution Date'),
(271, 'Last Update'),
(272, 'Agent'),
(273, 'Public Log'),
(274, 'Hot Flag'),
(275, 'Location'),
(276, 'Mise à jour'),
(277, 'Mise à jour du ticket'),
(278, 'User Request'),
(279, 'Ticket mis à jour'),
(280, 'Gestion des Incidents'),
(281, 'Gestion <br>des Incidents'),
(282, 'Dossiers Clients'),
(283, 'Démo Sydel Univers'),
(284, 'Evénements'),
(285, 'Evénements paginés'),
(286, 'Commandes de préparation'),
(288, 'Point d''entrée'),
(291, 'Search'),
(292, 'Rechercher un client :'),
(293, 'SU Code'),
(294, 'ajouter une Organisation (client) :'),
(295, 'Le client n''existe pas. Vous pouvez ajouter'),
(296, 'Le client'),
(297, 'existe'),
(298, 'Connexion'),
(299, 'Choisissez un client'),
(301, 'Choisissez un client :'),
(302, 'Modifier la tarification d''une zone'),
(303, 'Validation'),
(304, 'Nouvelle affaire'),
(305, 'valider'),
(306, 'Site''s Name'),
(307, 'Adresse'),
(308, 'Code postal'),
(309, 'Il faut un code postal.'),
(310, 'Ville'),
(311, 'Ajout d''un nouveau site'),
(312, 'Contact''s Name'),
(313, 'Contact''s First name'),
(314, 'Contact''s Mail'),
(315, 'Contact''s phone'),
(316, 'Contact''s Function'),
(317, 'Ajout d''un nouveau contact'),
(318, 'Server''s Name'),
(319, 'Server''s Function'),
(320, 'Server''s IP Address'),
(321, 'Server''s OS'),
(322, 'Ajout d''un nouveau serveur'),
(323, 'Database Instance (xxxsup1.yyyxxxsup1.world)'),
(324, 'Listener (YYYXXXSUP1)'),
(325, 'Database (YYYXXXDBSUP'),
(326, 'String (yyyxxxsup)'),
(327, 'Ajout de l''instance de production'),
(328, 'Ajout de l''instance de test'),
(329, 'Le site'),
(330, 'Un contact'),
(331, 'Un serveur'),
(332, 'Une instance de production'),
(333, 'Une instance de test'),
(334, 'Un site'),
(335, 'VPN & Validation'),
(336, 'Intranet'),
(337, 'Aide à l''imputation'),
(338, 'Annuaire'),
(339, 'Trouver son matricule'),
(341, 'Clients'),
(342, 'Saisie des informations'),
(343, 'Saisissez votre nom'),
(344, 'Saisissez un nom et/ou prénom'),
(345, 'Choisissez un client et/ou saisissez un nom et/ou prénom'),
(347, 'Prénom'),
(348, 'Demande'),
(349, 'test_order=6'),
(350, 'test_order=7'),
(351, 'Drop-down list'),
(352, 'Nom Utilisateur'),
(353, 'Prénom Utilisateur'),
(354, 'Service de l''utilisateur'),
(355, 'Fonction de l''utilisateur'),
(356, 'hidden'),
(357, 'list'),
(358, 'Date de début'),
(359, 'Date de fin'),
(360, 'date and time'),
(361, 'duration'),
(362, 'Read Only'),
(363, 'Type d''offre'),
(364, 'Adresse IP Internet VISIO à connecter'),
(365, 'Date & heure prévue'),
(366, 'Durée prévue'),
(367, 'Durée du stage'),
(368, 'Vous êtes là'),
(369, 'Vous commencez'),
(370, 'Caché'),
(371, 'Vous serez payé'),
(372, 'Remarque'),
(373, 'Fin'),
(374, 'Gestion des comptes stagiaires'),
(375, 'Ajouter des pièce jointes'),
(376, 'Gestion des comptes utilisateur'),
(377, 'Déclaration d''incident'),
(379, 'Tableau de bord global'),
(380, 'Ne visualiser que vos tickets'),
(381, 'Préférences'),
(382, 'Confirmation'),
(383, 'Changement de mot de passe'),
(384, 'Mot de passe'),
(385, 'The passwords do not match'),
(386, 'Please choose a password between 4-15 characters'),
(387, 'Nouveau Mot de passe :'),
(388, 'Merci de choisir un mot de passe comprenant 4 à 15 caractères'),
(389, 'Confirmer le nouveau mot de passe :'),
(390, 'Les mots de passe ne correspondent pas.'),
(391, 'Votre mot de passe a bien été modifié.'),
(392, 'Vos modifications ont été prises en compte'),
(393, ''),
(394, 'Vos incidents en cours'),
(395, 'Demande espace de création et partage de fichiers - VisioConférence via le web'),
(396, 'Type de compte'),
(397, 'Date'),
(398, 'Go To Meeting'),
(399, 'Vos incidents fermés'),
(400, 'Raison de la suspension'),
(401, 'Resolution Code'),
(402, 'Pending Reason'),
(403, 'Solution'),
(404, 'Résolution'),
(405, 'Fermeture'),
(406, 'Ré-ouvrir'),
(407, 'Imprimer'),
(408, 'Journal Public'),
(409, 'Date de clôture'),
(410, 'Rechercher'),
(411, 'Critères'),
(412, 'N° de Bureau'),
(413, 'Date de mise en application'),
(414, 'Ressources Informatiques'),
(415, 'Tuteur (stagiaire)'),
(416, 'Pour Comptes d''utilisateurs'),
(417, 'nom'),
(418, 'Second template'),
(419, 'Ajout, Retrait ou Modification d''un compte utilisateur'),
(420, 'Adr. IP Internet VISIO à connecter'),
(421, 'Demande  Opération d''Administration du SI'),
(422, 'Demande mise à jour Keypass'),
(423, 'Demande d''assistance'),
(424, 'new'),
(425, 'undefined'),
(426, 'service_request'),
(427, 'Started'),
(428, 'qualified'),
(429, 'low'),
(430, 'user_issue'),
(431, 'id'),
(432, 'Demande conférence téléphonique'),
(433, 'Demande amélioration fonctionnelle logiciel métier'),
(434, 'Demande test validation Visioconférence'),
(435, 'Services - Le catalogue'),
(436, 'assigned'),
(437, 'high'),
(438, 'assistance_util'),
(439, 'resolved'),
(440, 'medium'),
(441, 'Date de naissance et lieu de naissance'),
(442, 'Numéro de Sécurité Sociale'),
(443, 'Statut'),
(444, 'Nom du département'),
(445, 'Nom du service'),
(446, 'Nom du responsable'),
(447, 'Type de contrat'),
(448, 'Date d’arrivée'),
(451, 'Compétence'),
(452, 'Demande accès au Système d''Information'),
(453, 'Demande reconditionnement poste de travail'),
(457, 'Contacts Services'),
(458, 'File name'),
(459, 'Manage media'),
(460, 'Demande dépannage matériel'),
(461, 'First Name'),
(462, 'Last Name'),
(463, 'Activ'),
(464, 'iTop Production'),
(465, 'Edit User'),
(467, 'Manage Ldap'),
(468, 'ldap'),
(469, 'Email2'),
(470, 'Edit Ldap User'),
(471, 'generate'),
(472, 'import'),
(473, 'delete all'),
(474, 'Is local'),
(475, 'Create Account'),
(476, 'Groupe'),
(477, 'Manage Import Ldap'),
(478, 'Import'),
(479, 'Delete all'),
(480, 'Has a local Account'),
(481, 'Manage Users'),
(482, 'users'),
(483, 'Oui'),
(484, 'Non'),
(485, 'Edit Itop User'),
(486, 'Manage iTop'),
(487, 'iTop'),
(488, 'Manage Import iTop Users'),
(489, 'Import iTop Users'),
(490, 'Organization'),
(491, 'Classe'),
(492, 'Function'),
(493, 'Frequence'),
(494, 'Last Execution Date'),
(495, 'Manage Cron Task'),
(496, 'Task'),
(497, 'Class'),
(498, 'Provider'),
(499, 'Contrats Fournisseur'),
(500, 'Subtitle'),
(501, 'Link Module'),
(502, 'Link Controller'),
(503, 'Link Action'),
(504, 'Manage Services'),
(505, 'Settings'),
(506, 'Start Date'),
(507, 'Admin Services Clients'),
(508, 'Manage Import iTop'),
(509, 'Pages'),
(510, 'Manage Local Users'),
(511, 'Translation'),
(512, 'User permission'),
(513, 'Group permission'),
(514, 'Cron Task'),
(515, 'Clear cache'),
(516, 'Cache'),
(517, 'Contents'),
(518, 'Store'),
(519, 'Votre espace Services'),
(520, 'Demande d''acquisition matériel/logiciel informatique'),
(521, 'Demande accès à distance pour une démonstration sur site client'),
(522, 'Liste des contracts fournisseurs'),
(523, 'Tableaux de bord'),
(524, 'Nouvelle requête utilisateur.'),
(525, 'iTop - Déclarer un incident'),
(526, 'Requêtes Utilisateur'),
(527, 'Assistance'),
(529, 'Manage profile''s users'),
(530, 'profile''s user'),
(531, 'Service ID'),
(532, 'Code'),
(533, 'Manage Services Styles'),
(534, 'Services Styles'),
(535, 'Manage Home Services Page'),
(536, 'Home Services Page'),
(537, 'Import Itop Services'),
(538, 'Import Itop Services Subcategory'),
(539, 'Manage Media'),
(540, 'Microsoft Office Support'),
(541, 'Software removal'),
(542, 'Windows installation/upgrade'),
(543, 'Demande opération administration'),
(544, 'New IP address'),
(545, 'assistance'),
(546, 'closed'),
(547, 'Demande installation ou mise à jour outil sur poste de travail'),
(548, 'Catalogue de Services.'),
(549, 'Script permissions'),
(550, 'Consulter la liste des incidents'),
(551, 'Nouvelle déclaration d''incident'),
(552, 'Type'),
(553, 'Configuration'),
(554, 'Local Users'),
(555, 'Create'),
(556, 'iTop Users'),
(557, 'Ldap User'),
(558, 'Organizations'),
(559, 'Your site at a glance'),
(560, 'Hey %s, what’s up?'),
(561, 'Creation date'),
(562, 'Manage Organizations'),
(563, 'Text'),
(564, 'End Date'),
(565, 'Manage Alerts'),
(566, 'Alert'),
(567, 'Edit Alert'),
(568, 'Alerts'),
(569, 'Users Profiles'),
(570, 'Dashboard'),
(571, 'Manage Widget'),
(572, 'Widget'),
(573, 'Widgets'),
(574, 'Compte iTop déclaré'),
(575, 'Pas de compte iTop déclaré'),
(576, 'interaction sur les tickets possible'),
(577, 'interaction sur les tickets impossible'),
(578, 'Réglages de vos préférences'),
(579, 'Régler ici vos préférences comme la langue ou les sites auquels vous souhaitez vous rattacher.'),
(580, 'Langue'),
(581, 'Changer de langue pour :'),
(582, 'Mot de passe valide.'),
(583, 'Mot de passe invalide.'),
(584, 'Le mot de passe doit faire au moins 5 caractères.'),
(585, 'Le mot de passe a été modifié.'),
(586, 'Informations'),
(587, 'Environnement :'),
(588, 'Version du Portail'),
(589, 'Version'),
(590, 'Opened request'),
(591, 'Tickets per status'),
(592, 'Other informations'),
(593, 'Listes des Services'),
(594, 'Veuillez renseigner les informations ci-dessous :'),
(595, 'Contrats Fournisseurs'),
(598, 'Gestion des incidents'),
(599, 'Utilisateur'),
(600, 'Organisation'),
(601, 'Ticket par code résolution'),
(602, 'Code résolution'),
(603, 'Nbre de tickets'),
(604, 'Ticket par Service'),
(605, 'Répartition des tickets selon leur code de résolution'),
(606, 'Les incidents ouverts sur l''année écoulée'),
(607, '''Mois - Année'''),
(608, '''Nbre de tickets'''),
(609, '''Tickets par mois'''),
(610, 'Le graphique ci-dessus donne la répartition des tickets incidents déclarés les mois passés.'),
(611, 'Répartition des tickets selon les services'),
(612, 'Traitement en cours...'),
(613, 'Rechercher :'),
(614, '_MENU_ éléments'),
(615, 'Affichage de l\\''élément _START_ &agrave; _END_ sur _TOTAL_ éléments'),
(616, 'Affichage de l\\''élément'),
(617, 'Affichage de l\\''élément 0 à 0 sur 0 éléments'),
(618, '(filtré de _MAX_ éléments au total)'),
(619, 'Chargement en cours...'),
(620, 'Aucun élément à afficher'),
(621, 'Aucune donnée disponible dans le tableau'),
(622, 'Premier'),
(623, 'Précédent'),
(624, 'Suivant'),
(625, 'Dernier'),
(626, 'activer pour trier la colonne par ordre croissant'),
(627, 'activer pour trier la colonne par ordre décroissant'),
(628, 'collection'),
(629, 'Export'),
(630, 'Copier'),
(631, 'Listes des tickets.'),
(632, '<h1>Visualisation en mode édition</h1><p>Utiliser les fonction d\\''édition du navigateur. Presser \\''Echappe\\'' pour revenir à la page</p>'),
(633, 'Provider Contract'),
(634, 'Vos Contacts'),
(635, 'Le responsable'),
(636, 'Les membres de l''équipe'),
(637, 'Mes requêtes fermées'),
(638, 'incident'),
(639, 'Consulter notre catalogue de service'),
(640, 'Hiérarchie des Services'),
(641, 'Catalogue produits'),
(642, 'Prix'),
(643, 'Panier en cours'),
(644, 'Votre Store'),
(645, 'Version de iTop'),
(646, 'Please fill the informations below :'),
(647, 'New user request'),
(648, 'Votre demande a bien été enregistrée sous la référence'),
(649, 'Vous recevrez très bientôt un mail vous rappelant la référence votre demande.'),
(650, 'L''équipe de support vous remercie.'),
(651, 'Configuration Files'),
(652, 'Modification des fichier de configuration.'),
(660, 'The file is not writable, you''re not alloed to modify it'),
(661, 'The file is not writable, you''re not alloed to modify it !'),
(663, 'Modification du fichier de configuration de l''application.'),
(664, 'Application'),
(665, '4'),
(666, 'Your Syleps Area');

-- --------------------------------------------------------

--
-- Structure de la table `user_profile`
--

CREATE TABLE IF NOT EXISTS `user_profile` (
`id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `about` text,
  `website` varchar(150) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `avatar_id` varchar(100) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `user_profile`
--

INSERT INTO `user_profile` (`id`, `user_id`, `nickname`, `about`, `website`, `created_at`, `updated_at`, `avatar_id`) VALUES
(2, 1, 'admin', NULL, NULL, '2012-11-19 15:54:52', '2015-03-27 17:18:59', NULL),
(14, 2, 'anonymous', NULL, NULL, '2015-02-14 14:21:25', '2015-02-14 14:21:25', NULL),
(15, 14, 'dali@demo.com', NULL, NULL, '2015-03-26 08:47:01', '2015-03-26 08:47:01', NULL),
(16, 15, 'pablo@demo.com', NULL, NULL, '2015-03-30 08:51:25', '2015-03-30 08:58:03', NULL),
(17, 16, 'admin', NULL, NULL, '2015-04-07 13:06:55', '2015-04-07 13:06:55', NULL);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `auth_belong`
--
ALTER TABLE `auth_belong`
 ADD PRIMARY KEY (`user_id`,`group_id`), ADD KEY `fk_belong__group_id___group__user_id` (`group_id`);

--
-- Index pour la table `auth_group`
--
ALTER TABLE `auth_group`
 ADD PRIMARY KEY (`id`), ADD KEY `fk_group__group_parent_id___group__id` (`group_parent_id`);

--
-- Index pour la table `auth_group_permission`
--
ALTER TABLE `auth_group_permission`
 ADD PRIMARY KEY (`group_id`,`permission_id`), ADD KEY `fk_group_permission__permission_id___permission__id` (`permission_id`);

--
-- Index pour la table `auth_permission`
--
ALTER TABLE `auth_permission`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `auth_user`
--
ALTER TABLE `auth_user`
 ADD PRIMARY KEY (`id`), ADD KEY `can_be_deleted` (`can_be_deleted`), ADD KEY `is_active` (`is_active`), ADD KEY `is_super_admin` (`is_super_admin`), ADD KEY `is_staff` (`is_staff`), ADD KEY `email` (`email`), ADD KEY `username` (`username`), ADD KEY `fk_user__user_parent_id___user__id` (`user_parent_id`);

--
-- Index pour la table `auth_user_permission`
--
ALTER TABLE `auth_user_permission`
 ADD PRIMARY KEY (`user_id`,`permission_id`), ADD KEY `fk_persmission__action_id___action__id` (`permission_id`);

--
-- Index pour la table `centurion_content_type`
--
ALTER TABLE `centurion_content_type`
 ADD PRIMARY KEY (`id`), ADD KEY `name` (`name`);

--
-- Index pour la table `centurion_navigation`
--
ALTER TABLE `centurion_navigation`
 ADD PRIMARY KEY (`id`), ADD KEY `proxy_model` (`proxy_model`,`proxy_pk`), ADD KEY `order` (`order`), ADD KEY `is_visible` (`is_visible`), ADD KEY `is_in_menu` (`is_in_menu`), ADD KEY `mptt_lft` (`mptt_lft`), ADD KEY `mptt_rgt` (`mptt_rgt`), ADD KEY `mptt_level` (`mptt_level`), ADD KEY `mptt_tree_id` (`mptt_tree_id`), ADD KEY `mptt_parent_id` (`mptt_parent_id`), ADD KEY `original_id` (`original_id`,`language_id`), ADD KEY `can_be_deleted` (`can_be_deleted`), ADD KEY `language_id` (`language_id`);

--
-- Index pour la table `centurion_site`
--
ALTER TABLE `centurion_site`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `cms_flatpage`
--
ALTER TABLE `cms_flatpage`
 ADD PRIMARY KEY (`id`), ADD KEY `flatpage_template_id` (`flatpage_template_id`), ADD KEY `flatpage_cover_id` (`cover_id`), ADD KEY `mptt_parent_id` (`mptt_parent_id`), ADD KEY `slug` (`slug`), ADD KEY `is_published` (`is_published`), ADD KEY `mptt_lft` (`mptt_lft`), ADD KEY `mptt_rgt` (`mptt_rgt`), ADD KEY `mptt_level` (`mptt_level`), ADD KEY `mptt_tree_id` (`mptt_tree_id`), ADD KEY `original_id` (`original_id`,`language_id`), ADD KEY `language_id` (`language_id`);

--
-- Index pour la table `cms_flatpage_template`
--
ALTER TABLE `cms_flatpage_template`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `cron_task`
--
ALTER TABLE `cron_task`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ecom_products`
--
ALTER TABLE `ecom_products`
 ADD PRIMARY KEY (`produitId`);

--
-- Index pour la table `media_duplicate`
--
ALTER TABLE `media_duplicate`
 ADD PRIMARY KEY (`id`), ADD KEY `file_id` (`file_id`);

--
-- Index pour la table `media_file`
--
ALTER TABLE `media_file`
 ADD PRIMARY KEY (`id`), ADD KEY `fk_file__user_id___user__id` (`user_id`), ADD KEY `proxy_model` (`proxy_model`), ADD KEY `proxy_pk` (`proxy_pk`), ADD KEY `belong_model` (`belong_model`), ADD KEY `belong_pk` (`belong_pk`), ADD KEY `file_id` (`file_id`), ADD KEY `local_filename` (`local_filename`);

--
-- Index pour la table `media_image`
--
ALTER TABLE `media_image`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `media_multiupload_ticket`
--
ALTER TABLE `media_multiupload_ticket`
 ADD PRIMARY KEY (`id`), ADD KEY `proxy_model` (`proxy_model_id`), ADD KEY `form_class_model` (`form_class_model_id`), ADD KEY `proxy_pk` (`proxy_pk`);

--
-- Index pour la table `media_video`
--
ALTER TABLE `media_video`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `portal_alert`
--
ALTER TABLE `portal_alert`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `portal_alert_organization`
--
ALTER TABLE `portal_alert_organization`
 ADD PRIMARY KEY (`alert_id`,`organization_id`), ADD KEY `FK_portal_alert_organization_portal_organization` (`organization_id`);

--
-- Index pour la table `portal_chat_questions`
--
ALTER TABLE `portal_chat_questions`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `portal_itop_user`
--
ALTER TABLE `portal_itop_user`
 ADD PRIMARY KEY (`id`), ADD KEY `email` (`email`), ADD KEY `login` (`login`);

--
-- Index pour la table `portal_ldap_user`
--
ALTER TABLE `portal_ldap_user`
 ADD PRIMARY KEY (`id`), ADD KEY `email` (`email`), ADD KEY `sn` (`sn`);

--
-- Index pour la table `portal_organization`
--
ALTER TABLE `portal_organization`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `portal_service_config`
--
ALTER TABLE `portal_service_config`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `portal_service_style`
--
ALTER TABLE `portal_service_style`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `code` (`code`), ADD KEY `fk_service__avatar_id___file__id` (`avatar_id`);

--
-- Index pour la table `portal_user_preference`
--
ALTER TABLE `portal_user_preference`
 ADD PRIMARY KEY (`user_id`,`preference_name`,`id`), ADD UNIQUE KEY `id` (`id`);

--
-- Index pour la table `portal_widget`
--
ALTER TABLE `portal_widget`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `translation_language`
--
ALTER TABLE `translation_language`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `translation_tag`
--
ALTER TABLE `translation_tag`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `translation_tag_uid`
--
ALTER TABLE `translation_tag_uid`
 ADD PRIMARY KEY (`uid_id`,`tag_id`), ADD KEY `FK_translation_tag_uid2` (`tag_id`);

--
-- Index pour la table `translation_translation`
--
ALTER TABLE `translation_translation`
 ADD PRIMARY KEY (`uid_id`,`language_id`), ADD KEY `uid_id` (`uid_id`), ADD KEY `language_id` (`language_id`);

--
-- Index pour la table `translation_uid`
--
ALTER TABLE `translation_uid`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user_profile`
--
ALTER TABLE `user_profile`
 ADD PRIMARY KEY (`id`), ADD KEY `fk_profile__user_id___user__id` (`user_id`), ADD KEY `fk_profile__avatar_id___file__id` (`avatar_id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `auth_group`
--
ALTER TABLE `auth_group`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT pour la table `auth_permission`
--
ALTER TABLE `auth_permission`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=82;
--
-- AUTO_INCREMENT pour la table `auth_user`
--
ALTER TABLE `auth_user`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT pour la table `centurion_content_type`
--
ALTER TABLE `centurion_content_type`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT pour la table `centurion_navigation`
--
ALTER TABLE `centurion_navigation`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=200;
--
-- AUTO_INCREMENT pour la table `centurion_site`
--
ALTER TABLE `centurion_site`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `cms_flatpage`
--
ALTER TABLE `cms_flatpage`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `cms_flatpage_template`
--
ALTER TABLE `cms_flatpage_template`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `cron_task`
--
ALTER TABLE `cron_task`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `ecom_products`
--
ALTER TABLE `ecom_products`
MODIFY `produitId` int(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=57;
--
-- AUTO_INCREMENT pour la table `media_duplicate`
--
ALTER TABLE `media_duplicate`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=73;
--
-- AUTO_INCREMENT pour la table `media_image`
--
ALTER TABLE `media_image`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=54;
--
-- AUTO_INCREMENT pour la table `media_multiupload_ticket`
--
ALTER TABLE `media_multiupload_ticket`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `media_video`
--
ALTER TABLE `media_video`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `portal_alert`
--
ALTER TABLE `portal_alert`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `portal_chat_questions`
--
ALTER TABLE `portal_chat_questions`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `portal_itop_user`
--
ALTER TABLE `portal_itop_user`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `portal_ldap_user`
--
ALTER TABLE `portal_ldap_user`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `portal_service_config`
--
ALTER TABLE `portal_service_config`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identifiant',AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT pour la table `portal_user_preference`
--
ALTER TABLE `portal_user_preference`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT pour la table `translation_language`
--
ALTER TABLE `translation_language`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `translation_tag`
--
ALTER TABLE `translation_tag`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `translation_uid`
--
ALTER TABLE `translation_uid`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=667;
--
-- AUTO_INCREMENT pour la table `user_profile`
--
ALTER TABLE `user_profile`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `auth_belong`
--
ALTER TABLE `auth_belong`
ADD CONSTRAINT `fk_belong__group_id___group__user_id` FOREIGN KEY (`group_id`) REFERENCES `auth_group` (`id`),
ADD CONSTRAINT `fk_reference_32` FOREIGN KEY (`user_id`) REFERENCES `auth_user` (`id`);

--
-- Contraintes pour la table `auth_group`
--
ALTER TABLE `auth_group`
ADD CONSTRAINT `fk_group__group_parent_id___group__id` FOREIGN KEY (`group_parent_id`) REFERENCES `auth_group` (`id`);

--
-- Contraintes pour la table `auth_group_permission`
--
ALTER TABLE `auth_group_permission`
ADD CONSTRAINT `fk_group_permission__group_id___group__id` FOREIGN KEY (`group_id`) REFERENCES `auth_group` (`id`),
ADD CONSTRAINT `fk_group_permission__permission_id___permission__id` FOREIGN KEY (`permission_id`) REFERENCES `auth_permission` (`id`);

--
-- Contraintes pour la table `auth_user`
--
ALTER TABLE `auth_user`
ADD CONSTRAINT `fk_user__user_parent_id___user__id` FOREIGN KEY (`user_parent_id`) REFERENCES `auth_user` (`id`);

--
-- Contraintes pour la table `auth_user_permission`
--
ALTER TABLE `auth_user_permission`
ADD CONSTRAINT `fk_permission__user_id___user__id` FOREIGN KEY (`user_id`) REFERENCES `auth_user` (`id`),
ADD CONSTRAINT `fk_persmission__action_id___action__id` FOREIGN KEY (`permission_id`) REFERENCES `auth_permission` (`id`);

--
-- Contraintes pour la table `centurion_navigation`
--
ALTER TABLE `centurion_navigation`
ADD CONSTRAINT `centurion_navigation_ibfk_1` FOREIGN KEY (`proxy_model`) REFERENCES `centurion_content_type` (`id`),
ADD CONSTRAINT `centurion_navigation_ibfk_2` FOREIGN KEY (`original_id`) REFERENCES `centurion_navigation` (`id`),
ADD CONSTRAINT `centurion_navigation_ibfk_3` FOREIGN KEY (`language_id`) REFERENCES `translation_language` (`id`),
ADD CONSTRAINT `centurion_navigation_ibfk_4` FOREIGN KEY (`language_id`) REFERENCES `translation_language` (`id`),
ADD CONSTRAINT `fk_navigation__navigation_parent_id___navigation__id` FOREIGN KEY (`mptt_parent_id`) REFERENCES `centurion_navigation` (`id`);

--
-- Contraintes pour la table `cms_flatpage`
--
ALTER TABLE `cms_flatpage`
ADD CONSTRAINT `cms_flatpage_ibfk_1` FOREIGN KEY (`mptt_parent_id`) REFERENCES `cms_flatpage` (`id`),
ADD CONSTRAINT `cms_flatpage_ibfk_2` FOREIGN KEY (`original_id`) REFERENCES `cms_flatpage` (`id`),
ADD CONSTRAINT `cms_flatpage_ibfk_3` FOREIGN KEY (`language_id`) REFERENCES `translation_language` (`id`),
ADD CONSTRAINT `cms_flatpage__banner_id___media_file__id` FOREIGN KEY (`cover_id`) REFERENCES `media_file` (`id`),
ADD CONSTRAINT `cms_flatpage__flatpage_template_id___cms_flatpage_template__id` FOREIGN KEY (`flatpage_template_id`) REFERENCES `cms_flatpage_template` (`id`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `media_duplicate`
--
ALTER TABLE `media_duplicate`
ADD CONSTRAINT `media_duplicate_ibfk_1` FOREIGN KEY (`file_id`) REFERENCES `media_file` (`id`);

--
-- Contraintes pour la table `media_file`
--
ALTER TABLE `media_file`
ADD CONSTRAINT `fk_file__user_id___user__id` FOREIGN KEY (`user_id`) REFERENCES `auth_user` (`id`);

--
-- Contraintes pour la table `media_multiupload_ticket`
--
ALTER TABLE `media_multiupload_ticket`
ADD CONSTRAINT `media_multiupload_ticket_ibfk_1` FOREIGN KEY (`proxy_model_id`) REFERENCES `centurion_content_type` (`id`),
ADD CONSTRAINT `media_multiupload_ticket_ibfk_2` FOREIGN KEY (`form_class_model_id`) REFERENCES `centurion_content_type` (`id`);

--
-- Contraintes pour la table `portal_alert_organization`
--
ALTER TABLE `portal_alert_organization`
ADD CONSTRAINT `FK_portal_alert_organization_portal_alert` FOREIGN KEY (`alert_id`) REFERENCES `portal_alert` (`id`),
ADD CONSTRAINT `FK_portal_alert_organization_portal_organization` FOREIGN KEY (`organization_id`) REFERENCES `portal_organization` (`id`);

--
-- Contraintes pour la table `portal_service_style`
--
ALTER TABLE `portal_service_style`
ADD CONSTRAINT `fk_service__avatar_id___file__id` FOREIGN KEY (`avatar_id`) REFERENCES `media_file` (`id`);

--
-- Contraintes pour la table `translation_tag_uid`
--
ALTER TABLE `translation_tag_uid`
ADD CONSTRAINT `FK_translation_tag_uid` FOREIGN KEY (`uid_id`) REFERENCES `translation_uid` (`id`),
ADD CONSTRAINT `FK_translation_tag_uid2` FOREIGN KEY (`tag_id`) REFERENCES `translation_tag` (`id`);

--
-- Contraintes pour la table `translation_translation`
--
ALTER TABLE `translation_translation`
ADD CONSTRAINT `translation_translation_ibfk_1` FOREIGN KEY (`uid_id`) REFERENCES `translation_uid` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `translation_translation_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `translation_language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `user_profile`
--
ALTER TABLE `user_profile`
ADD CONSTRAINT `fk_profile__avatar_id___file__id` FOREIGN KEY (`avatar_id`) REFERENCES `media_file` (`id`),
ADD CONSTRAINT `fk_profile__user_id___user__id` FOREIGN KEY (`user_id`) REFERENCES `auth_user` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;