-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 06. Dez 2021 um 11:40
-- Server-Version: 8.0.25
-- PHP-Version: 8.0.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `aulatechnik_db`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `attachment`
--

CREATE TABLE `attachment` (
  `attachment_id` int NOT NULL,
  `attribute_type_id` int NOT NULL,
  `attribute_id` int NOT NULL,
  `uploaded` datetime NOT NULL,
  `file_type` varchar(16) NOT NULL,
  `file_name` varchar(2048) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `attribute_type`
--

CREATE TABLE `attribute_type` (
  `attribute_type_id` int NOT NULL,
  `name` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Daten für Tabelle `attribute_type`
--

INSERT INTO `attribute_type` (`attribute_type_id`, `name`) VALUES
(1, 'event'),
(2, 'item'),
(3, 'to-do');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `comment`
--

CREATE TABLE `comment` (
  `comment_id` int NOT NULL,
  `user_id` int NOT NULL,
  `attribute_type_id` int NOT NULL,
  `attribute_id` int NOT NULL,
  `data` varchar(4096) NOT NULL,
  `posting_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Daten für Tabelle `comment`
--

INSERT INTO `comment` (`comment_id`, `user_id`, `attribute_type_id`, `attribute_id`, `data`, `posting_date`) VALUES
(53, 1, 3, 25, 'ddd', '2021-11-29 16:25:35');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `email_request`
--

CREATE TABLE `email_request` (
  `email_request_id` int NOT NULL,
  `email_request_type_id` int NOT NULL,
  `user_id` int NOT NULL,
  `code` varchar(512) NOT NULL,
  `request_created` datetime NOT NULL,
  `request_expiry` datetime NOT NULL,
  `verified` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Daten für Tabelle `email_request`
--

INSERT INTO `email_request` (`email_request_id`, `email_request_type_id`, `user_id`, `code`, `request_created`, `request_expiry`, `verified`) VALUES
(50, 1, 1, '8E988E61BA3F0BDAB3B31FD91DEF7DFD3A4EDF00', '2021-07-22 07:07:54', '2021-07-23 07:07:54', 1),
(51, 3, 1, '1234', '2021-07-22 09:38:08', '2021-07-23 09:38:08', 1),
(56, 2, 1, 'E59F7C41D74C112F3B29DF778D10B2D21D74A791', '2021-07-27 10:39:02', '2021-07-28 10:39:02', 1),
(64, 3, 1, 'D680BF08977C7CE2B8E9513CEB65852DCE77629E', '2021-09-22 15:19:57', '2021-09-23 15:19:57', 1),
(65, 3, 1, '36B13741007D185CB0EF36A5C058374B78C1FA4B', '2021-09-22 15:22:21', '2021-09-23 15:22:21', 1),
(66, 3, 1, '398D4E403E7114B243741D297D22C26A5B74883C', '2021-09-22 15:23:42', '2021-09-23 15:23:42', 1),
(78, 1, 1, 'B866F2794183E7D958464B7CF7FFAED89C01A067', '2021-09-24 10:17:40', '2021-09-25 10:17:40', 1),
(79, 2, 1, '5E1546445E72C5D98D8C72066E08EDA36A9F678E', '2021-09-24 10:18:10', '2021-09-25 10:18:10', 1),
(80, 1, 4, '8499CDE6BD55BAEACED1C71CE085763B7C7C5ED0', '2021-11-22 13:39:49', '2021-11-23 13:39:49', 1),
(81, 3, 4, 'E7BA43773B09C43E8FC825A85E0FED68D30D592F', '2021-11-22 13:40:34', '2021-11-23 13:40:34', 1),
(83, 2, 1, '98D5B1045E0421D13D06636C9E20EEC0B1CCDF2B', '2021-11-29 16:34:16', '2021-11-30 16:34:16', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `email_request_type`
--

CREATE TABLE `email_request_type` (
  `email_request_type_id` int NOT NULL,
  `name` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Daten für Tabelle `email_request_type`
--

INSERT INTO `email_request_type` (`email_request_type_id`, `name`) VALUES
(1, 'confirm email'),
(2, 'confirm school email'),
(3, 'password reset');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `event`
--

CREATE TABLE `event` (
  `event_id` int NOT NULL,
  `name` varchar(1024) NOT NULL,
  `event_client_id` int DEFAULT NULL,
  `event_location_id` int DEFAULT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime DEFAULT NULL,
  `description` mediumtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Daten für Tabelle `event`
--

INSERT INTO `event` (`event_id`, `name`, `event_client_id`, `event_location_id`, `start_time`, `end_time`, `description`) VALUES
(1, 'Event A', 1, 1, '2021-10-03 18:00:08', '2021-10-04 17:00:08', 'Testevent');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `event_client`
--

CREATE TABLE `event_client` (
  `event_client_id` int NOT NULL,
  `name` varchar(256) NOT NULL,
  `description` varchar(1024) DEFAULT NULL,
  `external` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Daten für Tabelle `event_client`
--

INSERT INTO `event_client` (`event_client_id`, `name`, `description`, `external`) VALUES
(1, 'Testclient', NULL, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `event_location`
--

CREATE TABLE `event_location` (
  `event_location_id` int NOT NULL,
  `name` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Daten für Tabelle `event_location`
--

INSERT INTO `event_location` (`event_location_id`, `name`) VALUES
(1, 'Testlocation');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `event_responsible`
--

CREATE TABLE `event_responsible` (
  `event_responsible_id` int NOT NULL,
  `user_id` int NOT NULL,
  `event_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `item`
--

CREATE TABLE `item` (
  `item_id` int NOT NULL,
  `item_type_id` int NOT NULL,
  `storage_id` int DEFAULT NULL,
  `name` varchar(512) NOT NULL,
  `lenght` varchar(11) DEFAULT NULL,
  `description` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `amount` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Daten für Tabelle `item`
--

INSERT INTO `item` (`item_id`, `item_type_id`, `storage_id`, `name`, `lenght`, `description`, `amount`) VALUES
(1, 1, NULL, 'DMX 10m Kabel', '10m', '', 0),
(2, 3, NULL, 'Glühbirne 10W', NULL, NULL, 7),
(3, 9, NULL, 'Glühbirne 10a', NULL, NULL, 4),
(8, 6, 4, 'eed', '4', 'ee', 2);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `item_type`
--

CREATE TABLE `item_type` (
  `item_type_id` int NOT NULL,
  `name_en` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `name_de` varchar(256) NOT NULL,
  `consumable` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Daten für Tabelle `item_type`
--

INSERT INTO `item_type` (`item_type_id`, `name_en`, `name_de`, `consumable`) VALUES
(1, 'cable', 'Kabel', 0),
(2, 'lamp', 'Lampe', 0),
(3, 'illuminant ', 'Leuchtmittel', 1),
(4, 'speaker', 'Lautsprecher', 0),
(5, 'moving heads', 'Moving-Head', 0),
(6, 'adapter', 'Adapter', 0),
(7, 'tool', 'Werkzeug', 0),
(8, 'other', 'Sonstiges', 0),
(9, 'other consumables', 'Sonstige Verbrauchsmaterialien', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lend`
--

CREATE TABLE `lend` (
  `lend_id` int NOT NULL,
  `user_id` int NOT NULL,
  `item_id` int NOT NULL,
  `amount` int NOT NULL,
  `return_date` date DEFAULT NULL,
  `returned` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Daten für Tabelle `lend`
--

INSERT INTO `lend` (`lend_id`, `user_id`, `item_id`, `amount`, `return_date`, `returned`) VALUES
(1, 1, 1, 24, '2021-09-22', 1),
(3, 1, 2, 5, '2021-09-10', 1),
(6, 1, 8, 15, NULL, 1),
(7, 1, 8, 10, '2021-10-07', 1),
(8, 1, 3, 3, '2021-09-23', 1),
(9, 1, 2, 3, '2021-11-26', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `notification`
--

CREATE TABLE `notification` (
  `notification_id` int NOT NULL,
  `notification_request_id` int NOT NULL,
  `email_sent` tinyint(1) NOT NULL,
  `seen` tinyint(1) NOT NULL,
  `time_posted` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `notification_request`
--

CREATE TABLE `notification_request` (
  `notification_request_id` int NOT NULL,
  `user_id` int NOT NULL,
  `email_update` tinyint(1) NOT NULL,
  `daily_update` tinyint(1) NOT NULL,
  `attribute_type_id` int NOT NULL,
  `attribute_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `storage`
--

CREATE TABLE `storage` (
  `storage_id` int NOT NULL,
  `storage_parent_id` int DEFAULT NULL,
  `storage_type_id` int NOT NULL,
  `name` varchar(256) NOT NULL,
  `size_x` int DEFAULT NULL,
  `size_y` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Daten für Tabelle `storage`
--

INSERT INTO `storage` (`storage_id`, `storage_parent_id`, `storage_type_id`, `name`, `size_x`, `size_y`) VALUES
(1, NULL, 1, 'Lager', NULL, NULL),
(2, 1, 2, 'Regal A', 5, 5),
(4, NULL, 1, 'Kellerr', NULL, NULL),
(5, NULL, 1, 'Regie', NULL, NULL),
(6, 2, 3, 'Box C', 3, 4),
(7, 2, 3, 'Box B', 0, 0),
(8, 2, 3, 'Box E', 5, 5);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `storage_type`
--

CREATE TABLE `storage_type` (
  `storage_type_id` int NOT NULL,
  `name_en` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `name_de` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Daten für Tabelle `storage_type`
--

INSERT INTO `storage_type` (`storage_type_id`, `name_en`, `name_de`) VALUES
(1, 'Room', 'Raum'),
(2, 'Shelf', 'Regal'),
(3, 'Box', 'Box');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tag`
--

CREATE TABLE `tag` (
  `tag_id` int NOT NULL,
  `name` varchar(256) NOT NULL,
  `colour` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Daten für Tabelle `tag`
--

INSERT INTO `tag` (`tag_id`, `name`, `colour`) VALUES
(1, 'Tag A', '#b3a400'),
(3, 'Tag B', '#e11414');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tag_assignment`
--

CREATE TABLE `tag_assignment` (
  `tag_assignment_id` int NOT NULL,
  `tag_id` int NOT NULL,
  `attribute_type_id` int NOT NULL,
  `attribute_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Daten für Tabelle `tag_assignment`
--

INSERT INTO `tag_assignment` (`tag_assignment_id`, `tag_id`, `attribute_type_id`, `attribute_id`) VALUES
(17, 1, 3, 22),
(19, 1, 3, 25),
(20, 3, 3, 25);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `todo_list`
--

CREATE TABLE `todo_list` (
  `todo_list_id` int NOT NULL,
  `event_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `todo_list_category_id` int DEFAULT NULL,
  `created` datetime NOT NULL,
  `name` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Daten für Tabelle `todo_list`
--

INSERT INTO `todo_list` (`todo_list_id`, `event_id`, `user_id`, `todo_list_category_id`, `created`, `name`) VALUES
(22, NULL, 1, 10, '2021-11-19 09:36:16', 'New ToDo List'),
(25, NULL, NULL, NULL, '2021-11-29 16:25:30', 'New ToDo List');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `todo_list_category`
--

CREATE TABLE `todo_list_category` (
  `todo_list_category_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `name` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Daten für Tabelle `todo_list_category`
--

INSERT INTO `todo_list_category` (`todo_list_category_id`, `user_id`, `name`) VALUES
(9, NULL, 'Important'),
(10, 1, 'ToDo');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `todo_list_entry`
--

CREATE TABLE `todo_list_entry` (
  `todo_list_entry_id` int NOT NULL,
  `todo_list_id` int NOT NULL,
  `parent_entry_id` int DEFAULT NULL,
  `name` varchar(256) NOT NULL,
  `checked` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Daten für Tabelle `todo_list_entry`
--

INSERT INTO `todo_list_entry` (`todo_list_entry_id`, `todo_list_id`, `parent_entry_id`, `name`, `checked`) VALUES
(27, 22, NULL, 'Test', 0),
(28, 22, 27, 'dd', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE `user` (
  `user_id` int NOT NULL,
  `role_id` int NOT NULL,
  `created` datetime NOT NULL,
  `active` tinyint(1) NOT NULL,
  `email` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `school_email` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `username` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `firstname` varchar(1024) NOT NULL,
  `lastname` varchar(1024) NOT NULL,
  `password` varchar(4096) NOT NULL,
  `preferred_language` varchar(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`user_id`, `role_id`, `created`, `active`, `email`, `school_email`, `username`, `firstname`, `lastname`, `password`, `preferred_language`) VALUES
(1, 1, '2021-07-17 14:37:37', 1, 'marius.kauling@web.de', 'tom.kauling2003@gmail.com', 'MariusSK', 'Marius', 'Kauling', '$2y$10$yK.JVni/OgagG74pr3M2peIofqi27Mv/VUFRB5d2wOQLPr/jl6Huy', 'de'),
(3, 2, '2021-07-27 14:37:37', 1, 'test@test.de', '', 'Testuser', 'Max', 'Musteruser', '$2y$10$m9OqD4pei9Uclhl2y4GLk.XkBf/5v6lbPQK.x3JlLEZHvwssRle1O', 'de'),
(4, 1, '2021-11-19 14:50:28', 1, 'aulatechnik_system@color-site-web.de', NULL, 'test1', 'test', 'test', '$2y$10$swNssKhmC5WLPDQ8nPaPD.hIUroE3MowKu0v75sas66NjNjf1WLRy', 'de');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_role`
--

CREATE TABLE `user_role` (
  `user_role_id` int NOT NULL,
  `name` varchar(256) NOT NULL,
  `pre_defined` tinyint(1) NOT NULL,
  `create_user` tinyint(1) NOT NULL,
  `edit_user` tinyint(1) NOT NULL,
  `delete_user` tinyint(1) NOT NULL,
  `edit_user_role` tinyint(1) NOT NULL,
  `create_event` tinyint(1) NOT NULL,
  `view_all_events` tinyint(1) NOT NULL,
  `view_own_events` tinyint(1) NOT NULL,
  `edit_all_events` tinyint(1) NOT NULL,
  `edit_own_events` tinyint(1) NOT NULL,
  `edit_event_responsibles` tinyint(1) NOT NULL,
  `delete_all_events` tinyint(1) NOT NULL,
  `delete_own_events` tinyint(1) NOT NULL,
  `edit_event_locations` tinyint(1) NOT NULL,
  `create_global_todo_list` tinyint(1) NOT NULL,
  `edit_all_todo_lists` tinyint(1) NOT NULL,
  `view_all_todo_lists` tinyint(1) NOT NULL,
  `edit_personal_todo_list` tinyint(1) NOT NULL,
  `edit_todo_list_categories` tinyint(1) NOT NULL,
  `edit_tags` tinyint(1) NOT NULL,
  `view_all_items` tinyint(1) NOT NULL,
  `view_specific_item` tinyint(1) NOT NULL,
  `create_new_item` tinyint(1) NOT NULL,
  `edit_item` tinyint(1) NOT NULL,
  `delete_item` tinyint(1) NOT NULL,
  `lend_item` tinyint(1) NOT NULL,
  `create_new_storage` tinyint(1) NOT NULL,
  `edit_storage` tinyint(1) NOT NULL,
  `delete_storage` tinyint(1) NOT NULL,
  `view_storages` tinyint(1) NOT NULL,
  `view_items_specific_storage` tinyint(1) NOT NULL,
  `comment_everything` tinyint(1) NOT NULL,
  `comment_own` tinyint(1) NOT NULL,
  `upload_attachment` tinyint(1) NOT NULL,
  `view_attachments` tinyint(1) NOT NULL,
  `delete_attachment` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Daten für Tabelle `user_role`
--

INSERT INTO `user_role` (`user_role_id`, `name`, `pre_defined`, `create_user`, `edit_user`, `delete_user`, `edit_user_role`, `create_event`, `view_all_events`, `view_own_events`, `edit_all_events`, `edit_own_events`, `edit_event_responsibles`, `delete_all_events`, `delete_own_events`, `edit_event_locations`, `create_global_todo_list`, `edit_all_todo_lists`, `view_all_todo_lists`, `edit_personal_todo_list`, `edit_todo_list_categories`, `edit_tags`, `view_all_items`, `view_specific_item`, `create_new_item`, `edit_item`, `delete_item`, `lend_item`, `create_new_storage`, `edit_storage`, `delete_storage`, `view_storages`, `view_items_specific_storage`, `comment_everything`, `comment_own`, `upload_attachment`, `view_attachments`, `delete_attachment`) VALUES
(1, 'Admin', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
(2, 'User', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 1, 0, 0, 0),
(4, 'New Role', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `attachment`
--
ALTER TABLE `attachment`
  ADD PRIMARY KEY (`attachment_id`),
  ADD KEY `fx_attribute_type_idx` (`attribute_type_id`);

--
-- Indizes für die Tabelle `attribute_type`
--
ALTER TABLE `attribute_type`
  ADD PRIMARY KEY (`attribute_type_id`);

--
-- Indizes für die Tabelle `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`comment_id`,`user_id`,`attribute_type_id`,`attribute_id`),
  ADD KEY `fx_comment_user_id` (`user_id`),
  ADD KEY `fx_comment_attribute_type_id` (`attribute_type_id`) USING BTREE;

--
-- Indizes für die Tabelle `email_request`
--
ALTER TABLE `email_request`
  ADD PRIMARY KEY (`email_request_id`,`email_request_type_id`,`user_id`),
  ADD KEY `fx_email_request_type_idx` (`email_request_type_id`) USING BTREE,
  ADD KEY `fx_email_request_user_idx` (`user_id`) USING BTREE;

--
-- Indizes für die Tabelle `email_request_type`
--
ALTER TABLE `email_request_type`
  ADD PRIMARY KEY (`email_request_type_id`);

--
-- Indizes für die Tabelle `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`event_id`),
  ADD KEY `fx_event_event_location_id` (`event_location_id`),
  ADD KEY `fx_event_event_client_idx` (`event_client_id`) USING BTREE;

--
-- Indizes für die Tabelle `event_client`
--
ALTER TABLE `event_client`
  ADD PRIMARY KEY (`event_client_id`);

--
-- Indizes für die Tabelle `event_location`
--
ALTER TABLE `event_location`
  ADD PRIMARY KEY (`event_location_id`);

--
-- Indizes für die Tabelle `event_responsible`
--
ALTER TABLE `event_responsible`
  ADD PRIMARY KEY (`event_responsible_id`,`user_id`,`event_id`),
  ADD KEY `fx_event_responsible_user_id` (`user_id`),
  ADD KEY `fx_event_responsible_event_id` (`event_id`);

--
-- Indizes für die Tabelle `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`item_id`,`item_type_id`),
  ADD KEY `fx_item_type_id` (`item_type_id`),
  ADD KEY `fx_storage_idx` (`storage_id`);

--
-- Indizes für die Tabelle `item_type`
--
ALTER TABLE `item_type`
  ADD PRIMARY KEY (`item_type_id`);

--
-- Indizes für die Tabelle `lend`
--
ALTER TABLE `lend`
  ADD PRIMARY KEY (`lend_id`,`user_id`,`item_id`),
  ADD KEY `fx_lend_item_idx` (`item_id`) USING BTREE,
  ADD KEY `fx_lend_user_idx` (`user_id`) USING BTREE;

--
-- Indizes für die Tabelle `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`notification_id`,`notification_request_id`),
  ADD KEY `fx_notification_notification_request_id` (`notification_request_id`);

--
-- Indizes für die Tabelle `notification_request`
--
ALTER TABLE `notification_request`
  ADD PRIMARY KEY (`notification_request_id`,`user_id`,`attribute_type_id`),
  ADD KEY `fx_notification_request_user_id` (`user_id`),
  ADD KEY `fx_notification_request_attribute_type_id` (`attribute_type_id`);

--
-- Indizes für die Tabelle `storage`
--
ALTER TABLE `storage`
  ADD PRIMARY KEY (`storage_id`,`storage_type_id`),
  ADD KEY `fx_storage_parent_idx` (`storage_parent_id`) USING BTREE,
  ADD KEY `fx_storage_type_idx` (`storage_type_id`) USING BTREE;

--
-- Indizes für die Tabelle `storage_type`
--
ALTER TABLE `storage_type`
  ADD PRIMARY KEY (`storage_type_id`);

--
-- Indizes für die Tabelle `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`tag_id`);

--
-- Indizes für die Tabelle `tag_assignment`
--
ALTER TABLE `tag_assignment`
  ADD PRIMARY KEY (`tag_assignment_id`,`tag_id`,`attribute_type_id`,`attribute_id`),
  ADD KEY `fx_tag_assignment_tag_id` (`tag_id`) USING BTREE,
  ADD KEY `fx_tag_assignment_attribute_type_id` (`attribute_type_id`) USING BTREE;

--
-- Indizes für die Tabelle `todo_list`
--
ALTER TABLE `todo_list`
  ADD PRIMARY KEY (`todo_list_id`),
  ADD KEY `fx_todo_list_event_id` (`event_id`),
  ADD KEY `fx_todo_list_todo_list_category_id` (`todo_list_category_id`),
  ADD KEY `fx_todo_list_user_id` (`user_id`);

--
-- Indizes für die Tabelle `todo_list_category`
--
ALTER TABLE `todo_list_category`
  ADD PRIMARY KEY (`todo_list_category_id`),
  ADD KEY `fx_todo_list_category_user_id` (`user_id`);

--
-- Indizes für die Tabelle `todo_list_entry`
--
ALTER TABLE `todo_list_entry`
  ADD PRIMARY KEY (`todo_list_entry_id`,`todo_list_id`),
  ADD KEY `fx_todo_list_entry_parent_entry_id` (`parent_entry_id`),
  ADD KEY `fx_todo_list_entry_todo_list_id` (`todo_list_id`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `school_email` (`school_email`),
  ADD KEY `fx_user_user_role_id` (`role_id`);

--
-- Indizes für die Tabelle `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`user_role_id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `attachment`
--
ALTER TABLE `attachment`
  MODIFY `attachment_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `attribute_type`
--
ALTER TABLE `attribute_type`
  MODIFY `attribute_type_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `comment`
--
ALTER TABLE `comment`
  MODIFY `comment_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT für Tabelle `email_request`
--
ALTER TABLE `email_request`
  MODIFY `email_request_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT für Tabelle `event`
--
ALTER TABLE `event`
  MODIFY `event_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `event_client`
--
ALTER TABLE `event_client`
  MODIFY `event_client_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `event_location`
--
ALTER TABLE `event_location`
  MODIFY `event_location_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `event_responsible`
--
ALTER TABLE `event_responsible`
  MODIFY `event_responsible_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `item`
--
ALTER TABLE `item`
  MODIFY `item_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT für Tabelle `item_type`
--
ALTER TABLE `item_type`
  MODIFY `item_type_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT für Tabelle `lend`
--
ALTER TABLE `lend`
  MODIFY `lend_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT für Tabelle `notification`
--
ALTER TABLE `notification`
  MODIFY `notification_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT für Tabelle `notification_request`
--
ALTER TABLE `notification_request`
  MODIFY `notification_request_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT für Tabelle `storage`
--
ALTER TABLE `storage`
  MODIFY `storage_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT für Tabelle `tag`
--
ALTER TABLE `tag`
  MODIFY `tag_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `tag_assignment`
--
ALTER TABLE `tag_assignment`
  MODIFY `tag_assignment_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT für Tabelle `todo_list`
--
ALTER TABLE `todo_list`
  MODIFY `todo_list_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT für Tabelle `todo_list_category`
--
ALTER TABLE `todo_list_category`
  MODIFY `todo_list_category_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT für Tabelle `todo_list_entry`
--
ALTER TABLE `todo_list_entry`
  MODIFY `todo_list_entry_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT für Tabelle `user_role`
--
ALTER TABLE `user_role`
  MODIFY `user_role_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `attachment`
--
ALTER TABLE `attachment`
  ADD CONSTRAINT `fx_attribute_type_idx` FOREIGN KEY (`attribute_type_id`) REFERENCES `attribute_type` (`attribute_type_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints der Tabelle `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `fx_comment_attribute_type_id` FOREIGN KEY (`attribute_type_id`) REFERENCES `attribute_type` (`attribute_type_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fx_comment_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints der Tabelle `email_request`
--
ALTER TABLE `email_request`
  ADD CONSTRAINT `fx_email_request_type_idx` FOREIGN KEY (`email_request_type_id`) REFERENCES `email_request_type` (`email_request_type_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fx_email_request_user_idx` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints der Tabelle `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `fx_event_event_client_idx` FOREIGN KEY (`event_client_id`) REFERENCES `event_client` (`event_client_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fx_event_event_location_id` FOREIGN KEY (`event_location_id`) REFERENCES `event_location` (`event_location_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints der Tabelle `event_responsible`
--
ALTER TABLE `event_responsible`
  ADD CONSTRAINT `fx_event_responsible_event_id` FOREIGN KEY (`event_id`) REFERENCES `event` (`event_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fx_event_responsible_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints der Tabelle `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `fx_item_type_id` FOREIGN KEY (`item_type_id`) REFERENCES `item_type` (`item_type_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fx_storage_idx` FOREIGN KEY (`storage_id`) REFERENCES `storage` (`storage_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints der Tabelle `lend`
--
ALTER TABLE `lend`
  ADD CONSTRAINT `fx_lend_user_idx` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `user_id ` FOREIGN KEY (`item_id`) REFERENCES `item` (`item_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints der Tabelle `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `fx_notification_notification_request_id` FOREIGN KEY (`notification_request_id`) REFERENCES `notification_request` (`notification_request_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints der Tabelle `notification_request`
--
ALTER TABLE `notification_request`
  ADD CONSTRAINT `fx_notification_request_attribute_type_id` FOREIGN KEY (`attribute_type_id`) REFERENCES `attribute_type` (`attribute_type_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fx_notification_request_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints der Tabelle `storage`
--
ALTER TABLE `storage`
  ADD CONSTRAINT `fx_storage_parent_idx` FOREIGN KEY (`storage_parent_id`) REFERENCES `storage` (`storage_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fx_storage_type_idx` FOREIGN KEY (`storage_type_id`) REFERENCES `storage_type` (`storage_type_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints der Tabelle `tag_assignment`
--
ALTER TABLE `tag_assignment`
  ADD CONSTRAINT `fx_tag_assignment_attribute_type_id` FOREIGN KEY (`attribute_type_id`) REFERENCES `attribute_type` (`attribute_type_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fx_tag_assignment_tag_id` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`tag_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints der Tabelle `todo_list`
--
ALTER TABLE `todo_list`
  ADD CONSTRAINT `	fx_todo_list_event_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fx_todo_list_event_id` FOREIGN KEY (`event_id`) REFERENCES `event` (`event_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fx_todo_list_todo_list_category_id` FOREIGN KEY (`todo_list_category_id`) REFERENCES `todo_list_category` (`todo_list_category_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints der Tabelle `todo_list_category`
--
ALTER TABLE `todo_list_category`
  ADD CONSTRAINT `fx_todo_list_category_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints der Tabelle `todo_list_entry`
--
ALTER TABLE `todo_list_entry`
  ADD CONSTRAINT `fx_todo_list_entry_parent_entry_id` FOREIGN KEY (`parent_entry_id`) REFERENCES `todo_list_entry` (`todo_list_entry_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fx_todo_list_entry_todo_list_id` FOREIGN KEY (`todo_list_id`) REFERENCES `todo_list` (`todo_list_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints der Tabelle `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fx_user_user_role_id` FOREIGN KEY (`role_id`) REFERENCES `user_role` (`user_role_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
