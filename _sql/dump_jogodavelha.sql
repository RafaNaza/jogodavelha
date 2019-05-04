-- Adminer 4.6.2 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE DATABASE `jogodavelha` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `jogodavelha`;

DROP TABLE IF EXISTS `registro_partida`;
CREATE TABLE `registro_partida` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jogador_vencedor` varchar(100) DEFAULT NULL,
  `jogadas_x` varchar(200) NOT NULL,
  `jogadas_o` varchar(200) NOT NULL,
  `jogada_vencedora` varchar(160) DEFAULT NULL,
  `marcador_vencedor` varchar(10) DEFAULT NULL,
  `quantidade_jogadas` varchar(10) DEFAULT NULL,
  `velha` set('S','N') NOT NULL DEFAULT 'N',
  `dificuldade` varchar(100) NOT NULL,
  `data_cadastro` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

TRUNCATE `registro_partida`;

-- 2019-05-04 05:28:10
