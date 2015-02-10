<?php

$installer = $this;
$installer->startSetup();

$installer->run("

	DROP TABLE IF EXISTS {$this->getTable('ipmenumanager_menu')};
	CREATE TABLE IF NOT EXISTS {$this->getTable('ipmenumanager_menu')} (
		`menu_id` int(11) unsigned NOT NULL auto_increment,
		`store_id` int(11) unsigned NOT NULL default 0,
		`title` varchar(255) NOT NULL default '',
		`code` varchar(32) NOT NULL default '',
		`is_enabled` tinyint(1) unsigned NOT NULL default 1,
		PRIMARY KEY (`menu_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	DROP TABLE IF EXISTS {$this->getTable('ipmenumanager_menuitem')};
	CREATE TABLE IF NOT EXISTS {$this->getTable('ipmenumanager_menuitem')} (
		`menuitem_id` int(11) unsigned NOT NULL auto_increment,
		`menu_id` int (11) unsigned NOT NULL default 0,
		`title` varchar(255) NOT NULL default '',
		`type` varchar(255) NOT NULL default '',
		`cat_id` varchar(255) NOT NULL default '',
		`url` varchar(255) NOT NULL default '',
		`class` varchar(255) NOT NULL default '',
		`parent` tinyint(3) unsigned NOT NULL default 0,
		`level` tinyint(3) unsigned NOT NULL default 1,
		`sort_order` tinyint(3) unsigned NOT NULL default 1,
		`composite_order` varchar(255) NOT NULL default '',
		`is_enabled` tinyint(1) unsigned NOT NULL default 1,
		KEY `FK_MENU_ID_MENUITEM` (`menu_id`),
		CONSTRAINT `FK_MENU_ID_MENUITEM` FOREIGN KEY (`menu_id`) REFERENCES `{$this->getTable('ipmenumanager_menu')}` (`menu_id`) ON DELETE CASCADE ON UPDATE CASCADE,
		PRIMARY KEY (`menuitem_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	ALTER TABLE {$this->getTable('ipmenumanager_menu')} ADD UNIQUE (code, store_id);

");

$installer->endSetup();
