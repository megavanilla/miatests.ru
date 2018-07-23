-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Июл 17 2018 г., 17:27
-- Версия сервера: 10.1.30-MariaDB
-- Версия PHP: 7.2.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `m1-shop`
--

-- --------------------------------------------------------

--
-- Структура таблицы `blog`
--

CREATE TABLE `blog` (
  `id` int(11) NOT NULL COMMENT 'Идентификатор',
  `datetime_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Время  обновления',
  `description` varchar(32) NOT NULL DEFAULT '' COMMENT 'Описание',
  `text` text COMMENT 'Текст блога',
  `href_img` varchar(555) NOT NULL DEFAULT '' COMMENT 'Ссылка на изображение'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `blog`
--

INSERT INTO `blog` (`id`, `datetime_update`, `description`, `text`, `href_img`) VALUES
(16, '2018-07-17 17:40:20', '3r3r3r3', 'e3r3r', ''),
(17, '2018-07-17 17:52:33', 'wqe', 'qwd', '3a39b5fb7931d2914549e34ac76a29654062e085.jpg'),
(18, '2018-07-17 18:01:10', 'wedesf', 'ewfsf222', 'ab84e151a292f46c4665f586152acd0f8e4e0b91.jpg'),
(20, '2018-07-17 18:22:57', 'werw', 'werewr', '956689865e0d704f8cb0db458ef29af4372cf037.jpg'),
(21, '2018-07-17 18:23:17', '_____________', 'ewr', '82f3466db2692b815dc3abe5e1d15be785f3ae2e.jpg');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `blog`
--
ALTER TABLE `blog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор', AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
