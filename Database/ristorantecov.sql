-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Giu 06, 2020 alle 12:19
-- Versione del server: 10.4.8-MariaDB
-- Versione PHP: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ristorantecov`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `articolo`
--

CREATE TABLE `articolo` (
  `idArticolo` int(11) NOT NULL,
  `quantità` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `articolo`
--

INSERT INTO `articolo` (`idArticolo`, `quantità`) VALUES
(48, 7),
(49, 9);

-- --------------------------------------------------------

--
-- Struttura della tabella `composto`
--

CREATE TABLE `composto` (
  `idProdotto` int(11) NOT NULL,
  `oraOrdine` time NOT NULL,
  `dataOrdine` date NOT NULL,
  `quantitàOrdinata` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `dispensa`
--

CREATE TABLE `dispensa` (
  `idDispensa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `dispensa`
--

INSERT INTO `dispensa` (`idDispensa`) VALUES
(1);

-- --------------------------------------------------------

--
-- Struttura della tabella `file`
--

CREATE TABLE `file` (
  `idFile` int(11) NOT NULL,
  `nomeFile` varchar(32) DEFAULT NULL,
  `sizeFile` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `file`
--

INSERT INTO `file` (`idFile`, `nomeFile`, `sizeFile`) VALUES
(1, 'pizzaAnans.txt', '231'),
(2, 'pastaCarbonara.txt', '254'),
(3, 'risottoMilan.txt', '928'),
(51, 'App.txt', '10060'),
(52, 'img5.jpg', '7827'),
(53, 'acqua.jpg', '3289'),
(54, 'Lasagna.txt', '10060');

-- --------------------------------------------------------

--
-- Struttura della tabella `ingrediente`
--

CREATE TABLE `ingrediente` (
  `idIngrediente` int(11) NOT NULL,
  `idDispensa` int(11) DEFAULT NULL,
  `quantitàInDispensa` int(11) DEFAULT NULL,
  `nomeIngrediente` varchar(64) DEFAULT NULL,
  `descrizioneIngrediente` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `ingrediente`
--

INSERT INTO `ingrediente` (`idIngrediente`, `idDispensa`, `quantitàInDispensa`, `nomeIngrediente`, `descrizioneIngrediente`) VALUES
(1, 1, 9100, 'farina', 'farina di tipo 00 '),
(2, 1, 2700, 'riso', 'riso integrale'),
(3, 1, 10, 'ananas', 'ananas tropicale'),
(9, 1, 0, 'ragu', 'ragu buono'),
(10, 1, 0, 'besciamella', 'besciamella buona');

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `num_ing_prod`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `num_ing_prod` (
`idP` int(11)
,`numP` bigint(21)
);

-- --------------------------------------------------------

--
-- Struttura della tabella `ordine`
--

CREATE TABLE `ordine` (
  `oraOrdine` time NOT NULL,
  `dataOrdine` date NOT NULL,
  `idTavolo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `piatto_pronto`
--

CREATE TABLE `piatto_pronto` (
  `idPiatto` int(11) NOT NULL,
  `idProdotto` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `piatto_pronto`
--

INSERT INTO `piatto_pronto` (`idPiatto`, `idProdotto`) VALUES
(19, 2),
(23, 2),
(14, 3),
(24, 47),
(25, 47);

-- --------------------------------------------------------

--
-- Struttura della tabella `prodotto`
--

CREATE TABLE `prodotto` (
  `idProdotto` int(11) NOT NULL,
  `idFile` int(11) DEFAULT NULL,
  `tipo` enum('ricetta','articolo') DEFAULT NULL,
  `nomeProdotto` varchar(128) DEFAULT NULL,
  `prezzo` decimal(5,2) DEFAULT NULL,
  `tempoRichiesto` time DEFAULT NULL,
  `img` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `prodotto`
--

INSERT INTO `prodotto` (`idProdotto`, `idFile`, `tipo`, `nomeProdotto`, `prezzo`, `tempoRichiesto`, `img`) VALUES
(1, 2, 'ricetta', 'Pasta alla Carbonara', '11.00', '00:17:00', 'img1.jpg'),
(2, 1, 'ricetta', 'Pizza all\'ananas', '15.00', '00:30:00', 'img2.jpg'),
(3, 3, 'ricetta', 'Risotto alla milanese', '9.99', '00:15:00', 'img3.jpg'),
(47, 51, 'ricetta', 'RaguBolognese', '12.00', '00:32:00', 'img4.jpg'),
(48, 52, 'articolo', 'CocaCola', '2.00', NULL, NULL),
(49, 53, 'articolo', 'Acqua', '1.00', NULL, NULL),
(50, 54, 'ricetta', 'Lasagna', '15.00', '00:23:00', 'lasagna.jpg');

-- --------------------------------------------------------

--
-- Struttura della tabella `tavolo`
--

CREATE TABLE `tavolo` (
  `idTavolo` int(11) NOT NULL,
  `numSedie` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `usato`
--

CREATE TABLE `usato` (
  `idIngrediente` int(11) NOT NULL,
  `idProdotto` int(11) NOT NULL,
  `quantitàUsata` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `usato`
--

INSERT INTO `usato` (`idIngrediente`, `idProdotto`, `quantitàUsata`) VALUES
(1, 2, 300),
(2, 3, 150),
(3, 2, 20),
(9, 47, 100),
(10, 50, 150);

-- --------------------------------------------------------

--
-- Struttura per vista `num_ing_prod`
--
DROP TABLE IF EXISTS `num_ing_prod`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `num_ing_prod`  AS  select `p`.`idProdotto` AS `idP`,count(`p`.`idProdotto`) AS `numP` from ((`prodotto` `p` join `usato` `u` on(`p`.`idProdotto` = `u`.`idProdotto`)) join `ingrediente` `i` on(`i`.`idIngrediente` = `u`.`idIngrediente`)) where `p`.`tipo` = 'ricetta' group by `p`.`idProdotto` ;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `articolo`
--
ALTER TABLE `articolo`
  ADD PRIMARY KEY (`idArticolo`);

--
-- Indici per le tabelle `composto`
--
ALTER TABLE `composto`
  ADD PRIMARY KEY (`idProdotto`,`oraOrdine`,`dataOrdine`),
  ADD KEY `oraOrdine` (`oraOrdine`,`dataOrdine`);

--
-- Indici per le tabelle `dispensa`
--
ALTER TABLE `dispensa`
  ADD PRIMARY KEY (`idDispensa`);

--
-- Indici per le tabelle `file`
--
ALTER TABLE `file`
  ADD PRIMARY KEY (`idFile`),
  ADD UNIQUE KEY `nomeFile` (`nomeFile`);

--
-- Indici per le tabelle `ingrediente`
--
ALTER TABLE `ingrediente`
  ADD PRIMARY KEY (`idIngrediente`),
  ADD UNIQUE KEY `nomeIngrediente` (`nomeIngrediente`),
  ADD KEY `idDispensa` (`idDispensa`);

--
-- Indici per le tabelle `ordine`
--
ALTER TABLE `ordine`
  ADD PRIMARY KEY (`oraOrdine`,`dataOrdine`);

--
-- Indici per le tabelle `piatto_pronto`
--
ALTER TABLE `piatto_pronto`
  ADD PRIMARY KEY (`idPiatto`),
  ADD KEY `idProdotto` (`idProdotto`);

--
-- Indici per le tabelle `prodotto`
--
ALTER TABLE `prodotto`
  ADD PRIMARY KEY (`idProdotto`),
  ADD UNIQUE KEY `nomeProdotto` (`nomeProdotto`),
  ADD UNIQUE KEY `img` (`img`),
  ADD KEY `idFile` (`idFile`);

--
-- Indici per le tabelle `tavolo`
--
ALTER TABLE `tavolo`
  ADD PRIMARY KEY (`idTavolo`);

--
-- Indici per le tabelle `usato`
--
ALTER TABLE `usato`
  ADD PRIMARY KEY (`idIngrediente`,`idProdotto`),
  ADD KEY `idProdotto` (`idProdotto`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `articolo`
--
ALTER TABLE `articolo`
  MODIFY `idArticolo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT per la tabella `dispensa`
--
ALTER TABLE `dispensa`
  MODIFY `idDispensa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT per la tabella `file`
--
ALTER TABLE `file`
  MODIFY `idFile` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT per la tabella `ingrediente`
--
ALTER TABLE `ingrediente`
  MODIFY `idIngrediente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT per la tabella `piatto_pronto`
--
ALTER TABLE `piatto_pronto`
  MODIFY `idPiatto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT per la tabella `prodotto`
--
ALTER TABLE `prodotto`
  MODIFY `idProdotto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `articolo`
--
ALTER TABLE `articolo`
  ADD CONSTRAINT `articolo_ibfk_1` FOREIGN KEY (`idArticolo`) REFERENCES `prodotto` (`idProdotto`);

--
-- Limiti per la tabella `composto`
--
ALTER TABLE `composto`
  ADD CONSTRAINT `composto_ibfk_1` FOREIGN KEY (`oraOrdine`,`dataOrdine`) REFERENCES `ordine` (`oraOrdine`, `dataOrdine`),
  ADD CONSTRAINT `composto_ibfk_2` FOREIGN KEY (`idProdotto`) REFERENCES `prodotto` (`idProdotto`);

--
-- Limiti per la tabella `ingrediente`
--
ALTER TABLE `ingrediente`
  ADD CONSTRAINT `ingrediente_ibfk_1` FOREIGN KEY (`idDispensa`) REFERENCES `dispensa` (`idDispensa`);

--
-- Limiti per la tabella `ordine`
--
ALTER TABLE `ordine`
  ADD CONSTRAINT `ordine_ibfk_1` FOREIGN KEY (`idTavolo`) REFERENCES `tavolo` (`idTavolo`);

--
-- Limiti per la tabella `piatto_pronto`
--
ALTER TABLE `piatto_pronto`
  ADD CONSTRAINT `piatto_pronto_ibfk_1` FOREIGN KEY (`idProdotto`) REFERENCES `prodotto` (`idProdotto`);

--
-- Limiti per la tabella `prodotto`
--
ALTER TABLE `prodotto`
  ADD CONSTRAINT `prodotto_ibfk_1` FOREIGN KEY (`idFile`) REFERENCES `file` (`idFile`);

--
-- Limiti per la tabella `usato`
--
ALTER TABLE `usato`
  ADD CONSTRAINT `usato_ibfk_1` FOREIGN KEY (`idProdotto`) REFERENCES `prodotto` (`idProdotto`),
  ADD CONSTRAINT `usato_ibfk_2` FOREIGN KEY (`idIngrediente`) REFERENCES `ingrediente` (`idIngrediente`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
