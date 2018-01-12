--
-- Скрипт сгенерирован Devart dbForge Studio for MySQL, Версия 7.1.30.0
-- Домашняя страница продукта: http://www.devart.com/ru/dbforge/mysql/studio
-- Дата скрипта: 04.10.2016 19:33:17
-- Версия сервера: 5.5.5-10.1.16-MariaDB
-- Версия клиента: 4.1
--


-- 
-- Отключение внешних ключей
-- 
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;

-- 
-- Установить режим SQL (SQL mode)
-- 
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- 
-- Установка кодировки, с использованием которой клиент будет посылать запросы на сервер
--
SET NAMES 'utf8';

-- 
-- Установка базы данных по умолчанию
--
USE izum;

--
-- Описание для таблицы bonuses
--
DROP TABLE IF EXISTS bonuses;
CREATE TABLE bonuses (
  step INT(11) UNSIGNED NOT NULL COMMENT 'Шаг начисления',
  step_desc VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'Описание шага начисления',
  name VARCHAR(50) NOT NULL DEFAULT '' COMMENT 'Название категории',
  bonus DECIMAL(11, 2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT 'Бонус начисления',
  PRIMARY KEY (step)
)
ENGINE = INNODB
AVG_ROW_LENGTH = 5461
CHARACTER SET utf8
COLLATE utf8_general_ci
COMMENT = 'Начисляемые бонусы';

--
-- Описание для таблицы calendar
--
DROP TABLE IF EXISTS calendar;
CREATE TABLE calendar (
  date DATE NOT NULL COMMENT 'Дата',
  weekday INT(11) AS (WEEKDAY(date)) PERSISTENT COMMENT 'Номер дня недели',
  PRIMARY KEY (date)
)
ENGINE = INNODB
AVG_ROW_LENGTH = 107
CHARACTER SET utf8
COLLATE utf8_general_ci
COMMENT = 'Календарь';

--
-- Описание для таблицы managers
--
DROP TABLE IF EXISTS managers;
CREATE TABLE managers (
  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Иднетификатор',
  fio VARCHAR(255) NOT NULL COMMENT 'ФИО',
  salary DECIMAL(11, 2) NOT NULL COMMENT 'Оклад',
  PRIMARY KEY (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 4
AVG_ROW_LENGTH = 5461
CHARACTER SET utf8
COLLATE utf8_general_ci
COMMENT = 'Менеджеры';

--
-- Описание для таблицы calls
--
DROP TABLE IF EXISTS calls;
CREATE TABLE calls (
  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор',
  id_manager INT(11) UNSIGNED NOT NULL COMMENT 'Идентификатор менеджера',
  date_call DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата звонка',
  PRIMARY KEY (id),
  CONSTRAINT FK_calls_managers_id FOREIGN KEY (id_manager)
    REFERENCES managers(id) ON DELETE CASCADE ON UPDATE CASCADE
)
ENGINE = INNODB
AUTO_INCREMENT = 10
AVG_ROW_LENGTH = 1820
CHARACTER SET utf8
COLLATE utf8_general_ci
COMMENT = 'Звонки менеджеров';

--
-- Описание для таблицы shedule
--
DROP TABLE IF EXISTS shedule;
CREATE TABLE shedule (
  id_managers INT(11) UNSIGNED NOT NULL COMMENT 'Идентификатор менеджера',
  date DATE NOT NULL COMMENT 'Дата',
  type_day ENUM('work','week') NOT NULL DEFAULT 'work' COMMENT 'Статус дня',
  PRIMARY KEY (id_managers, date),
  CONSTRAINT FK_shedule_calendar_date FOREIGN KEY (date)
    REFERENCES calendar(date) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT FK_shedule_managers_id FOREIGN KEY (id_managers)
    REFERENCES managers(id) ON DELETE CASCADE ON UPDATE CASCADE
)
ENGINE = INNODB
AVG_ROW_LENGTH = 16384
CHARACTER SET utf8
COLLATE utf8_general_ci
COMMENT = 'Рабочий график сотрудников';

DELIMITER $$

--
-- Описание для функции add_month_to_calendar
--
DROP FUNCTION IF EXISTS add_month_to_calendar$$
CREATE DEFINER = 'admin'@'localhost'
FUNCTION add_month_to_calendar(date_in DATE)
  RETURNS int(11)
  COMMENT 'Вставляет даты в календарь'
BEGIN
  DECLARE count_day_from_month INT(11) DEFAULT 0;/*Количество дней в месяце*/
  DECLARE start_day_from_month DATE;/*Первый день месяца*/
  DECLARE ITERATOR int DEFAULT 0;/*Значение итерации*/

  SET count_day_from_month = EXTRACT(DAY FROM LAST_DAY(date_in));/*Получили количество дней в месяце*/
  SET start_day_from_month = begin_of_month(date_in);/*Получили первый день месяца*/
  
  /*Для каждого дня месяца вставляем строку в календарь*/
  WHILE ITERATOR < count_day_from_month DO
    INSERT IGNORE INTO calendar (calendar.date) VALUES (DATE_ADD(start_day_from_month, INTERVAL ITERATOR DAY));
    SET ITERATOR = ITERATOR + 1;
  END WHILE;
RETURN 1;
END
$$

--
-- Описание для функции begin_of_month
--
DROP FUNCTION IF EXISTS begin_of_month$$
CREATE DEFINER = 'admin'@'localhost'
FUNCTION begin_of_month(in_date DATE)
  RETURNS date
  COMMENT 'Возвращает начало месяца'
BEGIN
	SET in_date = (CASE WHEN (in_date IS NOT NULL AND
			in_date != '0000-00-00') THEN (in_date) ELSE NOW() END);

	
	RETURN LAST_DAY(in_date) + INTERVAL 1 DAY - INTERVAL 1 MONTH;
END
$$

--
-- Описание для функции end_of_month
--
DROP FUNCTION IF EXISTS end_of_month$$
CREATE DEFINER = 'admin'@'localhost'
FUNCTION end_of_month(in_date DATE)
  RETURNS date
  COMMENT 'Возвращает конец месяца'
BEGIN
	SET in_date = (CASE WHEN (in_date IS NOT NULL AND
			in_date != '0000-00-00') THEN (in_date) ELSE NOW() END);

	
	RETURN LAST_DAY(in_date);
END
$$

--
-- Описание для функции get_count_calls_from_manager
--
DROP FUNCTION IF EXISTS get_count_calls_from_manager$$
CREATE DEFINER = 'admin'@'localhost'
FUNCTION get_count_calls_from_manager(id_manager INT(11) UNSIGNED, date_in DATE)
  RETURNS int(11) unsigned
  COMMENT 'Возвращает количество звонков менеджера за день'
BEGIN

RETURN (
    SELECT IFNULL(COUNT(0),0) FROM calls c WHERE c.id_manager =  id_manager AND (DATE(c.date_call) = date_in)
  );
END
$$

--
-- Описание для функции get_step_bonuses
--
DROP FUNCTION IF EXISTS get_step_bonuses$$
CREATE DEFINER = 'admin'@'localhost'
FUNCTION get_step_bonuses(count_calls INT(11))
  RETURNS int(11) unsigned
  COMMENT 'Возвращает бонус за указанное количество звонков'
BEGIN
  DECLARE RES INT(11) UNSIGNED DEFAULT 0;
  SET RES = (SELECT IFNULL(b.bonus,(SELECT b2.bonus FROM bonuses b2 LIMIT 0,1)) FROM bonuses b WHERE count_calls-1 < b.step LIMIT 0,1);
RETURN RES;
END
$$

DELIMITER ;

--
-- Описание для представления history_bonus
--
DROP VIEW IF EXISTS history_bonus CASCADE;
CREATE OR REPLACE 
	DEFINER = 'admin'@'localhost'
VIEW history_bonus
AS
	select date_format(`calls`.`date_call`,'%d.%m.%Y') AS `date`,`managers`.`fio` AS `fio`,count(0) AS `count_call`,`get_step_bonuses`(count(0)) AS `bonus` from (`calls` join `managers` on((`calls`.`id_manager` = `managers`.`id`))) group by date_format(`calls`.`date_call`,'%d.%m.%Y'),`managers`.`fio`;

--
-- Описание для представления stat_calls
--
DROP VIEW IF EXISTS stat_calls CASCADE;
CREATE OR REPLACE 
	DEFINER = 'admin'@'localhost'
VIEW stat_calls
AS
	select date_format(`calendar`.`date`,'%d.%m.%Y') AS `date`,`managers`.`fio` AS `fio`,if((`get_count_calls_from_manager`(`managers`.`id`,`calendar`.`date`) > 0),convert(`get_count_calls_from_manager`(`managers`.`id`,`calendar`.`date`) using utf8),if((select count(0) from `shedule` where ((`shedule`.`date` = `calendar`.`date`) and (`shedule`.`type_day` = 'week')) limit 0,1),'Выходной','Не работал')) AS `count_call` from (`calendar` join `managers`);

--
-- Описание для представления total_stat
--
DROP VIEW IF EXISTS total_stat CASCADE;
CREATE OR REPLACE 
	DEFINER = 'admin'@'localhost'
VIEW total_stat
AS
	select date_format(`calendar`.`date`,'%m.%Y') AS `date`,`managers`.`fio` AS `fio`,`managers`.`salary` AS `salary`,sum(`get_count_calls_from_manager`(`managers`.`id`,`calendar`.`date`)) AS `count_call`,(sum(`get_count_calls_from_manager`(`managers`.`id`,`calendar`.`date`)) * `get_step_bonuses`(sum(`get_count_calls_from_manager`(`managers`.`id`,`calendar`.`date`)))) AS `bouns`,(`managers`.`salary` + (sum(`get_count_calls_from_manager`(`managers`.`id`,`calendar`.`date`)) * `get_step_bonuses`(sum(`get_count_calls_from_manager`(`managers`.`id`,`calendar`.`date`))))) AS `total_summ` from (`calendar` join `managers`) group by month(`calendar`.`date`),`managers`.`fio`;

DELIMITER $$

--
-- Описание для события fill_calendar
--
DROP EVENT IF EXISTS fill_calendar$$
CREATE 
	DEFINER = 'admin'@'localhost'
EVENT fill_calendar
	ON SCHEDULE EVERY '1' MONTH
	STARTS '2016-11-01 17:19:38'
	COMMENT 'Заполняем календарь каждый месяц'
	DO 
BEGIN
  SELECT add_month_to_calendar(NOW());
END
$$

ALTER EVENT fill_calendar
	ENABLE
$$

DELIMITER ;

-- 
-- Вывод данных для таблицы bonuses
--
INSERT INTO bonuses VALUES
(100, 'До 100(включительно)', 'Начальная', 101.00),
(200, 'До 200(включительно)', 'Средняя', 201.00),
(300, 'Более 300(включительно)', 'Высшая', 301.00);

-- 
-- Вывод данных для таблицы calendar
--
INSERT INTO calendar(date) VALUES
('2016-07-01'),
('2016-07-02'),
('2016-07-03'),
('2016-07-04'),
('2016-07-05'),
('2016-07-06'),
('2016-07-07'),
('2016-07-08'),
('2016-07-09'),
('2016-07-10'),
('2016-07-11'),
('2016-07-12'),
('2016-07-13'),
('2016-07-14'),
('2016-07-15'),
('2016-07-16'),
('2016-07-17'),
('2016-07-18'),
('2016-07-19'),
('2016-07-20'),
('2016-07-21'),
('2016-07-22'),
('2016-07-23'),
('2016-07-24'),
('2016-07-25'),
('2016-07-26'),
('2016-07-27'),
('2016-07-28'),
('2016-07-29'),
('2016-07-30'),
('2016-07-31'),
('2016-09-01'),
('2016-09-02'),
('2016-09-03'),
('2016-09-04'),
('2016-09-05'),
('2016-09-06'),
('2016-09-07'),
('2016-09-08'),
('2016-09-09'),
('2016-09-10'),
('2016-09-11'),
('2016-09-12'),
('2016-09-13'),
('2016-09-14'),
('2016-09-15'),
('2016-09-16'),
('2016-09-17'),
('2016-09-18'),
('2016-09-19'),
('2016-09-20'),
('2016-09-21'),
('2016-09-22'),
('2016-09-23'),
('2016-09-24'),
('2016-09-25'),
('2016-09-26'),
('2016-09-27'),
('2016-09-28'),
('2016-09-29'),
('2016-09-30'),
('2016-10-01'),
('2016-10-02'),
('2016-10-03'),
('2016-10-04'),
('2016-10-05'),
('2016-10-06'),
('2016-10-07'),
('2016-10-08'),
('2016-10-09'),
('2016-10-10'),
('2016-10-11'),
('2016-10-12'),
('2016-10-13'),
('2016-10-14'),
('2016-10-15'),
('2016-10-16'),
('2016-10-17'),
('2016-10-18'),
('2016-10-19'),
('2016-10-20'),
('2016-10-21'),
('2016-10-22'),
('2016-10-23'),
('2016-10-24'),
('2016-10-25'),
('2016-10-26'),
('2016-10-27'),
('2016-10-28'),
('2016-10-29'),
('2016-10-30'),
('2016-10-31'),
('2016-11-01'),
('2016-11-02'),
('2016-11-03'),
('2016-11-04'),
('2016-11-05'),
('2016-11-06'),
('2016-11-07'),
('2016-11-08'),
('2016-11-09'),
('2016-11-10'),
('2016-11-11'),
('2016-11-12'),
('2016-11-13'),
('2016-11-14'),
('2016-11-15'),
('2016-11-16'),
('2016-11-17'),
('2016-11-18'),
('2016-11-19'),
('2016-11-20'),
('2016-11-21'),
('2016-11-22'),
('2016-11-23'),
('2016-11-24'),
('2016-11-25'),
('2016-11-26'),
('2016-11-27'),
('2016-11-28'),
('2016-11-29'),
('2016-11-30'),
('2016-12-01'),
('2016-12-02'),
('2016-12-03'),
('2016-12-04'),
('2016-12-05'),
('2016-12-06'),
('2016-12-07'),
('2016-12-08'),
('2016-12-09'),
('2016-12-10'),
('2016-12-11'),
('2016-12-12'),
('2016-12-13'),
('2016-12-14'),
('2016-12-15'),
('2016-12-16'),
('2016-12-17'),
('2016-12-18'),
('2016-12-19'),
('2016-12-20'),
('2016-12-21'),
('2016-12-22'),
('2016-12-23'),
('2016-12-24'),
('2016-12-25'),
('2016-12-26'),
('2016-12-27'),
('2016-12-28'),
('2016-12-29'),
('2016-12-30'),
('2016-12-31');

-- 
-- Вывод данных для таблицы managers
--
INSERT INTO managers(id, fio, salary) VALUES
(1, 'Хельга Браун', 20000.00),
(2, 'Барак Обама', 30000.00),
(3, 'Денис Козлов', 40000.00);

-- 
-- Вывод данных для таблицы calls
--
INSERT INTO calls(id, id_manager, date_call) VALUES
(1, 1, '2016-10-01 12:57:13'),
(2, 1, '2016-10-04 12:57:48'),
(3, 2, '2016-10-04 12:58:57'),
(4, 3, '2016-10-04 12:59:02'),
(5, 3, '2016-10-04 12:59:08'),
(6, 1, '2016-10-04 13:08:05'),
(7, 1, '2016-10-05 13:08:18'),
(8, 1, '2016-09-01 15:54:54'),
(9, 2, '2016-07-12 15:57:45');

-- 
-- Вывод данных для таблицы shedule
--
INSERT INTO shedule(id_managers, date, type_day) VALUES
(2, '2016-10-06', 'week');

DELIMITER $$

--
-- Описание для триггера add_shedule
--
DROP TRIGGER IF EXISTS add_shedule$$
CREATE 
	DEFINER = 'admin'@'localhost'
TRIGGER add_shedule
	BEFORE INSERT
	ON shedule
	FOR EACH ROW
BEGIN
  IF(NEW.date IS NULL) THEN
    SET NEW.date = NOW();
  END IF;
END
$$

DELIMITER ;

-- 
-- Восстановить предыдущий режим SQL (SQL mode)
-- 
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;

-- 
-- Включение внешних ключей
-- 
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;