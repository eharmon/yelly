DROP TABLE IF EXISTS `lylina_feeds`;
CREATE TABLE IF NOT EXISTS `lylina_feeds` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `url` varchar(255) NOT NULL default '',
  `name` varchar(255) default NULL,
  `lastmod` varchar(255) default NULL,
  `etag` varchar(255) default NULL,
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `url` (`url`)
) TYPE=MyISAM ;

DROP TABLE IF EXISTS `lylina_items`;
CREATE TABLE IF NOT EXISTS `lylina_items` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `feed_id` int(10) unsigned NOT NULL default '0',
  `url` varchar(255) NOT NULL default '',
  `title` varchar(255) NOT NULL default '''no title''',
  `body` text,
  `dt` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `feed_id` (`feed_id`,`url`)
) TYPE=MyISAM ;

DROP TABLE IF EXISTS `lylina_userfeeds`;
CREATE TABLE IF NOT EXISTS `lylina_userfeeds` (
  `feed_id` int(10) unsigned NOT NULL default '0',
  `user_id` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`feed_id`,`user_id`)
) TYPE=MyISAM;

DROP TABLE IF EXISTS `lylina_users`;
CREATE TABLE IF NOT EXISTS `lylina_users` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `login` varchar(255) NOT NULL default '',
  `pass` varchar(255) NOT NULL default '',
  `magic` varchar(32) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `login` (`login`)
) TYPE=MyISAM AUTO_INCREMENT=3 ;

INSERT INTO lylina_users SET id='1', login='admin', pass='$1$1397484d$xt7b9DtY9aJt3XWjAQwJ//';