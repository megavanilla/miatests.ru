SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

CREATE DATABASE IF NOT EXISTS `win_style_test` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `win_style_test`;

DROP TABLE IF EXISTS `img_size_types`;
CREATE TABLE IF NOT EXISTS `img_size_types` (
  `size` enum('big','med','min','mic') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'mic' COMMENT 'Вид изображения',
  `width` int(11) NOT NULL DEFAULT '0' COMMENT 'Ширина изображения',
  `height` int(11) NOT NULL DEFAULT '0' COMMENT 'Высота изображения'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Таблица соответствия видов и размеров изображения';

INSERT INTO `img_size_types` (`size`, `width`, `height`) VALUES
('big', 800, 600),
('med', 600, 400),
('min', 300, 200),
('mic', 101, 101);


ALTER TABLE `img_size_types`
  ADD PRIMARY KEY (`size`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
