
CREATE TABLE `tracks` (
  `id` int(11) NOT NULL auto_increment,
  `artist` varchar(255) NOT NULL default '',
  `artistmbid` varchar(64) NOT NULL default '',
  `mbid` varchar(64) NOT NULL default '',
  `name` varchar(255) NOT NULL default '',
  `streamable` varchar(10) default NULL,
  `album` varchar(255) NOT NULL default '',
  `albummbid` varchar(64) NOT NULL default '',
  `urltrack` varchar(255) default NULL,
  `urlartist` varchar(255) default NULL,
  `imagesmall` varchar(255) default NULL,
  `imagemedium` varchar(255) default NULL,
  `imagelarge` varchar(255) default NULL,
  `imageextralarge` varchar(255) default NULL,
  `datestring` datetime NOT NULL default '0000-00-00 00:00:00',
  `dateuts` varchar(64) NOT NULL default '',
  `modified` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `dateuts` (`dateuts`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


