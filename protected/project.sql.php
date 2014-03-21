<?php exit; ?>

CREATE TABLE IF NOT EXISTS `items` (
  `key` varchar(64) NOT NULL,
  `real` varchar(255) NOT NULL,
  `uri` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `extension` varchar(32) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL,
  `size` bigint(20) unsigned NOT NULL,
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `search_keys` text NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
