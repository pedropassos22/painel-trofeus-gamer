DROP DATABASE IF EXISTS painel-trofeus-gamer;

CREATE DATABASE painel-trofeus-gamer
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE painel-trofeus-gamer;

CREATE TABLE `jogos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(150) NOT NULL,
  `horas` int(10) unsigned NOT NULL,
  `arquivo_save` varchar(255) DEFAULT NULL,
  `avaliacao` tinyint(3) unsigned NOT NULL,
  `comentario` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_c