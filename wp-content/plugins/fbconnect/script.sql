ALTER TABLE wp_comments ADD `fbconnect` varchar(50) NOT NULL DEFAULT '0';
ALTER TABLE wp_users ADD `fbconnect_lastlogin` int(14) NOT NULL DEFAULT '0';
ALTER TABLE wp_users ADD `fbconnect_userid` varchar(250) NOT NULL DEFAULT '0';