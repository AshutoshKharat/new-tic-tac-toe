-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.24-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.6.0.6765
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for tictactoe_db
CREATE DATABASE IF NOT EXISTS `tictactoe_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `tictactoe_db`;

-- Dumping structure for table tictactoe_db.tic_tac_toe
CREATE TABLE IF NOT EXISTS `tic_tac_toe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_x` char(1) DEFAULT NULL,
  `player_o` char(1) DEFAULT NULL,
  `board_state` varchar(1000) DEFAULT NULL,
  `winner` char(1) DEFAULT NULL,
  `current_player` char(1) DEFAULT NULL,
  `next_player` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table tictactoe_db.tic_tac_toe: ~0 rows (approximately)
DELETE FROM `tic_tac_toe`;

-- Dumping structure for table tictactoe_db.winners
CREATE TABLE IF NOT EXISTS `winners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `winner` char(1) DEFAULT NULL,
  `board_state` varchar(1000) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table tictactoe_db.winners: ~0 rows (approximately)
DELETE FROM `winners`;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
