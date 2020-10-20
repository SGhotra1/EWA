-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 04. Jan 2020 um 16:37
-- Server-Version: 10.4.8-MariaDB
-- PHP-Version: 7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `pizzaservice`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `angebot`
--

CREATE TABLE `angebot` (
  `PizzaNummer` int(11) NOT NULL,
  `PizzaName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Bilddatei` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Preis` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `angebot`
--

INSERT INTO `angebot` (`PizzaNummer`, `PizzaName`, `Bilddatei`, `Preis`) VALUES
(1, 'Margherita', 'Pizza/Margherita.png', '5.50'),
(2, 'Hawaii', 'Pizza/pizza-hawaii.png', '6.50'),
(3, 'Salami', 'Pizza/pizza-salami.png', '6.25'),
(4, 'Hühnchen', 'Pizza/chicken.png', '6.73');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bestelltepizza`
--

CREATE TABLE `bestelltepizza` (
  `PizzaID` int(11) NOT NULL,
  `fBestellungID` int(11) NOT NULL,
  `fPizzaNummer` int(11) NOT NULL,
  `Status` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `bestelltepizza`
--

INSERT INTO `bestelltepizza` (`PizzaID`, `fBestellungID`, `fPizzaNummer`, `Status`) VALUES
(94, 124, 4, 'Fertig'),
(95, 124, 3, 'Im Ofen'),
(96, 124, 4, 'Bestellt'),
(97, 124, 3, 'Bestellt'),
(98, 125, 3, 'Bestellt'),
(99, 125, 2, 'Bestellt'),
(100, 126, 3, 'Bestellt'),
(101, 126, 4, 'Bestellt');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bestellung`
--

CREATE TABLE `bestellung` (
  `BestellungID` int(11) NOT NULL,
  `Adresse` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Bestellzeitpunkt` timestamp NOT NULL DEFAULT current_timestamp(),
  `KundeName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Postleitzahl` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Bestellstatus` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `bestellung`
--

INSERT INTO `bestellung` (`BestellungID`, `Adresse`, `Bestellzeitpunkt`, `KundeName`, `Postleitzahl`, `Bestellstatus`) VALUES
(124, 'testad', '2020-01-01 19:29:09', 'JSTEST', '99999', 'Unterwegs'),
(125, 'adresse', '2020-01-02 13:08:26', 'test', '00000', 'Bestellt'),
(126, 'adr', '2020-01-02 14:16:27', 'kundenname', '55555', 'Unterwegs');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `angebot`
--
ALTER TABLE `angebot`
  ADD PRIMARY KEY (`PizzaNummer`);

--
-- Indizes für die Tabelle `bestelltepizza`
--
ALTER TABLE `bestelltepizza`
  ADD PRIMARY KEY (`PizzaID`),
  ADD KEY `fBestellungID` (`fBestellungID`);

--
-- Indizes für die Tabelle `bestellung`
--
ALTER TABLE `bestellung`
  ADD PRIMARY KEY (`BestellungID`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `angebot`
--
ALTER TABLE `angebot`
  MODIFY `PizzaNummer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT für Tabelle `bestelltepizza`
--
ALTER TABLE `bestelltepizza`
  MODIFY `PizzaID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT für Tabelle `bestellung`
--
ALTER TABLE `bestellung`
  MODIFY `BestellungID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
