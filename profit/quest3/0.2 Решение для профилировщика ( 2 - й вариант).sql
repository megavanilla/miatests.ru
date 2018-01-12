SELECT
  (SELECT DATE_SUB('2018-01-09',INTERVAL DAYOFMONTH('2019-01-09')-1 DAY) AS `date_start`) AS `date_start`,
  (SELECT DATE_ADD(DATE_SUB('2018-01-09',INTERVAL DAYOFMONTH('2019-01-09')+1 DAY), INTERVAL 1 MONTH) AS `date_end`) AS `date_end`,
  `name`
FROM `workers`
WHERE
`workers`.`id` IN (SELECT
  `salaries_2`.`worker_id`
FROM `salaries_2` USE INDEX (`status`)
WHERE salaries_2.`date` BETWEEN
  (SELECT DATE_SUB('2018-01-09', INTERVAL DAYOFMONTH('2019-01-09') - 1 DAY) AS `date_start`)
      AND
  (SELECT DATE_ADD(DATE_SUB('2018-01-09', INTERVAL DAYOFMONTH('2019-01-09') + 1 DAY), INTERVAL 1 MONTH) AS `date_end`)
AND `salaries_2`.`status` = '0');