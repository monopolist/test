CREATE TABLE IF NOT EXISTS `table_innodb` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(127) COLLATE utf8_bin NOT NULL,
  `desc` varchar(256) COLLATE utf8_bin NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `table_myisam` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(127) COLLATE utf8_bin NOT NULL,
  `desc` varchar(256) COLLATE utf8_bin NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin


CREATE TABLE IF NOT EXISTS `table_innodb_noindex` (
  `id` int(11) NOT NULL,
  `title` varchar(127) COLLATE utf8_bin NOT NULL,
  `desc` varchar(256) COLLATE utf8_bin NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `table_myisam_noindex` (
  `id` int(11) NOT NULL,
  `title` varchar(127) COLLATE utf8_bin NOT NULL,
  `desc` varchar(256) COLLATE utf8_bin NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date` date NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin

