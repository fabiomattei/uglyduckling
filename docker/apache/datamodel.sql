-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: mariadb
-- Creato il: Ago 07, 2018 alle 13:14
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
-- Struttura della tabella `asset`
--

CREATE TABLE `asset` (
  `as_id` int(10) UNSIGNED NOT NULL,
  `as_prsid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `as_siteid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `as_usrid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `as_offid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `as_archived` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `as_hash` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `as_slug` varchar(80) COLLATE utf8_bin NOT NULL DEFAULT '',
  `as_base_slug` varchar(80) COLLATE utf8_bin NOT NULL DEFAULT '',
  `as_family_slug` varchar(80) COLLATE utf8_bin NOT NULL DEFAULT '',
  `as_status` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `as_deleted` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `as_title` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `as_notes` text COLLATE utf8_bin NOT NULL,
  `as_data` longtext COLLATE utf8_bin NOT NULL,
  `as_iind1` int(11) NOT NULL DEFAULT '0',
  `as_iind2` int(11) NOT NULL DEFAULT '0',
  `as_iind3` int(11) NOT NULL DEFAULT '0',
  `as_iind4` int(11) NOT NULL DEFAULT '0',
  `as_iind5` int(11) NOT NULL DEFAULT '0',
  `as_tind1` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `as_tind2` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `as_tind3` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `as_tind4` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `as_tind5` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `as_dind1` decimal(10,2) NOT NULL DEFAULT '0.00',
  `as_dind2` decimal(10,2) NOT NULL DEFAULT '0.00',
  `as_dateind1` date NOT NULL,
  `as_dateind2` date NOT NULL,
  `as_timeind1` time NOT NULL,
  `as_timeind2` time NOT NULL,
  `as_updated` datetime NOT NULL,
  `as_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struttura della tabella `assetmovementsbook`
--

CREATE TABLE `assetmovementsbook` (
  `asmb_id` int(10) UNSIGNED NOT NULL,
  `asmb_siteid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `asmb_usrid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `asmb_offid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `asmb_asid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `asmb_movement` int(11) NOT NULL DEFAULT '0',
  `asmb_updated` datetime NOT NULL,
  `asmb_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `forminstance`
--

CREATE TABLE `forminstance` (
  `fi_id` int(10) UNSIGNED NOT NULL,
  `fi_prsid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `fi_siteid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `fi_usrid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `fi_offid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `fi_owner` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `fi_main` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `fi_archived` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `fi_hash` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `fi_slug` varchar(80) COLLATE utf8_bin NOT NULL DEFAULT '',
  `fi_base_slug` varchar(80) COLLATE utf8_bin NOT NULL DEFAULT '',
  `fi_family_slug` varchar(80) COLLATE utf8_bin NOT NULL DEFAULT '',
  `fi_flowslug` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `fi_status` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `fi_deleted` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `fi_title` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `fi_notes` text COLLATE utf8_bin NOT NULL,
  `fi_data` longtext COLLATE utf8_bin NOT NULL,
  `fi_iind1` int(11) NOT NULL DEFAULT '0',
  `fi_iind2` int(11) NOT NULL DEFAULT '0',
  `fi_iind3` int(11) NOT NULL DEFAULT '0',
  `fi_iind4` int(11) NOT NULL DEFAULT '0',
  `fi_iind5` int(11) NOT NULL DEFAULT '0',
  `fi_tind1` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `fi_tind2` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `fi_tind3` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `fi_tind4` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `fi_tind5` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `fi_dind1` decimal(10,2) NOT NULL DEFAULT '0.00',
  `fi_dind2` decimal(10,2) NOT NULL DEFAULT '0.00',
  `fi_dateind1` date NOT NULL,
  `fi_dateind2` date NOT NULL,
  `fi_timeind1` time NOT NULL,
  `fi_timeind2` time NOT NULL,
  `fi_updated` datetime NOT NULL,
  `fi_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dump dei dati per la tabella `forminstance`
--

INSERT INTO `forminstance` (`fi_id`, `fi_prsid`, `fi_siteid`, `fi_usrid`, `fi_offid`, `fi_owner`, `fi_main`, `fi_archived`, `fi_hash`, `fi_slug`, `fi_base_slug`, `fi_family_slug`, `fi_flowslug`, `fi_status`, `fi_deleted`, `fi_title`, `fi_notes`, `fi_data`, `fi_iind1`, `fi_iind2`, `fi_iind3`, `fi_iind4`, `fi_iind5`, `fi_tind1`, `fi_tind2`, `fi_tind3`, `fi_tind4`, `fi_tind5`, `fi_dind1`, `fi_dind2`, `fi_dateind1`, `fi_dateind2`, `fi_timeind1`, `fi_timeind2`, `fi_updated`, `fi_created`) VALUES
(1, 1, 0, 0, 0, 0, 0, 0, 'btUDjoyXRZz2qavHHs9Fanw6iatRvsIXR8PgpvQ6PZ90dlYclCxyqfbvXiGOn8mxtJZ5HkRVLIDb', 'expensev1', 'expense', 'expense', '', 'received', 0, 'prova richiesta', '', '<?xml version=\"1.0\" encoding=\"UTF-8\" ?><nodes><description>prova richiesta</description><amount>2</amount><duedate>2017-05-09</duedate></nodes>', 0, 0, 0, 0, 0, '', '', '', '', '', '2.00', '0.00', '0000-00-00', '0000-00-00', '00:00:00', '00:00:00', '2017-05-09 18:44:14', '2017-05-09 18:44:14'),
(2, 2, 0, 0, 0, 0, 1, 0, 'tzmASxS2xLLXTuLfEDONYZOJBfVvjrUpcvyjwfjq31JZAvl7wGmUjyxQBycQhOVtqoJ145g2jlQJ', 'expensev1', 'expense', 'expense', '', '', 0, 'povra 10 &euro; ', '', '<?xml version=\"1.0\" encoding=\"UTF-8\" ?><nodes><description>povra</description><amount>10</amount><duedate>2017-06-26</duedate></nodes>', 0, 0, 0, 0, 0, 'povra', '', '', '', '', '10.00', '0.00', '2017-06-26', '2017-06-26', '15:15:53', '15:15:53', '2017-06-26 15:15:53', '2017-06-26 15:15:53'),
(3, 3, 0, 0, 0, 0, 1, 0, 'Xt5q7W3KruNDR1vl3XmSdlkkKEIO3TlWQRrAz6NqsyduIiF78jgj1u9LEuw5MwpzosUM7XSfwWCk', 'expensev1', 'expense', 'expense', '', '', 0, 'xxx 0.04 &euro; ', '', '<?xml version=\"1.0\" encoding=\"UTF-8\" ?><nodes><description>xxx</description><amount>0.04</amount><duedate>2018-03-04</duedate></nodes>', 0, 0, 0, 0, 0, 'xxx', '', '', '', '', '0.04', '0.00', '2018-03-04', '2018-03-04', '21:03:09', '21:03:09', '2018-03-04 21:03:09', '2018-03-04 21:03:09');

-- --------------------------------------------------------

--
-- Struttura della tabella `planning`
--

CREATE TABLE `planning` (
  `pl_id` int(10) UNSIGNED NOT NULL,
  `pl_siteid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `pl_usrid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `pl_offid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `pl_type` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `pl_startdate` date NOT NULL,
  `pl_endingdate` date NOT NULL,
  `pl_monday` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `pl_tuesday` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `pl_wednesday` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `pl_thursday` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `pl_friday` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `pl_saturday` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `pl_sunday` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `pl_dayofmonth` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `pl_paperdata` longtext COLLATE utf8_bin NOT NULL,
  `pl_paperslug` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `pl_papertitle` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `pl_updated` datetime NOT NULL,
  `pl_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struttura della tabella `process`
--

CREATE TABLE `process` (
  `pr_id` int(10) UNSIGNED NOT NULL,
  `pr_siteid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `pr_offid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `pr_usrid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `pr_chapter_slug` varchar(255) NOT NULL DEFAULT '',
  `pr_base_slug` varchar(255) NOT NULL DEFAULT '',
  `pr_slug` varchar(255) NOT NULL DEFAULT '',
  `pr_deleted` tinyint(4) NOT NULL DEFAULT '0',
  `pr_closed` tinyint(4) NOT NULL DEFAULT '0',
  `pr_title` varchar(255) NOT NULL DEFAULT '',
  `pr_laststep` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `pr_updated` datetime NOT NULL,
  `pr_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `process`
--

INSERT INTO `process` (`pr_id`, `pr_siteid`, `pr_offid`, `pr_usrid`, `pr_chapter_slug`, `pr_base_slug`, `pr_slug`, `pr_deleted`, `pr_closed`, `pr_title`, `pr_laststep`, `pr_updated`, `pr_created`) VALUES
(1, 0, 0, 0, 'expense', 'expense', 'expenseprocessv1', 0, 0, 'prova richiesta', 1, '2017-05-09 18:44:14', '2017-05-09 18:44:14'),
(2, 0, 0, 0, 'expense', 'expense', 'expenseprocessv1', 0, 0, 'povra 10 &euro; ', 1, '2017-06-26 15:15:53', '2017-06-26 15:15:53'),
(3, 0, 0, 0, 'expense', 'expense', 'expenseprocessv1', 0, 0, 'xxx 0.04 &euro; ', 1, '2018-03-04 21:03:09', '2018-03-04 21:03:09');

-- --------------------------------------------------------

--
-- Struttura della tabella `processstep`
--

CREATE TABLE `processstep` (
  `prs_id` int(10) UNSIGNED NOT NULL,
  `prs_prid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `prs_siteid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `prs_offid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `prs_usrid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `prs_title` varchar(255) NOT NULL DEFAULT '',
  `prs_step` tinyint(4) NOT NULL DEFAULT '0',
  `prs_sent` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `prs_completed` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `prs_updated` datetime NOT NULL,
  `prs_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `processstep`
--

INSERT INTO `processstep` (`prs_id`, `prs_prid`, `prs_siteid`, `prs_offid`, `prs_usrid`, `prs_title`, `prs_step`, `prs_sent`, `prs_completed`, `prs_updated`, `prs_created`) VALUES
(1, 1, 1, 1, 1, 'prova richiesta', 1, 0, 0, '2017-05-09 18:44:14', '2017-05-09 18:44:14'),
(2, 2, 1, 1, 1, 'povra 10 &euro; ', 1, 0, 0, '2017-06-26 15:15:53', '2017-06-26 15:15:53'),
(3, 3, 1, 1, 1, 'xxx 0.04 &euro; ', 1, 0, 0, '2018-03-04 21:03:09', '2018-03-04 21:03:09');

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
-- Struttura della tabella `pwarchive`
--

CREATE TABLE `pwarchive` (
  `fi_id` int(10) UNSIGNED NOT NULL,
  `fi_prsid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `fi_siteid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `fi_usrid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `fi_offid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `fi_owner` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `fi_hash` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `fi_slug` varchar(80) COLLATE utf8_bin NOT NULL DEFAULT '',
  `fi_base_slug` varchar(80) COLLATE utf8_bin NOT NULL DEFAULT '',
  `fi_family_slug` varchar(80) COLLATE utf8_bin NOT NULL DEFAULT '',
  `fi_flowslug` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `fi_status` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `fi_deleted` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `fi_title` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `fi_notes` text COLLATE utf8_bin NOT NULL,
  `fi_data` longtext COLLATE utf8_bin NOT NULL,
  `fi_iind1` int(11) NOT NULL DEFAULT '0',
  `fi_iind2` int(11) NOT NULL DEFAULT '0',
  `fi_iind3` int(11) NOT NULL DEFAULT '0',
  `fi_iind4` int(11) NOT NULL DEFAULT '0',
  `fi_iind5` int(11) NOT NULL DEFAULT '0',
  `fi_tind1` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `fi_tind2` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `fi_tind3` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `fi_tind4` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `fi_tind5` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `fi_dind1` decimal(10,2) NOT NULL DEFAULT '0.00',
  `fi_dind2` decimal(10,2) NOT NULL DEFAULT '0.00',
  `fi_dateind1` date NOT NULL,
  `fi_dateind2` date NOT NULL,
  `fi_timeind1` time NOT NULL,
  `fi_timeind2` time NOT NULL,
  `fi_updated` datetime NOT NULL,
  `fi_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struttura della tabella `pwownerlog`
--

CREATE TABLE `pwownerlog` (
  `pwol_id` int(10) UNSIGNED NOT NULL,
  `pwol_siteid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `pwol_pwpid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `pwol_usrid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `pwol_starting_owner_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `pwol_ending_owner_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `pwol_note` text NOT NULL,
  `pwol_updated` datetime NOT NULL,
  `pwol_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `pwstatuslog`
--

CREATE TABLE `pwstatuslog` (
  `rpsl_id` int(10) UNSIGNED NOT NULL,
  `rpsl_siteid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `rpsl_usrid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `rpsl_pwpid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `rpsl_starting_state_slug` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `rpsl_ending_state_slug` varchar(255) COLLATE utf8_bin NOT NULL,
  `rpsl_note` text COLLATE utf8_bin NOT NULL,
  `rpsl_updated` datetime NOT NULL,
  `rpsl_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struttura della tabella `pwvisibility`
--

CREATE TABLE `pwvisibility` (
  `pwv_id` int(10) UNSIGNED NOT NULL,
  `pwv_siteid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `pwv_pwpid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `pwv_offid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `pwv_usrid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `pwv_updated` datetime NOT NULL,
  `pwv_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struttura della tabella `user`
--

CREATE TABLE `user` (
  `usr_id` bigint(20) NOT NULL,
  `usr_siteid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `usr_usrofid` int(10) UNSIGNED NOT NULL DEFAULT '0',
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

INSERT INTO `user` (`usr_id`, `usr_siteid`, `usr_usrofid`, `usr_name`, `usr_surname`, `usr_email`, `usr_hashedpsw`, `usr_password_updated`, `usr_updated`, `usr_created`) VALUES
(1, 1, 99, 'Admin', '', 'admin', '$2y$10$lisaKfP5VQ6.UM.AdN8C1u696UZnnVGc.eSDytaTC3eFtIf9XLM7q', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 1, 1, 'Manager', '', 'manager', '$2y$10$YmsVMDQrYjnEdbkqfB5pNuLzyeMQep0C/ahaRWQSs/AN63/U5SXTW', '2018-08-07 00:00:00', '2018-08-07 00:00:00', '2018-08-07 00:00:00');

-- --------------------------------------------------------

--
-- Struttura della tabella `useroffice`
--

CREATE TABLE `useroffice` (
  `usroff_id` int(10) UNSIGNED NOT NULL,
  `usroff_usrid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `usroff_offid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `usroff_default` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `usroff_updated` datetime NOT NULL,
  `usroff_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struttura della tabella `usroffice`
--

CREATE TABLE `usroffice` (
  `usrof_id` int(10) UNSIGNED NOT NULL,
  `usrof_slug` varchar(80) COLLATE utf8_bin NOT NULL DEFAULT '',
  `usrof_name` varchar(80) COLLATE utf8_bin NOT NULL DEFAULT '',
  `usrof_description` text COLLATE utf8_bin NOT NULL,
  `usrof_updated` datetime NOT NULL,
  `usrof_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dump dei dati per la tabella `usroffice`
--

INSERT INTO `usroffice` (`usrof_id`, `usrof_slug`, `usrof_name`, `usrof_description`, `usrof_updated`, `usrof_created`) VALUES
(1, 'admin', 'Admin', '', '2016-05-05 00:00:00', '2016-05-05 00:00:00');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `asset`
--
ALTER TABLE `asset`
  ADD PRIMARY KEY (`as_id`);

--
-- Indici per le tabelle `assetmovementsbook`
--
ALTER TABLE `assetmovementsbook`
  ADD PRIMARY KEY (`asmb_id`);

--
-- Indici per le tabelle `forminstance`
--
ALTER TABLE `forminstance`
  ADD PRIMARY KEY (`fi_id`);

--
-- Indici per le tabelle `planning`
--
ALTER TABLE `planning`
  ADD PRIMARY KEY (`pl_id`);

--
-- Indici per le tabelle `process`
--
ALTER TABLE `process`
  ADD PRIMARY KEY (`pr_id`);

--
-- Indici per le tabelle `processstep`
--
ALTER TABLE `processstep`
  ADD PRIMARY KEY (`prs_id`);

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
-- Indici per le tabelle `pwarchive`
--
ALTER TABLE `pwarchive`
  ADD PRIMARY KEY (`fi_id`);

--
-- Indici per le tabelle `pwownerlog`
--
ALTER TABLE `pwownerlog`
  ADD PRIMARY KEY (`pwol_id`);

--
-- Indici per le tabelle `pwstatuslog`
--
ALTER TABLE `pwstatuslog`
  ADD PRIMARY KEY (`rpsl_id`);

--
-- Indici per le tabelle `pwvisibility`
--
ALTER TABLE `pwvisibility`
  ADD PRIMARY KEY (`pwv_id`);

--
-- Indici per le tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`usr_id`);

--
-- Indici per le tabelle `useroffice`
--
ALTER TABLE `useroffice`
  ADD PRIMARY KEY (`usroff_id`);

--
-- Indici per le tabelle `usroffice`
--
ALTER TABLE `usroffice`
  ADD PRIMARY KEY (`usrof_id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `asset`
--
ALTER TABLE `asset`
  MODIFY `as_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `assetmovementsbook`
--
ALTER TABLE `assetmovementsbook`
  MODIFY `asmb_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `forminstance`
--
ALTER TABLE `forminstance`
  MODIFY `fi_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `planning`
--
ALTER TABLE `planning`
  MODIFY `pl_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `process`
--
ALTER TABLE `process`
  MODIFY `pr_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `processstep`
--
ALTER TABLE `processstep`
  MODIFY `prs_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
-- AUTO_INCREMENT per la tabella `pwarchive`
--
ALTER TABLE `pwarchive`
  MODIFY `fi_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `pwownerlog`
--
ALTER TABLE `pwownerlog`
  MODIFY `pwol_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `pwstatuslog`
--
ALTER TABLE `pwstatuslog`
  MODIFY `rpsl_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `pwvisibility`
--
ALTER TABLE `pwvisibility`
  MODIFY `pwv_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `user`
--
ALTER TABLE `user`
  MODIFY `usr_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `useroffice`
--
ALTER TABLE `useroffice`
  MODIFY `usroff_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `usroffice`
--
ALTER TABLE `usroffice`
  MODIFY `usrof_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
