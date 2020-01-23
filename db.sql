-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 22-Jan-2020 às 20:43
-- Versão do servidor: 10.3.16-MariaDB
-- versão do PHP: 7.3.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `caleb_mission`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `answer`
--

CREATE TABLE `answer` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `search` int(11) NOT NULL,
  `time` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `leader`
--

CREATE TABLE `leader` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `leader`
--

INSERT INTO `leader` (`id`) VALUES
(1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `member`
--

CREATE TABLE `member` (
  `id` int(11) NOT NULL,
  `document` varchar(20) NOT NULL,
  `document_type` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `birth` date DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `phone` char(11) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` char(32) NOT NULL,
  `point` int(11) DEFAULT NULL,
  `register` datetime DEFAULT current_timestamp(),
  `tshirt_size` varchar(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `member`
--

INSERT INTO `member` (`id`, `document`, `document_type`, `name`, `birth`, `address`, `phone`, `email`, `password`, `point`, `register`, `tshirt_size`) VALUES
(1, 'admin', 'sistema', 'Administrador', NULL, NULL, NULL, NULL, 'e6e061838856bf47e1de730719fb2609', NULL, '2020-01-07 14:41:06', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `option`
--

CREATE TABLE `option` (
  `id` int(11) NOT NULL,
  `question` int(11) NOT NULL,
  `number` int(3) NOT NULL,
  `text` varchar(150) DEFAULT NULL,
  `insert` int(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `point`
--

CREATE TABLE `point` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `address` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `question`
--

CREATE TABLE `question` (
  `id` int(11) NOT NULL,
  `search` int(11) NOT NULL,
  `number` int(3) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `text` varchar(200) DEFAULT NULL,
  `creation` datetime DEFAULT current_timestamp(),
  `type` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `search`
--

CREATE TABLE `search` (
  `id` int(11) NOT NULL,
  `point` int(11) NOT NULL,
  `creation` datetime DEFAULT current_timestamp(),
  `token` char(32) DEFAULT NULL,
  `name` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `selected_option`
--

CREATE TABLE `selected_option` (
  `id` int(11) NOT NULL,
  `answer` int(11) NOT NULL,
  `option` int(11) NOT NULL,
  `text` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `answer`
--
ALTER TABLE `answer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user` (`user`),
  ADD KEY `search` (`search`);

--
-- Índices para tabela `leader`
--
ALTER TABLE `leader`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`id`),
  ADD KEY `point` (`point`);

--
-- Índices para tabela `option`
--
ALTER TABLE `option`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question` (`question`);

--
-- Índices para tabela `point`
--
ALTER TABLE `point`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`id`),
  ADD KEY `search` (`search`);

--
-- Índices para tabela `search`
--
ALTER TABLE `search`
  ADD PRIMARY KEY (`id`),
  ADD KEY `point` (`point`);

--
-- Índices para tabela `selected_option`
--
ALTER TABLE `selected_option`
  ADD PRIMARY KEY (`id`),
  ADD KEY `answer` (`answer`),
  ADD KEY `option` (`option`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `answer`
--
ALTER TABLE `answer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `member`
--
ALTER TABLE `member`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `option`
--
ALTER TABLE `option`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `point`
--
ALTER TABLE `point`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `question`
--
ALTER TABLE `question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `search`
--
ALTER TABLE `search`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `selected_option`
--
ALTER TABLE `selected_option`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `answer`
--
ALTER TABLE `answer`
  ADD CONSTRAINT `answer_ibfk_2` FOREIGN KEY (`user`) REFERENCES `member` (`id`),
  ADD CONSTRAINT `answer_ibfk_3` FOREIGN KEY (`search`) REFERENCES `search` (`id`);

--
-- Limitadores para a tabela `leader`
--
ALTER TABLE `leader`
  ADD CONSTRAINT `leader_ibfk_1` FOREIGN KEY (`id`) REFERENCES `member` (`id`);

--
-- Limitadores para a tabela `member`
--
ALTER TABLE `member`
  ADD CONSTRAINT `member_ibfk_1` FOREIGN KEY (`point`) REFERENCES `point` (`id`);

--
-- Limitadores para a tabela `option`
--
ALTER TABLE `option`
  ADD CONSTRAINT `option_ibfk_1` FOREIGN KEY (`question`) REFERENCES `question` (`id`);

--
-- Limitadores para a tabela `question`
--
ALTER TABLE `question`
  ADD CONSTRAINT `question_ibfk_1` FOREIGN KEY (`search`) REFERENCES `search` (`id`);

--
-- Limitadores para a tabela `search`
--
ALTER TABLE `search`
  ADD CONSTRAINT `search_ibfk_1` FOREIGN KEY (`point`) REFERENCES `point` (`id`);

--
-- Limitadores para a tabela `selected_option`
--
ALTER TABLE `selected_option`
  ADD CONSTRAINT `selected_option_ibfk_1` FOREIGN KEY (`answer`) REFERENCES `answer` (`id`),
  ADD CONSTRAINT `selected_option_ibfk_2` FOREIGN KEY (`option`) REFERENCES `option` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
