
# DB: test -----------------------------------------
CREATE TABLE IF NOT EXISTS `test_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `tel` varchar(32) NOT NULL,
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- テーブルのデータをダンプしています `test_table`
--

INSERT INTO `test_table` (`id`, `name`, `tel`, `created`, `modified`) VALUES
(1, 'test', '090-0000-0000', '0000-00-00 00:00:00', '2012-09-19 08:09:58'),
(2, 'test0', '080-0000-0000', '0000-00-00 00:00:00', '2012-09-19 08:09:58');


# DB: test1 -----------------------------------------
CREATE TABLE IF NOT EXISTS `test1_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `tel` varchar(32) NOT NULL,
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- テーブルのデータをダンプしています `test1_table`
--

INSERT INTO `test1_table` (`id`, `name`, `tel`, `created`, `modified`) VALUES
(1, 'test1子', '03-1111-1111', '0000-00-00 00:00:00', '2012-09-19 08:12:27'),
(2, 'test11太郎', '045-1111-1111', '0000-00-00 00:00:00', '2012-09-19 08:12:27');


# DB: test2 -----------------------------------------
CREATE TABLE IF NOT EXISTS `test2_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `tel` varchar(32) NOT NULL,
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- テーブルのデータをダンプしています `test2_table`
--

INSERT INTO `test2_table` (`id`, `name`, `tel`, `created`, `modified`) VALUES
(1, 'test2子', '03-2222-2222', '0000-00-00 00:00:00', '2012-09-18 23:12:27'),
(2, 'test22太郎', '045-2222-2222', '0000-00-00 00:00:00', '2012-09-18 23:12:27');


