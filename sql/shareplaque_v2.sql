-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tempo de Geração: 
-- Versão do Servidor: 5.5.27
-- Versão do PHP: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Banco de Dados: `shareplaque_v2`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `anuncio`
--

CREATE TABLE IF NOT EXISTS `anuncio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `descricao` text NOT NULL,
  `status` int(11) NOT NULL,
  `pageviews` int(11) NOT NULL,
  `unique_pageviews` int(11) NOT NULL,
  `dt_cadastro` timestamp NULL DEFAULT NULL,
  `dt_alteracao` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `pergunta`
--

CREATE TABLE IF NOT EXISTS `pergunta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_anuncio` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `msg_pergunta` varchar(255) NOT NULL,
  `msg_resposta` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `data` timestamp NULL DEFAULT NULL,
  `data_resposta` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `plano`
--

CREATE TABLE IF NOT EXISTS `plano` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_servico` int(10) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `descricao` text NOT NULL,
  `preco` float NOT NULL,
  `dia_publicacao` int(10) NOT NULL,
  `data_cadastro` timestamp NULL DEFAULT NULL,
  `data_alteracao` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `plano_anuncio`
--

CREATE TABLE IF NOT EXISTS `plano_anuncio` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_anuncio` int(10) NOT NULL,
  `id_plano` int(10) NOT NULL,
  `status` int(10) NOT NULL,
  `data_inicio` timestamp NULL DEFAULT NULL,
  `data_fim` timestamp NULL DEFAULT NULL,
  `media_clique` int(10) NOT NULL,
  `posicao_media` float NOT NULL,
  `impressao` int(10) NOT NULL,
  `data_cadastro` timestamp NULL DEFAULT NULL,
  `data_alteracao` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `servico`
--

CREATE TABLE IF NOT EXISTS `servico` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `data_cadastro` timestamp NULL DEFAULT NULL,
  `data_alteracao` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Serviços para divulgação' AUTO_INCREMENT=8 ;

--
-- Extraindo dados da tabela `servico`
--

INSERT INTO `servico` (`id`, `nome`, `data_cadastro`, `data_alteracao`) VALUES
(1, 'Google', '2013-02-18 22:44:51', NULL),
(2, 'Yahoo', '2013-02-18 22:49:34', NULL),
(3, 'Metro News', '2013-02-18 22:52:06', NULL),
(5, 'Revista Play', '2013-02-18 22:54:06', NULL),
(6, 'E-mail', '2013-02-18 23:00:10', NULL),
(7, 'Google Adwords', '2013-02-19 00:47:44', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) DEFAULT NULL,
  `cep` int(10) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `token` varchar(50) NOT NULL,
  `senha` varchar(128) DEFAULT NULL,
  `opt_newsletter` int(1) DEFAULT NULL,
  `credito` float DEFAULT NULL,
  `diretorio` varchar(50) DEFAULT NULL,
  `username_indicado` varchar(13) DEFAULT NULL,
  `id_indicado` int(11) DEFAULT NULL,
  `status` int(10) NOT NULL,
  `data_cadastro` timestamp NULL DEFAULT NULL,
  `data_alteracao` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_token` (`email`,`token`),
  UNIQUE KEY `email_username_uk` (`email`,`username_indicado`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`id`, `nome`, `cep`, `email`, `token`, `senha`, `opt_newsletter`, `credito`, `diretorio`, `username_indicado`, `id_indicado`, `status`, `data_cadastro`, `data_alteracao`) VALUES
(6, 'André Augusto', 9999999, 'andrework@gmail.com', '42cf05a8f97ca2de3e9de395de8a0bd6', '0ab478795daa5b428b51e548a69414f94a4da5380ae0cd92198694afc52bd0bb58347762a4037efce9d8b03736aa2250dce454706346893e0ed0b388f13f17d0', 1, 2856, 'files/2012/09/09/09/', '357711', 0, 10, '2012-09-20 20:24:10', '2013-01-19 07:19:42');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
