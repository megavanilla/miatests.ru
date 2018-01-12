/*Удаляем старые, если есть*/
DROP TABLE IF EXISTS salaries_2;
DROP TABLE IF EXISTS salaries;
DROP TABLE IF EXISTS workers;

/*Создаём новые, если нету*/
CREATE TABLE IF NOT EXISTS workers (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(50) NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB
CHARACTER SET utf8
COLLATE utf8_general_ci;


CREATE TABLE IF NOT EXISTS salaries (
  worker_id int(11) NOT NULL,
  date date NOT NULL,
  value decimal(11, 2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (worker_id, date),
  INDEX date (date),
  CONSTRAINT salaries_ibfk_1 FOREIGN KEY (worker_id)
  REFERENCES workers (id) ON DELETE CASCADE ON UPDATE CASCADE
)
ENGINE = INNODB
CHARACTER SET utf8
COLLATE utf8_general_ci;

CREATE TABLE IF NOT EXISTS salaries_2 (
  worker_id int(11) NOT NULL,
  date date NOT NULL,
  salary decimal(11, 2) NOT NULL,
  status enum ('0', '1') NOT NULL DEFAULT '0',
  PRIMARY KEY (worker_id, date),
  INDEX date (date),
  INDEX status (status),
  CONSTRAINT salaries_2_ibfk_1 FOREIGN KEY (worker_id)
  REFERENCES workers (id) ON DELETE CASCADE ON UPDATE CASCADE
)
ENGINE = INNODB
CHARACTER SET utf8
COLLATE utf8_general_ci;

/*Вставляем данные*/

INSERT INTO workers(id, name) VALUES
(1, 'Иванов'),
(2, 'Петров'),
(3, 'Сидоров'),
(4, 'Лукарев'),
(5, 'Шухарев');

INSERT INTO salaries(worker_id, date, value) VALUES
(1, '2017-02-01', 2.00),
(1, '2017-03-01', 3.00),
(1, '2017-04-01', 4.00),
(1, '2017-05-01', 5.00),
(1, '2017-07-01', 7.00),
(1, '2017-08-01', 8.00),
(1, '2017-09-01', 9.00),
(1, '2017-10-01', 10.00),
(1, '2017-12-01', 12.00),
(2, '2017-01-01', 1.00),
(2, '2017-02-01', 2.00),
(2, '2017-03-01', 3.00),
(2, '2017-05-01', 5.00),
(2, '2017-06-01', 6.00),
(2, '2017-07-01', 7.00),
(2, '2017-08-01', 8.00),
(2, '2017-10-01', 10.00),
(2, '2017-11-01', 11.00),
(2, '2017-12-01', 12.00),
(3, '2017-01-01', 1.00),
(3, '2017-03-01', 3.00),
(3, '2017-04-01', 4.00),
(3, '2017-05-01', 5.00),
(3, '2017-06-01', 6.00),
(3, '2017-08-01', 8.00),
(3, '2017-09-01', 9.00),
(3, '2017-10-01', 10.00),
(3, '2017-11-01', 11.00),
(4, '2017-01-01', 1.00),
(4, '2017-02-01', 2.00),
(4, '2017-03-01', 3.00),
(4, '2017-04-11', 4.00),
(4, '2017-06-01', 6.00),
(4, '2017-07-01', 7.00),
(4, '2017-08-01', 8.00),
(4, '2017-09-01', 9.00),
(4, '2017-11-01', 11.00),
(4, '2017-12-01', 12.00),
(5, '2017-01-01', 1.00),
(5, '2017-02-01', 2.00),
(5, '2017-04-01', 4.00),
(5, '2017-05-01', 5.00),
(5, '2017-06-01', 6.00),
(5, '2017-07-01', 7.00),
(5, '2017-09-01', 9.00),
(5, '2017-10-01', 10.00),
(5, '2017-11-01', 11.00),
(5, '2017-12-01', 12.00);

INSERT INTO salaries_2(worker_id, date, salary, status) VALUES
(1, '2018-01-01', 123123.00, '1'),
(2, '2018-01-01', 123123.00, '1'),
(3, '2018-01-12', 123.00, '1'),
(4, '2018-01-01', 13123.00, '0'),
(5, '2018-01-01', 1231.00, '1');