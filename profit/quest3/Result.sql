/*Расчитаем один раз дату, которая на входе может отличаться*/
SET @date_start = (SELECT DATE_SUB('2017-04-09',INTERVAL DAYOFMONTH('2017-04-09')-1 DAY) AS `date_start`);
SET @date_end = (SELECT DATE_ADD(DATE_SUB('2017-04-09',INTERVAL DAYOFMONTH('2017-04-09')+1 DAY), INTERVAL 1 MONTH) AS `date_end`);
/**/
SELECT
    @date_start AS `date_start`, @date_end AS `date_end`, `name`
FROM
    `workers`
  USE INDEX (PRIMARY)
WHERE NOT
    `workers`.`id` IN(
    SELECT
      `salaries`.`worker_id`
    FROM `salaries`
    USE INDEX (`date`)
    WHERE `salaries`.`date` BETWEEN @date_start AND @date_end
);