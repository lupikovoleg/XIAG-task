CREATE TABLE `link` (
  `id` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `link` varchar(30) NOT NULL,
  `alias` varchar(30) NOT NULL,
  `clicks` int(12) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`,`link`,`alias`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;