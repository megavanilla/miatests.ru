CREATE TABLE IF NOT EXISTS sessions (
  session_id varchar(255) NOT NULL COMMENT 'Идентификатор сессии',
  date_touched timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Время доступа к данным',
  sess_data text DEFAULT NULL COMMENT 'Данные сессии',
  PRIMARY KEY (session_id),
  INDEX date_touched (date_touched)
)
CHARACTER SET utf8
COLLATE utf8_unicode_ci;