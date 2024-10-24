-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 02, 2012 at 02:32 AM
-- Server version: 5.1.58
-- PHP Version: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ocrpg`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE IF NOT EXISTS `accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `conta` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `registered` int(11) NOT NULL DEFAULT '0',
  `last_active` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(255) NOT NULL DEFAULT '',
  `last_ip` varchar(255) NOT NULL DEFAULT '',
  `validkey` varchar(255) NOT NULL DEFAULT '',
  `remember` enum('t','f') NOT NULL DEFAULT 'f',
  `name` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT '',
  `sex` enum('m','f','n') NOT NULL DEFAULT 'n',
  `ref` varchar(11) NOT NULL DEFAULT '',
  `creditos` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1433 ;

-- --------------------------------------------------------

--
-- Table structure for table `account_log`
--

CREATE TABLE IF NOT EXISTS `account_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL DEFAULT '0',
  `msg` text CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `time` int(11) NOT NULL DEFAULT '0',
  `status` enum('read','unread') NOT NULL DEFAULT 'unread',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=120 ;

-- --------------------------------------------------------

--
-- Table structure for table `allquests`
--

CREATE TABLE IF NOT EXISTS `allquests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  `lvl` int(11) NOT NULL,
  `to_lvl` int(11) NOT NULL DEFAULT '0',
  `prize` varchar(255) NOT NULL,
  `cost` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `allquests`
--

INSERT INTO `allquests` (`id`, `name`, `desc`, `lvl`, `to_lvl`, `prize`, `cost`) VALUES
(5, 'Treinamento do Assassino', 'Prove que você já está pronto para ser um guerreiro! Treine com o grande mestre Hastakk e adquira experiência rapidamente.', 25, 35, '3 níveis', 3800),
(4, 'A Mansão de Lord Drofus', 'Trevus está sem tempo para fazer uma entrega para o poderoso Lord Drofus. Ajude-o a fazer a entrega e ganhe a habilidade de transferir items.', 40, 0, 'Habilidade para transferir ouro e items', 8000);

-- --------------------------------------------------------

--
-- Table structure for table `attacked`
--

CREATE TABLE IF NOT EXISTS `attacked` (
  `player_id` int(11) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL DEFAULT '0',
  `attacker_id` int(11) NOT NULL DEFAULT '0',
  KEY `Otimizacao1` (`player_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bans`
--

CREATE TABLE IF NOT EXISTS `bans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` mediumint(11) NOT NULL DEFAULT '0',
  `msg` text CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Table structure for table `bixos`
--

CREATE TABLE IF NOT EXISTS `bixos` (
  `player_id` int(11) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL DEFAULT '0',
  `hp` int(11) NOT NULL DEFAULT '0',
  `magia` int(11) NOT NULL DEFAULT '0',
  `turnos` int(11) NOT NULL DEFAULT '0',
  `quest` enum('t','f') NOT NULL DEFAULT 'f',
  `vez` enum('p','e') NOT NULL DEFAULT 'p',
  `type` tinyint(2) NOT NULL DEFAULT '0',
  `mana` int(11) NOT NULL DEFAULT '0',
  `mul` tinyint(2) NOT NULL DEFAULT '1',
  KEY `Otimizacao2` (`player_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bixos`
--

INSERT INTO `bixos` (`player_id`, `id`, `hp`, `magia`, `turnos`, `quest`, `vez`, `type`, `mana`, `mul`) VALUES
(7, 1, 0, 0, 0, 'f', 'p', 99, 135, 9),
(25, 1, 0, 0, 0, 'f', 'e', 99, 15, 1),
(56, 2, 0, 0, 0, 'f', 'p', 99, 20, 1),
(33, 2, 0, 0, 0, 'f', 'p', 99, 20, 1),
(74, 1, 0, 0, 0, 'f', 'p', 99, 75, 5),
(93, 1, 0, 0, 0, 'f', 'e', 99, 15, 1),
(114, 1, 0, 0, 0, 'f', 'e', 99, 0, 1),
(124, 1, 0, 0, 0, 'f', 'e', 99, 15, 1),
(118, 1, 0, 0, 0, 'f', 'p', 99, 15, 1),
(129, 1, 40, 0, 0, 'f', 'p', 98, 15, 1),
(159, 1, 0, 0, 0, 'f', 'e', 99, 15, 1),
(157, 1, 0, 0, 0, 'f', 'e', 99, 0, 1),
(151, 1, 0, 0, 0, 'f', 'p', 99, 135, 9),
(165, 1, 7, 0, 0, 'f', 'e', 97, 0, 1),
(185, 1, 0, 0, 0, 'f', 'e', 99, 15, 1),
(184, 1, 0, 0, 0, 'f', 'e', 99, 0, 1),
(162, 3, 0, 0, 0, 'f', 'p', 99, 25, 1),
(199, 1, 0, 0, 0, 'f', 'e', 99, 0, 1),
(202, 1, 0, 0, 0, 'f', 'e', 99, 15, 1),
(210, 1, 0, 0, 0, 'f', 'e', 99, 15, 1),
(216, 1, 0, 0, 0, 'f', 'e', 99, 0, 1),
(152, 2, 0, 0, 0, 'f', 'p', 99, 20, 1),
(222, 1, 0, 0, 0, 'f', 'e', 99, 15, 1),
(252, 1, 0, 0, 0, 'f', 'e', 99, 15, 1),
(237, 2, 0, 0, 0, 'f', 'p', 99, 20, 1),
(271, 1, 0, 0, 0, 'f', 'p', 99, 15, 10),
(272, 1, 0, 0, 0, 'f', 'e', 99, 15, 1),
(274, 1, 0, 0, 0, 'f', 'p', 99, 15, 10),
(275, 1, 0, 0, 0, 'f', 'p', 99, 15, 10),
(276, 1, 0, 0, 0, 'f', 'p', 99, 15, 10),
(283, 1, 400, 0, 0, 'f', 'p', 98, 15, 10),
(285, 1, 0, 0, 0, 'f', 'p', 99, 15, 10),
(265, 2, 70, 0, 0, 'f', 'p', 95, 20, 1),
(291, 1, 0, 0, 0, 'f', 'p', 99, 15, 1),
(293, 1, 0, 0, 0, 'f', 'p', 99, 15, 1),
(295, 1, 0, 0, 0, 'f', 'p', 99, 15, 10),
(310, 1, 0, 0, 0, 'f', 'e', 99, 0, 1),
(317, 1, 0, 0, 0, 'f', 'e', 99, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `blueprint_items`
--

CREATE TABLE IF NOT EXISTS `blueprint_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `description` text COLLATE latin1_general_ci NOT NULL,
  `type` enum('armor','boots','helmet','legs','shield','weapon','amulet','addon','potion','stone','ring') COLLATE latin1_general_ci NOT NULL DEFAULT 'armor',
  `effectiveness` int(11) NOT NULL DEFAULT '0',
  `price` int(11) NOT NULL DEFAULT '0',
  `img` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `voc` int(11) NOT NULL DEFAULT '0',
  `needlvl` int(11) NOT NULL DEFAULT '1',
  `needpromo` enum('t','f','p') COLLATE latin1_general_ci NOT NULL DEFAULT 'f',
  `needring` enum('t','f') COLLATE latin1_general_ci NOT NULL DEFAULT 'f',
  `canbuy` enum('t','f','s') COLLATE latin1_general_ci NOT NULL DEFAULT 't',
  PRIMARY KEY (`id`),
  KEY `Otimizacao1` (`type`),
  KEY `Otimizacao2` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=211 ;

--
-- Dumping data for table `blueprint_items`
--

INSERT INTO `blueprint_items` (`id`, `name`, `description`, `type`, `effectiveness`, `price`, `img`, `voc`, `needlvl`, `needpromo`, `needring`, `canbuy`) VALUES
(1, 'Capacete dos anões', 'Os anões são especialistas na criação de equipamentos de defesa.', 'helmet', 23, 11000, 'dwarvenhelmet.gif', 0, 32, 'f', 'f', 't'),
(2, 'Armadura do guerreiro', 'A armadura mais utilizada pelos exércitos da região.', 'armor', 38, 23000, 'dwarvenarmor.gif', 0, 48, 'f', 'f', 't'),
(3, 'Calças de bronze', 'Calças feitas de bronze.', 'legs', 13, 4960, 'brasslegs.gif', 0, 22, 'f', 'f', 't'),
(4, 'Espada de T.Magor', 'Fabricada por Thoy Magor, um dos cavaleiros mais poderosos de todos os tempos!', 'weapon', 42, 26500, 'warlordsword.gif', 2, 44, 'f', 'f', 't'),
(5, 'Armadura de ferro', 'Proporciona uma defesa razoável com baixo custo.', 'armor', 17, 2000, 'darkarmor.gif', 0, 12, 'f', 'f', 't'),
(6, 'Escudo escuro', 'Escudo feito por um guerreiro maligno.', 'shield', 32, 37000, 'darkshield.gif', 0, 49, 'f', 'f', 't'),
(7, 'Botas de couro', 'Simples botas de couro.', 'boots', 5, 200, 'leatherboots.gif', 0, 1, 'f', 'f', 't'),
(8, 'Faca', 'Uma simples faca.', 'weapon', 7, 300, 'knife.gif', 2, 1, 'f', 'f', 't'),
(9, 'Escudo de madeira', 'Feito de madeira, porém muito barato.', 'shield', 5, 100, 'woodenshield.gif', 0, 1, 'f', 'f', 't'),
(10, 'Escudo redondo', 'Feito de um material desconhecido, mas não muito resistente.', 'shield', 9, 470, 'roundshield.gif', 0, 7, 'f', 'f', 't'),
(11, 'Escudo de ferro', 'Uma chapa de ferro.', 'shield', 12, 1700, 'iroshield.gif', 0, 14, 'f', 'f', 't'),
(13, 'Elmo do esqueleto', 'Feito de ossos dos guerreiros mais poderosos das gerações anteriores.', 'helmet', 52, 80000, 'skullhelmet.gif', 0, 82, 'f', 'f', 't'),
(14, 'Escudo do dragão', 'Os poderes de dragão estão presentes neste escudo.', 'shield', 43, 86000, 'dragonshield.gif', 0, 70, 't', 'f', 't'),
(15, 'Escudo viking', 'Os Vikings fazem escudos muito bons, o problema é que eles trabalham bebados.', 'shield', 16, 2700, 'vikingshield.gif', 0, 21, 'f', 'f', 't'),
(18, 'Machado duplo', 'Lâmina dupla, estrago em dobro.', 'weapon', 22, 4895, 'doubleaxe.gif', 2, 18, 'f', 'f', 't'),
(19, 'Armadura selvagem', 'Utilizada pelas tribos nativas.', 'armor', 12, 1000, 'leopard.gif', 0, 6, 'f', 'f', 't'),
(20, 'Toca de couro', 'Simples vestimenta.', 'helmet', 8, 300, 'leatherhelmet.gif', 0, 1, 'f', 'f', 't'),
(21, 'Calça de couro', 'Simples vestimenta.', 'legs', 4, 500, 'leatherlegs.gif', 0, 1, 'f', 'f', 't'),
(22, 'Armadura de couro', 'Simples vestimenta.', 'armor', 7, 500, 'belted_cape.gif', 0, 1, 'f', 'f', 't'),
(23, 'Espada longa', 'Espada com uma lâmina comprida, que pode fazer um belo estrago.', 'weapon', 19, 2000, 'longsword.gif', 2, 12, 'f', 'f', 't'),
(25, 'Armadura nobre', 'Sem descrição.', 'armor', 23, 4850, 'noblearmor.gif', 0, 24, 'f', 'f', 't'),
(26, 'Armadura de ouro', 'Possui uma grande defesa, porém muito cobiçada devido sua raridade.', 'armor', 33, 15600, 'goldenarmor.gif', 0, 40, 'f', 'f', 't'),
(27, 'Martelo', 'Um martelo muito resistente, porém pesado.', 'weapon', 15, 595, 'battlehammer.gif', 2, 7, 'f', 'f', 't'),
(28, 'Machado gigante', 'Um machado gigantesco, com um imenso poder de ataque.', 'weapon', 38, 19600, 'giantaxe.gif', 2, 38, 'f', 'f', 't'),
(29, 'Machado dourado', 'Um machado feito de ouro.', 'weapon', 32, 13500, 'goldenaxe.gif', 2, 31, 'f', 'f', 't'),
(30, 'Espada de cavaleiro', 'A espada usada pelos cavaleiros de Elryn.', 'weapon', 27, 10050, 'crimson_sword.gif', 2, 25, 'f', 'f', 't'),
(31, 'Escudo real', 'Alguns anos atrás este tipo de escudo era usado pelas tropas do rei.', 'shield', 19, 5500, 'kiteshield.gif', 0, 28, 'f', 'f', 't'),
(32, 'Escudo de aço', 'Um escudo feito de aço.', 'shield', 24, 12000, 'steelshield.gif', 0, 35, 'f', 'f', 't'),
(33, 'Escudo da águia', 'Inspirado em uma águia, este escudo pode resistir a golpes muito fortes.', 'shield', 29, 22000, 'eagleshield.gif', 0, 42, 'f', 'f', 't'),
(34, 'Calça de ferro', 'Possui uma boa defesa, porém é um equipamento muito pesado.', 'legs', 18, 10000, 'chainlegs.gif', 0, 32, 'f', 'f', 't'),
(35, 'Calça selvagem', 'Usada por tribos antigas, bastante leve, porém não propõe muita defesa.', 'legs', 8, 2150, 'blue_legs.gif', 0, 11, 'f', 'f', 't'),
(36, 'Calças místicas', 'Até hoje ninguém conseguiu descobrir de onde todo o poder destas calças vem.', 'legs', 30, 34000, 'lightning_legs.gif', 0, 52, 'f', 'f', 't'),
(37, 'Capacete selvagem', 'Usado por tribos antigas, bastante leve, porém não propõe muita defesa.', 'helmet', 14, 1260, 'ragnir_helmet.gif', 0, 12, 'f', 'f', 't'),
(161, 'Armadura divina', 'A armadura dos deuses.', 'armor', 85, 2000000, 'yalahari_armor.gif', 0, 320, 'f', 'f', 'f'),
(39, 'Capacete de ferro', 'Possui uma boa defesa, porém é um equipamento muito pesado.', 'helmet', 19, 4950, 'krimhorn_helmet.gif', 0, 24, 'f', 'f', 't'),
(40, 'Botas de crocodilo', 'Feitas com couro de crocodilo.', 'boots', 8, 800, 'crocodileboots.gif', 0, 12, 'f', 'f', 't'),
(41, 'Botas de urso', 'Este tipo de bota proporciona uma ótima proteção à variações de temperatura.', 'boots', 13, 2000, 'fur_boots.gif', 0, 24, 'f', 'f', 't'),
(42, 'Botas de latão', 'Feitas de um metal pouco resistente.', 'boots', 17, 5500, 'ironboots.gif', 0, 36, 'f', 'f', 't'),
(43, 'Machado da morte', 'O machado da morte!', 'weapon', 57, 58600, 'deathaxe.gif', 2, 62, 'f', 'f', 't'),
(44, 'Espada de ouro', 'Uma espada feita de ouro.', 'weapon', 45, 34650, 'goldensword.gif', 2, 50, 'f', 'f', 't'),
(47, 'Botas de dragão', 'Botas feitas com escamas de dragão.', 'boots', 49, 87000, 'dragon_scale_boots.gif', 0, 80, 't', 'f', 't'),
(51, 'Machado vampírico', 'O machado dos vampiros.', 'weapon', 64, 65650, 'vampireaxe.gif', 2, 68, 'f', 'f', 't'),
(52, 'Machado templário', 'Este tipo de machado era usado pelos guerreiros que defendiam os antigos templos.', 'weapon', 53, 46850, 'templaraxe.gif', 2, 56, 'f', 'f', 't'),
(53, 'Espada de batalha', 'Não queira saber quem fez esta espada.', 'weapon', 68, 78450, 'battlesword.gif', 2, 75, 'f', 'f', 't'),
(57, 'Manto de sangue', 'Um estranho tipo de magia faz deste manto uma ótima defesa.', 'armor', 28, 7560, 'bloodrobe.gif', 0, 32, 'f', 'f', 't'),
(58, 'Armadura de turquesa', 'Armadura feita com uma pedra preciosa muito resistente.', 'armor', 68, 97000, 'frozen_plate.gif', 0, 92, 'f', 'f', 't'),
(59, 'Armadura do caos', 'A armadura do caos.', 'armor', 48, 42350, 'lavos_armor.gif', 0, 62, 'f', 'f', 't'),
(61, 'Calças de aço', 'Uma liga metálica muito resistente.', 'legs', 24, 23000, 'legsdeplate.gif', 0, 40, 'f', 'f', 't'),
(62, 'Calças de ouro', 'Calças feitas de ouro.', 'legs', 47, 89500, 'goldenlegs.gif', 0, 89, 'f', 'f', 't'),
(64, 'Calça dos anões', 'Os anões são especialistas na criação de equipamentos de defesa.', 'legs', 37, 42500, 'Dwarven_Legs.gif', 0, 60, 'f', 'f', 't'),
(65, 'Escudo de grifo', 'Feito da pele de um griffo.', 'shield', 36, 52000, 'griffinshield.gif', 0, 56, 'f', 'f', 't'),
(66, 'Escudo do caos', 'O escudo do caos.', 'shield', 38, 74000, 'chaosshield.gif', 0, 63, 'f', 'f', 't'),
(67, 'Armadura da escuridão', 'A caveira gravada nesta armadura faz as pessoas que a viram nunca mais esquecê-la.', 'armor', 60, 79500, 'darknessarmor.gif', 0, 84, 'f', 'f', 't'),
(68, 'Elmo de dragão', 'Feito de escamas de dragão.', 'helmet', 34, 35000, 'dragonscalehelmet.gif', 0, 54, 'f', 'f', 't'),
(69, 'Elmo do guerreiro', 'Equipamento com muita defesa, porém pesado.', 'helmet', 40, 48650, 'warriorhelmet.gif', 0, 62, 'f', 'f', 't'),
(71, 'Botas míticas', 'Essas botas apresentam uma combinação de metais jamais antes vista.', 'boots', 54, 110000, 'draken_boots.gif', 0, 90, 'f', 'f', 't'),
(73, 'Amuleto', 'Um simples amuleto. ', 'amulet', 3, 100, 'bronzeamulet.gif', 0, 1, 'f', 'f', 't'),
(74, 'Amuleto de prata', 'Um amuleto feito de prata.', 'amulet', 6, 450, 'silvernecklace.gif', 0, 7, 'f', 'f', 't'),
(75, 'Amuleto de ouro', 'Um amuleto feito de ouro.', 'amulet', 12, 2600, 'staramulet.gif', 0, 21, 'f', 'f', 't'),
(76, 'Amuleto de proteção ', 'Proteção divina.', 'amulet', 15, 5000, 'protectionamulet.gif', 0, 28, 'f', 'f', 't'),
(77, 'Colar da terra', 'Pedaço de terra mágica, possui poderes ocultos de cura.', 'amulet', 23, 35000, 'terra_amulet.gif', 0, 49, 'f', 'f', 't'),
(78, 'Amuleto de rubi', 'Este amuleto possui um enorme rubi.', 'amulet', 35, 90000, 'lifeamulet.gif', 0, 79, 't', 'f', 't'),
(79, 'Amuleto da vida', 'Este amuleto possui um enorme poder medicinal.', 'amulet', 39, 100000, 'amuletoflife.gif', 0, 90, 't', 't', 't'),
(81, 'Shuriken', 'Uma estrela ninja.', 'weapon', 8, 300, 'throwingstar.gif', 1, 1, 'f', 'f', 't'),
(82, 'Lança', 'Uma simples lança.', 'weapon', 15, 595, 'spear.gif', 1, 4, 'f', 'f', 't'),
(83, 'Arco', 'Um simples arco.', 'weapon', 18, 2000, 'bow.gif', 1, 7, 'f', 'f', 't'),
(84, 'Lança de aço', 'Uma lança feita de aço.', 'weapon', 20, 4895, 'steelspear.gif', 1, 14, 'f', 'f', 't'),
(85, 'Arco dos elfos', 'Um arco usado pelos elfos. ', 'weapon', 24, 10050, 'yols_bow.gif', 1, 23, 'f', 'f', 't'),
(86, 'Lança real', 'Lança usada pelos guardas do rei. ', 'weapon', 32, 19600, 'royal_spear.gif', 1, 31, 'f', 'f', 't'),
(87, 'Arco de aço', 'Um arco revestido com aço.', 'weapon', 27, 13500, 'steelbow.gif', 1, 25, 'f', 'f', 't'),
(88, 'Besta', 'Uma versão do arco mais rápida e precisa. ', 'weapon', 37, 26500, 'crossbow.gif', 1, 36, 'f', 'f', 't'),
(89, 'Arco de gelo', 'Um arco feito de gelo.', 'weapon', 44, 34650, 'icebow.gif', 1, 47, 'f', 'f', 't'),
(90, 'Besta modificada', 'Uma besta aperfeiçoada.', 'weapon', 49, 46850, 'ironworker.gif', 1, 55, 'f', 'f', 't'),
(91, 'Besta de caçados', 'Uma besta usada pelos antigos caçadores de monstros.', 'weapon', 57, 58600, 'the_devileye.gif', 1, 64, 'f', 'f', 't'),
(92, 'Bastão', 'Um simples bastão.', 'weapon', 7, 300, 'staff.gif', 3, 1, 'f', 'f', 't'),
(93, 'Bastão lápis-lazúli', 'Bastão com um Lápis-lazúli na ponta. ', 'weapon', 16, 595, 'bluestaff.gif', 3, 9, 'f', 'f', 't'),
(94, 'Bastão de cura', 'O bastão dos curandeiros.', 'weapon', 22, 2000, 'shamanstaff.gif', 3, 18, 'f', 'f', 't'),
(95, 'Bastão da lua', 'O bastão da lua.', 'weapon', 27, 4895, 'moonstaff.gif', 3, 25, 'f', 'f', 't'),
(96, 'Bastão do fogo', 'O bastão do fogo.', 'weapon', 33, 13500, 'firestaff.gif', 3, 32, 'f', 'f', 't'),
(97, 'Bastão dos oceanos', 'O bastão dos oceanos.', 'weapon', 36, 19600, 'oceanstaff.gif', 3, 36, 'f', 'f', 't'),
(98, 'Bastão encantado ', 'Um bastão encantado.', 'weapon', 40, 26500, 'enchantedstaff.gif', 3, 42, 'f', 'f', 't'),
(99, 'Bastão do caos', 'O Bastão do caos.', 'weapon', 44, 34650, 'chaosstaff.gif', 3, 47, 'f', 'f', 't'),
(100, 'Bastão dos anjos', 'O bastão dos anjos.', 'weapon', 56, 58600, 'queens_sceptre.gif', 3, 60, 'f', 'f', 't'),
(101, 'Bastão do mal ', 'Um bastão com propriedades do mal.', 'weapon', 65, 65650, 'wand_of_voodoo.gif', 3, 67, 'f', 'f', 't'),
(102, 'Bastão do trovão ', 'O bastão do trovão.', 'weapon', 50, 46850, 'blessed_sceptre.gif', 3, 56, 'f', 'f', 't'),
(103, 'Armadura de dragão', 'Os poderes de dragão estão presentes nesta armadura.', 'armor', 54, 59150, 'earthborn_titan_armor.gif', 0, 72, 'f', 'f', 't'),
(107, 'Orbe do vento', 'Item de quest.', 'addon', 0, 10500, 'windorb.gif', 0, 1, 'f', 'f', 'f'),
(108, 'Orbe da terra', 'Item de quest.', 'addon', 0, 11200, 'earthorb.gif', 0, 1, 'f', 'f', 'f'),
(109, 'Orbe do fogo', 'Item de quest.', 'addon', 0, 11900, 'fireorb.gif', 0, 1, 'f', 'f', 'f'),
(110, 'Orbe da água', 'Item de quest.', 'addon', 0, 12600, 'waterorb.gif', 0, 1, 'f', 'f', 'f'),
(111, 'Roda de titânio', 'Item de quest.', 'addon', 0, 21000, 'tataniumwell.gif', 0, 1, 'f', 'f', 'f'),
(112, 'Cristal encantado', 'Item de quest.', 'addon', 0, 14000, 'jeweledcrystal.gif', 0, 1, 'f', 'f', 'f'),
(113, 'Cajado da tempestade', 'Um cajado com poderes incríveis. ', 'weapon', 75, 97620, 'wand_of_starstorm.gif', 3, 90, 'f', 'f', 't'),
(115, 'Lança encantada', 'Uma lança encantada.', 'weapon', 67, 65650, 'enchanted_spear.gif', 1, 72, 'f', 'f', 't'),
(116, 'Caixa', 'Item de quest.', 'addon', 0, 21000, 'box.gif', 0, 1, 'f', 'f', 'f'),
(117, 'Besta de ouro', 'Uma besta feita de ouro.', 'weapon', 87, 97620, 'royal_crossbow.gif', 1, 117, 't', 'f', 't'),
(118, 'Primeira parte da espada', 'Item de quest.', 'addon', 0, 35000, 'cabo.gif', 0, 1, 'f', 'f', 'f'),
(119, 'Segunda parte da espada', 'Item de quest.', 'addon', 0, 28000, 'gelo1.gif', 0, 1, 'f', 'f', 'f'),
(120, 'Terceira parte da espada', 'Item de quest.', 'addon', 0, 28000, 'gelo2.gif', 0, 1, 'f', 'f', 'f'),
(121, 'Lâmina de gelo', 'Esta espada especial pode ser usada por pessoas de qualquer vocação.', 'weapon', 110, 868000, 'iceblade.gif', 0, 145, 'f', 'f', 'f'),
(123, 'Arco divino ', 'Um arco com propriedades divinas.', 'weapon', 80, 78450, 'divinebow.gif', 1, 90, 'f', 't', 't'),
(124, 'Cajado do dragão', 'Feito das escamas de um dragão.', 'weapon', 70, 78450, 'dragonstaff.gif', 3, 72, 't', 'f', 't'),
(125, 'Espada gigante', 'Espada de grande poder, porém pesada, usada pelos mais fortes guerreiros.', 'weapon', 76, 97620, 'giantsword.gif', 2, 90, 'f', 't', 't'),
(126, 'Escudo mágico', 'Forças mágicas fazem com que este escudo resista a grandes ataques.', 'shield', 48, 105000, 'magicshield.gif', 0, 90, 't', 't', 't'),
(127, 'Escudo antigo', 'Escudo usado por T.Magor', 'shield', 55, 583520, 'ancientshield.gif', 0, 145, 'f', 'f', 't'),
(128, 'Caçadora de crânios', 'Esta espada pode ser usada por qualquer vocação.', 'weapon', 120, 1449280, 'skullhunter.gif', 0, 195, 'f', 'f', 't'),
(129, 'Armadura imperial', 'Armadura usada pelos imperiais.', 'armor', 42, 34000, 'imperialarm.gif', 0, 54, 'f', 'f', 't'),
(130, 'Capacete de herói ', 'Usado por verdadeiros heróis.', 'helmet', 58, 99000, 'herohelmet.gif', 0, 92, 'f', 'f', 't'),
(131, 'Escudo do herói', 'Escudo usado por heróis.', 'shield', 60, 807520, 'heroshield.gif', 0, 170, 'f', 'f', 't'),
(133, 'Armadura da morte', 'A armadura da morte.', 'armor', 79, 1780800, 'skullcracker_armor.gif', 0, 140, 't', 'f', 't'),
(134, 'Cajado da morte', 'O cajado da morte.', 'weapon', 125, 2240000, 'underworld_rod.gif', 3, 200, 'f', 'f', 't'),
(135, 'Botas de ouro', 'Grande agilidade, porém cobiçada, elevando muito seu preço.', 'boots', 62, 530000, 'goldenboots.gif', 0, 120, 't', 'f', 't'),
(136, 'Poção de vida', 'Recupera até 5 mil de vida.', 'potion', 0, 100, 'healthpotion.gif', 0, 1, 'f', 'f', 'f'),
(137, 'Poção de energia', 'Recupera até 50 de energia.', 'potion', 0, 2800, 'energypotion.gif', 0, 1, 'f', 'f', 'f'),
(138, 'Escudo das trevas', 'Usado por guerreiros malignos.', 'shield', 70, 2128000, 'nightmareshield.gif', 0, 205, 'f', 'f', 't'),
(139, 'Amuleto da morte', 'Usado pelo ceifador, lhe dando poderes imortalísticos.   ', 'amulet', 42, 510000, 'stoneskinamulet.gif', 0, 120, 't', 'f', 't'),
(140, 'Escudo demoniaco', 'Escudo com propriedades demoníacas.', 'shield', 82, 4200000, 'infernalshield.gif', 0, 150, 'f', 'f', 'f'),
(141, 'Pedra de Maturação', 'Esta pedra poderá transformar seu item optimizado em um item +13 ou +14, porém, a chance da pedra não funcionar é de 30%.', 'stone', 0, 5000000, 'maturestone.gif', 0, 1, 'f', 'f', 'f'),
(143, 'Besta indestrutível', 'Uma besta tão dura, que é quase indestrutível. ', 'weapon', 130, 1680000, 'hardcorecrossbow.gif', 1, 190, 'f', 'f', 't'),
(144, 'Pedra de Força #1', 'Adiciona +5 de força no seu item. Esta pedra pode ser usada apenas uma vez por item, e a chance dela quebrar durante o processo é de 30%.', 'stone', 0, 100000, 'forstone1.gif', 0, 1, 'f', 'f', 'f'),
(145, 'Pedra de Vitalidade #1', 'Adiciona +5 de vitalidade no seu item. Esta pedra pode ser usada apenas uma vez por item, e a chance dela quebrar durante o processo é de 30%.', 'stone', 0, 80000, 'vitstone1.gif', 0, 1, 'f', 'f', 'f'),
(146, 'Pedra de Agilidade #1', 'Adiciona +5 de agilidade no seu item. Esta pedra pode ser usada apenas uma vez por item, e a chance dela quebrar durante o processo é de 30%.', 'stone', 0, 80000, 'agistone1.gif', 0, 1, 'f', 'f', 'f'),
(147, 'Pedra de Resistência #1', 'Adiciona +5 de resistência no seu item. Esta pedra pode ser usada apenas uma vez por item, e a chance dela quebrar durante o processo é de 30%.', 'stone', 0, 100000, 'resstone1.gif', 0, 1, 'f', 'f', 'f'),
(148, 'Poção grande de vida', 'Recupera até 10 mil de vida.', 'potion', 0, 5600, 'bighealthpotion.gif', 0, 1, 'f', 'f', 'f'),
(149, 'Calça demoniaca', 'As calças do demônio.', 'legs', 53, 780000, 'demonlegs.gif', 0, 160, 'f', 'f', 't'),
(150, 'Poção de mana', 'Recupera até 500 de mana.', 'potion', 0, 2800, 'manapotion.gif', 0, 1, 'f', 'f', 'f'),
(151, 'Espada de Friden', 'Espada usada por Friden, maior guerreiro de todos os tempos, esta espada possui poderes místicos tão fortes, que apenas um guerreiro muito poderoso pode controla-la.', 'weapon', 135, 3500000, 'fridensword.gif', 2, 280, 'p', 'f', 'f'),
(152, 'Cajado do Drácula. ', 'Cajado usado pelo Drácula, com poderes tão surpreendentes que apenas um guerreiro muito poderoso pode manipula-la.', 'weapon', 135, 3500000, 'draculawand.gif', 3, 280, 'p', 'f', 'f'),
(153, 'Arco de Baltazar', 'Arco usado por Baltazar, o melhor arqueiro, poderia atingir um alvo a quilômetros de distancia, para poder usa-lo você precisa ser muito preciso.', 'weapon', 140, 3710000, 'baltazarbow.gif', 1, 280, 'p', 'f', 'f'),
(155, 'Presente', 'Ao abrir este presente você pode encontrar qualquer item ou quantidade de ouro.', 'addon', 0, 21000, 'presente.gif', 0, 50, 'f', 'f', 'f'),
(156, 'Orbe de Oddin', 'Um orb raríssimo, com grandes poderes. Encontrar um destes orbs com um monstro é tão difícil quanto ganhar na loteria.', 'addon', 0, 140000, 'oddinorb.gif', 0, 1, 'f', 'f', 'f'),
(157, 'Magic Golden Bar', 'Uma barra de ouro. Muitos acreditam que essas barras possuem poderes mágicos.', 'addon', 0, 210000, 'magicgoldenbar.gif', 0, 1, 'f', 'f', 'f'),
(159, 'Caixa', 'Um pacote imperial, muito valioso.', 'addon', 0, 6300000, 'box.gif', 0, 1, 'f', 'f', 'f'),
(173, 'Armadura demoníaca', 'Sem descrição.', 'armor', 82, 1650000, 'demonarmor.gif', 0, 170, 'f', 'f', 'f'),
(162, 'Calça divina', 'A calça dos deuses.', 'legs', 56, 1680000, 'elitelegs.gif', 0, 320, 'f', 'f', 'f'),
(163, 'Anel encantado', 'Aumenta todos seus status em 10 pontos.', 'ring', 0, 1000000, 'jewring.gif', 0, 80, 'f', 'f', 'f'),
(164, 'Anel de força', 'Aumenta 10 pontos de status em força.', 'ring', 0, 15000, 'forring.gif', 0, 25, 'f', 'f', 'f'),
(165, 'Anel de vitalidade', 'Aumenta 10 pontos de status em vitalidade.', 'ring', 0, 10000, 'vitring.gif', 0, 25, 'f', 'f', 'f'),
(166, 'Anel de agilidade', 'Aumenta 10 pontos de status em agilidade.', 'ring', 0, 10000, 'agiring.gif', 0, 25, 'f', 'f', 'f'),
(167, 'Anel de resistência ', 'Aumenta 10 pontos de status em resistência.', 'ring', 0, 15000, 'resring.gif', 0, 25, 'f', 'f', 'f'),
(168, 'Anel encantado e otimizado', 'Aumenta todos seus status em 20 pontos.', 'ring', 0, 2000000, 'newjewring.gif', 0, 100, 'f', 'f', 'f'),
(169, 'Anel escuro', 'Aumenta 10 pontos de status em força e 15 pontos em resistência.', 'ring', 0, 30000, 'darkring.gif', 0, 40, 'f', 'f', 'f'),
(170, 'Anel de energia', 'Aumenta 15 pontos de status em vitalidade, 15 pontos em agilidade e 5 pontos em resistência. ', 'ring', 0, 30000, 'energyring.gif', 0, 40, 'f', 'f', 'f'),
(171, 'Amuleto do trovão', 'Amuleto feito por Thor, o deus do trovão. ', 'amulet', 48, 1000000, 'shockwave_amulet.gif', 0, 125, 'f', 'f', 'f'),
(172, 'Anel da morte', 'Aumenta sua força e agilidade em 40 pontos e sua vitalidade e resistência em 30 pontos.', 'ring', 0, 900000, 'deathring.gif', 0, 135, 'f', 'f', 'f'),
(174, 'Capacete de demônio', 'Capacete com poderes demoníacos. ', 'helmet', 65, 800000, 'demonhelmet.gif', 0, 135, 'f', 'f', 'f'),
(175, 'Escudo da fortaleza', 'Antigamente usado por guardas da fortaleza.', 'shield', 82, 3000000, 'towershield.gif', 0, 150, 'f', 'f', 'f'),
(176, 'Anel da vida', 'Aumenta sua força e agilidade em 30 pontos e sua vitalidade e resistência em 40 pontos.', 'ring', 0, 750000, 'lifering.gif', 0, 140, 'f', 'f', 't'),
(177, 'Cristal magico', 'Um cristal. Muitos acreditam que esses cristais possuem poderes mágicos.', 'addon', 0, 300000, 'magiccrystal.gif', 0, 1, 'f', 'f', 'f'),
(178, 'Anel de cristal', 'Poderoso anel formado por 3 cristais de jóias.', 'ring', 0, 3000000, 'ringofthesky.gif', 0, 200, 'f', 'f', 'f'),
(179, 'Garrafa de vinho', 'Aumenta 10% da sua força, porém diminui 5% da sua resistência.', 'potion', 30, 2000, 'bottle.gif', 0, 18, 'f', 'f', 'f'),
(180, 'Garrafa de uísque', 'Aumenta 10% da sua resistência, porém diminui 5% da sua agilidade.', 'potion', 60, 5000, 'whisky.gif', 0, 18, 'f', 'f', 'f'),
(181, 'Garrafa de cerveja', 'Aumenta 5% da sua agilidade.', 'potion', 15, 750, 'beer.gif', 0, 18, 'f', 'f', 'f'),
(182, 'Copo d''água', 'Remove o efeito de qualquer bebida que você tenha tomado.', 'potion', 1, 10, 'water.png', 0, 1, 'f', 'f', 'f'),
(183, 'Amuleto de dragão', 'Amuleto feito das escamas de um dragão.', 'amulet', 31, 80000, 'cruzaderamulet.gif', 0, 70, 'f', 'f', 't'),
(184, 'Colar de pedra', 'Uma pedra mística com poderes curandeiros.', 'amulet', 21, 20000, 'brokenamulet.gif', 0, 42, 'f', 'f', 't'),
(185, 'Amuleto de platina', 'Amuleto feito de platina, muito resistente \r\ne poderoso.', 'amulet', 10, 1500, 'platinumamulet.gif', 0, 14, 'f', 'f', 't'),
(186, 'Colar de safira', 'Colar de ouro com uma safira mágica, encontrada nos lugares mais remotos do mundo.', 'amulet', 26, 50000, 'crystalnecklace.gif', 0, 56, 'f', 'f', 't'),
(187, 'Colar do elfos', 'Usado pelos elfos curandeiros.', 'amulet', 28, 70000, 'sacred_tree_amulet.gif', 0, 63, 'f', 'f', 't'),
(188, 'Colar de energia', 'Colar com um pouco de energia de uma estrela que caiu na terra.', 'amulet', 19, 10000, 'elvenamulet.gif', 0, 35, 'f', 'f', 't'),
(189, 'Capacete negro', 'Esconde uma maldisão imprevisível.', 'helmet', 28, 23500, 'darkhelmet.gif', 0, 42, 'f', 'f', 't'),
(190, 'Elmo real', 'O elmo utilizado pelos cavaleiros da realeza.', 'helmet', 47, 61350, 'royalhelmet.gif', 0, 75, 'f', 'f', 't'),
(191, 'Calça dos carrascos', 'Calça utilizada pelos carrascos.', 'legs', 6, 890, 'studdedlegs.gif', 0, 6, 'f', 'f', 't'),
(192, 'Calça negra', 'Dita por muitos como amaldiçoada.', 'legs', 43, 52600, 'crslegs.gif', 0, 72, 'f', 'f', 't'),
(132, 'Botas encantadas', 'Estas botas possuem poderes mágicos, garantindo uma agilidade incrível.', 'boots', 68, 1600000, 'enboots.gif', 0, 145, 'f', 'f', 'f'),
(158, 'Elmo Dourado', 'Feito com barras de ouro muito raras.', 'helmet', 70, 1000000, 'goldenhelmet.gif', 0, 140, 'f', 'f', 'f'),
(196, 'Botas de cristal', 'Essas botas são feitas a partir de vários cristais preciosos.', 'boots', 41, 56000, 'crystal_boots.gif', 0, 60, 'f', 'f', 't'),
(197, 'Botas do guerreiro', 'Botas feitas sob medida para os mais fortes guerreiros do reino.', 'boots', 46, 75000, 'guardian_boots.gif', 0, 72, 'f', 'f', 't'),
(198, 'Botas de ferro', 'Possui uma boa agilidade, porém é um equipamento muito pesado.', 'boots', 25, 16000, 'stellboots.gif', 0, 48, 'f', 'f', 't'),
(200, 'Botas de Aço', 'Uma liga metálica muito resistente.', 'boots', 32, 31000, 'acoboots.gif', 0, 55, 'f', 'f', 't'),
(201, 'Botas de Elryn', 'As botas do guardião do império de Elryn.', 'boots', 35, 48000, 'botas_de_elryn.gif', 0, 39, 'f', 'f', 'f'),
(202, 'Armadura das Sombras', 'Uma armadura muito rara e de altissimo valor.', 'armor', 54, 54000, 'senior_armor.gif', 0, 60, 'f', 'f', 'f'),
(203, 'Elmo Sombrio', 'Uma elmo muito raro e de altissimo valor.', 'helmet', 45, 55000, 'senior_helmet.gif', 0, 60, 'f', 'f', 'f'),
(204, 'Calças Sombrias', 'Um par de calças muito caro e de altissimo valor.', 'legs', 44, 51000, 'senior_legs.gif', 0, 60, 'f', 'f', 'f'),
(205, 'Espada Sangrenta', 'Muito sangue já foi derramado por esta espada.', 'weapon', 65, 74000, 'espada_sangrenta.gif', 2, 60, 'f', 'f', 'f'),
(206, 'Cajado das Sombras', 'O podr das trevas se concentra neste cajado.', 'weapon', 69, 71000, 'cajado_sombras.gif', 3, 60, 'f', 'f', 'f'),
(207, 'Besta da Morte', 'Sem descrição.', 'weapon', 66, 63000, 'besta_da_morte.gif', 1, 60, 'f', 'f', 'f'),
(208, 'Armadura Glacial', 'Sem descrição.', 'armor', 67, 92000, 'glacial_armor.gif', 0, 80, 'f', 'f', 'f'),
(209, 'Elmo Congelado', 'Sem descrição.', 'helmet', 57, 90000, 'glacial_helmet.gif', 0, 80, 'f', 'f', 'f'),
(210, 'Calças Glaciais', 'Sem descrição.', 'legs', 46, 75000, 'glacial_legs.gif', 0, 80, 'f', 'f', 'f');

-- --------------------------------------------------------

--
-- Table structure for table `blueprint_magias`
--

CREATE TABLE IF NOT EXISTS `blueprint_magias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `descri` text NOT NULL,
  `cost` int(11) NOT NULL DEFAULT '0',
  `mana` int(11) NOT NULL DEFAULT '0',
  `precisa` varchar(255) NOT NULL DEFAULT 'f',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `blueprint_magias`
--

INSERT INTO `blueprint_magias` (`id`, `nome`, `descri`, `cost`, `mana`, `precisa`) VALUES
(1, 'Reforço', 'Aumente o dano em 15% por 5 turnos.', 15, 25, '4'),
(2, 'Agressivo', 'Aumente seu dano em 45% porém reduz a resistência em 15% por 5 turnos.', 35, 40, '1, 3'),
(3, 'Ataque Duplo', 'De um ataque duplo em seu oponente.', 15, 30, '4'),
(4, 'Cura', 'Recupere parte de sua vida.', 0, 15, 'f'),
(6, 'Defesa Dupla', 'Defenda 2 vezes seguidas.', 15, 25, '4'),
(7, 'Resistência', 'Aumente sua resistência em 20% por 5 turnos.', 15, 30, '4'),
(8, 'Ataque Quádruplo', 'Ataque 4 vezes seguidas.', 45, 65, '2, 12'),
(9, 'Defesa Quádrupla', 'Defenda 4 vezes seguidas.', 40, 55, '11'),
(10, 'Escudo Místico', 'Faz o ataque do usuário voltar para ele por 3 turnos.', 50, 80, '9'),
(11, 'Tontura', 'Deixe o oponente tonto, reduzindo seu ataque em 50% por 5 turnos.', 30, 40, '6, 7'),
(12, 'Força Súbita', 'Aumente seu dano em 35% por 5 turnos.', 30, 45, '1, 3');

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE IF NOT EXISTS `chat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `from` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `to` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `message` text COLLATE latin1_general_ci NOT NULL,
  `sent` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `recd` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=60589 ;

-- --------------------------------------------------------

--
-- Table structure for table `completed_tasks`
--

CREATE TABLE IF NOT EXISTS `completed_tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6957 ;

-- --------------------------------------------------------

--
-- Table structure for table `cron`
--

CREATE TABLE IF NOT EXISTS `cron` (
  `name` varchar(45) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `value` varchar(45) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Extraindo dados da tabela `cron`
--

INSERT INTO `cron` (`name`, `value`) VALUES
('reset_last', '1319566657'),
('reset_time', '60'),
('revive_last', '1228961728'),
('revive_time', '1800'),
('interest_last', '1319566354'),
('interest_time', '86400'),
('died_last', '1230776435'),
('died_time', '86400'),
('tax_last', '1724467200'),
('tax_time', '86400');

-- --------------------------------------------------------


--
-- Table structure for table `duels`
--

CREATE TABLE IF NOT EXISTS `duels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `p_id` int(11) NOT NULL,
  `e_id` int(11) NOT NULL,
  `p_magia` tinyint(2) NOT NULL,
  `e_magia` tinyint(2) NOT NULL,
  `p_turnos` tinyint(3) NOT NULL,
  `e_turnos` tinyint(3) NOT NULL,
  `p_type` tinyint(3) NOT NULL,
  `e_type` tinyint(3) NOT NULL,
  `timeout` int(11) NOT NULL DEFAULT '0',
  `vez` enum('p','e') NOT NULL DEFAULT 'e',
  `status` varchar(25) NOT NULL DEFAULT 'w',
  `venceu` int(11) NOT NULL DEFAULT '0',
  `extra` int(11) NOT NULL DEFAULT '0',
  `log` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=131 ;

-- --------------------------------------------------------

--
-- Table structure for table `dungeon`
--

CREATE TABLE IF NOT EXISTS `dungeon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `img` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `monsters` varchar(255) NOT NULL,
  `prize` varchar(255) NOT NULL,
  `level` int(11) NOT NULL,
  `time` int(11) NOT NULL DEFAULT '900',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `dungeon`
--

INSERT INTO `dungeon` (`id`, `img`, `name`, `monsters`, `prize`, `level`, `time`) VALUES
(1, '', 'Império de Elryn', '5, 7, 10, 60', '5000, 201', 1, 600),
(2, '', 'Floresta Negra', '2, 3, 4, 61', '1500, 185', 0, 300),
(3, '', 'Pequenos Problemas', '62, 63, 64', '1500, 1', 1, 240),
(4, '', 'Invasão no Império', '6, 9, 65, 15', '15000, 36', 3, 240);

-- --------------------------------------------------------

--
-- Table structure for table `dungeon_status`
--

CREATE TABLE IF NOT EXISTS `dungeon_status` (
  `player_id` int(11) NOT NULL DEFAULT '0',
  `dungeon_id` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '0',
  `start` int(11) NOT NULL DEFAULT '0',
  `finish` int(11) NOT NULL DEFAULT '0',
  `fail` smallint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `forum_answer`
--

CREATE TABLE IF NOT EXISTS `forum_answer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(4) NOT NULL DEFAULT '0',
  `a_user_id` int(11) NOT NULL DEFAULT '0',
  `a_answer` longtext NOT NULL,
  `a_datetime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `Otimizacao2` (`question_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1270 ;

--
-- Dumping data for table `forum_answer`
--

INSERT INTO `forum_answer` (`id`, `question_id`, `a_user_id`, `a_answer`, `a_datetime`) VALUES
(1, 2, 52, 'Voc n‹o encontrou o usu‡rio Alucard! Ele deve estar trabalhando ou caando. Voltar.<br />\r\n<br />\r\ncaando. ahauauhauh', 1345224154),
(2, 2, 1, 'Estamos com problemas de codificação nos acentos. Em breve serão concertados.', 1345224507),
(3, 3, 1, ':NAAO:', 1345224536),
(4, 3, 13, 'Cuidado Sr. ADM em alguns casos emoticons podem ser considerados flood levando a receber alerta de até 20%. Recomendo que o senhor não volte a cometer este erro.<br />\r\n<br />\r\n:trollface:', 1345224682),
(5, 3, 11, 'O ADM levar alerta, esta é boa, rs.', 1345225940),
(7, 4, 1, 'Ae evoluiu agora tem até clip hahah', 1345228453),
(8, 4, 94, 'Hahahaha tem mesmo mais eu nao estou conseguindo por o video direto aki no forum ta bugado?', 1345229126),
(9, 5, 13, 'Obrigado Vdzz pela sinceridade, já estamos ciente do bug e estamos corrigindo, eu fiquei na espera para ver qual player seria sincero bastante para reportar esse bug ao invés do restante só abusar.<br />\r\n<br />\r\nAgradeço novamente, logo corrigiremos.', 1345230546),
(10, 2, 112, 'Ei ta todo mundo me matando e tal, PVP ta liberado muito cedo ', 1345231324),
(11, 4, 2, 'EU E O DEH AUEHAEUAEHAUEHAEUAHEAUEHAUEHAUEHAEUAHEUAHEAUEHA. O OCEEE!!!!!!!<br />\r\nAEAUEHAUEAUHEAEA', 1345231325),
(12, 4, 1, 'O youtube bugou mesmo ;x<br />\r\nvou arrumar depois', 1345231391),
(13, 6, 1, 'A previsão é de estar online até segunda feira.', 1345231456),
(15, 4, 94, 'EHAISOHESAIOHEOSAIHEIOSAHEOISAHIOESAHIOESA', 1345232571),
(18, 4, 2, 'Ficou muito foda essa música. vamo fazer outra deh.', 1345234486),
(19, 10, 126, 'kkkkkkkkk duvido que alguém lembre de mim', 1345236392),
(20, 10, 169, 'Lyon?', 1345236624),
(21, 10, 2, 'Cater meu amr?', 1345236687),
(22, 3, 126, 'kkkkkkkkk tenso. quer ver se essa ''geração'' do OC vai ser dahora ou se vai ser meia boca', 1345236701),
(23, 10, 6, 'Lyon sou eu , swordmaster e o cater ... ISMAEL CARA DE BUNDA , S2 S2 BY LYON AHWUHAUHAUHW', 1345238101),
(434, 117, 6, 'Erro ja foi corrigido , para mais informações entre , www.ocrpg.com/changelog.php .', 1345848305),
(435, 105, 6, 'Este Bug Já Foi Corrigido , Para mais informações Entre : www.ocrpg.com/changelog.php .', 1345848410),
(436, 6, 6, 'A Loja Vip está Sendo Desenvolvida , Para Mais Informações Entre : www.ocrpg.com/changelog.php .', 1345848462),
(437, 1, 1222, 'curti d+', 1345848701),
(438, 46, 6, 'Para ver se este bug foi resolvido , entre em ; www.ocrpg.com/changelog.php , lá você saberá de atualizações , correções entre outras coisas .', 1345848743),
(439, 62, 6, 'Aeee \\o/ rs , Música loca . curti (y)', 1345848940);

-- --------------------------------------------------------

--
-- Table structure for table `forum_description`
--

CREATE TABLE IF NOT EXISTS `forum_description` (
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `forum_description`
--


-- --------------------------------------------------------

--
-- Table structure for table `forum_question`
--

CREATE TABLE IF NOT EXISTS `forum_question` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `topic` varchar(255) NOT NULL DEFAULT '',
  `category` enum('noticias','tutorial','sugestoes','duvidas','gangues','outros','trade','reino','fan','off') NOT NULL DEFAULT 'noticias',
  `detail` longtext CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `postado` int(11) NOT NULL DEFAULT '0',
  `last_post` int(11) NOT NULL DEFAULT '0',
  `view` int(4) NOT NULL DEFAULT '0',
  `reply` int(4) NOT NULL DEFAULT '0',
  `up` int(11) NOT NULL DEFAULT '0',
  `down` int(11) NOT NULL DEFAULT '0',
  `fixo` enum('t','f') NOT NULL DEFAULT 'f',
  `closed` enum('t','f') NOT NULL DEFAULT 'f',
  `vota` enum('t','f') NOT NULL DEFAULT 't',
  `serv` tinyint(2) NOT NULL DEFAULT '0',
  `reino` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `Otimizacao6` (`category`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=277 ;

--
-- Dumping data for table `forum_question`
--

INSERT INTO `forum_question` (`id`, `topic`, `category`, `detail`, `user_id`, `postado`, `last_post`, `view`, `reply`, `up`, `down`, `fixo`, `closed`, `vota`, `serv`, `reino`) VALUES
(1, 'O Confronto :: O Início', 'noticias', '[center][img]http://ocrpg.comimages//logo-dark.png[/img][/center]<br />\r\n<br />\r\nFoi dado aberta a nova temporada do Oconfronto, agora em sua mais nova versão, com design renovado, novas funções e muito mais rpg.<br />\r\n<br />\r\nLogo de cara já é possível perceber a grande reformulação que o game sofreu, novas imagens e cores.<br />\r\n<br />\r\nGame agora com novas funções e sistemas, e não para por ai, temos mais sistemas no papel para lançar com o tempo. Irei explicar um pouco mais sobre os sistemas presentes.<br />\r\n<br />\r\n[b][center][big]Batalhar Contra Jogadores[/big][/center][/b]<br />\r\n[small][center]Ta afim de uma grana extra?[/center][/small]<br />\r\nAgora o sistema de batalha contém com uma lista de procurados, players que mais "pklizam" no game sobem ao topo dessa lista, quem os matar ganhará uma grande recompensa em troca.<br />\r\n<br />\r\n[center][big][b]Duelo[/b][/big][/center]<br />\r\n[center][small]Lute em tempo real contra seu inimigo.[/small][/center]<br />\r\nNovo sistema de duelo tão prometido nas versões anteriores do [b]Oconfronto[/b]. Escolha atenciosamente seu inimigo, o desafie. Ele terá árdua missão: aceitar, ou não aceitar o desafio. Em tempo real você dê um lado escolhendo magias tanto de ataque ou defesa ele do outro atacando ou defendendo. Cada player tem um tempo de 30 segundos para decidir seu ataque. Escolha bem seu ataque, ele poderá definir o rumo da sua batalha.<br />\r\n<br />\r\n[center][big][b]Ganhar Ouro[/b][/big][/center]<br />\r\n[small][center]Convide Novos Amigos para OC e Ganhe Ouro e Itens ![/center][/small]<br />\r\nAgora contamos com um novo e belo sistema de recompensas ao convidar novos amigos. Ao algum amigo fizer registro através de seu [b]Link de Referência[/b] o sistema irá contabilizar em sua conta quantos convites foram enviados, teu amigo ao atingir level 40 o sistema dará uma recompensa a você. Poderá receber tanto uma pequena quantia de gold ou até mesmo um item super valioso com atributos únicos. Tudo depende... de quantos amigos você irá convidar.<br />\r\n<br />\r\nVisite a [url=http://ocrpg.com/earn.php]página[/url] e confira lista de itens que estão disponíveis como recompensa.<br />\r\n<br />\r\n[center][b][big]Arena[/big][/b][/center]<br />\r\n[small][b][center]Local destinado aos mais bravos guerreiros.[/center][/b][/small]<br />\r\nGosta de desafios? Arena é local certo para você! Lá você encontra uma lista de tarefas disponíveis. Cada tarefa tem o seu tempo para que seja realizada, e recompensa ao ser completada Cada tarefa completada você receberá "dungeon points" Que são  bem valiosos, quer descobrir para que são? Te desafio a conseguir alguns. Escolha sua tarefa e mate os monstros que estão aterrorizando o império. Mas vai uma dica... Cuidado, eles não estão para brincadeira.<br />\r\n<br />\r\n[big][center][b]Taverna[/b][/center][/big]<br />\r\n[small][center][b]O Imperador é bondoso.[/b][/center][/small]<br />\r\nTaverna, conhecido como local de descontração onde todos os guerreiros pegam suas bebidas sentam e se divertem... Mas também é local onde todo tipo de gente aparece dos mais sombrosos confins do império. Boatos dizem que até o imperador envio mensageiros a este local com intuito de entregar "tarefas" aos novos guerreiros, e claro, em troca recompensas. Cada level tem suas tarefas, que ao ser completadas são entregues fortuna ou equipamentos. Até dizem por ai que algumas tarefas rendem as tão sonhadas "Medalhas".<br />\r\n<br />\r\n<br />\r\nBem guerreiros, este são alguns novos sistemas, além desses temos outras reformulações em sistemas já existentes.<br />\r\nNão vou explicar todos detalhadamente, pois acredito que quase todos já são guerreiros experientes do império, cujo estavam aposentados mas agora teu imperador os requisitaram novamente. Embainha tua espada e escudo e vá a luta.<br />\r\n<br />\r\n<br />\r\nAvance teu level, melhore teus equipamentos, mantenha os amigos perto e o inimigos sempre mortos. <br />\r\n<br />\r\nEsta declarada abertura oficial do [b]OCONFRONTO[/b] Batalha Medieval.', 1, 1345219174, 1350392030, 724, 20, 0, 0, 'f', 'f', 'f', 1, 1),
(2, 'Problemas com o Jogo', 'outros', 'Se você encontrou algum erro ou funcionamento indevido no jogo, por favor reporte aqui.<br />\r\nCaso o erro seja grave demais para divulgar publicamente, [url=bugs.php]clique aqui[/url] e mande uma mensagem para a administração.', 1, 1345221616, 1351180497, 913, 61, 0, 0, 'f', 'f', 'f', 1, 1),
(3, 'Este Tópico Vai Eternizar.', 'off', 'Um dos primeiros tópico da nova era do Oconfronto, quem ai acha que devemos eternizar este tópico? <br />\r\n<br />\r\nComentem a vontade e digam o que acharam do novo Oconfronto!<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\n:megusta:', 13, 1345224037, 1345344115, 108, 10, 0, 0, 'f', 'f', 'f', 1, 3),
(4, '[MUSIC] O Retorno - Deeh & Junior', 'off', 'Koe pessoal blz?? vim aki postar uma brincadeira q eu e meu amigo edilon junior preparamos para o novo OC!<br />\r\n<br />\r\n[url]http://www.youtube.com/watch?v=d-Ci47qwRsw&feature=youtu.be[/url]<br />\r\n<br />\r\nBOA SORTE A TODOS!!!!<br />\r\n<br />\r\n<br />\r\nOBS: essa musica é destinada ao antigo destiny não ao pela saco q criou com o nick dos outros agr....', 94, 1345227508, 1345304773, 131, 9, 0, 0, 'f', 'f', 'f', 1, 2),
(5, 'Bug?', 'outros', 'Como não achei a seção para postar isto, irei reportar este bug aqui nesta seção. Quando você está batalhando e clica em "curar", o seu hp fica bem acima do que ele realmente é. No caso o meu na hora foi para 405, depois que batalhei com um gorila caiu para 307..<br />\r\nAcredito que isto seja um bug, não achei nenhum tópico a respeito.<br />\r\nAi está uma screen provando:<br />\r\n[img]http://i.imgur.com/P9gsL.png[/img]<br />\r\n<br />\r\nAposto que muitas pessoas já abusaram disto, rs.', 80, 1345230010, 1345230546, 68, 1, 0, 0, 'f', 't', 'f', 1, 3),
(6, 'Loja VIP', 'outros', 'vai ser liberada quando...', 129, 1345230486, 1345848462, 70, 3, 0, 0, 'f', 't', 'f', 1, 1),
(8, 'Energia', 'duvidas', 'Tem como aumentar a energia? E se tiver como ou quando?', 104, 1345233656, 1345238916, 43, 1, 0, 0, 'f', 't', 'f', 1, 1),
(10, 'ISMAEL', 'off', 'ESTOU DE VOLTA A ATIVA NO OC. <br />\r\nPARA SEUS PESADELOS HAEUHAEUHUAEHUA ZOA', 169, 1345236118, 1350705846, 113, 10, 0, 0, 'f', 'f', 'f', 1, 3),
(11, 'Mistério', 'off', 'Para quem está se perguntando quem sou, eis que vou me revelar aos queridos amigos.<br />\r\n<br />\r\nPrazer, TSHJ.<br />\r\n<br />\r\n<br />\r\nme amem menos.<br />\r\n<br />\r\n<br />\r\n:trollface:', 13, 1345238615, 1345346993, 87, 6, 0, 0, 'f', 'f', 'f', 1, 3),
(12, 'Pontos Misticos', 'duvidas', 'Como é que ganha pontos misticos?', 104, 1345238662, 1345238823, 34, 1, 0, 0, 'f', 't', 'f', 1, 1),
(129, 'Hawk''s Shop', 'trade', '[center][b][color=black][u][big][i]Hawk''s Shop[/i][/big][/u][/color][/b][/center]<br />\r\n<br />\r\n<br />\r\nItens a venda:<br />\r\n<br />\r\n<br />\r\n[center][b][color=blue]! NEW ![/color][/b][/center]<br />\r\n<br />\r\n<br />\r\n<br />\r\n[center]<br />\r\n[img]http://ocrpg.com/images/itens/senior_armor.gif[/img][/center]<br />\r\n[center][color=black]Armadura das sombras +0 - Level: 60 Def: 54[/s][/color][/center]<br />\r\n[center][color=black][b][s](Atenção: Esta armor o ferreiro não tem na loja)[/s][/b][/color][/center]<br />\r\n[center][color=black]C$:[/color] [color=red]60,000[/color][/center]<br />\r\n<br />\r\n<br />\r\n[center][img]http://ocrpg.com/images/itens/crslegs.gif[/img][/center]<br />\r\n[center][color=black][s]Calça negra +0[/s][/color] [color=red][s]+1res[/s][/color] [color=black][s]- Level: 72[/s][/color][/center]<br />\r\n[center][color=black]C$:[/color] [color=red]--[/color][/center]<br />\r\n[center][color=black]Comprado por:[/color] [url=/profile.php?id=Deiverr]Deiverr[/url][/center]<br />\r\n<br />\r\n<br />\r\n[center]<br />\r\n[img]http://ocrpg.com/images/itens/wand_of_voodoo.gif[/img][/center]<br />\r\n[center][color=black]Bastao do mal +0 - Level: 67[/color][/center]<br />\r\n[center][color=black]C$:[/color] [color=red]60,000[/color][/center]<br />\r\n<br />\r\n[center][img]http://ocrpg.com/images/itens/terra_amulet.gif[/img][/center]<br />\r\n[center][color=black]Amuleto da terra+0[/color] [color=gray]+1for[/color] [color=black]- Level: 49[/color][/center]<br />\r\n[center][color=black]C$:[/color] [color=red]30,000[/color][/center]<br />\r\n<br />\r\n[center][img]http://ocrpg.com/images/itens/imperialarm.gif[/img][/center]<br />\r\n[center][color=black][s]Armadura imperial +0[/s][/color] [color=gray][s]+1for[/s][/color] [color=blue][s]+3agi[/s][/color] [color=red][s]+3res[/s][/color] [color=black][s]- Level: 54[/s][/color][/center][center]<br />\r\n[color=black]C$:[/color] [color=red]--[/color][/center]<br />\r\n[center][color=black]Comprado por:[/color] [url=/profile.php?id=Deiverr]Deiverr[/url][/center]<br />\r\n<br />\r\n[center]<br />\r\n[img]http://ocrpg.com/images/itens/deathaxe.gif[/img][/center]<br />\r\n[center][color=black]Machado da morte +0 - Level: 62[/color][/center]<br />\r\n[center][color=black]C$:[/color] [color=red]50,000[/color][/center]<br />\r\n<br />\r\n[center][img]http://ocrpg.com/images/itens/goldenarmor.gif[/img][/center]<br />\r\n[center][color=black]Armadura de ouro +0[/color] [color=red]+4res[/color] [color=black]- Level: 40[/color][/center]<br />\r\n[center][color=black]C$:[/color] [color=red]13,000[/color][/center]<br />\r\n<br />\r\n[center]<br />\r\n[img]http://ocrpg.com/images/itens/darkshield.gif[/img][/center]<br />\r\n[center][color=black]Escudo Escuro +0 - Level 49[/color][/center]<br />\r\n[center][color=black]C$:[/color] [color=red]30,000[/color][/center]<br />\r\n<br />\r\n<br />\r\n[center]<br />\r\n[img]http://ocrpg.com/images/itens/senior_legs.gif[/img][/center]<br />\r\n[center][color=black][s]Calças sombrias +0[/s][/color] [color=green][s]+4vit[/s][/color] [color=red][s]+3res[/s][/color][color=black][s]- Level: 60 Def: 44[/s][/color][/center]<br />\r\n[center][color=black][b][s](Atenção: Esta calça o ferreiro não tem na loja)[/s][/b][/color][/center]<br />\r\n[center][color=black]C$:[/color] [color=red]--[/color][/center]<br />\r\n[center][color=black]Comprado por:[/color] [url=ocrpg.com/profile.php?id=Poso]Poso[/url][/center]<br />\r\n<br />\r\n[center]<br />\r\n[img]http://ocrpg.com/images/itens/darkring.gif[/img][/center]<br />\r\n[center][color=black]Anel escuro[/color] [color=gray]+10for[/color] [color=red]+15res[/color][/center]<br />\r\n[center][color=black]C$:[/color] [color=red]45,000[/color][/center]<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\n[color=blue][b]Sessão 1:[/b][/color]<br />\r\n<br />\r\n[center][img]http://ocrpg.com/images/itens/noblearmor.gif[/img][/center]<br />\r\n[center][color=black][s]Armadura Nobre +9 - Level: 24[/s][/color][/center]<br />\r\n<br />\r\n[center][color=black]C$:[/color] [color=red]--[/color][/center]<br />\r\n[center][color=red]Comprado por:[/color] [url=http://ocrpg.com/profile.php?id=Poso]Poso[/url][/center]<br />\r\n<br />\r\n[center][img]http://ocrpg.com/images/itens/dwarvenhelmet.gif[/img][/center]<br />\r\n[center][color=black][s]Capacete dos anões +9 - Level: 32[/s]<br />\r\n[/color][/center]<br />\r\n[center][color=black]C$:[/color] [color=red]--[/color]<br />\r\n[color=red]Comprado por:[/color] [url=http://ocrpg.com/profile.php?id=Poso]Poso[/url][/center]<br />\r\n<br />\r\n[center][img]http://ocrpg.com/images/itens/steelshield.gif[/img][/center] <br />\r\n[center][color=black][s]Escudo de Aço +9 - Level: 35[/s][/color][/center]<br />\r\n[center][color=black]C$:[/color] [color=red]--[/color]<br />\r\n[color=red]Comprado por:[/color] [url=http://ocrpg.com/profile.php?id=Poso]Poso[/url][/center]<br />\r\n<br />\r\n<br />\r\n[center]<br />\r\n[img]http://ocrpg.com/images/itens/moonstaff.gif[/img][/center] <br />\r\n[center][color=black]Bastão da lua +9 - Level: 25[/color][/center]<br />\r\n[center]<br />\r\n[color=black]C$:[/color] [color=red]30,000[/color][/center]<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\n[color=blue][b]Sessão 2:[/b][/color]<br />\r\n[center]<br />\r\n[img]http://ocrpg.com/images/itens/queens_sceptre.gif[/img][/center] <br />\r\n[center][color=black]Bastão Dos Anjos +0 - Level 60[/color][/center]<br />\r\n<br />\r\n[center][color=black]C$:[/color] [color=red]50,000[/color][/center]<br />\r\n<br />\r\n<br />\r\n<br />\r\n[center][img]http://ocrpg.com/images/itens/earthborn_titan_armor.gif[/img][/center] <br />\r\n[center][color=black][s]Armadura de dragão +0 - Level: 70[/s][/color]<br />\r\n[/center]<br />\r\n[center][color=black]C$:[/color] [color=red]--[/color][/center]<br />\r\n[center][color=red]Comprado por:[/color] [url=http://ocrpg.com/profile.php?id=Vectron]Vectron[/url][/center]<br />\r\n<br />\r\n<br />\r\n[center]<br />\r\n[img]http://ocrpg.com/images/itens/wand_of_voodoo.gif[/img][/center] <br />\r\n[center][color=black][s]Bastão do mal +0 - Level: 67[/s][/color][/center]<br />\r\n<br />\r\n[center][color=black]C$:[/color] [color=red]--[/color][/center]<br />\r\n<br />\r\n[center][color=red]Comprado por:[/color] [url=http://ocrpg.com/profile.php?id=Hufy]Hufy-chan[/url][/center]<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\n[color=blue][b]Sessão 3:[/b][/color]<br />\r\n[center]<br />\r\n[img]http://ocrpg.com/images/itens/darkhelmet.gif[/img][/center] <br />\r\n[center][color=black][s]Capacete Negro +0 +3for - Level: 42[/s][/color][/center]<br />\r\n<br />\r\n[center][color=black]C$:[/color] [color=red]--[/color][/center]<br />\r\n[center][color=red]Comprado por:[/color] [url=ocrpg.com/profile.php?id=Poso]Poso[/url][/center]<br />\r\n<br />\r\n[center][b][i][color=BLUE]<br />\r\n!NEW![/color][/i][/b][/center]<br />\r\n[center][img]http://ocrpg.com/images/itens/enchantedstaff.gif[/img][/center]<br />\r\n[center][color=black]Basão Encantado +0[/color] [color=green]+3vit[/color][/center]<br />\r\n[center][color=black]C$:[/color] [color=red]22,000[/color][/center]<br />\r\n<br />\r\n[center][img]http://ocrpg.com/images/itens/enchantedstaff.gif[/img][/center] <br />\r\n[center][color=black]Bastão encantado +7 Level: 42[/color][/center]<br />\r\n<br />\r\n[center][color=black]C$:[/color] [color=red]90,000[/color][/center]<br />\r\n<br />\r\n<br />\r\n[center][img]http://ocrpg.com/images/itens/dwarvenarmor.gif[/img][/center] <br />\r\n[center][color=black]Armadura de Guerreiro +4 - Level: 48[/color][/center]<br />\r\n<br />\r\n[center][color=black]C$:[/color] [color=red]50,000[/color][/center]<br />\r\n[center]<br />\r\n[img]http://ocrpg.com/images/itens/dragonstaff.gif[/img][/center] <br />\r\n[center][color=black]Cajado do Dragão +4 - Level: 72 + Voc. Superior[/color][/center]<br />\r\n<br />\r\n[center][color=black]C$:[/color] [color=red]190,000[/color][/center]<br />\r\n<br />\r\n[center][img]http://ocrpg.com/images/itens/dragonshield.gif[/img][/center] <br />\r\n[center][color=black]Escudo do Dragão +8 - Level: 70 + Voc. Superior[/color][/center]<br />\r\n<br />\r\n[center][color=black]C$:[/color] [color=red]350,000<br />\r\n[/color][/center]<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\n', 2, 1345975673, 1347119997, 339, 18, 0, 0, 'f', 'f', 'f', 1, 3),
(130, 'Avaliem', 'fan', '[center][img]http://ocrpg.com/imgs/89c50340e7d39362f302208bfb38c0fa.png[/img]<br />\r\n<br />\r\nDeem suas opiniões e críticas. Faz um tempão que não mecho com o Photoshop, mas enfim, avaliem de 0 á 10 e dê sua sugestão/crítica etc.[/center]', 126, 1345989560, 1350705689, 101, 8, 0, 0, 'f', 'f', 'f', 1, 1),
(131, 'Vendo .', 'trade', 'Vendo Capacete Dos Anões + 8 , Quem Quiser Compra Me Chama Privado ou manda Mensagem . ', 6, 1346010077, 1346010077, 30, 0, 0, 0, 'f', 'f', 'f', 1, 1),
(132, 'Vendo Porçoes:Mana,é 4 Porçoes de Vida Ea Mais Barata Do Mercado.', 'trade', 'Comprem Pessoal Ea Mais Barata Do Mercado.', 1342, 1346030232, 1346099696, 32, 2, 0, 0, 'f', 'f', 'f', 1, 3),
(134, 'PVP E PVM', 'duvidas', 'Quero saber qts % coloco em pvp e qts % coloco em pvm<br />\r\n<br />\r\nkero saber me ajudem pf', 873, 1346037024, 1346542646, 81, 5, 0, 0, 'f', 'f', 'f', 1, 2),
(135, 'Bug Barra de Exp descoberto', 'outros', 'Pelo que andei reparando, todos que reclamam do bug na barra de experiencia tem nomes grandes, até mesmo esse topico ali embaixo que ja foi fechado e não pude comenta-lo. Os que tem nome pequeno não acontece isso, pq?<br />\r\nOnde tem o link para seu propio perfil (seu nome), sim é ali mesmo que está o problema.<br />\r\nJa repararam quando a pessoa tem nome composto o segundo nome sempre vai para baixo justamente onde fica o nivel e a barra de exp? talvez não seja isso que faz sumir, o nome empurrando a barra para baixo ou algo do tipo?<br />\r\nReparem só nesse link mesmo do topico anterior um se chama Bruce Dickinson "Bugado" e outro se chama Near "Sem Bug".<br />\r\n[img]http://i.imgur.com/Uc3lf.png[/img]<br />\r\n[color=blue]http://i.imgur.com/Uc3lf.png[/color]', 1396, 1346038753, 1346086999, 45, 1, 0, 0, 'f', 'f', 'f', 1, 3),
(136, 'oO', 'off', 'Você ganhou 16,666 de experiência e 4,732 de ouro.', 60, 1346040623, 1346095767, 23, 2, 0, 0, 'f', 'f', 'f', 1, 1),
(141, 'Jogadores oque Voces Acham Sobre Carsh no OC??   Leiam Este Topico', 'sugestoes', 'Não Botem Carsh Porque Fica ruim Um Cara Level 7 Com a Arma Fodastica que ele compro com Carsh, Um Cara Level 50 Que Nao tem Condiçoes de Pagar o Carsh Ele vai ficar mo Lixao Com arma Podre??<br />\r\n<br />\r\n<br />\r\n<br />\r\nJogadores Voces Qerem Carsh no Oc??<br />\r\n<br />\r\nVotem Aqui em Baixo Sim ( ) ou Não ( )<br />\r\n<br />\r\nPara um Jogo Melhor Sem Carsh,Todos os Jogos tem Carsh e se o OC nao Tiver Duvido se Ele Não Sera Melhor do Que Agora.<br />\r\n', 1342, 1346077255, 1346206811, 107, 9, 1, 7, 'f', 't', 't', 1, 3),
(145, 'Problema no sistema de equipamentos', 'sugestoes', 'Bom , mal entrei no game (jogando pelo ipad) e ja me dou com um problema tem q arrastar o item pra equipar poderia ser diferente , pois arrastar nao da no ipad pelo menos n nesse jogo, gostaria q tivesse um segundo geito de equipar como clickar no item e aparecer as opcoes la de equipar remover e etc.... Obrigado pela atençao , espero q melhorem isso ', 1466, 1346178599, 1346216285, 37, 5, 0, 0, 'f', 't', 'f', 1, 1),
(147, 'Futebol', 'off', 'Gosta de futebol internacional ? ta rolando um grande clássico , REAL MADRID x BARCELONA<br />\r\n<br />\r\nhttp://aovivo.esporteinterativo.com.br/', 6, 1346273234, 1347485402, 31, 3, 0, 0, 'f', 'f', 'f', 1, 1),
(148, 'Problema em vendas de itens no mercado', 'outros', 'Seguinte , hoje a tarde eu fui vender orbs , itens de quest no mercado , ai deu tudo certinho para colocar , mais não aparece no mercado , a opção , ITENS DE QUEST , para aparecer itens a serem vendidos , e então eu perdi 2 orbs , e tenho o Hufy como testemunha , preciso que o ADM , me devolva .', 873, 1346278468, 1346296092, 20, 1, 0, 0, 'f', 't', 'f', 1, 2),
(149, 'Lyon Shop.', 'trade', 'Vendo , -Preços a Combinar , mande uma mp ou chame privado.<br />\r\n<br />\r\n                                Escudo Grifo + 7 .<br />\r\n              [img]http://ocrpg.com/images/itens/griffinshield.gif[/img]<br />\r\n<br />\r\n                                 Calças Místicas + 7 <br />\r\n              [img]http://ocrpg.com/images/itens/lightning_legs.gif[/img]<br />\r\n                                       <br />\r\n<br />\r\n', 6, 1346297080, 1346297080, 43, 0, 0, 0, 'f', 'f', 'f', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `for_use`
--

CREATE TABLE IF NOT EXISTS `for_use` (
  `item_id` int(11) NOT NULL,
  `for` int(11) NOT NULL,
  `vit` int(11) NOT NULL,
  `agi` int(11) NOT NULL,
  `res` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `for_use`
--

INSERT INTO `for_use` (`item_id`, `for`, `vit`, `agi`, `res`) VALUES
(179, 10, 0, 0, -5),
(180, 0, 0, -5, 10),
(181, 0, 0, 5, 0),
(182, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

CREATE TABLE IF NOT EXISTS `friends` (
  `uid` int(11) NOT NULL DEFAULT '0',
  `fname` varchar(255) NOT NULL DEFAULT '',
  KEY `Otimizacao6` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL DEFAULT '0',
  `player_id` int(11) NOT NULL DEFAULT '0',
  `exp` int(11) NOT NULL DEFAULT '0',
  `kills` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `group_invite`
--

CREATE TABLE IF NOT EXISTS `group_invite` (
  `group_id` int(11) NOT NULL DEFAULT '0',
  `invited_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `guilds`
--

CREATE TABLE IF NOT EXISTS `guilds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reino` enum('0','1','2','3') COLLATE latin1_general_ci NOT NULL DEFAULT '0',
  `leader` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `vice` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `name` varchar(25) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `tag` varchar(4) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `members` int(11) NOT NULL DEFAULT '1',
  `maxmembers` int(11) NOT NULL DEFAULT '20',
  `motd` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `price` int(11) NOT NULL DEFAULT '20',
  `img` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT 'default_guild.png',
  `gold` int(11) NOT NULL DEFAULT '0',
  `blocked` int(11) NOT NULL DEFAULT '0',
  `blurb` text COLLATE latin1_general_ci NOT NULL,
  `msgs` tinyint(1) NOT NULL DEFAULT '0',
  `pagopor` int(11) NOT NULL DEFAULT '0',
  `registered` int(11) NOT NULL DEFAULT '0',
  `serv` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `Otimizacao6` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Table structure for table `guild_aliance`
--

CREATE TABLE IF NOT EXISTS `guild_aliance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guild_na` varchar(255) NOT NULL DEFAULT '',
  `aled_na` varchar(255) NOT NULL DEFAULT '',
  `time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `guild_aliance`
--


-- --------------------------------------------------------

--
-- Table structure for table `guild_enemy`
--

CREATE TABLE IF NOT EXISTS `guild_enemy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guild_na` varchar(255) NOT NULL DEFAULT '',
  `enemy_na` varchar(255) NOT NULL DEFAULT '',
  `time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `guild_enemy`
--


-- --------------------------------------------------------

--
-- Table structure for table `guild_invites`
--

CREATE TABLE IF NOT EXISTS `guild_invites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL DEFAULT '0',
  `guild_id` int(11) NOT NULL DEFAULT '0',
  `message` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ix_guild_invites_player_guild` (`player_id`,`guild_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=261 ;

-- --------------------------------------------------------

--
-- Table structure for table `guild_paliance`
--

CREATE TABLE IF NOT EXISTS `guild_paliance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guild_na` varchar(255) NOT NULL DEFAULT '',
  `aled_na` varchar(255) NOT NULL DEFAULT '',
  `time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `guild_paliance`
--


-- --------------------------------------------------------

--
-- Table structure for table `hunt`
--

CREATE TABLE IF NOT EXISTS `hunt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL,
  `start` int(11) NOT NULL,
  `hunttype` varchar(50) NOT NULL,
  `hunttime` varchar(3) NOT NULL,
  `status` enum('t','f','a') NOT NULL DEFAULT 't',
  PRIMARY KEY (`id`),
  KEY `Otimizacao1` (`status`),
  KEY `Otimizacao2` (`player_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16545 ;

-- --------------------------------------------------------

--
-- Table structure for table `ignored`
--

CREATE TABLE IF NOT EXISTS `ignored` (
  `uid` int(11) NOT NULL DEFAULT '0',
  `bid` int(11) NOT NULL DEFAULT '0',
  KEY `Otimizacao6` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `in_use`
--

CREATE TABLE IF NOT EXISTS `in_use` (
  `player_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  KEY `Otimizacao1` (`player_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE IF NOT EXISTS `items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL DEFAULT '0',
  `item_id` int(11) NOT NULL DEFAULT '0',
  `item_bonus` int(11) NOT NULL DEFAULT '0',
  `for` tinyint(2) NOT NULL DEFAULT '0',
  `vit` tinyint(2) NOT NULL DEFAULT '0',
  `agi` tinyint(2) NOT NULL DEFAULT '0',
  `res` smallint(2) NOT NULL DEFAULT '0',
  `status` enum('equipped','unequipped') COLLATE latin1_general_ci NOT NULL DEFAULT 'unequipped',
  `tile` tinyint(3) NOT NULL DEFAULT '1',
  `mark` char(1) COLLATE latin1_general_ci NOT NULL DEFAULT 'f',
  `item_event` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `Otimizacao1` (`status`),
  KEY `Otimizacao2` (`player_id`),
  KEY `Otimizacao3` (`item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=41322 ;

-- --------------------------------------------------------

--
-- Table structure for table `list_credito`
--

CREATE TABLE IF NOT EXISTS `list_credito` (
  `qt` int(11) NOT NULL DEFAULT '0',
  `qt_reward` int(11) NOT NULL DEFAULT '0',
  `valor` decimal(10,2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `list_credito`
--

INSERT INTO `list_credito` (`qt`, `qt_reward`, `valor`) VALUES
(1, 15, '7.00'),
(10, 25, '10.00'),
(19, 50, '19.00'),
(24, 75, '24.00'),
(30, 100, '30.00');

-- --------------------------------------------------------

--
-- Table structure for table `logbat`
--

CREATE TABLE IF NOT EXISTS `logbat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL DEFAULT '0',
  `msg` text COLLATE latin1_general_ci NOT NULL,
  `status` enum('read','unread') COLLATE latin1_general_ci NOT NULL DEFAULT 'unread',
  `time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `Otimizacao1` (`time`),
  KEY `Otimizacao2` (`player_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=25118 ;

-- --------------------------------------------------------

--
-- Table structure for table `login_tries`
--

CREATE TABLE IF NOT EXISTS `login_tries` (
  `ip` varchar(50) NOT NULL DEFAULT '',
  `tries` tinyint(1) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `login_tries`
--


-- --------------------------------------------------------

--
-- Table structure for table `log_battle`
--

CREATE TABLE IF NOT EXISTS `log_battle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL DEFAULT '0',
  `log` longtext CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `log_battle`
--


-- --------------------------------------------------------

--
-- Table structure for table `log_errors`
--

CREATE TABLE IF NOT EXISTS `log_errors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `msg` text NOT NULL,
  `time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `log_errors`
--


-- --------------------------------------------------------

--
-- Table structure for table `log_forum`
--

CREATE TABLE IF NOT EXISTS `log_forum` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post` int(11) NOT NULL DEFAULT '0',
  `msg` text NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `log_forum`
--

INSERT INTO `log_forum` (`id`, `post`, `msg`, `type`, `time`) VALUES
(1, 0, 'O tópico Explicações foi deletado pelo moderador <b>Lord</b>', 0, 1351144523),
(2, 0, 'O tópico Versão Mobile Esclarecimentos foi deletado pelo moderador <b>Lord</b>', 0, 1351144529),
(3, 0, 'O tópico Mudanças Recentes foi deletado pelo moderador <b>Lord</b>', 0, 1351144537),
(4, 0, 'O tópico Troll Master foi deletado pelo moderador <b>Lord</b>', 0, 1351144603);

-- --------------------------------------------------------

--
-- Table structure for table `log_friends`
--

CREATE TABLE IF NOT EXISTS `log_friends` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fname` varchar(255) NOT NULL DEFAULT '',
  `log` longtext CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9240 ;

-- --------------------------------------------------------

--
-- Table structure for table `log_gm`
--

CREATE TABLE IF NOT EXISTS `log_gm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `msg` text NOT NULL,
  `time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `log_gm`
--


-- --------------------------------------------------------

--
-- Table structure for table `log_gold`
--

CREATE TABLE IF NOT EXISTS `log_gold` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL DEFAULT '0',
  `name1` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `name2` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `value` int(11) NOT NULL DEFAULT '0',
  `action` varchar(255) NOT NULL DEFAULT '',
  `time` int(11) NOT NULL DEFAULT '0',
  `status` enum('read','unread') NOT NULL DEFAULT 'unread',
  PRIMARY KEY (`id`),
  KEY `Otimizacao1` (`player_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2409 ;

-- --------------------------------------------------------

--
-- Table structure for table `log_item`
--

CREATE TABLE IF NOT EXISTS `log_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL DEFAULT '0',
  `name1` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `name2` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `value` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `itemid` int(11) NOT NULL DEFAULT '0',
  `action` varchar(255) NOT NULL DEFAULT '',
  `aditional` varchar(255) NOT NULL DEFAULT '',
  `time` int(11) NOT NULL DEFAULT '0',
  `status` enum('read','unread') NOT NULL DEFAULT 'unread',
  PRIMARY KEY (`id`),
  KEY `Otimizacao2` (`player_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5197 ;

-- --------------------------------------------------------

--
-- Table structure for table `log_reino`
--

CREATE TABLE IF NOT EXISTS `log_reino` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reino` int(11) NOT NULL DEFAULT '0',
  `log` longtext CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=343 ;

-- --------------------------------------------------------

--
-- Table structure for table `loot`
--

CREATE TABLE IF NOT EXISTS `loot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `monster_id` int(11) NOT NULL DEFAULT '0',
  `item_id` int(11) NOT NULL DEFAULT '0',
  `item_prepo` varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT 'uma',
  `item_name` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `loot`
--

INSERT INTO `loot` (`id`, `monster_id`, `item_id`, `item_prepo`, `item_name`) VALUES
(1, 13, 107, 'um', 'Orbe do vento'),
(2, 14, 108, 'um', 'Orbe da terra'),
(3, 15, 110, 'um', 'Orbe da água'),
(4, 16, 109, 'um', 'Orbe do fogo'),
(5, 17, 112, 'um', 'Cristal encantado');

-- --------------------------------------------------------

--
-- Table structure for table `lotto`
--

CREATE TABLE IF NOT EXISTS `lotto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL DEFAULT '0',
  `serv` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `Otimizacao1` (`player_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1643 ;

--
-- Dumping data for table `lotto`
--


-- --------------------------------------------------------

--
-- Table structure for table `magias`
--

CREATE TABLE IF NOT EXISTS `magias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL DEFAULT '0',
  `magia_id` int(11) NOT NULL DEFAULT '0',
  `used` enum('t','f') NOT NULL DEFAULT 't',
  PRIMARY KEY (`id`),
  KEY `Otimizacao2` (`player_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2533 ;

-- --------------------------------------------------------

--
-- Table structure for table `mail`
--

CREATE TABLE IF NOT EXISTS `mail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `to` int(11) NOT NULL DEFAULT '0',
  `from` int(11) NOT NULL DEFAULT '0',
  `subject` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `body` text COLLATE latin1_general_ci NOT NULL,
  `time` int(11) NOT NULL DEFAULT '0',
  `status` enum('read','unread') COLLATE latin1_general_ci NOT NULL DEFAULT 'unread',
  PRIMARY KEY (`id`),
  KEY `Otimizacao1` (`to`),
  KEY `Otimizacao2` (`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=2891 ;

-- --------------------------------------------------------

--
-- Table structure for table `market`
--

CREATE TABLE IF NOT EXISTS `market` (
  `market_id` int(11) NOT NULL DEFAULT '0',
  `ite_id` int(11) NOT NULL DEFAULT '0',
  `price` int(11) NOT NULL DEFAULT '0',
  `seller` varchar(50) NOT NULL DEFAULT '',
  `expira` int(11) NOT NULL DEFAULT '0',
  `serv` tinyint(2) NOT NULL DEFAULT '0',
  KEY `seller` (`seller`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `medalhas`
--

CREATE TABLE IF NOT EXISTS `medalhas` (
  `player_id` int(11) NOT NULL DEFAULT '0',
  `medalha` varchar(255) NOT NULL DEFAULT '',
  `type` enum('1','2','3') NOT NULL DEFAULT '3',
  `motivo` text NOT NULL,
  KEY `Otimizacao2` (`player_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `monsters`
--

CREATE TABLE IF NOT EXISTS `monsters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prepo` varchar(25) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT 'o',
  `username` varchar(25) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `image_path` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT 'default.png',
  `level` int(11) NOT NULL DEFAULT '0',
  `strength` int(11) NOT NULL DEFAULT '0',
  `vitality` int(11) NOT NULL DEFAULT '0',
  `agility` int(11) NOT NULL DEFAULT '0',
  `hp` int(11) NOT NULL DEFAULT '0',
  `mana` int(50) NOT NULL,
  `mtexp` int(5) NOT NULL DEFAULT '1',
  `loot` int(5) NOT NULL DEFAULT '1',
  `evento` enum('t','f','n') NOT NULL DEFAULT 'f',
  PRIMARY KEY (`id`),
  KEY `Otimizacao1` (`username`),
  KEY `Otimizacao6` (`level`),
  KEY `Otimizacao5` (`evento`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=66 ;

--
-- Dumping data for table `monsters`
--

INSERT INTO `monsters` (`id`, `prepo`, `username`, `image_path`, `level`, `strength`, `vitality`, `agility`, `hp`, `mana`, `mtexp`, `loot`, `evento`) VALUES
(1, 'a', 'Cobra', 'rat.gif', 1, 4, 3, 3, 40, 15, 20, 1, 'f'),
(2, 'o', 'Lobo', 'snake.gif', 5, 8, 8, 8, 70, 20, 45, 1, 'f'),
(3, 'o', 'Gorila', 'default.png', 10, 15, 13, 15, 130, 25, 65, 1, 'f'),
(4, 'o', 'Crocodilo', 'default.png', 15, 25, 20, 25, 230, 30, 90, 1, 'f'),
(5, 'o', 'Guerreiro de Elryn', 'kimont.png', 30, 66, 45, 60, 380, 45, 255, 1, 'f'),
(6, 'o', 'Nômade', 'noma.png', 20, 36, 29, 35, 240, 35, 130, 1, 'f'),
(7, 'o', 'Arqueiro de Elryn', 'pamont.png', 35, 78, 53, 70, 420, 50, 320, 1, 'f'),
(8, 'o', 'Thrall', 'thrall.png', 45, 100, 68, 90, 530, 60, 425, 1, 'f'),
(65, 'o', 'Ladrão', 'default.png', 33, 70, 40, 65, 380, 40, 300, 1, 'n'),
(10, 'o', 'Mago de Elryn', 'grunt.png', 40, 89, 60, 80, 480, 55, 365, 1, 'f'),
(11, 'o', 'Elfo', 'mumia.png', 50, 111, 75, 100, 580, 65, 470, 1, 'f'),
(12, 'o', 'Feiticeiro', 'feiticeiro.gif', 55, 122, 83, 110, 620, 70, 525, 1, 'f'),
(13, 'o', 'Decapitador', 'decapitador.gif', 60, 133, 90, 120, 680, 75, 575, 50, 'f'),
(14, 'o', 'Guerreiro Zumbi', 'zumbi.png', 70, 155, 105, 140, 760, 85, 710, 50, 'f'),
(15, 'o', 'Tauren', 'tauren.png', 80, 177, 120, 160, 850, 95, 815, 50, 'f'),
(16, 'a', 'Múmia', 'mend.png', 90, 199, 135, 180, 945, 105, 900, 50, 'f'),
(17, 'o', 'Dragão de Pedra', 'silver.png', 110, 234, 165, 220, 1100, 135, 1180, 60, 'f'),
(20, 'o', 'Grifo', 'aracnith.png', 120, 256, 180, 240, 1200, 155, 1282, 1, 'f'),
(24, 'o', 'Harpia', 'caz.png', 170, 410, 301, 385, 1700, 255, 1850, 1, 'f'),
(25, 'o', 'Minotauro Gigante', 'bigmino.png', 140, 320, 230, 290, 1400, 195, 1470, 1, 'f'),
(26, 'o', 'Titan', 'titan.png', 150, 360, 260, 320, 1500, 215, 1600, 1, 'f'),
(27, 'o', 'Dragão Marinho', 'sea.gif', 160, 389, 280, 350, 1600, 235, 1750, 1, 'f'),
(28, 'o', 'Munos', 'munos.gif', 210, 540, 390, 530, 2100, 335, 2310, 1, 'f'),
(29, 'o', 'Cavaleiro Negro', 'blackknight.gif', 270, 790, 530, 705, 2700, 375, 2960, 1, 'f'),
(30, 'a', 'Medusa', 'angel.gif', 310, 920, 595, 800, 3150, 395, 3525, 1, 'f'),
(31, 'o', 'Gigante de Lava', 'mag.gif', 290, 860, 565, 740, 2900, 385, 3200, 1, 'f'),
(32, 'o', 'Guerreiro de Ferro', 'ferro.png', 130, 285, 200, 262, 1300, 175, 1350, 1, 'f'),
(34, 'o', 'Dragão', 'dragon.png', 100, 221, 150, 200, 1010, 115, 980, 1, 'f'),
(35, 'o', 'Vampiro', 'orba.png', 180, 440, 322, 417, 1810, 275, 1956, 1, 'f'),
(36, 'o', 'Basilisco', 'demonsblu.gif', 190, 470, 342, 455, 1900, 295, 2100, 1, 'f'),
(37, 'o', 'Chimera', 'demons.png', 200, 500, 362, 490, 2000, 315, 2200, 1, 'f'),
(38, 'o', 'Anjo', 'satan.gif', 400, 886, 600, 800, 4000, 415, 3920, 1, 'f'),
(39, 'o', 'Demonio', '', 650, 1439, 975, 1300, 7000, 665, 6350, 1, 'f'),
(40, 'o', 'Cerberus', 'zeus.png', 700, 1550, 1050, 1400, 7900, 715, 6850, 1, 'f'),
(41, 'o', 'Dragão Negro', 'blackdragon.gif', 425, 942, 638, 850, 4200, 440, 4150, 1, 'f'),
(42, 'o', 'Ogro Gigante', 'ogro.gif', 230, 620, 436, 600, 2300, 355, 2560, 1, 'f'),
(43, 'o', 'Dragão Vermelho', 'reddragon.gif', 450, 996, 675, 900, 5000, 465, 4400, 1, 'f'),
(44, 'o', 'Dragão das Trevas', 'default.png', 475, 1052, 713, 950, 5300, 490, 4650, 1, 'f'),
(45, 'a', 'Fênix', 'default.png', 525, 1163, 800, 1050, 5700, 540, 5140, 1, 'f'),
(46, 'o', 'Obelisco', 'default.png', 570, 1272, 870, 1150, 6300, 585, 5580, 1, 'f'),
(47, 'o', 'Zeus', 'default.png', 850, 1910, 1275, 1700, 10000, 865, 8300, 1, 'f'),
(48, 'o', 'Hades', 'default.png', 775, 1700, 1150, 1550, 8900, 790, 7590, 1, 'f'),
(49, 'o', 'Beserker', 'default.png', 250, 702, 485, 650, 2500, 370, 2750, 1, 'f'),
(50, 'o', 'Boss 1', 'default.png', 10, 15, 13, 15, 125, 30, 100, 1, 't'),
(52, 'o', 'Boss 2', 'default.png', 25, 54, 40, 47, 350, 50, 300, 1, 't'),
(53, 'o', 'Boss 3', 'default.png', 60, 135, 92, 120, 690, 80, 750, 1, 't'),
(54, 'o', 'Boss 4', 'default.png', 100, 225, 160, 210, 1050, 200, 1120, 1, 't'),
(55, 'o', 'Boss 5', 'default.png', 130, 297, 197, 278, 1400, 200, 1450, 1, 't'),
(56, 'o', 'Boss 6', 'default.png', 160, 400, 293, 372, 1660, 500, 1830, 1, 't'),
(57, 'o', 'Boss 7', 'default.png', 200, 520, 370, 515, 2100, 300, 2350, 1, 't'),
(59, 'o', 'Boss 8', 'default.png', 250, 750, 415, 680, 2600, 300, 2960, 1, 'n'),
(60, 'o', 'Guardião de Elryn', 'default.png', 55, 125, 85, 110, 650, 125, 800, 1, 'n'),
(61, 'o', 'Urso Gigante', 'default.png', 20, 38, 30, 36, 270, 50, 200, 1, 'n'),
(62, 'o', 'Anão', 'default.png', 10, 13, 15, 13, 120, 25, 70, 1, 'n'),
(63, 'o', 'Anão Soldado', 'default.png', 20, 38, 30, 36, 250, 60, 190, 1, 'n'),
(64, 'o', 'Anão Mago', 'default.png', 32, 68, 48, 60, 400, 65, 290, 1, 'n'),
(9, 'o', 'Assassino', 'asas.gif', 25, 54, 38, 47, 340, 40, 200, 1, 'f');

-- --------------------------------------------------------

--
-- Table structure for table `monster_tasks`
--

CREATE TABLE IF NOT EXISTS `monster_tasks` (
  `player_id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `value` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `other`
--

CREATE TABLE IF NOT EXISTS `other` (
  `player_id` int(11) NOT NULL DEFAULT '0',
  `value` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pending`
--

CREATE TABLE IF NOT EXISTS `pending` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL DEFAULT '0',
  `pending_id` varchar(11) NOT NULL DEFAULT '',
  `pending_status` varchar(255) NOT NULL DEFAULT '',
  `pending_time` int(11) NOT NULL DEFAULT '0',
  `pending_other` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Otimizacao1` (`pending_id`,`pending_status`,`player_id`),
  KEY `Otimizacao2` (`player_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2500 ;

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

CREATE TABLE IF NOT EXISTS `players` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `acc_id` int(11) NOT NULL DEFAULT '0',
  `username` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `reino` enum('0','1','2','3') COLLATE latin1_general_ci NOT NULL DEFAULT '0',
  `posts` int(11) NOT NULL DEFAULT '0',
  `gm_rank` int(11) NOT NULL DEFAULT '1',
  `registered` int(11) NOT NULL DEFAULT '0',
  `last_active` int(11) NOT NULL DEFAULT '0',
  `uptime` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `level` int(11) NOT NULL DEFAULT '1',
  `last_level` int(11) NOT NULL DEFAULT '1',
  `voc` enum('archer','knight','mage') COLLATE latin1_general_ci NOT NULL DEFAULT 'archer',
  `promoted` enum('t','f','r','s','p') COLLATE latin1_general_ci NOT NULL DEFAULT 'f',
  `stat_points` int(11) NOT NULL DEFAULT '5',
  `magic_points` int(11) NOT NULL DEFAULT '1',
  `buystats` mediumint(11) NOT NULL DEFAULT '0',
  `gold` int(11) NOT NULL DEFAULT '200',
  `totalbet` int(11) NOT NULL DEFAULT '0',
  `bank` int(11) NOT NULL DEFAULT '0',
  `work` enum('t','f') COLLATE latin1_general_ci NOT NULL DEFAULT 'f',
  `worklvl` varchar(11) COLLATE latin1_general_ci NOT NULL DEFAULT '1',
  `hp` int(11) NOT NULL DEFAULT '150',
  `deadtime` int(11) NOT NULL DEFAULT '0',
  `maxhp` int(11) NOT NULL DEFAULT '150',
  `exp` int(11) NOT NULL DEFAULT '0',
  `maxexp` int(11) NOT NULL DEFAULT '50',
  `mana` int(11) NOT NULL DEFAULT '75',
  `maxmana` int(11) NOT NULL DEFAULT '75',
  `extramana` int(11) NOT NULL DEFAULT '0',
  `energy` int(11) NOT NULL DEFAULT '100',
  `maxenergy` int(11) NOT NULL DEFAULT '100',
  `strength` int(11) NOT NULL DEFAULT '1',
  `vitality` int(11) NOT NULL DEFAULT '1',
  `agility` int(11) NOT NULL DEFAULT '1',
  `resistance` int(11) NOT NULL DEFAULT '1',
  `interest` tinyint(1) NOT NULL DEFAULT '0',
  `kills` int(11) NOT NULL DEFAULT '0',
  `akills` int(11) NOT NULL DEFAULT '0',
  `monsterkill` int(11) NOT NULL DEFAULT '0',
  `monsterkilled` int(11) NOT NULL DEFAULT '0',
  `groupmonsterkilled` int(11) NOT NULL DEFAULT '0',
  `deaths` int(11) NOT NULL DEFAULT '0',
  `killed` int(11) NOT NULL DEFAULT '0',
  `tour` enum('t','f') COLLATE latin1_general_ci NOT NULL DEFAULT 'f',
  `died` int(11) NOT NULL DEFAULT '0',
  `ref` int(11) NOT NULL DEFAULT '0',
  `guild` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `avatar` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT 'anonimo.gif',
  `validkey` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `transpass` varchar(25) COLLATE latin1_general_ci NOT NULL DEFAULT 'f',
  `ban` int(11) NOT NULL DEFAULT '0',
  `alerts` varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
  `tier` tinyint(2) NOT NULL DEFAULT '0',
  `serv` tinyint(2) NOT NULL DEFAULT '0',
  `vip` int(11) NOT NULL DEFAULT '0',
  `subname` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `Otimizacao1` (`hp`),
  KEY `Otimizacao2` (`deadtime`),
  KEY `Otimizacao3` (`username`),
  KEY `Otimizacao4` (`gm_rank`),
  KEY `Otimizacao5` (`last_active`),
  KEY `Otimizacao6` (`guild`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=2310 ;

-- --------------------------------------------------------

--
-- Table structure for table `players_ref`
--

CREATE TABLE IF NOT EXISTS `players_ref` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_p_ref` int(11) NOT NULL,
  `id_p_c` int(11) NOT NULL,
  `date_regis` int(11) NOT NULL,
  `date_end` int(10) NOT NULL DEFAULT '0',
  `event` int(1) NOT NULL DEFAULT '0',
  `session_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1428 ;

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE IF NOT EXISTS `profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL DEFAULT '0',
  `perfil` text CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `Otimizacao6` (`player_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=181 ;

-- --------------------------------------------------------

--
-- Table structure for table `promo`
--

CREATE TABLE IF NOT EXISTS `promo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL DEFAULT '0',
  `username` varchar(255) NOT NULL DEFAULT '',
  `refs` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `promo`
--


-- --------------------------------------------------------

--
-- Table structure for table `pwar`
--

CREATE TABLE IF NOT EXISTS `pwar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guild_id` int(11) NOT NULL,
  `enemy_id` int(11) NOT NULL,
  `bet` int(11) NOT NULL,
  `players_guild` varchar(255) NOT NULL,
  `players_enemy` varchar(255) NOT NULL,
  `results` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `status` enum('t','f','p','g','e') NOT NULL DEFAULT 'p',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `pwar`
--


-- --------------------------------------------------------

--
-- Table structure for table `quests`
--

CREATE TABLE IF NOT EXISTS `quests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL DEFAULT '0',
  `quest_id` int(11) NOT NULL DEFAULT '0',
  `quest_status` int(11) NOT NULL DEFAULT '0',
  `extra` int(11) DEFAULT NULL,
  `pago` enum('t','f') NOT NULL DEFAULT 'f',
  PRIMARY KEY (`id`),
  KEY `Otimizacao1` (`player_id`),
  KEY `Otimizacao2` (`quest_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=451 ;

-- --------------------------------------------------------

--
-- Table structure for table `referal`
--

CREATE TABLE IF NOT EXISTS `referal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `Otimizacao1` (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `referal`
--


-- --------------------------------------------------------

--
-- Table structure for table `ref_list_prem`
--

CREATE TABLE IF NOT EXISTS `ref_list_prem` (
  `qt` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `bonus` int(11) NOT NULL DEFAULT '0',
  `gold` int(1) NOT NULL,
  `event` int(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ref_list_prem`
--

INSERT INTO `ref_list_prem` (`qt`, `item_id`, `bonus`, `gold`, `event`) VALUES
(20, 126, 8, 20000, 1),
(20, 14, 6, 23000, 1),
(15, 66, 7, 36000, 1),
(7, 33, 9, 15900, 1),
(22, 130, 5, 54000, 1),
(20, 190, 8, 23000, 1),
(15, 69, 5, 13500, 1),
(4, 189, 9, 5000, 1),
(25, 149, 9, 54000, 1),
(15, 62, 7, 18000, 1),
(10, 61, 7, 15000, 1),
(10, 36, 6, 8000, 1),
(20, 135, 9, 1000, 1),
(20, 47, 9, 48000, 1),
(10, 200, 7, 0, 1),
(10, 198, 9, 13000, 1),
(20, 133, 9, 35000, 1),
(20, 103, 9, 12000, 1),
(15, 59, 7, 19000, 1),
(12, 2, 9, 6500, 1),
(8, 25, 6, 4800, 1),
(20, 125, 8, 28000, 1),
(15, 43, 4, 300, 1),
(10, 44, 6, 0, 1),
(10, 29, 5, 8000, 1),
(6, 18, 9, 15000, 1),
(20, 139, 7, 1000, 1),
(20, 78, 6, 4000, 1),
(20, 187, 7, 2000, 1),
(15, 186, 6, 7000, 1),
(10, 184, 7, 7000, 1),
(5, 188, 3, 5000, 1);

-- --------------------------------------------------------

--
-- Table structure for table `reinos`
--

CREATE TABLE IF NOT EXISTS `reinos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `imperador` int(11) NOT NULL DEFAULT '0',
  `ouro` int(11) NOT NULL DEFAULT '0',
  `imagem` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `poll` int(11) NOT NULL DEFAULT '0',
  `gates` int(11) NOT NULL DEFAULT '0',
  `worktime` int(11) NOT NULL DEFAULT '0',
  `tax` enum('0','0.01','0.015','0.02') COLLATE latin1_general_ci NOT NULL DEFAULT '0.01',
  `work` enum('0','0.1','0.15','0.2') COLLATE latin1_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `reinos`
--

INSERT INTO `reinos` (`id`, `nome`, `imperador`, `ouro`, `imagem`, `poll`, `gates`, `worktime`, `tax`, `work`) VALUES
(1, 'Cathal', 0, 40363593, 'reinoa.png', 1351404553, 1349974248, 1350095360, '0.01', '0.2'),
(2, 'Eroda', 0, 9227105, 'reinob.png', 1351298700, 1351555607, 1351675995, '0.02', '0.2'),
(3, 'Turkic', 0, 8905723, 'reinoc.png', 1351441145, 1351619280, 1351762993, '0.02', '0.2');

-- --------------------------------------------------------

--
-- Table structure for table `reino_tovote`
--

CREATE TABLE IF NOT EXISTS `reino_tovote` (
  `player_id` int(11) NOT NULL DEFAULT '0',
  `reino_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reino_votes`
--

CREATE TABLE IF NOT EXISTS `reino_votes` (
  `player_id` int(11) NOT NULL DEFAULT '0',
  `vote_id` int(11) NOT NULL DEFAULT '0',
  `reino_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `revenge`
--

CREATE TABLE IF NOT EXISTS `revenge` (
  `player_id` int(11) NOT NULL DEFAULT '0',
  `enemy_id` int(11) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL DEFAULT '0',
  KEY `Otimizacao2` (`player_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `name` varchar(255) NOT NULL DEFAULT '',
  `value` varchar(255) NOT NULL DEFAULT '',
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`name`, `value`) VALUES
('activate_level', '30'),
('allow_upload', 't'),
('armour_default_limit', '10'),
('bank_interest_rate', '2'),
('bank_limit', '1000000'),
('closed', '1310158800'),
('dead_time', '1800'),
('difficulty_1', '27'),
('end_lotto_1', '0'),
('end_lotto_2', '0'),
('end_promo', '0'),
('end_tour_1_1', '1351972800'),
('end_tour_1_2', '1351972800'),
('end_tour_2_1', '1351972800'),
('end_tour_2_2', '1351972800'),
('end_tour_3_1', '1351972800'),
('end_tour_3_2', '1351972800'),
('end_tour_4_1', '1351972800'),
('end_tour_4_2', '1351972800'),
('end_tour_5_1', '1351972800'),
('end_tour_5_2', '1351972800'),
('energy_potion', '50'),
('eventoexp', '1351468994'),
('eventoouro', '1351468994'),
('hospital_rate', '1'),
('index_log_error', 'no'),
('index_log_ip', 'no'),
('last_tour_1_1', 'Warman'),
('last_tour_1_2', 'Ninguém'),
('last_tour_2_1', 'Ninguém'),
('last_tour_2_2', 'Ninguém'),
('last_tour_3_1', 'Kronnus'),
('last_tour_3_2', 'Ninguém'),
('last_tour_4_1', 'Ninguém'),
('last_tour_4_2', 'Ninguém'),
('last_tour_5_1', 'Ninguém'),
('last_tour_5_2', 'Ninguém'),
('last_winner_1', 'Split'),
('last_winner_2', 'ThownMG'),
('lottery_1', 'f'),
('lottery_2', 'f'),
('lottery_premio_1', 'Capacete de demônio'),
('lottery_premio_2', 'Infernal Shield'),
('lottery_price_1', '1500'),
('lottery_price_2', '2000'),
('lottery_tic_1', '0'),
('lottery_tic_2', '0'),
('members_default_limit', '10'),
('monster_battle_rounds', '130'),
('promo', 'f'),
('promo_last_winner', 'ItaloGustavo'),
('promo_premio', '2000000'),
('promo_tempo', '14 dias'),
('pvp_battle_rounds', '200'),
('securyty_capcha', '25'),
('tournament_1_1', 't'),
('tournament_1_2', 't'),
('tournament_2_1', 't'),
('tournament_2_2', 't'),
('tournament_3_1', 't'),
('tournament_3_2', 't'),
('tournament_4_1', 't'),
('tournament_4_2', 't'),
('tournament_5_1', 't'),
('tournament_5_2', 't'),
('tour_lvl1_1_1', '1'),
('tour_lvl1_1_2', '1'),
('tour_lvl1_2_1', '100'),
('tour_lvl1_2_2', '100'),
('tour_lvl1_3_1', '200'),
('tour_lvl1_3_2', '200'),
('tour_lvl1_4_1', '300'),
('tour_lvl1_4_2', '300'),
('tour_lvl1_5_1', '400'),
('tour_lvl1_5_2', '400'),
('tour_lvl2_1_1', '99'),
('tour_lvl2_1_2', '99'),
('tour_lvl2_2_1', '199'),
('tour_lvl2_2_2', '199'),
('tour_lvl2_3_1', '299'),
('tour_lvl2_3_2', '299'),
('tour_lvl2_4_1', '399'),
('tour_lvl2_4_2', '399'),
('tour_lvl2_5_1', '999'),
('tour_lvl2_5_2', '999'),
('tour_members_1_1', '42'),
('tour_members_1_2', '0'),
('tour_members_2_1', '0'),
('tour_members_2_2', '0'),
('tour_members_3_1', '0'),
('tour_members_3_2', '0'),
('tour_members_4_1', '0'),
('tour_members_4_2', '0'),
('tour_members_5_1', '0'),
('tour_members_5_2', '0'),
('tour_price_1_1', '1000'),
('tour_price_1_2', '1000'),
('tour_price_2_1', '3000'),
('tour_price_2_2', '3000'),
('tour_price_3_1', '5000'),
('tour_price_3_2', '5000'),
('tour_price_4_1', '8000'),
('tour_price_4_2', '8000'),
('tour_price_5_1', '13000'),
('tour_price_5_2', '13000'),
('tour_win_1_1', '150000'),
('tour_win_1_2', '150000'),
('tour_win_2_1', '300000'),
('tour_win_2_2', '300000'),
('tour_win_3_1', '1000000'),
('tour_win_3_2', '1000000'),
('tour_win_4_1', '2000000'),
('tour_win_4_2', '2000000'),
('tour_win_5_1', '4000000'),
('tour_win_5_2', '4000000'),
('user_record', '61'),
('wanteds', '0'),
('weapons_default_limit', '10'),
('win_id_1', '174'),
('win_id_2', '140'),
('earn', '2500'),
('event_convidados', '1');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `obj_type` enum('monster','level','pvp') NOT NULL,
  `obj_value` int(11) NOT NULL,
  `obj_extra` int(11) NOT NULL DEFAULT '0',
  `win_type` enum('gold','exp','item') NOT NULL,
  `win_value` int(11) NOT NULL,
  `needlvl` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=34 ;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `obj_type`, `obj_value`, `obj_extra`, `win_type`, `win_value`, `needlvl`) VALUES
(1, 'monster', 1, 10, 'gold', 500, 1),
(2, 'monster', 2, 15, 'item', 5, 1),
(4, 'level', 10, 0, 'gold', 1200, 5),
(5, 'pvp', 10, 0, 'exp', 900, 5),
(6, 'monster', 3, 5, 'exp', 550, 5),
(7, 'monster', 6, 25, 'gold', 3200, 12),
(8, 'monster', 1200, 0, 'item', 32, 30),
(9, 'monster', 5, 30, 'gold', 4200, 21),
(10, 'pvp', 45, 0, 'gold', 6200, 35),
(22, 'monster', 12, 20, 'exp', 13000, 40),
(19, 'monster', 3, 80, 'item', 3, 17),
(21, 'monster', 7, 30, 'exp', 10000, 33),
(20, 'monster', 400, 0, 'gold', 1000, 25),
(23, 'monster', 14, 150, 'item', 129, 52),
(11, 'monster', 8, 120, 'gold', 5000, 40),
(12, 'level', 42, 0, 'item', 2, 30),
(13, 'monster', 13, 250, 'exp', 15000, 44),
(24, 'level', 35, 0, 'gold', 2000, 25),
(25, 'level', 5, 0, 'exp', 300, 1),
(26, 'monster', 4, 20, 'gold', 5000, 10),
(27, 'pvp', 30, 0, 'exp', 1200, 14),
(28, 'level', 18, 0, 'gold', 2000, 15),
(29, 'pvp', 50, 0, 'gold', 8000, 46),
(30, 'level', 55, 0, 'item', 68, 50),
(31, 'pvp', 120, 0, 'gold', 20000, 55),
(32, 'level', 45, 0, 'item', 165, 40),
(33, 'level', 63, 0, 'item', 169, 50);

-- --------------------------------------------------------

--
-- Table structure for table `thumb`
--

CREATE TABLE IF NOT EXISTS `thumb` (
  `topic_id` int(11) NOT NULL DEFAULT '0',
  `player_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_chat`
--

CREATE TABLE IF NOT EXISTS `user_chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL,
  `reino` int(11) NOT NULL,
  `guild` int(11) NOT NULL DEFAULT '0',
  `msg` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10601 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_log`
--

CREATE TABLE IF NOT EXISTS `user_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL DEFAULT '0',
  `msg` text CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `status` enum('read','unread') NOT NULL DEFAULT 'unread',
  `time` int(11) NOT NULL DEFAULT '0',
  `show` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `Otimizacao1` (`player_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=117888 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_online`
--

CREATE TABLE IF NOT EXISTS `user_online` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(255) NOT NULL DEFAULT '',
  `time` int(11) NOT NULL DEFAULT '0',
  `login` int(11) NOT NULL DEFAULT '0',
  `serv` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=411585 ;

-- --------------------------------------------------------

--
-- Table structure for table `vip_shop`
--

CREATE TABLE IF NOT EXISTS `vip_shop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` enum('item','gold') NOT NULL,
  `value` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `sells` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `vip_shop`
--

INSERT INTO `vip_shop` (`id`, `name`, `type`, `value`, `price`, `sells`) VALUES
(5, '500000 moedas de ouro', 'gold', '500000', 2, 0),
(12, 'Set Sombrio', 'item', '202, 203, 204', 22, 0),
(8, '5000000 moedas de ouro', 'gold', '5000000', 14, 2),
(13, 'Besta da Morte', 'item', '207', 8, 0),
(14, 'Cajado das Sombras', 'item', '206', 8, 0),
(15, 'Espada Sangrenta', 'item', '205', 8, 0),
(16, 'Set Glacial', 'item', '208, 209, 210', 25, 1);

-- --------------------------------------------------------

--
-- Table structure for table `wanted`
--

CREATE TABLE IF NOT EXISTS `wanted` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL,
  `kills` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2009 ;

-- --------------------------------------------------------

--
-- Table structure for table `work`
--

CREATE TABLE IF NOT EXISTS `work` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL,
  `start` int(11) NOT NULL,
  `worktype` varchar(50) NOT NULL,
  `worktime` tinyint(2) NOT NULL,
  `gold` int(11) NOT NULL,
  `status` enum('t','f','a') NOT NULL DEFAULT 't',
  PRIMARY KEY (`id`),
  KEY `Otimizacao1` (`status`),
  KEY `Otimizacao2` (`player_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2644 ;
