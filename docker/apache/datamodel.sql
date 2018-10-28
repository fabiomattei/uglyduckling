-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: mariadb
-- Creato il: Ott 28, 2018 alle 11:54
-- Versione del server: 10.1.21-MariaDB-1~jessie
-- Versione PHP: 7.2.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `firststep`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `documentsubscriptionv1`
--

CREATE TABLE `documentsubscriptionv1` (
  `id` int(11) UNSIGNED NOT NULL,
  `sourceuserid` int(11) UNSIGNED DEFAULT NULL,
  `sourcegroup` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `name` text COLLATE utf8_bin,
  `amount` decimal(16,2) DEFAULT NULL,
  `duedate` date DEFAULT NULL,
  `docstatus` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `doccreated` datetime DEFAULT NULL,
  `docupdated` datetime DEFAULT NULL,
  `docsent` datetime DEFAULT NULL,
  `docreceived` datetime DEFAULT NULL,
  `docrejected` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dump dei dati per la tabella `documentsubscriptionv1`
--

INSERT INTO `documentsubscriptionv1` (`id`, `sourceuserid`, `sourcegroup`, `name`, `amount`, `duedate`, `docstatus`, `doccreated`, `docupdated`, `docsent`, `docreceived`, `docrejected`) VALUES
(1, NULL, NULL, 'Gino', '12.00', '2018-10-20', 'DRAFT', '2018-10-14 09:25:20', '2018-10-14 09:25:20', NULL, NULL, NULL),
(2, 2, 'managergroup', 'Ginoa', '12.00', '2018-10-20', 'DRAFT', '2018-10-14 09:30:07', '2018-10-17 14:23:04', NULL, NULL, NULL),
(3, 2, 'managergroup', 'Ginob', '12.00', '2018-10-20', 'DRAFT', '2018-10-14 09:31:30', '2018-10-17 14:23:56', NULL, NULL, NULL),
(4, 2, 'managergroup', 'Gino', '12.00', '2018-10-20', 'DRAFT', '2018-10-14 09:31:50', '2018-10-14 09:31:50', NULL, NULL, NULL),
(5, 2, 'managergroup', 'Gino', '12.00', '2018-10-20', 'DRAFT', '2018-10-14 09:32:03', '2018-10-14 09:32:03', NULL, NULL, NULL),
(6, 2, 'managergroup', 'Gino', '12.00', '2018-10-20', 'DRAFT', '2018-10-14 09:32:20', '2018-10-14 09:32:20', NULL, NULL, NULL),
(7, 2, 'managergroup', 'Gino', '12.00', '2018-10-20', 'DRAFT', '2018-10-14 09:33:12', '2018-10-14 09:33:12', NULL, NULL, NULL),
(8, 2, 'managergroup', 'Gino', '12.00', '2018-10-20', 'DRAFT', '2018-10-14 09:34:20', '2018-10-14 09:34:20', NULL, NULL, NULL),
(9, 2, 'managergroup', 'Gino', '12.00', '2018-10-20', 'DRAFT', '2018-10-14 09:41:04', '2018-10-14 09:41:04', NULL, NULL, NULL),
(10, 2, 'managergroup', 'Gino', '12.00', '2018-10-20', 'DRAFT', '2018-10-14 09:42:05', '2018-10-14 09:42:05', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `projectfile`
--

CREATE TABLE `projectfile` (
  `prjfi_id` int(10) UNSIGNED NOT NULL,
  `prjfi_siteid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `prjfi_pwpid` int(10) UNSIGNED NOT NULL,
  `prjfi_usrid` int(10) UNSIGNED NOT NULL,
  `prjfi_name` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `prjfi_type` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `prjfi_size` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `prjfi_content` mediumblob NOT NULL,
  `prjfi_updated` datetime NOT NULL,
  `prjfi_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struttura della tabella `projectmessage`
--

CREATE TABLE `projectmessage` (
  `prjme_id` int(10) UNSIGNED NOT NULL,
  `prjme_siteid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `prjme_usrid` int(10) UNSIGNED NOT NULL,
  `prjme_pwpid` int(10) UNSIGNED NOT NULL,
  `prjme_prjmeid` int(10) UNSIGNED NOT NULL,
  `prjme_body` text COLLATE utf8_bin NOT NULL,
  `prjme_updated` datetime NOT NULL,
  `prjme_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struttura della tabella `projectmilestone`
--

CREATE TABLE `projectmilestone` (
  `prjmi_id` int(10) UNSIGNED NOT NULL,
  `prjmi_siteid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `prjmi_usrid` int(10) UNSIGNED NOT NULL,
  `prjmi_pwpid` int(10) UNSIGNED NOT NULL,
  `prjmi_body` text COLLATE utf8_bin NOT NULL,
  `prjmi_duedate` date NOT NULL,
  `prjmi_updated` datetime NOT NULL,
  `prjmi_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struttura della tabella `projecttask`
--

CREATE TABLE `projecttask` (
  `prjtk_id` int(10) UNSIGNED NOT NULL,
  `prjtk_siteid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `prjtk_usrid` int(10) UNSIGNED NOT NULL,
  `prjtk_pwpid` int(10) UNSIGNED NOT NULL,
  `prjtk_prjmiid` int(10) UNSIGNED NOT NULL,
  `prjtk_ownerid` int(10) UNSIGNED NOT NULL,
  `prjtk_body` text COLLATE utf8_bin NOT NULL,
  `prjtk_duedate` date NOT NULL,
  `prjtk_updated` datetime NOT NULL,
  `prjtk_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struttura della tabella `requestv1`
--

CREATE TABLE `requestv1` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `duedate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dump dei dati per la tabella `requestv1`
--

INSERT INTO `requestv1` (`id`, `name`, `amount`, `duedate`) VALUES
(1, 'Fabio', 13, '2018-08-25'),
(4, 'Gino', 12, '2018-10-20');

-- --------------------------------------------------------

--
-- Struttura della tabella `user`
--

CREATE TABLE `user` (
  `usr_id` bigint(20) NOT NULL,
  `usr_defaultgroup` varchar(80) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `usr_name` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `usr_surname` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `usr_email` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `usr_hashedpsw` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `usr_password_updated` datetime NOT NULL,
  `usr_updated` datetime NOT NULL,
  `usr_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dump dei dati per la tabella `user`
--

INSERT INTO `user` (`usr_id`, `usr_defaultgroup`, `usr_name`, `usr_surname`, `usr_email`, `usr_hashedpsw`, `usr_password_updated`, `usr_updated`, `usr_created`) VALUES
(1, 'administrationgroup', 'Admin', '', 'admin', '$2y$10$gUWxjdAJRE.KyWqEZh4w1.kRDAumgVamek.BBq.Li2CMkK7GGeeV2', '2018-10-28 10:04:49', '2018-10-21 10:13:39', '2018-10-21 00:00:00'),
(2, 'managergroup', 'Manager', '', 'manager', '$2y$10$YmsVMDQrYjnEdbkqfB5pNuLzyeMQep0C/ahaRWQSs/AN63/U5SXTW', '2018-08-07 00:00:00', '2018-08-07 00:00:00', '2018-08-07 00:00:00');

-- --------------------------------------------------------

--
-- Struttura della tabella `usergroup`
--

CREATE TABLE `usergroup` (
  `ug_id` int(10) UNSIGNED NOT NULL,
  `ug_groupslug` varchar(80) COLLATE utf8_bin NOT NULL DEFAULT '',
  `ug_userid` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `ug_updated` datetime NOT NULL,
  `ug_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `documentsubscriptionv1`
--
ALTER TABLE `documentsubscriptionv1`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `projectfile`
--
ALTER TABLE `projectfile`
  ADD PRIMARY KEY (`prjfi_id`);

--
-- Indici per le tabelle `projectmessage`
--
ALTER TABLE `projectmessage`
  ADD PRIMARY KEY (`prjme_id`);

--
-- Indici per le tabelle `projectmilestone`
--
ALTER TABLE `projectmilestone`
  ADD PRIMARY KEY (`prjmi_id`);

--
-- Indici per le tabelle `projecttask`
--
ALTER TABLE `projecttask`
  ADD PRIMARY KEY (`prjtk_id`);

--
-- Indici per le tabelle `requestv1`
--
ALTER TABLE `requestv1`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`usr_id`);

--
-- Indici per le tabelle `usergroup`
--
ALTER TABLE `usergroup`
  ADD PRIMARY KEY (`ug_id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `documentsubscriptionv1`
--
ALTER TABLE `documentsubscriptionv1`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT per la tabella `projectfile`
--
ALTER TABLE `projectfile`
  MODIFY `prjfi_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `projectmessage`
--
ALTER TABLE `projectmessage`
  MODIFY `prjme_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `projectmilestone`
--
ALTER TABLE `projectmilestone`
  MODIFY `prjmi_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `projecttask`
--
ALTER TABLE `projecttask`
  MODIFY `prjtk_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `requestv1`
--
ALTER TABLE `requestv1`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `user`
--
ALTER TABLE `user`
  MODIFY `usr_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `usergroup`
--
ALTER TABLE `usergroup`
  MODIFY `ug_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
