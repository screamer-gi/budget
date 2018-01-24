-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Янв 24 2018 г., 21:32
-- Версия сервера: 5.6.21-log
-- Версия PHP: 7.0.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `budget`
--

-- --------------------------------------------------------

--
-- Структура таблицы `expense`
--

CREATE TABLE `expense` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL COMMENT 'Дата',
  `amount` double NOT NULL COMMENT 'Сумма',
  `place_id` int(11) DEFAULT NULL COMMENT 'Место',
  `cash` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Нал',
  `comment` varchar(200) NOT NULL DEFAULT '' COMMENT 'Комментарий'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Расходы';

-- --------------------------------------------------------

--
-- Структура таблицы `expense_category`
--

CREATE TABLE `expense_category` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `ordering` int(11) NOT NULL COMMENT 'Порядок'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `expense_detail`
--

CREATE TABLE `expense_detail` (
  `id` int(11) NOT NULL,
  `expense_id` int(11) NOT NULL COMMENT 'Операция',
  `category_id` int(11) NOT NULL COMMENT 'Категория',
  `formula` varchar(100) NOT NULL DEFAULT '' COMMENT 'Формула суммы',
  `amount` double NOT NULL COMMENT 'Вычисленная сумма'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `expense_place`
--

CREATE TABLE `expense_place` (
  `id` int(11) NOT NULL,
  `location` varchar(100) NOT NULL COMMENT 'место операции'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `expense`
--
ALTER TABLE `expense`
  ADD PRIMARY KEY (`id`),
  ADD KEY `date` (`date`),
  ADD KEY `cash` (`cash`),
  ADD KEY `expense_place_id` (`place_id`);

--
-- Индексы таблицы `expense_category`
--
ALTER TABLE `expense_category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ordering` (`ordering`);

--
-- Индексы таблицы `expense_detail`
--
ALTER TABLE `expense_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expense_detail_category_id` (`category_id`),
  ADD KEY `expense_detail_expense_id` (`expense_id`);

--
-- Индексы таблицы `expense_place`
--
ALTER TABLE `expense_place`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `expense`
--
ALTER TABLE `expense`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `expense_category`
--
ALTER TABLE `expense_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `expense_detail`
--
ALTER TABLE `expense_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `expense_place`
--
ALTER TABLE `expense_place`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `expense`
--
ALTER TABLE `expense`
  ADD CONSTRAINT `expense_place_id` FOREIGN KEY (`place_id`) REFERENCES `expense_place` (`id`);

--
-- Ограничения внешнего ключа таблицы `expense_detail`
--
ALTER TABLE `expense_detail`
  ADD CONSTRAINT `expense_detail_category_id` FOREIGN KEY (`category_id`) REFERENCES `expense_category` (`id`),
  ADD CONSTRAINT `expense_detail_expense_id` FOREIGN KEY (`expense_id`) REFERENCES `expense` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
